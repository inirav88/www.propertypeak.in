<?php

namespace App\Modules\RealEstate\Services;

use App\Models\User;
use App\Modules\RealEstate\Models\Developer;
use App\Modules\RealEstate\Models\DeveloperProject;
use App\Modules\RealEstate\Models\Property;
use App\Modules\RealEstate\Repositories\DeveloperRepository;
use App\Modules\RealEstate\Repositories\ProjectRepository;
use App\Modules\RealEstate\Repositories\PropertyRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DeveloperService
{
    public function __construct(
        private DeveloperRepository $developerRepository,
        private ProjectRepository $projectRepository,
        private PropertyRepository $propertyRepository,
    ) {}

    /**
     * Create or update developer profile.
     *
     * @param User $user
     * @param array $data
     * @return Developer
     */
    public function saveProfile(User $user, array $data): Developer
    {
        $developer = $this->developerRepository->findByUserId($user->id);

        // Handle logo upload
        if (isset($data['logo']) && $data['logo'] instanceof UploadedFile) {
            $data['logo'] = $this->uploadImage($data['logo'], 'developers/logos');
            
            // Delete old logo if exists
            if ($developer && $developer->logo) {
                Storage::disk('public')->delete($developer->logo);
            }
        }

        // Handle banner upload
        if (isset($data['banner_image']) && $data['banner_image'] instanceof UploadedFile) {
            $data['banner_image'] = $this->uploadImage($data['banner_image'], 'developers/banners');
            
            // Delete old banner if exists
            if ($developer && $developer->banner_image) {
                Storage::disk('public')->delete($developer->banner_image);
            }
        }

        if ($developer) {
            return $this->developerRepository->update($developer, $data);
        }

        // Create new developer profile
        $data['user_id'] = $user->id;
        $data['slug'] = $this->generateUniqueSlug($data['company_name'] ?? $user->name);

        return $this->developerRepository->create($data);
    }

    /**
     * Get developer dashboard statistics.
     *
     * @param int $developerId
     * @return array
     */
    public function getDashboardStats(int $developerId): array
    {
        $developer = $this->developerRepository->findById($developerId);

        if (!$developer) {
            return [];
        }

        return [
            'total_projects' => $developer->total_projects,
            'ongoing_projects' => $developer->ongoing_projects,
            'completed_projects' => $developer->completed_projects,
            'total_properties' => $developer->properties()->count(),
            'approved_properties' => $developer->properties()
                ->where('approval_status', 'approved')
                ->count(),
            'pending_properties' => $developer->properties()
                ->where('approval_status', 'pending')
                ->count(),
            'total_views' => 0, // Will be implemented with analytics
            'total_leads' => 0, // Will be implemented with lead management
        ];
    }

    /**
     * Get developer's projects.
     *
     * @param int $developerId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getProjects(int $developerId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->projectRepository->findByDeveloperIdWithAll($developerId, $perPage);
    }

    /**
     * Get developer's properties.
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
     * Create a new project for developer.
     *
     * @param int $developerId
     * @param array $data
     * @return DeveloperProject
     */
    public function createProject(int $developerId, array $data): DeveloperProject
    {
        $data['developer_id'] = $developerId;
        $data['slug'] = $this->generateProjectSlug($data['name']);
        $data['approval_status'] = 'pending';

        // Handle gallery images
        if (isset($data['gallery_images'])) {
            $data['gallery_images'] = $this->uploadMultipleImages(
                $data['gallery_images'],
                'projects/gallery'
            );
        }

        // Handle brochure upload
        if (isset($data['brochure']) && $data['brochure'] instanceof UploadedFile) {
            $data['brochure'] = $this->uploadFile($data['brochure'], 'projects/brochures');
        }

        $project = $this->projectRepository->create($data);

        // Update developer project count
        $this->developerRepository->incrementProjectCount($developerId, 'total');

        return $project;
    }

    /**
     * Update a project.
     *
     * @param DeveloperProject $project
     * @param array $data
     * @return DeveloperProject
     */
    public function updateProject(DeveloperProject $project, array $data): DeveloperProject
    {
        // Handle new gallery images
        if (isset($data['gallery_images'])) {
            $existingImages = $project->gallery_images ?? [];
            $newImages = $this->uploadMultipleImages($data['gallery_images'], 'projects/gallery');
            $data['gallery_images'] = array_merge($existingImages, $newImages);
        }

        // Handle brochure update
        if (isset($data['brochure']) && $data['brochure'] instanceof UploadedFile) {
            // Delete old brochure
            if ($project->brochure) {
                Storage::disk('public')->delete($project->brochure);
            }
            $data['brochure'] = $this->uploadFile($data['brochure'], 'projects/brochures');
        }

        return $this->projectRepository->update($project, $data);
    }

    /**
     * Delete a project.
     *
     * @param DeveloperProject $project
     * @return bool
     */
    public function deleteProject(DeveloperProject $project): bool
    {
        $developerId = $project->developer_id;
        $status = $project->status;

        // Delete associated files
        if ($project->gallery_images) {
            foreach ($project->gallery_images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        if ($project->brochure) {
            Storage::disk('public')->delete($project->brochure);
        }

        $deleted = $this->projectRepository->delete($project);

        if ($deleted) {
            // Update developer project counts
            $this->developerRepository->decrementProjectCount($developerId, 'total');
            if ($status === 'ongoing' || $status === 'completed') {
                $this->developerRepository->decrementProjectCount($developerId, $status);
            }
        }

        return $deleted;
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
        $data['added_by_role'] = 'developer';
        $data['slug'] = $this->generatePropertySlug($data['title']);
        $data['approval_status'] = 'pending';

        // Handle property images
        if (isset($data['images'])) {
            $data['images'] = $this->uploadMultipleImages($data['images'], 'properties');
        }

        return $this->propertyRepository->create($data);
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
        // Delete associated images
        if ($property->images) {
            foreach ($property->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        return $this->propertyRepository->delete($property);
    }

    /**
     * Get public developer profile.
     *
     * @param string $slug
     * @return Developer|null
     */
    public function getPublicProfile(string $slug): ?Developer
    {
        return $this->developerRepository->findApprovedBySlug($slug);
    }

    /**
     * List approved developers.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function listApproved(int $perPage = 12): LengthAwarePaginator
    {
        return $this->developerRepository->paginateApproved($perPage);
    }

    /**
     * Search developers.
     *
     * @param string $query
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function search(string $query, int $perPage = 12): LengthAwarePaginator
    {
        return $this->developerRepository->search($query, $perPage);
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
     * Upload a file.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @return string
     */
    private function uploadFile(UploadedFile $file, string $directory): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($directory, $filename, 'public');
    }

    /**
     * Generate unique slug for developer.
     *
     * @param string $name
     * @return string
     */
    private function generateUniqueSlug(string $name): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (Developer::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Generate unique slug for project.
     *
     * @param string $name
     * @return string
     */
    private function generateProjectSlug(string $name): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (DeveloperProject::where('slug', $slug)->exists()) {
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
