<?php

namespace Botble\AgentBroker\Services;

use Botble\AgentBroker\Models\AgentProfileRevision;
use Botble\Base\Facades\EmailHandler;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class AgentBrokerApprovalService
{
    public function approveProfileRevision(AgentProfileRevision $revision, ?string $note = null): void
    {
        DB::transaction(function () use ($revision, $note): void {
            $profile = $revision->profile;
            $payload = Arr::only($revision->payload ?? [], $profile->getFillable());

            $profile->fill($payload);
            $profile->approved_at = now();
            $profile->approved_by = auth()->id();
            $profile->save();

            $revision->status = 'approved';
            $revision->reviewed_at = now();
            $revision->approved_at = now();
            $revision->reviewed_by = auth()->id();
            $revision->rejection_reason = null;
            $revision->save();

            AgentProfileRevision::query()
                ->where('entity_id', $revision->entity_id)
                ->where('id', '!=', $revision->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'cancelled',
                    'reviewed_by' => auth()->id(),
                    'reviewed_at' => now(),
                    'rejection_reason' => 'Superseded by approved revision #' . $revision->id,
                ]);

            if ($profile->account?->email) {
                EmailHandler::setModule(AGENT_BROKER_MODULE_SCREEN_NAME)
                    ->setVariableValues([
                        'agent_name' => $profile->account->name,
                        'approval_note' => $note,
                    ])
                    ->sendUsingTemplate('agent-profile-approved', $profile->account->email);
            }
        });
    }

    public function rejectProfileRevision(AgentProfileRevision $revision, string $reason): void
    {
        DB::transaction(function () use ($revision, $reason): void {
            $revision->status = 'rejected';
            $revision->reviewed_at = now();
            $revision->reviewed_by = auth()->id();
            $revision->rejection_reason = $reason;
            $revision->save();

            $profile = $revision->profile;

            if ($profile->account?->email) {
                EmailHandler::setModule(AGENT_BROKER_MODULE_SCREEN_NAME)
                    ->setVariableValues([
                        'agent_name' => $profile->account->name,
                        'rejection_reason' => $reason,
                    ])
                    ->sendUsingTemplate('agent-profile-rejected', $profile->account->email);
            }
        });
    }
}
