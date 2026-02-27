<?php

namespace App\Modules\RealEstate\Repositories;

use App\Models\User;
use App\Modules\RealEstate\Contracts\DeveloperRepositoryInterface;
use App\Modules\RealEstate\Models\Developer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class DeveloperRepository implements DeveloperRepositoryInterface
{
    /**
     * Create a new developer.
     */
    public function create(array $data): Developer
    {
        return DB::transaction(function () use ($data): Developer {
            $developer = Developer::create($data);
            return $developer->fresh(['user']);
        });
    }

    /**
     * Update a developer.
     */
    public function update(Developer $developer, array $data): Developer
    {
        return DB::transaction(function () use ($developer, $data): Developer {
            $developer->update($data);
            return $developer->fresh(['user']);
        });
    }

    /**
     * Delete a developer.
     */
    public function delete(Developer $developer): bool
    {
        return DB::transaction(function () use ($developer): bool {
            return $developer->delete();
        });
    }

    /**
     * Find developer by ID.
     */
    public function findById(int $id): ?Developer
    {
        return Developer::with(['user', 'projects'])->find($id);
    }

    /**
     * Find developer by slug.
     */
    public function findBySlug(string $slug): ?Developer
    {
        return Developer::with(['user', 'projects'])
            ->where('slug', $slug)
            ->first();
    }

    /**
     * Find approved developer by slug.
     */
    public function findApprovedBySlug(string $slug): ?Developer
    {
        return Developer::with([
                'user',
                'approvedProjects' => function ($query): void {
                    $query->where('approval_status', 'approved')
                        ->orderBy('created_at', 'desc');
                },
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
     * Find developer by user ID.
     */
    public function findByUserId(int $userId): ?Developer
    {
        return Developer::with(['user'])
            ->where('user_id', $userId)
            ->first();
    }

    /**
     * Get all developers with pagination.
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Developer::with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get all approved developers with pagination.
     */
    public function paginateApproved(int $perPage = 15): LengthAwarePaginator
    {
        return Developer::with(['user'])
            ->withCount(['projects', 'properties'])
            ->whereHas('user', function ($query): void {
                $query->where('status', User::STATUS_APPROVED);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get all approved developers.
     */
    public function findApproved(): Collection
    {
        return Developer::with(['user'])
            ->withCount(['projects', 'properties'])
            ->whereHas('user', function ($query): void {
                $query->where('status', User::STATUS_APPROVED);
            })
            ->orderBy('company_name')
            ->get();
    }

    /**
     * Get featured developers.
     */
    public function findFeatured(int $limit = 6): Collection
    {
        return Developer::with(['user'])
            ->withCount(['projects', 'properties'])
            ->whereHas('user', function ($query): void {
                $query->where('status', User::STATUS_APPROVED);
            })
            ->orderBy('completed_projects', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Search developers by name.
     */
    public function search(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return Developer::with(['user'])
            ->withCount(['projects', 'properties'])
            ->where(function ($q) use ($query): void {
                $q->where('company_name', 'like', '%' . $query . '%')
                    ->orWhere('description', 'like', '%' . $query . '%')
                    ->orWhere('rera_id', 'like', '%' . $query . '%');
            })
            ->whereHas('user', function ($q): void {
                $q->where('status', User::STATUS_APPROVED);
            })
            ->orderBy('company_name')
            ->paginate($perPage);
    }

    /**
     * Get developers by city.
     */
    public function findByCity(string $city, int $perPage = 15): LengthAwarePaginator
    {
        return Developer::with(['user'])
            ->whereHas('projects', function ($query) use ($city): void {
                $query->where('city', $city)
                    ->where('approval_status', 'approved');
            })
            ->whereHas('user', function ($query): void {
                $query->where('status', User::STATUS_APPROVED);
            })
            ->orderBy('company_name')
            ->paginate($perPage);
    }

    /**
     * Increment project counts for a developer.
     */
    public function incrementProjectCount(int $developerId, string $type): bool
    {
        $developer = $this->findById($developerId);

        if (!$developer) {
            return false;
        }

        $field = match ($type) {
            'completed' => 'completed_projects',
            'ongoing' => 'ongoing_projects',
            default => 'total_projects',
        };

        $developer->increment($field);

        if ($type === 'completed' || $type === 'ongoing') {
            $developer->increment('total_projects');
        }

        return true;
    }

    /**
     * Decrement project counts for a developer.
     */
    public function decrementProjectCount(int $developerId, string $type): bool
    {
        $developer = $this->findById($developerId);

        if (!$developer) {
            return false;
        }

        $field = match ($type) {
            'completed' => 'completed_projects',
            'ongoing' => 'ongoing_projects',
            default => 'total_projects',
        };

        if ($developer->$field > 0) {
            $developer->decrement($field);
        }

        if (($type === 'completed' || $type === 'ongoing') && $developer->total_projects > 0) {
            $developer->decrement('total_projects');
        }

        return true;
    }
}
