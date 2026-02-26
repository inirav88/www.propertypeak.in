<?php

namespace App\Modules\RealEstate\Repositories;

use App\Models\User;
use App\Modules\RealEstate\Contracts\ProjectRepositoryInterface;
use App\Modules\RealEstate\Models\DeveloperProject;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ProjectRepository implements ProjectRepositoryInterface
{
    /**
     * Create a new project.
     */
    public function create(array $data): DeveloperProject
    {
        return DB::transaction(function () use ($data): DeveloperProject {
            $project = DeveloperProject::create($data);
            return $project->fresh(['developer', 'developer.user']);
        });
    }

    /**
     * Update a project.
     */
    public function update(DeveloperProject $project, array $data): DeveloperProject
    {
        return DB::transaction(function () use ($project, $data): DeveloperProject {
            $project->update($data);
            return $project->fresh(['developer', 'developer.user']);
        });
    }

    /**
     * Delete a project.
     */
    public function delete(DeveloperProject $project): bool
    {
        return DB::transaction(function () use ($project): bool {
            return $project->delete();
        });
    }

    /**
     * Find project by ID.
     */
    public function findById(int $id): ?DeveloperProject
    {
        return DeveloperProject::with(['developer', 'developer.user', 'properties'])->find($id);
    }

    /**
     * Find project by slug.
     */
    public function findBySlug(string $slug): ?DeveloperProject
    {
        return DeveloperProject::with(['developer', 'developer.user', 'properties'])
            ->where('slug', $slug)
            ->first();
    }

    /**
     * Find approved project by slug.
     */
    public function findApprovedBySlug(string $slug): ?DeveloperProject
    {
        return DeveloperProject::with([
                'developer',
                'developer.user',
                'approvedProperties' => function ($query): void {
                    $query->where('approval_status', 'approved')
                        ->orderBy('created_at', 'desc');
                }
            ])
            ->where('slug', $slug)
            ->where('approval_status', 'approved')
            ->whereHas('developer.user', function ($query): void {
                $query->where('status', User::STATUS_APPROVED);
            })
            ->first();
    }

    /**
     * Get all projects with pagination.
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return DeveloperProject::with(['developer', 'developer.user'])
            ->where('approval_status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get all projects including non-approved with pagination.
     */
    public function paginateAll(int $perPage = 15): LengthAwarePaginator
    {
        return DeveloperProject::with(['developer', 'developer.user'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get pending projects with pagination.
     */
    public function paginatePending(int $perPage = 15): LengthAwarePaginator
    {
        return DeveloperProject::with(['developer', 'developer.user'])
            ->where('approval_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get all approved projects.
     */
    public function findApproved(): Collection
    {
        return DeveloperProject::with(['developer', 'developer.user'])
            ->where('approval_status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get projects by developer ID.
     */
    public function findByDeveloperId(int $developerId, int $perPage = 15): LengthAwarePaginator
    {
        return DeveloperProject::with(['properties'])
            ->where('developer_id', $developerId)
            ->where('approval_status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get projects by developer ID with all statuses.
     */
    public function findByDeveloperIdWithAll(int $developerId, int $perPage = 15): LengthAwarePaginator
    {
        return DeveloperProject::with(['properties'])
            ->where('developer_id', $developerId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get featured projects.
     */
    public function findFeatured(int $limit = 6): Collection
    {
        return DeveloperProject::with(['developer', 'developer.user'])
            ->where('is_featured', true)
            ->where(function ($q): void {
                $q->whereNull('featured_until')
                    ->orWhere('featured_until', '>', now());
            })
            ->where('approval_status', 'approved')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get projects by city.
     */
    public function findByCity(string $city, int $perPage = 15): LengthAwarePaginator
    {
        return DeveloperProject::with(['developer', 'developer.user'])
            ->where('city', $city)
            ->where('approval_status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get projects by status.
     */
    public function findByStatus(string $status, int $perPage = 15): LengthAwarePaginator
    {
        return DeveloperProject::with(['developer', 'developer.user'])
            ->where('status', $status)
            ->where('approval_status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get projects by type.
     */
    public function findByType(string $type, int $perPage = 15): LengthAwarePaginator
    {
        return DeveloperProject::with(['developer', 'developer.user'])
            ->where('type', $type)
            ->where('approval_status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Search projects.
     */
    public function search(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return DeveloperProject::with(['developer', 'developer.user'])
            ->where(function ($q) use ($query): void {
                $q->where('name', 'like', '%' . $query . '%')
                    ->orWhere('description', 'like', '%' . $query . '%')
                    ->orWhere('location', 'like', '%' . $query . '%')
                    ->orWhere('city', 'like', '%' . $query . '%')
                    ->orWhere('rera_number', 'like', '%' . $query . '%');
            })
            ->where('approval_status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Approve a project.
     */
    public function approve(int $projectId, int $approvedBy): bool
    {
        $project = DeveloperProject::find($projectId);

        if (!$project) {
            return false;
        }

        return $project->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $approvedBy,
        ]);
    }

    /**
     * Reject a project.
     */
    public function reject(int $projectId, int $rejectedBy): bool
    {
        $project = DeveloperProject::find($projectId);

        if (!$project) {
            return false;
        }

        return $project->update([
            'approval_status' => 'rejected',
            'approved_at' => now(),
            'approved_by' => $rejectedBy,
        ]);
    }

    /**
     * Get upcoming projects.
     */
    public function findUpcoming(int $limit = 6): Collection
    {
        return DeveloperProject::with(['developer', 'developer.user'])
            ->where('status', 'upcoming')
            ->where('approval_status', 'approved')
            ->orderBy('launch_date', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get ongoing projects.
     */
    public function findOngoing(int $limit = 6): Collection
    {
        return DeveloperProject::with(['developer', 'developer.user'])
            ->where('status', 'ongoing')
            ->where('approval_status', 'approved')
            ->orderBy('completion_date', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get completed projects.
     */
    public function findCompleted(int $limit = 6): Collection
    {
        return DeveloperProject::with(['developer', 'developer.user'])
            ->where('status', 'completed')
            ->where('approval_status', 'approved')
            ->orderBy('completion_date', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get projects by developer and status.
     */
    public function findByDeveloperAndStatus(int $developerId, string $status, int $perPage = 15): LengthAwarePaginator
    {
        return DeveloperProject::with(['properties'])
            ->where('developer_id', $developerId)
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
