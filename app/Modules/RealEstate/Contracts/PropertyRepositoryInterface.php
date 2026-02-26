<?php

namespace App\Modules\RealEstate\Contracts;

use App\Modules\RealEstate\Models\Property;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface PropertyRepositoryInterface
{
    /**
     * Create a new property.
     *
     * @param array $data
     * @return Property
     */
    public function create(array $data): Property;

    /**
     * Update a property.
     *
     * @param Property $property
     * @param array $data
     * @return Property
     */
    public function update(Property $property, array $data): Property;

    /**
     * Delete a property.
     *
     * @param Property $property
     * @return bool
     */
    public function delete(Property $property): bool;

    /**
     * Find property by ID.
     *
     * @param int $id
     * @return Property|null
     */
    public function findById(int $id): ?Property;

    /**
     * Find property by slug.
     *
     * @param string $slug
     * @return Property|null
     */
    public function findBySlug(string $slug): ?Property;

    /**
     * Find property by slug (including non-approved).
     *
     * @param string $slug
     * @return Property|null
     */
    public function findBySlugWithAll(string $slug): ?Property;

    /**
     * Get all properties with pagination.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get all properties including non-approved with pagination.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginateAll(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get pending properties with pagination.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginatePending(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get properties by user ID.
     *
     * @param int $userId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByUserId(int $userId, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get properties by user ID with all statuses.
     *
     * @param int $userId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByUserIdWithAll(int $userId, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get properties by developer project ID.
     *
     * @param int $projectId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByProjectId(int $projectId, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get featured properties.
     *
     * @param int $limit
     * @return Collection
     */
    public function findFeatured(int $limit = 6): Collection;

    /**
     * Get properties by city.
     *
     * @param string $city
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByCity(string $city, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get properties by type (sale/rent/pg).
     *
     * @param string $type
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByType(string $type, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get properties by property type.
     *
     * @param string $propertyType
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByPropertyType(string $propertyType, int $perPage = 15): LengthAwarePaginator;

    /**
     * Search properties.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function search(array $filters, int $perPage = 15): LengthAwarePaginator;

    /**
     * Approve a property.
     *
     * @param int $propertyId
     * @param int $approvedBy
     * @return bool
     */
    public function approve(int $propertyId, int $approvedBy): bool;

    /**
     * Reject a property.
     *
     * @param int $propertyId
     * @param int $rejectedBy
     * @return bool
     */
    public function reject(int $propertyId, int $rejectedBy): bool;

    /**
     * Bulk approve properties.
     *
     * @param array $propertyIds
     * @param int $approvedBy
     * @return int
     */
    public function bulkApprove(array $propertyIds, int $approvedBy): int;

    /**
     * Get similar properties.
     *
     * @param Property $property
     * @param int $limit
     * @return Collection
     */
    public function findSimilar(Property $property, int $limit = 4): Collection;

    /**
     * Update property status.
     *
     * @param int $propertyId
     * @param string $status
     * @return bool
     */
    public function updateStatus(int $propertyId, string $status): bool;
}
