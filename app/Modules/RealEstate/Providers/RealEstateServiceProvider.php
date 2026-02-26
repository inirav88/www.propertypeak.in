<?php

namespace App\Modules\RealEstate\Providers;

use App\Modules\RealEstate\Console\Commands\GeneratePropertySlugs;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RealEstateServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register module bindings
        $this->registerRepositories();
        $this->registerServices();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'realestate');
        
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'realestate-migrations');

        // Register policies
        $this->app->register(PolicyServiceProvider::class);

        // Load module routes (dashboard, admin)
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Register console commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                GeneratePropertySlugs::class,
            ]);
        }

        // Register callbacks to run after all providers boot
        $this->app->booted(function () {
            // Override Botble's auth model with our extended User model
            config()->set(['auth.providers.users.model' => \App\Models\User::class]);

            // Register public routes after all providers boot (to override Botble routes)
            $this->overridePublicRoutes();
        });
    }

    /**
     * Override Botble's public routes with our implementation
     */
    private function overridePublicRoutes(): void
    {
        Route::middleware('web')->group(function () {
            // Override Botble's developer routes
            Route::get('developers', \App\Modules\RealEstate\Http\Controllers\Frontend\DeveloperPublicController::class . '@index')
                ->name('developers.index');
            Route::get('developers/{slug}', \App\Modules\RealEstate\Http\Controllers\Frontend\DeveloperPublicController::class . '@show')
                ->name('developers.show');

            // Override Botble's agent routes  
            Route::get('agents', \App\Modules\RealEstate\Http\Controllers\Frontend\AgentPublicController::class . '@index')
                ->name('agents.index');
            Route::get('agents/{slug}', \App\Modules\RealEstate\Http\Controllers\Frontend\AgentPublicController::class . '@show')
                ->name('agents.show');
        });
    }

    /**
     * Register repositories.
     */
    private function registerRepositories(): void
    {
        $this->app->singleton(
            \App\Modules\RealEstate\Contracts\DeveloperRepositoryInterface::class,
            \App\Modules\RealEstate\Repositories\DeveloperRepository::class
        );

        $this->app->singleton(
            \App\Modules\RealEstate\Contracts\AgentRepositoryInterface::class,
            \App\Modules\RealEstate\Repositories\AgentRepository::class
        );

        $this->app->singleton(
            \App\Modules\RealEstate\Contracts\PropertyRepositoryInterface::class,
            \App\Modules\RealEstate\Repositories\PropertyRepository::class
        );

        $this->app->singleton(
            \App\Modules\RealEstate\Contracts\ProjectRepositoryInterface::class,
            \App\Modules\RealEstate\Repositories\ProjectRepository::class
        );
    }

    /**
     * Register services.
     */
    private function registerServices(): void
    {
        $this->app->singleton(\App\Modules\RealEstate\Services\DeveloperService::class);
        $this->app->singleton(\App\Modules\RealEstate\Services\AgentService::class);
        $this->app->singleton(\App\Modules\RealEstate\Services\PropertyApprovalService::class);
        $this->app->singleton(\App\Modules\RealEstate\Services\RegistrationService::class);
    }
}
