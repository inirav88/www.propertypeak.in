<?php

namespace Botble\RealEstate\Http\Controllers\Fronts;

use Botble\Base\Facades\Assets;
use Botble\Base\Facades\EmailHandler;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Language\Facades\Language;
use Botble\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Botble\Media\Chunks\Exceptions\UploadMissingFileException;
use Botble\Media\Chunks\Handler\DropZoneUploadHandler;
use Botble\Media\Chunks\Receiver\FileReceiver;
use Botble\Media\Facades\RvMedia;
use Botble\Media\Models\MediaFile;
use Botble\Optimize\Facades\OptimizerHelper;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Models\Payment;
use Botble\PayPal\Services\Gateways\PayPalPaymentService;
use Botble\RealEstate\Facades\RealEstateHelper;
use Botble\RealEstate\Forms\AccountForm;
use Botble\RealEstate\Forms\Fronts\ChangePasswordForm;
use Botble\RealEstate\Forms\Fronts\ProfileForm;
use Botble\RealEstate\Http\Requests\AvatarRequest;
use Botble\RealEstate\Http\Requests\SettingRequest;
use Botble\RealEstate\Http\Requests\UpdatePasswordRequest;
use Botble\RealEstate\Http\Resources\AccountResource;
use Botble\RealEstate\Http\Resources\ActivityLogResource;
use Botble\RealEstate\Http\Resources\PackageResource;
use Botble\RealEstate\Http\Resources\TransactionResource;
use Botble\RealEstate\Models\Account;
use Botble\RealEstate\Models\AccountActivityLog;
use Botble\RealEstate\Models\Package;
use Botble\RealEstate\Models\Transaction;
use Botble\RealEstate\Services\CouponService;
use Botble\Theme\Facades\Theme;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PublicAccountController extends BaseController
{
    public function __construct()
    {
        OptimizerHelper::disable();
    }

    protected function getAllowedPackageTypesForAccount(Account $account): array
    {
        $primaryPackageType = match ($account->type) {
            'builder' => 'builder',
            'owner', 'member' => 'owner',
            'agent' => 'agent',
            default => 'agent',
        };

        return [$primaryPackageType, 'addon'];
    }

    protected function resolvePackageType(Package $package): ?string
    {
        if (in_array($package->package_type, ['owner', 'agent', 'builder', 'addon'], true)) {
            return $package->package_type;
        }

        $name = str($package->name)->lower()->toString();

        return match (true) {
            str_contains($name, 'add-on'), str_contains($name, 'addon'), str_contains($name, 'add on') => 'addon',
            str_contains($name, 'builder') => 'builder',
            str_contains($name, 'owner') => 'owner',
            str_contains($name, 'agent') => 'agent',
            default => null,
        };
    }

    protected function isPackageAllowedForAccount(Package $package, Account $account): bool
    {
        $packageType = $this->resolvePackageType($package);

        if (! $packageType) {
            return false;
        }

        return in_array($packageType, $this->getAllowedPackageTypesForAccount($account), true);
    }

    public function ajaxGetPackages()
    {
        abort_unless(RealEstateHelper::isEnabledCreditsSystem(), 404);

        $account = Account::query()->with(['packages'])->findOrFail(auth('account')->id());

        $allowedPackageTypes = $this->getAllowedPackageTypesForAccount($account);

        $packages = Package::query()
            ->wherePublished()
            ->where(function ($query) use ($allowedPackageTypes) {
                $query
                    ->whereIn('package_type', $allowedPackageTypes)
                    ->orWhereNull('package_type');
            })
            ->get();

        if (is_plugin_active('language') && is_plugin_active('language-advanced')) {
            Language::setCurrentAdminLocale(App::getLocale());
            LanguageAdvancedManager::initModelRelations();
            $packages->load('translations');
        }

        $packages = $packages->filter(function ($package) use ($account) {
            if (! $this->isPackageAllowedForAccount($package, $account)) {
                return false;
            }

            return empty($package->account_limit)
                || $account->packages->where('id', $package->id)->count() < $package->account_limit;
        });

        return $this->httpResponse()->setData([
            'packages' => PackageResource::collection($packages),
            'account' => new AccountResource($account),
        ]);
    }

    public function ajaxSubscribePackage(Request $request)
    {
        abort_unless(RealEstateHelper::isEnabledCreditsSystem(), 404);

        $package = Package::query()->findOrFail($request->input('id'));
        $account = Account::query()->findOrFail(auth('account')->id());

        abort_if(! $this->isPackageAllowedForAccount($package, $account), 403);

        abort_if(
            $package->account_limit &&
            $account->packages()->where('package_id', $package->getKey())->count() >= $package->account_limit,
            403
        );

        session(['subscribed_packaged_id' => $package->id]);

        if ((float) $package->price) {
            return $this->httpResponse()->setData([
                'next_page' => route('public.account.package.subscribe', $package->id),
            ]);
        }

        $this->savePayment($package, null, true);

        return $this
            ->httpResponse()
            ->setData(new AccountResource($account->refresh()))
            ->setMessage(trans('plugins/real-estate::package.add_credit_success'));
    }

    public function getSubscribePackage(int|string $id, CouponService $service)
    {
        abort_unless(RealEstateHelper::isEnabledCreditsSystem(), 404);

        $package = Package::query()->findOrFail($id);
        $account = Account::query()->findOrFail(auth('account')->id());

        abort_if(! $this->isPackageAllowedForAccount($package, $account), 403);

        Session::put('cart_total', $package->price);

        $this->pageTitle(trans('plugins/real-estate::package.subscribe_package', ['name' => $package->name]));

        add_filter(PAYMENT_FILTER_AFTER_PAYMENT_METHOD, function () use ($service, $package) {
            $totalAmount = $service->getAmountAfterDiscount(
                Session::get('coupon_discount_amount', 0),
                $package->price
            );

            return view('plugins/real-estate::coupons.partials.form', compact('package', 'totalAmount'));
        });

        return view('plugins/real-estate::account.checkout', compact('package'));
    }
}
