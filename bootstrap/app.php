<?php

use App\Modules\RealEstate\Http\Middleware\ApprovedAccountMiddleware;
use App\Modules\RealEstate\Http\Middleware\CheckPublicProfileAccess;
use App\Modules\RealEstate\Http\Middleware\RedirectIfNotAgent;
use App\Modules\RealEstate\Http\Middleware\RedirectIfNotDeveloper;
use App\Modules\RealEstate\Http\Middleware\RoleMiddleware;
use App\Modules\RealEstate\Providers\RealEstateServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withProviders([
        RealEstateServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware): void {
        // Register middleware aliases
        $middleware->alias([
            // Role-based access control
            'role' => RoleMiddleware::class,
            
            // Account approval verification
            'approved' => ApprovedAccountMiddleware::class,
            
            // Combined role + approval checks
            'developer' => RedirectIfNotDeveloper::class,
            'agent' => RedirectIfNotAgent::class,
            
            // Public profile visibility
            'public.profile' => CheckPublicProfileAccess::class,
        ]);

        // Add middleware groups for common scenarios
        $middleware->appendToGroup('developer.auth', [
            'auth',
            'developer',
        ]);

        $middleware->appendToGroup('agent.auth', [
            'auth',
            'agent',
        ]);

        $middleware->appendToGroup('admin.auth', [
            'auth',
            'role:admin',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
