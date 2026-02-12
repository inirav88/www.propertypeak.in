<?php

namespace Botble\AgentBroker\Http\Controllers\Fronts;

use Botble\AgentBroker\Http\Requests\AgentPublicLeadRequest;
use Botble\AgentBroker\Models\AgentLead;
use Botble\AgentBroker\Models\AgentProfile;
use Botble\Base\Facades\EmailHandler;
use Botble\Base\Http\Controllers\BaseController;
use Botble\RealEstate\Enums\ConsultStatusEnum;
use Botble\RealEstate\Models\Consult;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class AgentPublicLeadController extends BaseController
{
    public function store(AgentPublicLeadRequest $request, string $slug): JsonResponse|RedirectResponse
    {
        $agent = AgentProfile::query()
            ->with('account')
            ->where('slug', $slug)
            ->whereNotNull('approved_at')
            ->where('status', 'active')
            ->where('is_blocked', false)
            ->firstOrFail();

        $payload = $this->sanitize($request->validated());

        DB::transaction(function () use ($agent, $payload): void {
            $lead = AgentLead::query()->create([
                'agent_profile_id' => $agent->id,
                'property_id' => Arr::get($payload, 'property_id'),
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
                'project_id' => null,
                'property_id' => Arr::get($payload, 'property_id'),
                'reference_type' => AgentProfile::class,
                'reference_id' => $agent->id,
                'ip_address' => request()->ip(),
                'status' => ConsultStatusEnum::UNREAD,
            ]);

            $this->dispatchEmails($agent, $lead);
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
            'property_id' => Arr::get($payload, 'property_id') ? (int) Arr::get($payload, 'property_id') : null,
        ];
    }

    protected function dispatchEmails(AgentProfile $agent, AgentLead $lead): void
    {
        $ownerEmail = $agent->contact_email ?: $agent->account?->email;
        $adminEmail = setting('admin_email') ?: config('mail.from.address');
        $mode = (string) setting('agent_broker_lead_routing_mode', config('plugins.agent-broker.settings.lead_routing.mode', 'both'));

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
            'agent_name' => $agent->account?->name ?: __('Agent'),
            'agent_profile_url' => url($agent->slug),
        ];

        foreach (array_unique($recipients) as $recipient) {
            EmailHandler::setModule(AGENT_BROKER_MODULE_SCREEN_NAME)
                ->setVariableValues($variables)
                ->sendUsingTemplate('agent-lead-notification', $recipient);
        }

        $sendConfirmation = (bool) setting(
            'agent_broker_send_confirmation_email',
            config('plugins.agent-broker.settings.lead_routing.send_confirmation_email', false)
        );

        if ($sendConfirmation && $lead->email) {
            EmailHandler::setModule(AGENT_BROKER_MODULE_SCREEN_NAME)
                ->setVariableValues($variables)
                ->sendUsingTemplate('agent-lead-confirmation', $lead->email);
        }
    }
}
