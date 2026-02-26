<?php

namespace App\Modules\RealEstate\Contracts;

use App\Modules\RealEstate\Models\DeveloperProject;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ProjectRepositoryInterface
{
    /**
     * Create a new project.
     *
     * @param array $data
     * @return DeveloperProject
     */
    public function create(array $data): DeveloperProject;

    /**
     * Update a project.
     *
     * @param DeveloperProject $project
     * @param array $data
     * @return DeveloperProject
     */
    public function update(DeveloperProject $project, array $data): DeveloperProject;

    /**
     * Delete a project.
     *
     * @param DeveloperProject $project
     * @return bool
     */
    public function delete(DeveloperProject $project): bool;

    /**
     * Find project by ID.
     *
     * @param int $id
     * @return DeveloperProject|null
     */
    public function findById(int $id): ?DeveloperProject;

    /**
     * Find project by slug.
     *
     * @param string $slug
     * @return DeveloperProject|null
     */
    public function findBySlug(string $slug): ?DeveloperProject;

    /**
     * Find approved project by slug.
     *
     * @param string $slug
     * @return DeveloperProject|null
     */
    public function findApprovedBySlug(string $slug): ?DeveloperProject;

    /**
     * Get all projects with pagination.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get all projects including non-approved with pagination.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginateAll(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get pending projects with pagination.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginatePending(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get all approved projects.
     *
     * @return Collection
     */
    public function findApproved(): Collection;

    /**
     * Get projects by developer ID.
     *
     * @param int $developerId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByDeveloperId(int $developerId, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get projects by developer ID with all statuses.
     *
     * @param int $developerId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByDeveloperIdWithAll(int $developerId, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get featured projects.
     *
     * @param int $limit
     * @return Collection
     */
    public function findFeatured(int $limit = 6): Collection;

    /**
     * Get projects by city.
     *
     * @param string $city
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByCity(string $city, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get projects by status (upcoming/ongoing/completed).
     *
     * @param string $status
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByStatus(string $status, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get projects by type.
     *
     * @param string $type
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByType(string $type, int $perPage = 15): LengthAwarePaginator;

    /**
     * Search projects.
     *
     * @param string $query
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function search(string $query, int $perPage = 15): LengthAwarePaginator;

    /**
     * Approve a project.
     *
     * @param int $projectId
     * @param int $approvedBy
     * @return bool
     */
    public function approve(int $projectId, int $approvedBy): bool;

    /**
     * Reject a project.
     *
     * @param int $projectId
     * @param int $rejectedBy
     * @return bool
     */
    public function reject(int $projectId, int $rejectedBy): bool;

    /**
     * Get upcoming projects.
     *
     * @param int $limit
     * @return Collection
     */
    public function findUpcoming(int $limit = 6): Collection;

    /**
     * Get ongoing projects.
     *
     * @param int $limit
     * @return Collection
     */
    public function findOngoing(int $limit = 6): Collection;

    /**
     * Get completed projects.
     *
     * @param int $limit
     * @return Collection
     */
    public function findCompleted(int $limit = 6): Collection;

    /**
     * Get projects by developer and status.
     *
     * @param int $developerId
     * @param string $status
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByDeveloperAndStatus(int $developerId, string $status, int $perPage = 15): LengthAwarePaginator;
}
