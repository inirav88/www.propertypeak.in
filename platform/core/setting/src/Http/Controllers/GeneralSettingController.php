<?php

namespace Botble\Setting\Http\Controllers;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Supports\Core;
use Botble\Base\Supports\Language;
use Botble\Setting\Forms\GeneralSettingForm;
use Botble\Setting\Http\Requests\GeneralSettingRequest;
use Botble\Setting\Http\Requests\LicenseSettingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class GeneralSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('core/setting::setting.general_setting'));

        $form = GeneralSettingForm::create();

        return view('core/setting::general', compact('form'));
    }

    public function update(GeneralSettingRequest $request): BaseHttpResponse
    {
        $data = Arr::except($request->input(), [
            'locale',
        ]);

        $locale = $request->input('locale');
        if ($locale && array_key_exists($locale, Language::getAvailableLocales())) {
            session()->put('site-locale', $locale);
        }

        $isDemoModeEnabled = BaseHelper::hasDemoModeEnabled();

        if (! $isDemoModeEnabled) {
            $data['locale'] = $locale;
        }

        cache()->forget('core.base.boot_settings');

        return $this->performUpdate($data);
    }

    public function getVerifyLicense(Request $request, Core $core)
    {
        return $this
            ->httpResponse()
            ->setMessage('License verification is disabled.')
            ->setData([
                'activated_at' => now()->format('M d Y'),
                'licensed_to' => setting('admin_title', config('core.base.general.base_name')),
            ]);
    }

    public function activateLicense(LicenseSettingRequest $request, Core $core): BaseHttpResponse
    {
        return $this
            ->httpResponse()
            ->setMessage('License verification is disabled.')
            ->setData([
                'activated_at' => now()->format('M d Y'),
                'licensed_to' => $request->input('buyer') ?: setting('admin_title', config('core.base.general.base_name')),
            ]);
    }

    public function deactivateLicense(Core $core)
    {
        return $this
            ->httpResponse()
            ->setMessage('License verification is disabled.');
    }

    public function resetLicense(LicenseSettingRequest $request, Core $core)
    {
        return $this
            ->httpResponse()
            ->setMessage('License verification is disabled.');
    }

}
