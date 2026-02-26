<?php

namespace App\Modules\RealEstate\Policies;

use App\Models\User;
use App\Modules\RealEstate\Models\Property;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PropertyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any properties.
     * Anyone can view approved properties (handled by global scope).
     */
    public function viewAny(User $user): Response
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can view the property.
     * Anyone can view approved properties.
     * Owners can view their own pending/rejected properties.
     * Admins can view all properties.
     */
    public function view(User $user, Property $property): Response
    {
        // Admin can view any property
        if ($user->isAdmin()) {
            return Response::allow();
        }

        // Owner can view their own property regardless of status
        if ($property->user_id === $user->id) {
            return Response::allow();
        }

        // Others can only view approved properties (enforced by global scope)
        return $property->isApproved()
            ? Response::allow()
            : Response::deny('You do not have permission to view this property.');
    }

    /**
     * Determine whether the user can create properties.
     * Only developers and agents can create properties.
     * Must be approved to create.
     */
    public function create(User $user): Response
    {
        // Only developers and agents can create properties
        if (!$user->isDeveloper() && !$user->isAgent()) {
            return Response::deny('Only developers and agents can create properties.');
        }

        // Must be approved
        if (!$user->isApproved()) {
            return Response::deny('Your account must be approved to create properties.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can update the property.
     * Only the owner can update their property.
     * Admins can update any property.
     */
    public function update(User $user, Property $property): Response
    {
        // Admin can update any property
        if ($user->isAdmin()) {
            return Response::allow();
        }

        // Only owner can update
        if ($property->user_id !== $user->id) {
            return Response::deny('You can only update your own properties.');
        }

        // Check if user is approved
        if (!$user->isApproved()) {
            return Response::deny('Your account must be approved to update properties.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can delete the property.
     * Only the owner can delete their property.
     * Admins can delete any property.
     */
    public function delete(User $user, Property $property): Response
    {
        // Admin can delete any property
        if ($user->isAdmin()) {
            return Response::allow();
        }

        // Only owner can delete
        if ($property->user_id !== $user->id) {
            return Response::deny('You can only delete your own properties.');
        }

        // Check if user is approved
        if (!$user->isApproved()) {
            return Response::deny('Your account must be approved to delete properties.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can approve the property.
     * Only admins can approve properties.
     */
    public function approve(User $user, Property $property): Response
    {
        if (!$user->isAdmin()) {
            return Response::deny('Only administrators can approve properties.');
        }

        if ($property->isApproved()) {
            return Response::deny('Property is already approved.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can reject the property.
     * Only admins can reject properties.
     */
    public function reject(User $user, Property $property): Response
    {
        if (!$user->isAdmin()) {
            return Response::deny('Only administrators can reject properties.');
        }

        if ($property->approval_status === 'rejected') {
            return Response::deny('Property is already rejected.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can bulk approve properties.
     * Only admins can bulk approve.
     */
    public function bulkApprove(User $user): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::deny('Only administrators can bulk approve properties.');
    }

    /**
     * Determine whether the user can view pending properties.
     * Only admins and the owner can view pending properties.
     */
    public function viewPending(User $user): Response
    {
        if ($user->isAdmin()) {
            return Response::allow();
        }

        // Developers and agents can view their own pending properties
        if ($user->isDeveloper() || $user->isAgent()) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to view pending properties.');
    }

    /**
     * Determine whether the user can feature the property.
     * Only admins can feature properties.
     */
    public function feature(User $user, Property $property): Response
    {
        if (!$user->isAdmin()) {
            return Response::deny('Only administrators can feature properties.');
        }

        if (!$property->isApproved()) {
            return Response::deny('Only approved properties can be featured.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can manage images for the property.
     * Only the owner or admin can manage images.
     */
    public function manageImages(User $user, Property $property): Response
    {
        if ($user->isAdmin()) {
            return Response::allow();
        }

        if ($property->user_id !== $user->id) {
            return Response::deny('You can only manage images for your own properties.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can view the property's owner details.
     * Admin can view all, others only for approved properties.
     */
    public function viewOwnerDetails(User $user, Property $property): Response
    {
        if ($user->isAdmin()) {
            return Response::allow();
        }

        return $property->isApproved()
            ? Response::allow()
            : Response::deny('Owner details are only available for approved properties.');
    }
}
