<?php

namespace App\Modules\RealEstate\Repositories;

use App\Modules\RealEstate\Contracts\PropertyRepositoryInterface;
use App\Modules\RealEstate\Models\Property;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PropertyRepository implements PropertyRepositoryInterface
{
    /**
     * Create a new property.
     */
    public function create(array $data): Property
    {
        return DB::transaction(function () use ($data): Property {
            $property = Property::withoutGlobalScope('approved')->create($data);
            return $property->fresh(['user', 'developer', 'agent']);
        });
    }

    /**
     * Update a property.
     */
    public function update(Property $property, array $data): Property
    {
        return DB::transaction(function () use ($property, $data): Property {
            $property->update($data);
            return $property->fresh(['user', 'developer', 'agent']);
        });
    }

    /**
     * Delete a property.
     */
    public function delete(Property $property): bool
    {
        return DB::transaction(function () use ($property): bool {
            return $property->delete();
        });
    }

    /**
     * Find property by ID.
     */
    public function findById(int $id): ?Property
    {
        return Property::with(['user', 'developer', 'agent', 'developerProject'])->find($id);
    }

    /**
     * Find property by slug.
     */
    public function findBySlug(string $slug): ?Property
    {
        return Property::withoutGlobalScope('approved')
            ->with(['user', 'developer', 'agent', 'developerProject'])
            ->where('slug', $slug)
            ->whereIn('approval_status', ['approved', 'pending'])
            ->first();
    }

    /**
     * Find property by slug (including non-approved).
     */
    public function findBySlugWithAll(string $slug): ?Property
    {
        return Property::withoutGlobalScope('approved')
            ->with(['user', 'developer', 'agent', 'developerProject'])
            ->where('slug', $slug)
            ->first();
    }

    /**
     * Get all properties with pagination (approved only due to global scope).
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Property::with(['user', 'developer', 'agent'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get all properties including non-approved with pagination.
     */
    public function paginateAll(int $perPage = 15): LengthAwarePaginator
    {
        return Property::withoutGlobalScope('approved')
            ->with(['user', 'developer', 'agent'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get pending properties with pagination.
     */
    public function paginatePending(int $perPage = 15): LengthAwarePaginator
    {
        return Property::withoutGlobalScope('approved')
            ->with(['user', 'developer', 'agent'])
            ->where('approval_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get properties by user ID (approved only).
     */
    public function findByUserId(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return Property::with(['developerProject'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get properties by user ID with all statuses.
     */
    public function findByUserIdWithAll(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return Property::withoutGlobalScope('approved')
            ->with(['developerProject'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get properties by developer project ID.
     */
    public function findByProjectId(int $projectId, int $perPage = 15): LengthAwarePaginator
    {
        return Property::with(['user', 'developer', 'developerProject'])
            ->where('developer_project_id', $projectId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get featured properties.
     */
    public function findFeatured(int $limit = 6): Collection
    {
        return Property::with(['user', 'developer', 'agent'])
            ->where('is_featured', true)
            ->where(function ($q): void {
                $q->whereNull('featured_until')
                    ->orWhere('featured_until', '>', now());
            })
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get properties by location.
     */
    public function findByCity(string $city, int $perPage = 15): LengthAwarePaginator
    {
        return Property::with(['user', 'developer', 'agent'])
            ->where('location', 'like', '%' . $city . '%')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get properties by type (sale/rent/pg).
     */
    public function findByType(string $type, int $perPage = 15): LengthAwarePaginator
    {
        return Property::with(['user', 'developer', 'agent'])
            ->where('type', $type)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get properties by property type.
     * Note: Uses 'type' column since Botble schema doesn't have separate property_type column
     */
    public function findByPropertyType(string $propertyType, int $perPage = 15): LengthAwarePaginator
    {
        return Property::with(['user', 'developer', 'agent'])
            ->where('type', $propertyType)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Search properties with filters.
     */
    public function search(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = Property::with(['user', 'developer', 'agent']);

        // Location filter (uses 'location' column in Botble schema)
        if (!empty($filters['city'])) {
            $query->where('location', 'like', '%' . $filters['city'] . '%');
        }

        // Type filter (sale/rent/pg)
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        // Property type filter (maps to 'type' column in Botble)
        if (!empty($filters['property_type'])) {
            $query->where('type', $filters['property_type']);
        }

        // Bedrooms filter (uses 'number_bedroom' column)
        if (!empty($filters['bedrooms'])) {
            $query->where('number_bedroom', '>=', $filters['bedrooms']);
        }

        // Price range filter
        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        // Keyword search (uses 'name' instead of 'title', 'location' instead of 'city/address')
        if (!empty($filters['keyword'])) {
            $keyword = $filters['keyword'];
            $query->where(function ($q) use ($keyword): void {
                $q->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('description', 'like', '%' . $keyword . '%')
                    ->orWhere('location', 'like', '%' . $keyword . '%')
                    ->orWhere('content', 'like', '%' . $keyword . '%');
            });
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($perPage);
    }

    /**
     * Approve a property.
     */
    public function approve(int $propertyId, int $approvedBy): bool
    {
        $property = Property::withoutGlobalScope('approved')->find($propertyId);

        if (!$property) {
            return false;
        }

        return $property->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $approvedBy,
        ]);
    }

    /**
     * Reject a property.
     */
    public function reject(int $propertyId, int $rejectedBy): bool
    {
        $property = Property::withoutGlobalScope('approved')->find($propertyId);

        if (!$property) {
            return false;
        }

        return $property->update([
            'approval_status' => 'rejected',
            'approved_at' => now(),
            'approved_by' => $rejectedBy,
        ]);
    }

    /**
     * Bulk approve properties.
     */
    public function bulkApprove(array $propertyIds, int $approvedBy): int
    {
        return Property::withoutGlobalScope('approved')
            ->whereIn('id', $propertyIds)
            ->where('approval_status', 'pending')
            ->update([
                'approval_status' => 'approved',
                'approved_at' => now(),
                'approved_by' => $approvedBy,
            ]);
    }

    /**
     * Get similar properties.
     */
    public function findSimilar(Property $property, int $limit = 4): Collection
    {
        return Property::withoutGlobalScope('approved')
            ->with(['user', 'developer', 'agent'])
            ->where('id', '!=', $property->id)
            ->whereIn('approval_status', ['approved', 'pending'])
            ->where(function ($q) use ($property): void {
                // Use location instead of city since that's the actual column name
                if ($property->location) {
                    $q->orWhere('location', $property->location);
                }
                // Use type instead of property_type since that's the actual column name
                if ($property->type) {
                    $q->orWhere('type', $property->type);
                }
                if ($property->price) {
                    $q->orWhereBetween('price', [$property->price * 0.8, $property->price * 1.2]);
                }
            })
            ->limit($limit)
            ->get();
    }

    /**
     * Update property status.
     */
    public function updateStatus(int $propertyId, string $status): bool
    {
        $property = Property::withoutGlobalScope('approved')->find($propertyId);

        if (!$property) {
            return false;
        }

        return $property->update(['status' => $status]);
    }
}
