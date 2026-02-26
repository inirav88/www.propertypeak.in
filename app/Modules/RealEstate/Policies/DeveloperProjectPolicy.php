<?php

namespace App\Modules\RealEstate\Policies;

use App\Models\User;
use App\Modules\RealEstate\Models\Developer;
use App\Modules\RealEstate\Models\DeveloperProject;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class DeveloperProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any projects.
     * Anyone can view approved projects.
     */
    public function viewAny(User $user): Response
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can view the project.
     * Anyone can view approved projects.
     * Developer owners can view their own projects.
     * Admins can view all projects.
     */
    public function view(User $user, DeveloperProject $project): Response
    {
        // Admin can view any project
        if ($user->isAdmin()) {
            return Response::allow();
        }

        // Check if user is the developer owner
        if ($this->isDeveloperOwner($user, $project)) {
            return Response::allow();
        }

        // Others can only view approved projects
        return $project->isApproved()
            ? Response::allow()
            : Response::deny('You do not have permission to view this project.');
    }

    /**
     * Determine whether the user can create projects.
     * Only developers can create projects.
     * Must be approved to create.
     */
    public function create(User $user): Response
    {
        // Only developers can create projects
        if (!$user->isDeveloper()) {
            return Response::deny('Only developers can create projects.');
        }

        // Must be approved
        if (!$user->isApproved()) {
            return Response::deny('Your account must be approved to create projects.');
        }

        // Check if developer has a profile
        if (!$user->developer) {
            return Response::deny('You must complete your developer profile first.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can update the project.
     * Only the developer owner can update their projects.
     * Admins can update any project.
     * Agents cannot update developer projects.
     */
    public function update(User $user, DeveloperProject $project): Response
    {
        // Admin can update any project
        if ($user->isAdmin()) {
            return Response::allow();
        }

        // Agents cannot modify developer projects
        if ($user->isAgent()) {
            return Response::deny('Agents cannot modify developer projects.');
        }

        // Check if user is the developer owner
        if (!$this->isDeveloperOwner($user, $project)) {
            return Response::deny('You can only update your own projects.');
        }

        // Check if user is approved
        if (!$user->isApproved()) {
            return Response::deny('Your account must be approved to update projects.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can delete the project.
     * Only the developer owner can delete their projects.
     * Admins can delete any project.
     * Agents cannot delete developer projects.
     */
    public function delete(User $user, DeveloperProject $project): Response
    {
        // Admin can delete any project
        if ($user->isAdmin()) {
            return Response::allow();
        }

        // Agents cannot delete developer projects
        if ($user->isAgent()) {
            return Response::deny('Agents cannot delete developer projects.');
        }

        // Check if user is the developer owner
        if (!$this->isDeveloperOwner($user, $project)) {
            return Response::deny('You can only delete your own projects.');
        }

        // Check if user is approved
        if (!$user->isApproved()) {
            return Response::deny('Your account must be approved to delete projects.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can approve the project.
     * Only admins can approve projects.
     */
    public function approve(User $user, DeveloperProject $project): Response
    {
        if (!$user->isAdmin()) {
            return Response::deny('Only administrators can approve projects.');
        }

        if ($project->isApproved()) {
            return Response::deny('Project is already approved.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can reject the project.
     * Only admins can reject projects.
     */
    public function reject(User $user, DeveloperProject $project): Response
    {
        if (!$user->isAdmin()) {
            return Response::deny('Only administrators can reject projects.');
        }

        if ($project->approval_status === 'rejected') {
            return Response::deny('Project is already rejected.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can add properties to the project.
     * Only the developer owner can add properties to their projects.
     */
    public function addProperty(User $user, DeveloperProject $project): Response
    {
        // Admin can add properties to any project
        if ($user->isAdmin()) {
            return Response::allow();
        }

        // Agents cannot add properties to developer projects
        if ($user->isAgent()) {
            return Response::deny('Agents cannot add properties to developer projects.');
        }

        // Check if user is the developer owner
        if (!$this->isDeveloperOwner($user, $project)) {
            return Response::deny('You can only add properties to your own projects.');
        }

        // Check if user is approved
        if (!$user->isApproved()) {
            return Response::deny('Your account must be approved to add properties.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can manage project gallery.
     * Only the developer owner or admin can manage gallery.
     */
    public function manageGallery(User $user, DeveloperProject $project): Response
    {
        if ($user->isAdmin()) {
            return Response::allow();
        }

        // Agents cannot manage developer project galleries
        if ($user->isAgent()) {
            return Response::deny('Agents cannot modify developer project galleries.');
        }

        if (!$this->isDeveloperOwner($user, $project)) {
            return Response::deny('You can only manage gallery for your own projects.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can manage floor plans.
     * Only the developer owner or admin can manage floor plans.
     */
    public function manageFloorPlans(User $user, DeveloperProject $project): Response
    {
        if ($user->isAdmin()) {
            return Response::allow();
        }

        // Agents cannot manage developer project floor plans
        if ($user->isAgent()) {
            return Response::deny('Agents cannot modify developer project floor plans.');
        }

        if (!$this->isDeveloperOwner($user, $project)) {
            return Response::deny('You can only manage floor plans for your own projects.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can upload brochure.
     * Only the developer owner or admin can upload brochure.
     */
    public function uploadBrochure(User $user, DeveloperProject $project): Response
    {
        if ($user->isAdmin()) {
            return Response::allow();
        }

        // Agents cannot upload brochures for developer projects
        if ($user->isAgent()) {
            return Response::deny('Agents cannot upload brochures for developer projects.');
        }

        if (!$this->isDeveloperOwner($user, $project)) {
            return Response::deny('You can only upload brochure for your own projects.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can view pending projects.
     * Only admins and the developer owner can view pending projects.
     */
    public function viewPending(User $user): Response
    {
        if ($user->isAdmin()) {
            return Response::allow();
        }

        // Developers can view their own pending projects
        if ($user->isDeveloper()) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to view pending projects.');
    }

    /**
     * Determine whether the user can feature the project.
     * Only admins can feature projects.
     */
    public function feature(User $user, DeveloperProject $project): Response
    {
        if (!$user->isAdmin()) {
            return Response::deny('Only administrators can feature projects.');
        }

        if (!$project->isApproved()) {
            return Response::deny('Only approved projects can be featured.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can view project analytics.
     * Only the developer owner or admin can view analytics.
     */
    public function viewAnalytics(User $user, DeveloperProject $project): Response
    {
        if ($user->isAdmin()) {
            return Response::allow();
        }

        if ($this->isDeveloperOwner($user, $project)) {
            return Response::allow();
        }

        return Response::deny('You can only view analytics for your own projects.');
    }

    /**
     * Check if the user is the developer owner of the project.
     *
     * @param User $user
     * @param DeveloperProject $project
     * @return bool
     */
    private function isDeveloperOwner(User $user, DeveloperProject $project): bool
    {
        if (!$user->isDeveloper()) {
            return false;
        }

        $developer = $user->developer;

        if (!$developer) {
            return false;
        }

        return $project->developer_id === $developer->id;
    }
}
