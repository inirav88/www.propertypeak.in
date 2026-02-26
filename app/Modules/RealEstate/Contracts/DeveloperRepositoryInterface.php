<?php

namespace App\Modules\RealEstate\Contracts;

use App\Modules\RealEstate\Models\Developer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface DeveloperRepositoryInterface
{
    /**
     * Create a new developer.
     *
     * @param array $data
     * @return Developer
     */
    public function create(array $data): Developer;

    /**
     * Update a developer.
     *
     * @param Developer $developer
     * @param array $data
     * @return Developer
     */
    public function update(Developer $developer, array $data): Developer;

    /**
     * Delete a developer.
     *
     * @param Developer $developer
     * @return bool
     */
    public function delete(Developer $developer): bool;

    /**
     * Find developer by ID.
     *
     * @param int $id
     * @return Developer|null
     */
    public function findById(int $id): ?Developer;

    /**
     * Find developer by slug.
     *
     * @param string $slug
     * @return Developer|null
     */
    public function findBySlug(string $slug): ?Developer;

    /**
     * Find approved developer by slug.
     *
     * @param string $slug
     * @return Developer|null
     */
    public function findApprovedBySlug(string $slug): ?Developer;

    /**
     * Find developer by user ID.
     *
     * @param int $userId
     * @return Developer|null
     */
    public function findByUserId(int $userId): ?Developer;

    /**
     * Get all developers with pagination.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get all approved developers with pagination.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginateApproved(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get all approved developers.
     *
     * @return Collection
     */
    public function findApproved(): Collection;

    /**
     * Get featured developers.
     *
     * @param int $limit
     * @return Collection
     */
    public function findFeatured(int $limit = 6): Collection;

    /**
     * Search developers by name.
     *
     * @param string $query
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function search(string $query, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get developers by city.
     *
     * @param string $city
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByCity(string $city, int $perPage = 15): LengthAwarePaginator;

    /**
     * Increment project counts for a developer.
     *
     * @param int $developerId
     * @param string $type (completed|ongoing|total)
     * @return bool
     */
    public function incrementProjectCount(int $developerId, string $type): bool;

    /**
     * Decrement project counts for a developer.
     *
     * @param int $developerId
     * @param string $type (completed|ongoing|total)
     * @return bool
     */
    public function decrementProjectCount(int $developerId, string $type): bool;
}
