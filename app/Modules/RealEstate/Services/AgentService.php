<?php

namespace App\Modules\RealEstate\Services;

use App\Models\User;
use App\Modules\RealEstate\Models\Agent;
use App\Modules\RealEstate\Models\Property;
use App\Modules\RealEstate\Repositories\AgentRepository;
use App\Modules\RealEstate\Repositories\PropertyRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AgentService
{
    public function __construct(
        private AgentRepository $agentRepository,
        private PropertyRepository $propertyRepository,
    ) {}

    /**
     * Create or update agent profile.
     *
     * @param User $user
     * @param array $data
     * @return Agent
     */
    public function saveProfile(User $user, array $data): Agent
    {
        $agent = $this->agentRepository->findByUserId($user->id);

        // Handle avatar upload
        if (isset($data['avatar']) && $data['avatar'] instanceof UploadedFile) {
            $data['avatar'] = $this->uploadImage($data['avatar'], 'agents/avatars');
            
            // Delete old avatar if exists
            if ($agent && $agent->avatar) {
                Storage::disk('public')->delete($agent->avatar);
            }
        }

        // Handle banner upload
        if (isset($data['banner_image']) && $data['banner_image'] instanceof UploadedFile) {
            $data['banner_image'] = $this->uploadImage($data['banner_image'], 'agents/banners');
            
            // Delete old banner if exists
            if ($agent && $agent->banner_image) {
                Storage::disk('public')->delete($agent->banner_image);
            }
        }

        if ($agent) {
            return $this->agentRepository->update($agent, $data);
        }

        // Create new agent profile
        $data['user_id'] = $user->id;
        $data['slug'] = $this->generateUniqueSlug(
            $data['first_name'] . ' ' . $data['last_name']
        );

        return $this->agentRepository->create($data);
    }

    /**
     * Get agent dashboard statistics.
     *
     * @param int $agentId
     * @return array
     */
    public function getDashboardStats(int $agentId): array
    {
        $agent = $this->agentRepository->findById($agentId);

        if (!$agent) {
            return [];
        }

        return [
            'total_properties' => $agent->properties()->count(),
            'active_listings' => $agent->properties()
                ->where('status', 'available')
                ->where('approval_status', 'approved')
                ->count(),
            'approved_properties' => $agent->properties()
                ->where('approval_status', 'approved')
                ->count(),
            'pending_properties' => $agent->properties()
                ->where('approval_status', 'pending')
                ->count(),
            'total_sales' => $agent->total_sales,
            'experience_years' => $agent->experience_years,
            'total_views' => 0, // Will be implemented with analytics
            'total_leads' => 0, // Will be implemented with lead management
        ];
    }

    /**
     * Get agent's properties.
     *
     * @param int $userId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getProperties(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->propertyRepository->findByUserIdWithAll($userId, $perPage);
    }

    /**
     * Create a new property.
     *
     * @param int $userId
     * @param array $data
     * @return Property
     */
    public function createProperty(int $userId, array $data): Property
    {
        $data['user_id'] = $userId;
        $data['added_by_role'] = 'agent';
        $data['slug'] = $this->generatePropertySlug($data['title']);
        $data['approval_status'] = 'pending';

        // Handle property images
        if (isset($data['images'])) {
            $data['images'] = $this->uploadMultipleImages($data['images'], 'properties');
        }

        $property = $this->propertyRepository->create($data);

        // Update agent listing count
        $this->agentRepository->incrementPropertyCount($userId);

        return $property;
    }

    /**
     * Update a property.
     *
     * @param Property $property
     * @param array $data
     * @return Property
     */
    public function updateProperty(Property $property, array $data): Property
    {
        // Handle new images
        if (isset($data['images'])) {
            $existingImages = $property->images ?? [];
            $newImages = $this->uploadMultipleImages($data['images'], 'properties');
            $data['images'] = array_merge($existingImages, $newImages);
        }

        return $this->propertyRepository->update($property, $data);
    }

    /**
     * Delete a property.
     *
     * @param Property $property
     * @return bool
     */
    public function deleteProperty(Property $property): bool
    {
        $userId = $property->user_id;

        // Delete associated images
        if ($property->images) {
            foreach ($property->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $deleted = $this->propertyRepository->delete($property);

        if ($deleted) {
            // Update agent listing count
            $this->agentRepository->decrementPropertyCount($userId);
        }

        return $deleted;
    }

    /**
     * Get public agent profile.
     *
     * @param string $slug
     * @return Agent|null
     */
    public function getPublicProfile(string $slug): ?Agent
    {
        return $this->agentRepository->findApprovedBySlug($slug);
    }

    /**
     * List approved agents.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function listApproved(int $perPage = 12): LengthAwarePaginator
    {
        return $this->agentRepository->paginateApproved($perPage);
    }

    /**
     * Search agents.
     *
     * @param string $query
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function search(string $query, int $perPage = 12): LengthAwarePaginator
    {
        return $this->agentRepository->search($query, $perPage);
    }

    /**
     * Get agents by city.
     *
     * @param string $city
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getByCity(string $city, int $perPage = 12): LengthAwarePaginator
    {
        return $this->agentRepository->findByCity($city, $perPage);
    }

    /**
     * Get agents by specialization.
     *
     * @param string $specialization
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getBySpecialization(string $specialization, int $perPage = 12): LengthAwarePaginator
    {
        return $this->agentRepository->findBySpecialization($specialization, $perPage);
    }

    /**
     * Update agent statistics.
     *
     * @param int $agentId
     * @param array $stats
     * @return bool
     */
    public function updateStats(int $agentId, array $stats): bool
    {
        return $this->agentRepository->updateStats($agentId, $stats);
    }

    /**
     * Record a sale.
     *
     * @param int $agentId
     * @return bool
     */
    public function recordSale(int $agentId): bool
    {
        $agent = $this->agentRepository->findById($agentId);

        if (!$agent) {
            return false;
        }

        return $this->agentRepository->updateStats($agentId, [
            'total_sales' => $agent->total_sales + 1,
        ]);
    }

    /**
     * Upload an image.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @return string
     */
    private function uploadImage(UploadedFile $file, string $directory): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($directory, $filename, 'public');
    }

    /**
     * Upload multiple images.
     *
     * @param array $files
     * @param string $directory
     * @return array
     */
    private function uploadMultipleImages(array $files, string $directory): array
    {
        $paths = [];
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $paths[] = $this->uploadImage($file, $directory);
            }
        }
        return $paths;
    }

    /**
     * Generate unique slug for agent.
     *
     * @param string $name
     * @return string
     */
    private function generateUniqueSlug(string $name): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (Agent::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Generate unique slug for property.
     *
     * @param string $title
     * @return string
     */
    private function generatePropertySlug(string $title): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while (Property::withoutGlobalScope('approved')->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
