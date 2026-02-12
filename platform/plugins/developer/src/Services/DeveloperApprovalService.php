<?php

namespace Botble\Developer\Services;

use Botble\Base\Facades\EmailHandler;
use Botble\Developer\Models\DeveloperProfileRevision;
use Botble\Developer\Models\DeveloperProjectRevision;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class DeveloperApprovalService
{
    public function approveProfileRevision(DeveloperProfileRevision $revision, ?string $note = null): void
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

            DeveloperProfileRevision::query()
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
                EmailHandler::setModule(DEVELOPER_MODULE_SCREEN_NAME)
                    ->setVariableValues([
                        'developer_name' => $profile->account->name,
                        'approval_note' => $note,
                    ])
                    ->sendUsingTemplate('developer-profile-approved', $profile->account->email);
            }
        });
    }

    public function rejectProfileRevision(DeveloperProfileRevision $revision, string $reason): void
    {
        DB::transaction(function () use ($revision, $reason): void {
            $revision->status = 'rejected';
            $revision->reviewed_at = now();
            $revision->reviewed_by = auth()->id();
            $revision->rejection_reason = $reason;
            $revision->save();

            $profile = $revision->profile;

            if ($profile->account?->email) {
                EmailHandler::setModule(DEVELOPER_MODULE_SCREEN_NAME)
                    ->setVariableValues([
                        'developer_name' => $profile->account->name,
                        'rejection_reason' => $reason,
                    ])
                    ->sendUsingTemplate('developer-profile-rejected', $profile->account->email);
            }
        });
    }

    public function approveProjectRevision(DeveloperProjectRevision $revision, ?string $note = null): void
    {
        DB::transaction(function () use ($revision, $note): void {
            $project = $revision->project;
            $payload = Arr::only($revision->payload ?? [], $project->getFillable());

            $project->fill($payload);
            $project->approval_status = 'approved';
            $project->approved_at = now();
            $project->approved_by = auth()->id();
            $project->published_at = $project->published_at ?: now();
            $project->save();

            $revision->status = 'approved';
            $revision->reviewed_at = now();
            $revision->approved_at = now();
            $revision->reviewed_by = auth()->id();
            $revision->rejection_reason = null;
            $revision->save();

            DeveloperProjectRevision::query()
                ->where('entity_id', $revision->entity_id)
                ->where('id', '!=', $revision->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'cancelled',
                    'reviewed_by' => auth()->id(),
                    'reviewed_at' => now(),
                    'rejection_reason' => 'Superseded by approved revision #' . $revision->id,
                ]);

            $profile = $project->developerProfile;

            if ($profile?->account?->email) {
                EmailHandler::setModule(DEVELOPER_MODULE_SCREEN_NAME)
                    ->setVariableValues([
                        'developer_name' => $profile->account->name,
                        'approval_note' => $note,
                    ])
                    ->sendUsingTemplate('developer-profile-approved', $profile->account->email);
            }
        });
    }

    public function rejectProjectRevision(DeveloperProjectRevision $revision, string $reason): void
    {
        DB::transaction(function () use ($revision, $reason): void {
            $revision->status = 'rejected';
            $revision->reviewed_at = now();
            $revision->reviewed_by = auth()->id();
            $revision->rejection_reason = $reason;
            $revision->save();

            $project = $revision->project;

            if ($project) {
                $project->approval_status = 'rejected';
                $project->save();

                if ($project->developerProfile?->account?->email) {
                    EmailHandler::setModule(DEVELOPER_MODULE_SCREEN_NAME)
                        ->setVariableValues([
                            'developer_name' => $project->developerProfile->account->name,
                            'rejection_reason' => $reason,
                        ])
                        ->sendUsingTemplate('developer-profile-rejected', $project->developerProfile->account->email);
                }
            }
        });
    }
}
