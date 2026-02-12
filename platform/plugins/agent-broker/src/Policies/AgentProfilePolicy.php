<?php

namespace Botble\AgentBroker\Policies;

use App\Models\User;
use Botble\AgentBroker\Models\AgentProfile;

class AgentProfilePolicy
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
        return $user->hasPermission('agent-broker.profiles.index');
    }

    public function view(User $user, AgentProfile $profile): bool
    {
        return $user->hasPermission('agent-broker.profiles.index');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('agent-broker.profiles.create');
    }

    public function update(User $user, AgentProfile $profile): bool
    {
        return $user->hasPermission('agent-broker.profiles.edit');
    }

    public function delete(User $user, AgentProfile $profile): bool
    {
        return $user->hasPermission('agent-broker.profiles.destroy');
    }

    public function moderate(User $user): bool
    {
        return $user->hasPermission('agent-broker.approvals.moderate');
    }
}
