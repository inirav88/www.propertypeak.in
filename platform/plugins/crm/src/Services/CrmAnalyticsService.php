<?php

namespace Botble\Crm\Services;

use Botble\RealEstate\Models\Consult;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CrmAnalyticsService
{
    /**
     * @var array<int, string>
     */
    protected array $stages = [
        'new',
        'contacted',
        'follow_up',
        'qualified',
        'won',
        'lost',
    ];

    /**
     * @var array<int, string>
     */
    protected array $allowedReferenceTypes = [
        'Botble\\Developer\\Models\\DeveloperProfile',
        'Botble\\AgentBroker\\Models\\AgentProfile',
    ];

    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, int|float>
     */
    public function getSummaryStats(array $filters = []): array
    {
        $result = $this->applyFilters($filters)
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN pipeline_stage = 'new' THEN 1 ELSE 0 END) as new_count")
            ->selectRaw("SUM(CASE WHEN pipeline_stage = 'contacted' THEN 1 ELSE 0 END) as contacted_count")
            ->selectRaw("SUM(CASE WHEN pipeline_stage = 'follow_up' THEN 1 ELSE 0 END) as follow_up_count")
            ->selectRaw("SUM(CASE WHEN pipeline_stage = 'qualified' THEN 1 ELSE 0 END) as qualified_count")
            ->selectRaw("SUM(CASE WHEN pipeline_stage = 'won' THEN 1 ELSE 0 END) as won_count")
            ->selectRaw("SUM(CASE WHEN pipeline_stage = 'lost' THEN 1 ELSE 0 END) as lost_count")
            ->first();

        $total = (int) ($result->total ?? 0);
        $won = (int) ($result->won_count ?? 0);

        return [
            'total' => $total,
            'new' => (int) ($result->new_count ?? 0),
            'contacted' => (int) ($result->contacted_count ?? 0),
            'follow_up' => (int) ($result->follow_up_count ?? 0),
            'qualified' => (int) ($result->qualified_count ?? 0),
            'won' => $won,
            'lost' => (int) ($result->lost_count ?? 0),
            'conversion_rate' => $total > 0 ? round(($won / $total) * 100, 2) : 0.0,
        ];
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, int>
     */
    public function getStageDistribution(array $filters = []): array
    {
        $distribution = array_fill_keys($this->stages, 0);

        $rows = $this->applyFilters($filters)
            ->select('pipeline_stage', DB::raw('COUNT(*) as total'))
            ->groupBy('pipeline_stage')
            ->get();

        foreach ($rows as $row) {
            $stage = (string) $row->pipeline_stage;

            if (in_array($stage, $this->stages, true)) {
                $distribution[$stage] = (int) $row->total;
            }
        }

        return $distribution;
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array<int, array<string, int|float|string|null>>
     */
    public function getUserPerformance(array $filters = []): array
    {
        $user = Auth::user();

        $rows = $this->applyFilters($filters)
            ->leftJoin('users', 'users.id', '=', 're_consults.assigned_to')
            ->selectRaw('re_consults.assigned_to as user_id')
            ->selectRaw("COALESCE(users.first_name, 'Unassigned') as name")
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN re_consults.pipeline_stage = 'won' THEN 1 ELSE 0 END) as won")
            ->groupBy('re_consults.assigned_to', 'users.first_name')
            ->get()
            ->map(function ($row): array {
                $total = (int) $row->total;
                $won = (int) $row->won;

                return [
                    'user_id' => $row->user_id ? (int) $row->user_id : null,
                    'name' => (string) $row->name,
                    'total' => $total,
                    'won' => $won,
                    'conversion_rate' => $total > 0 ? round(($won / $total) * 100, 2) : 0.0,
                ];
            })
            ->sortByDesc('conversion_rate')
            ->values()
            ->all();

        if ($user && ! $user->isSuperUser()) {
            return array_values(array_filter(
                $rows,
                fn (array $row): bool => (int) ($row['user_id'] ?? 0) === (int) $user->getKey()
            ));
        }

        return $rows;
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, array<string, int|float>>
     */
    public function getReferenceSplit(array $filters = []): array
    {
        $rows = $this->applyFilters($filters)
            ->select('reference_type')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN pipeline_stage = 'won' THEN 1 ELSE 0 END) as won")
            ->groupBy('reference_type')
            ->get();

        $result = [];

        foreach ($rows as $row) {
            if (! $row->reference_type) {
                continue;
            }

            $key = class_basename($row->reference_type);
            $total = (int) $row->total;
            $won = (int) $row->won;

            $result[$key] = [
                'total' => $total,
                'won' => $won,
                'conversion_rate' => $total > 0 ? round(($won / $total) * 100, 2) : 0.0,
            ];
        }

        return $result;
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    protected function applyFilters(array $filters): Builder
    {
        $query = Consult::query();

        [$from, $to] = $this->resolveDateRange($filters);

        $query->whereBetween('created_at', [$from, $to]);

        if (! empty($filters['stage']) && in_array($filters['stage'], $this->stages, true)) {
            $query->where('pipeline_stage', $filters['stage']);
        }

        if (array_key_exists('assigned_to', $filters) && $filters['assigned_to'] !== null && $filters['assigned_to'] !== '') {
            $query->where('assigned_to', (int) $filters['assigned_to']);
        }

        if (! empty($filters['reference_type']) && in_array($filters['reference_type'], $this->allowedReferenceTypes, true)) {
            $query->where('reference_type', $filters['reference_type']);
        }

        $user = Auth::user();

        if ($user && ! $user->isSuperUser()) {
            $query->where('assigned_to', $user->getKey());
        }

        return $query;
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array{0: CarbonInterface, 1: CarbonInterface}
     */
    protected function resolveDateRange(array $filters): array
    {
        $defaultFrom = Carbon::now()->startOfMonth();
        $defaultTo = Carbon::now()->endOfMonth();

        $from = ! empty($filters['from']) ? Carbon::parse($filters['from'])->startOfDay() : $defaultFrom;
        $to = ! empty($filters['to']) ? Carbon::parse($filters['to'])->endOfDay() : $defaultTo;

        if ($from->greaterThan($to)) {
            return [$defaultFrom, $defaultTo];
        }

        return [$from, $to];
    }
}
