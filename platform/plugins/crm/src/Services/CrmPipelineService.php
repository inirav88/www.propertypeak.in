<?php

namespace Botble\Crm\Services;

use Botble\ACL\Models\User;
use Botble\RealEstate\Models\Consult;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CrmPipelineService
{
    /**
     * @return array<int, string>
     */
    public function getAvailableStages(): array
    {
        return [
            'new',
            'contacted',
            'follow_up',
            'qualified',
            'won',
            'lost',
        ];
    }

    public function moveToStage(Consult $lead, string $stage): Consult
    {
        $stage = trim($stage);

        if (! in_array($stage, $this->getAvailableStages(), true)) {
            throw new InvalidArgumentException(sprintf('Invalid pipeline stage: %s', $stage));
        }

        return DB::transaction(function () use ($lead, $stage): Consult {
            $lead->pipeline_stage = $stage;

            if ($stage === 'contacted' && ! $lead->contacted_at) {
                $lead->contacted_at = now();
            }

            $lead->save();

            return $lead->refresh();
        });
    }

    public function assignTo(Consult $lead, ?int $userId): Consult
    {
        return DB::transaction(function () use ($lead, $userId): Consult {
            if ($userId === null) {
                $lead->assigned_to = null;
                $lead->save();

                return $lead->refresh();
            }

            $user = User::query()->findOrFail($userId);

            if (! $user->isSuperUser() && ! $user->hasPermission('crm.view')) {
                throw new InvalidArgumentException('Assigned user must have crm.view permission.');
            }

            $lead->assigned_to = $user->getKey();
            $lead->save();

            return $lead->refresh();
        });
    }

    public function scheduleFollowUp(Consult $lead, CarbonInterface $date): Consult
    {
        return DB::transaction(function () use ($lead, $date): Consult {
            $lead->follow_up_at = $date->copy();
            $lead->save();

            return $lead->refresh();
        });
    }
}
