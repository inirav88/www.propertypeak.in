<?php

namespace Botble\Developer\Http\Controllers\Fronts;

use Botble\Base\Facades\EmailHandler;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Developer\Http\Requests\DeveloperPublicLeadRequest;
use Botble\Developer\Models\DeveloperLead;
use Botble\Developer\Models\DeveloperProfile;
use Botble\RealEstate\Enums\ConsultStatusEnum;
use Botble\RealEstate\Models\Consult;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class DeveloperPublicLeadController extends BaseController
{
    public function store(DeveloperPublicLeadRequest $request, string $slug): JsonResponse|RedirectResponse
    {
        $developer = DeveloperProfile::query()
            ->with('account')
            ->where('slug', $slug)
            ->whereNotNull('approved_at')
            ->where('status', 'active')
            ->where('is_blocked', false)
            ->firstOrFail();

        $payload = $this->sanitize($request->validated());

        DB::transaction(function () use ($developer, $payload): void {
            $lead = DeveloperLead::query()->create([
                'developer_profile_id' => $developer->id,
                'developer_project_id' => Arr::get($payload, 'project_id'),
                'name' => Arr::get($payload, 'name'),
                'email' => Arr::get($payload, 'email'),
                'phone' => Arr::get($payload, 'phone'),
                'message' => Arr::get($payload, 'message'),
                'source' => 'public_profile',
                'status' => 'new',
                'metadata' => ['ip_address' => request()->ip()],
            ]);

            Consult::query()->create([
                'name' => Arr::get($payload, 'name'),
                'email' => Arr::get($payload, 'email'),
                'phone' => Arr::get($payload, 'phone'),
                'content' => Arr::get($payload, 'message'),
                'project_id' => Arr::get($payload, 'project_id'),
                'property_id' => null,
                'reference_type' => DeveloperProfile::class,
                'reference_id' => $developer->id,
                'ip_address' => request()->ip(),
                'status' => ConsultStatusEnum::UNREAD,
            ]);

            $this->dispatchEmails($developer, $lead);
        });

        $message = __('Your enquiry has been submitted successfully.');

        if ($request->expectsJson()) {
            return response()->json(['message' => $message]);
        }

        return back()->with('success', $message);
    }

    protected function sanitize(array $payload): array
    {
        return [
            'name' => trim(strip_tags((string) Arr::get($payload, 'name'))),
            'email' => trim((string) Arr::get($payload, 'email')),
            'phone' => trim(strip_tags((string) Arr::get($payload, 'phone', ''))),
            'message' => trim(strip_tags((string) Arr::get($payload, 'message'))),
            'project_id' => Arr::get($payload, 'project_id') ? (int) Arr::get($payload, 'project_id') : null,
        ];
    }

    protected function dispatchEmails(DeveloperProfile $developer, DeveloperLead $lead): void
    {
        $ownerEmail = $developer->contact_email ?: $developer->account?->email;
        $adminEmail = setting('admin_email') ?: config('mail.from.address');
        $mode = (string) setting('developer_lead_routing_mode', config('plugins.developer.settings.lead_routing.mode', 'both'));

        $recipients = match ($mode) {
            'owner_only' => array_filter([$ownerEmail]),
            'admin_only' => array_filter([$adminEmail]),
            default => array_filter([$ownerEmail, $adminEmail]),
        };

        $variables = [
            'lead_name' => $lead->name,
            'lead_email' => $lead->email,
            'lead_phone' => $lead->phone,
            'lead_message' => $lead->message,
            'developer_name' => $developer->company_name,
            'developer_profile_url' => url($developer->slug),
        ];

        foreach (array_unique($recipients) as $recipient) {
            EmailHandler::setModule(DEVELOPER_MODULE_SCREEN_NAME)
                ->setVariableValues($variables)
                ->sendUsingTemplate('developer-lead-notification', $recipient);
        }

        $sendConfirmation = (bool) setting(
            'developer_send_confirmation_email',
            config('plugins.developer.settings.lead_routing.send_confirmation_email', false)
        );

        if ($sendConfirmation && $lead->email) {
            EmailHandler::setModule(DEVELOPER_MODULE_SCREEN_NAME)
                ->setVariableValues($variables)
                ->sendUsingTemplate('developer-lead-confirmation', $lead->email);
        }
    }
}
