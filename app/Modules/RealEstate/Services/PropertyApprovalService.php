<?php

namespace App\Modules\RealEstate\Services;

use App\Models\User;
use App\Modules\RealEstate\Models\DeveloperProject;
use App\Modules\RealEstate\Models\Property;
use App\Modules\RealEstate\Notifications\PropertyApproved;
use App\Modules\RealEstate\Notifications\PropertyRejected;
use App\Modules\RealEstate\Repositories\DeveloperRepository;
use App\Modules\RealEstate\Repositories\ProjectRepository;
use App\Modules\RealEstate\Repositories\PropertyRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class PropertyApprovalService
{
    public function __construct(
        private PropertyRepository $propertyRepository,
        private ProjectRepository $projectRepository,
        private DeveloperRepository $developerRepository,
    ) {}

    /**
     * Approve a property.
     *
     * @param int $propertyId
     * @param int $approvedBy
     * @return array{success: bool, property: Property|null, message: string}
     */
    public function approveProperty(int $propertyId, int $approvedBy): array
    {
        try {
            DB::beginTransaction();

            $property = $this->propertyRepository->findById($propertyId);

            if (!$property) {
                return [
                    'success' => false,
                    'property' => null,
                    'message' => 'Property not found.',
                ];
            }

            if ($property->approval_status === 'approved') {
                return [
                    'success' => false,
                    'property' => $property,
                    'message' => 'Property is already approved.',
                ];
            }

            $success = $this->propertyRepository->approve($propertyId, $approvedBy);

            if (!$success) {
                DB::rollBack();
                return [
                    'success' => false,
                    'property' => null,
                    'message' => 'Failed to approve property.',
                ];
            }

            // Refresh to get updated data
            $property = $this->propertyRepository->findById($propertyId);

            // Notify property owner
            $this->notifyOwner($property, 'approved');

            DB::commit();

            return [
                'success' => true,
                'property' => $property,
                'message' => 'Property approved successfully.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'property' => null,
                'message' => 'Error approving property: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Reject a property.
     *
     * @param int $propertyId
     * @param int $rejectedBy
     * @param string|null $reason
     * @return array{success: bool, property: Property|null, message: string}
     */
    public function rejectProperty(int $propertyId, int $rejectedBy, ?string $reason = null): array
    {
        try {
            DB::beginTransaction();

            $property = $this->propertyRepository->findById($propertyId);

            if (!$property) {
                return [
                    'success' => false,
                    'property' => null,
                    'message' => 'Property not found.',
                ];
            }

            if ($property->approval_status === 'rejected') {
                return [
                    'success' => false,
                    'property' => $property,
                    'message' => 'Property is already rejected.',
                ];
            }

            $success = $this->propertyRepository->reject($propertyId, $rejectedBy);

            if (!$success) {
                DB::rollBack();
                return [
                    'success' => false,
                    'property' => null,
                    'message' => 'Failed to reject property.',
                ];
            }

            // Refresh to get updated data
            $property = $this->propertyRepository->findById($propertyId);

            // Notify property owner
            $this->notifyOwner($property, 'rejected', $reason);

            DB::commit();

            return [
                'success' => true,
                'property' => $property,
                'message' => 'Property rejected successfully.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'property' => null,
                'message' => 'Error rejecting property: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Bulk approve properties.
     *
     * @param array $propertyIds
     * @param int $approvedBy
     * @return array{success: bool, count: int, message: string}
     */
    public function bulkApprove(array $propertyIds, int $approvedBy): array
    {
        try {
            DB::beginTransaction();

            if (empty($propertyIds)) {
                return [
                    'success' => false,
                    'count' => 0,
                    'message' => 'No properties selected.',
                ];
            }

            $count = $this->propertyRepository->bulkApprove($propertyIds, $approvedBy);

            // Notify owners
            foreach ($propertyIds as $propertyId) {
                $property = $this->propertyRepository->findById($propertyId);
                if ($property) {
                    $this->notifyOwner($property, 'approved');
                }
            }

            DB::commit();

            return [
                'success' => true,
                'count' => $count,
                'message' => $count . ' ' . ($count === 1 ? 'property' : 'properties') . ' approved successfully.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'count' => 0,
                'message' => 'Error approving properties: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Approve a project.
     *
     * @param int $projectId
     * @param int $approvedBy
     * @return array{success: bool, project: DeveloperProject|null, message: string}
     */
    public function approveProject(int $projectId, int $approvedBy): array
    {
        try {
            DB::beginTransaction();

            $project = $this->projectRepository->findById($projectId);

            if (!$project) {
                return [
                    'success' => false,
                    'project' => null,
                    'message' => 'Project not found.',
                ];
            }

            if ($project->approval_status === 'approved') {
                return [
                    'success' => false,
                    'project' => $project,
                    'message' => 'Project is already approved.',
                ];
            }

            $success = $this->projectRepository->approve($projectId, $approvedBy);

            if (!$success) {
                DB::rollBack();
                return [
                    'success' => false,
                    'project' => null,
                    'message' => 'Failed to approve project.',
                ];
            }

            // Update developer project counts based on status
            $this->updateDeveloperProjectCounts($project);

            // Refresh to get updated data
            $project = $this->projectRepository->findById($projectId);

            DB::commit();

            return [
                'success' => true,
                'project' => $project,
                'message' => 'Project approved successfully.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'project' => null,
                'message' => 'Error approving project: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Reject a project.
     *
     * @param int $projectId
     * @param int $rejectedBy
     * @param string|null $reason
     * @return array{success: bool, project: DeveloperProject|null, message: string}
     */
    public function rejectProject(int $projectId, int $rejectedBy, ?string $reason = null): array
    {
        try {
            DB::beginTransaction();

            $project = $this->projectRepository->findById($projectId);

            if (!$project) {
                return [
                    'success' => false,
                    'project' => null,
                    'message' => 'Project not found.',
                ];
            }

            if ($project->approval_status === 'rejected') {
                return [
                    'success' => false,
                    'project' => $project,
                    'message' => 'Project is already rejected.',
                ];
            }

            $success = $this->projectRepository->reject($projectId, $rejectedBy);

            if (!$success) {
                DB::rollBack();
                return [
                    'success' => false,
                    'project' => null,
                    'message' => 'Failed to reject project.',
                ];
            }

            // Refresh to get updated data
            $project = $this->projectRepository->findById($projectId);

            DB::commit();

            return [
                'success' => true,
                'project' => $project,
                'message' => 'Project rejected successfully.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'project' => null,
                'message' => 'Error rejecting project: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get pending properties for admin approval.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPendingProperties(int $perPage = 20): LengthAwarePaginator
    {
        return $this->propertyRepository->paginatePending($perPage);
    }

    /**
     * Get pending projects for admin approval.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPendingProjects(int $perPage = 20): LengthAwarePaginator
    {
        return $this->projectRepository->paginatePending($perPage);
    }

    /**
     * Get approval statistics.
     *
     * @return array
     */
    public function getApprovalStats(): array
    {
        return [
            'properties' => [
                'pending' => Property::withoutGlobalScope('approved')
                    ->where('approval_status', 'pending')
                    ->count(),
                'approved' => Property::where('approval_status', 'approved')->count(),
                'rejected' => Property::withoutGlobalScope('approved')
                    ->where('approval_status', 'rejected')
                    ->count(),
            ],
            'projects' => [
                'pending' => DeveloperProject::where('approval_status', 'pending')->count(),
                'approved' => DeveloperProject::where('approval_status', 'approved')->count(),
                'rejected' => DeveloperProject::where('approval_status', 'rejected')->count(),
            ],
        ];
    }

    /**
     * Notify property owner about approval/rejection.
     *
     * @param Property $property
     * @param string $action (approved|rejected)
     * @param string|null $reason
     * @return void
     */
    private function notifyOwner(Property $property, string $action, ?string $reason = null): void
    {
        $owner = $property->user;

        if (!$owner) {
            return;
        }

        if ($action === 'approved') {
            $owner->notify(new PropertyApproved($property));
        } else {
            $owner->notify(new PropertyRejected($property, $reason));
        }
    }

    /**
     * Update developer project counts based on project status.
     *
     * @param DeveloperProject $project
     * @return void
     */
    private function updateDeveloperProjectCounts(DeveloperProject $project): void
    {
        $this->developerRepository->incrementProjectCount($project->developer_id, 'total');

        if ($project->status === 'ongoing' || $project->status === 'completed') {
            $this->developerRepository->incrementProjectCount(
                $project->developer_id,
                $project->status
            );
        }
    }
}
