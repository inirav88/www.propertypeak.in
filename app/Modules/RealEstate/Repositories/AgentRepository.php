<?php

namespace App\Modules\RealEstate\Repositories;

use App\Models\User;
use App\Modules\RealEstate\Contracts\AgentRepositoryInterface;
use App\Modules\RealEstate\Models\Agent;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AgentRepository implements AgentRepositoryInterface
{
    /**
     * Create a new agent.
     */
    public function create(array $data): Agent
    {
        return DB::transaction(function () use ($data): Agent {
            $agent = Agent::create($data);
            return $agent->fresh(['user']);
        });
    }

    /**
     * Update an agent.
     */
    public function update(Agent $agent, array $data): Agent
    {
        return DB::transaction(function () use ($agent, $data): Agent {
            $agent->update($data);
            return $agent->fresh(['user']);
        });
    }

    /**
     * Delete an agent.
     */
    public function delete(Agent $agent): bool
    {
        return DB::transaction(function () use ($agent): bool {
            return $agent->delete();
        });
    }

    /**
     * Find agent by ID.
     */
    public function findById(int $id): ?Agent
    {
        return Agent::with(['user'])->find($id);
    }

    /**
     * Find agent by slug.
     */
    public function findBySlug(string $slug): ?Agent
    {
        return Agent::with(['user'])
            ->where('slug', $slug)
            ->first();
    }

    /**
     * Find approved agent by slug.
     */
    public function findApprovedBySlug(string $slug): ?Agent
    {
        return Agent::with([
                'user',
                'approvedProperties' => function ($query): void {
                    $query->where('approval_status', 'approved')
                        ->orderBy('created_at', 'desc');
                }
            ])
            ->where('slug', $slug)
            ->whereHas('user', function ($query): void {
                $query->where('status', User::STATUS_APPROVED);
            })
            ->first();
    }

    /**
     * Find agent by user ID.
     */
    public function findByUserId(int $userId): ?Agent
    {
        return Agent::with(['user'])
            ->where('user_id', $userId)
            ->first();
    }

    /**
     * Get all agents with pagination.
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Agent::with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get all approved agents with pagination.
     */
    public function paginateApproved(int $perPage = 15): LengthAwarePaginator
    {
        return Agent::with(['user'])
            ->withCount('properties')
            ->whereHas('user', function ($query): void {
                $query->where('status', User::STATUS_APPROVED);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get all approved agents.
     */
    public function findApproved(): Collection
    {
        return Agent::with(['user'])
            ->withCount('properties')
            ->whereHas('user', function ($query): void {
                $query->where('status', User::STATUS_APPROVED);
            })
            ->orderBy('first_name')
            ->get();
    }

    /**
     * Get featured agents.
     */
    public function findFeatured(int $limit = 6): Collection
    {
        return Agent::with(['user'])
            ->withCount('properties')
            ->whereHas('user', function ($query): void {
                $query->where('status', User::STATUS_APPROVED);
            })
            ->orderBy('total_sales', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Search agents by name.
     */
    public function search(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return Agent::with(['user'])
            ->withCount('properties')
            ->where(function ($q) use ($query): void {
                $q->where('first_name', 'like', '%' . $query . '%')
                    ->orWhere('last_name', 'like', '%' . $query . '%')
                    ->orWhere('bio', 'like', '%' . $query . '%')
                    ->orWhere('rera_id', 'like', '%' . $query . '%');
            })
            ->whereHas('user', function ($q): void {
                $q->where('status', User::STATUS_APPROVED);
            })
            ->orderBy('first_name')
            ->paginate($perPage);
    }

    /**
     * Get agents by city.
     */
    public function findByCity(string $city, int $perPage = 15): LengthAwarePaginator
    {
        return Agent::with(['user'])
            ->whereJsonContains('service_areas', $city)
            ->whereHas('user', function ($query): void {
                $query->where('status', User::STATUS_APPROVED);
            })
            ->orderBy('first_name')
            ->paginate($perPage);
    }

    /**
     * Get agents by specialization.
     */
    public function findBySpecialization(string $specialization, int $perPage = 15): LengthAwarePaginator
    {
        return Agent::with(['user'])
            ->whereJsonContains('specializations', $specialization)
            ->whereHas('user', function ($query): void {
                $query->where('status', User::STATUS_APPROVED);
            })
            ->orderBy('experience_years', 'desc')
            ->paginate($perPage);
    }

    /**
     * Update agent stats.
     */
    public function updateStats(int $agentId, array $stats): bool
    {
        $agent = $this->findById($agentId);

        if (!$agent) {
            return false;
        }

        $allowedFields = ['total_sales', 'active_listings', 'experience_years'];
        $updateData = array_intersect_key($stats, array_flip($allowedFields));

        if (empty($updateData)) {
            return false;
        }

        return $agent->update($updateData);
    }

    /**
     * Increment property count.
     */
    public function incrementPropertyCount(int $agentId): bool
    {
        $agent = $this->findById($agentId);

        if (!$agent) {
            return false;
        }

        $agent->increment('active_listings');
        return true;
    }

    /**
     * Decrement property count.
     */
    public function decrementPropertyCount(int $agentId): bool
    {
        $agent = $this->findById($agentId);

        if (!$agent) {
            return false;
        }

        if ($agent->active_listings > 0) {
            $agent->decrement('active_listings');
        }

        return true;
    }
}
