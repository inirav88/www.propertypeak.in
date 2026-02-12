<?php

namespace Botble\Developer\Policies;

use App\Models\User;
use Botble\Developer\Models\DeveloperProject;

class DeveloperProjectPolicy
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
        return $user->hasPermission('developer.projects.index');
    }

    public function view(User $user, DeveloperProject $project): bool
    {
        return $user->hasPermission('developer.projects.index');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('developer.projects.create');
    }

    public function update(User $user, DeveloperProject $project): bool
    {
        return $user->hasPermission('developer.projects.edit');
    }

    public function delete(User $user, DeveloperProject $project): bool
    {
        return $user->hasPermission('developer.projects.destroy');
    }

    public function moderate(User $user): bool
    {
        return $user->hasPermission('developer.approvals.moderate');
    }
}
