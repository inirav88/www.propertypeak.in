<?php

namespace Botble\AgentBroker\Http\Controllers\Fronts;

use Botble\AgentBroker\Models\AgentLead;
use Botble\AgentBroker\Models\AgentProfile;
use Botble\AgentBroker\Models\AgentProfileRevision;
use Botble\Base\Facades\EmailHandler;
use Botble\Base\Http\Controllers\BaseController;
use Botble\RealEstate\Models\Account;
use Botble\RealEstate\Models\Property;
use Botble\Developer\Services\SlugConflictService;
use Illuminate\Http\Request;

class AgentDashboardController extends BaseController
{
    public function editProfile()
    {
        $account = auth('account')->user();

        abort_unless(in_array($account->type, ['agent', 'member']), 403);

        $profile = AgentProfile::query()->firstOrCreate([
            'account_id' => $account->id,
        ], [
            'slug' => $account->username,
            'status' => 'draft',
        ]);

        return view('plugins/agent-broker::fronts.profile', compact('profile', 'account'));
    }

    public function updateProfile(Request $request)
    {
        /** @var Account $account */
        $account = auth('account')->user();

        abort_unless(in_array($account->type, ['agent', 'member']), 403);

        $profile = AgentProfile::query()->firstOrCreate([
            'account_id' => $account->id,
        ], [
            'slug' => $account->username,
            'status' => 'draft',
        ]);

        $payload = $request->validate([
            'slug' => ['required', 'string', 'max:255', function (string $attribute, mixed $value, \Closure $fail) use ($profile): void {
                $service = app(SlugConflictService::class);

                if (! $service->isAvailable((string) $value, AgentProfile::class, $profile->id)) {
                    $fail(__('The :attribute has already been taken or reserved.', ['attribute' => $attribute]));
                }
            }],
            'bio' => ['nullable', 'string'],
            'designation' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
            'website' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:active,inactive,draft'],
        ]);

        if ($this->shouldAutoApprove($profile)) {
            $profile->fill($payload);
            $profile->status = $payload['status'] ?? 'active';
            $profile->approved_at = now();
            $profile->approved_by = null;
            $profile->save();

            return $this->httpResponse()->setMessage(__('Profile updated successfully.'));
        }

        $revision = AgentProfileRevision::query()->create([
            'entity_id' => $profile->id,
            'entity_type' => AgentProfile::class,
            'payload' => $payload,
            'status' => 'pending',
            'submitted_by' => $account->id,
            'submitted_at' => now(),
        ]);

        $this->notifyAdminProfileSubmission($account, $revision);

        return $this->httpResponse()->setMessage(__('Profile changes submitted for approval.'));
    }

    public function properties()
    {
        $account = auth('account')->user();

        abort_unless(in_array($account->type, ['agent', 'member']), 403);

        $properties = Property::query()
            ->where('author_id', $account->id)
            ->where('author_type', Account::class)
            ->latest()
            ->paginate(20);

        return view('plugins/agent-broker::fronts.properties', compact('properties', 'account'));
    }

    public function leads()
    {
        $account = auth('account')->user();

        abort_unless(in_array($account->type, ['agent', 'member']), 403);

        $profile = AgentProfile::query()->where('account_id', $account->id)->firstOrFail();

        $leads = AgentLead::query()->where('agent_profile_id', $profile->id)->latest()->paginate(20);

        return view('plugins/agent-broker::fronts.leads', compact('leads', 'profile'));
    }

    protected function shouldAutoApprove(AgentProfile $profile): bool
    {
        $requiresApproval = $profile->requires_approval;

        if (is_null($requiresApproval)) {
            $requiresApproval = (bool) setting('agent_broker_require_approval_default', true);
        }

        $globalAutoApprove = (bool) setting('agent_broker_global_auto_approve', false);

        return $profile->auto_approve_changes || $globalAutoApprove || ! $requiresApproval;
    }

    protected function notifyAdminProfileSubmission(Account $account, AgentProfileRevision $revision): void
    {
        $adminEmail = setting('admin_email') ?: config('mail.from.address');

        if (! $adminEmail) {
            return;
        }

        EmailHandler::setModule(AGENT_BROKER_MODULE_SCREEN_NAME)
            ->setVariableValues([
                'agent_name' => $account->name,
                'agent_email' => $account->email,
                'revision_url' => route('agent-broker.approvals.index') . '#revision-' . $revision->id,
            ])
            ->sendUsingTemplate('agent-profile-change-submitted', $adminEmail);
    }
}
