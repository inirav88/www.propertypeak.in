<?php

namespace Botble\Developer\Policies;

use App\Models\User;
use Botble\Developer\Models\DeveloperProfile;

class DeveloperProfilePolicy
{
    public function before(User $user): bool|null
    {
        if ($user->super_user) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('developer.profiles.index');
    }

    public function view(User $user, DeveloperProfile $profile): bool
    {
        return $user->hasPermission('developer.profiles.index');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('developer.profiles.create');
    }

    public function update(User $user, DeveloperProfile $profile): bool
    {
        return $user->hasPermission('developer.profiles.edit');
    }

    public function delete(User $user, DeveloperProfile $profile): bool
    {
        return $user->hasPermission('developer.profiles.destroy');
    }
}
