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

            // Register Shortcodes
            $this->registerShortcodes();
            
            // Register Admin Menu
            $this->registerAdminMenu();
        });
    }

    /**
     * Register Shortcodes for tools
     */
    private function registerShortcodes(): void
    {
        if (function_exists('add_shortcode')) {
            add_shortcode('area-converter', 'Area Converter', 'Land area unit converter', function () {
                $controller = $this->app->make(\App\Modules\RealEstate\Http\Controllers\Frontend\AreaConverterController::class);
                return $controller->getContent();
            });

            add_shortcode('rent-agreement', 'Rent Agreement', 'Rent agreement generator', function () {
                $controller = $this->app->make(\App\Modules\RealEstate\Http\Controllers\Frontend\RentAgreementController::class);
                return $controller->getContent();
            });

            add_shortcode('rent-receipt', 'Rent Receipt', 'Rent receipt generator', function () {
                $controller = $this->app->make(\App\Modules\RealEstate\Http\Controllers\Frontend\RentReceiptController::class);
                return $controller->getContent();
            });
        }
    }

    /**
     * Register Admin Menu for Tools
     */
    private function registerAdminMenu(): void
    {
        \Botble\Base\Facades\DashboardMenu::registerItem([
            'id'          => 'cms-plugins-real-estate-tools',
            'priority'    => 5,
            'parent_id'   => null,
            'name'        => 'Real Estate Tools',
            'icon'        => 'fa fa-tools',
            'url'         => '#',
            'permissions' => [],
        ])
        ->registerItem([
            'id'          => 'cms-plugins-area-converter',
            'priority'    => 1,
            'parent_id'   => 'cms-plugins-real-estate-tools',
            'name'        => 'Area Converter',
            'icon'        => 'fa fa-calculator',
            'url'         => url('area-converter'),
            'permissions' => [],
        ])
        ->registerItem([
            'id'          => 'cms-plugins-rent-agreement',
            'priority'    => 2,
            'parent_id'   => 'cms-plugins-real-estate-tools',
            'name'        => 'Rent Agreement',
            'icon'        => 'fa fa-file-contract',
            'url'         => url('rent-agreement'),
            'permissions' => [],
        ])
        ->registerItem([
            'id'          => 'cms-plugins-rent-receipt',
            'priority'    => 3,
            'parent_id'   => 'cms-plugins-real-estate-tools',
            'name'        => 'Rent Receipt',
            'icon'        => 'fa fa-receipt',
            'url'         => url('rent-receipt-generator'),
            'permissions' => [],
        ]);
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
