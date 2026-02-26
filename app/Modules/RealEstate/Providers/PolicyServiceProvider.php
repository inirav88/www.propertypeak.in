<?php

namespace App\Modules\RealEstate\Providers;

use App\Modules\RealEstate\Models\DeveloperProject;
use App\Modules\RealEstate\Models\Property;
use App\Modules\RealEstate\Policies\DeveloperProjectPolicy;
use App\Modules\RealEstate\Policies\PropertyPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class PolicyServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected array $policies = [
        Property::class => PropertyPolicy::class,
        DeveloperProject::class => DeveloperProjectPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        $this->registerGates();
    }

    /**
     * Register the application's policies.
     */
    protected function registerPolicies(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }

    /**
     * Register additional gates.
     */
    protected function registerGates(): void
    {
        // Gate for viewing admin panel
        Gate::define('access-admin-panel', function ($user): bool {
            return $user->isAdmin();
        });

        // Gate for viewing developer dashboard
        Gate::define('access-developer-dashboard', function ($user): bool {
            return $user->isDeveloper() && $user->isApproved();
        });

        // Gate for viewing agent dashboard
        Gate::define('access-agent-dashboard', function ($user): bool {
            return $user->isAgent() && $user->isApproved();
        });

        // Gate for viewing public profiles
        Gate::define('view-public-profile', function ($user, $profileUser): bool {
            // Anyone can view approved profiles
            return $profileUser->isApproved();
        });

        // Gate for managing user accounts (admin only)
        Gate::define('manage-users', function ($user): bool {
            return $user->isAdmin();
        });

        // Gate for approving user accounts
        Gate::define('approve-users', function ($user): bool {
            return $user->isAdmin();
        });

        // Gate for editing own profile
        Gate::define('edit-profile', function ($user, $profileUser): bool {
            return $user->id === $profileUser->id || $user->isAdmin();
        });
    }
}
