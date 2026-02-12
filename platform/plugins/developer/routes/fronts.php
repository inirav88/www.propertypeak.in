<?php

use Botble\Developer\Http\Controllers\Fronts\DeveloperDashboardController;
use Botble\Developer\Http\Controllers\Fronts\DeveloperPublicLeadController;
use Botble\Developer\Http\Controllers\Fronts\SlugResolverController;
use Botble\RealEstate\Http\Middleware\EnsureAccountIsApproved;
use Botble\RealEstate\Http\Middleware\LocaleMiddleware;
use Illuminate\Support\Facades\Route;

if (defined('THEME_MODULE_SCREEN_NAME')) {
    Route::group([
        'middleware' => ['web', 'core', 'account', EnsureAccountIsApproved::class, 'account.not_blocked', LocaleMiddleware::class],
    ], function (): void {
        Route::prefix('account/developer')->name('public.account.developer.')->group(function (): void {
            Route::get('profile', [DeveloperDashboardController::class, 'editProfile'])->name('profile.edit');
            Route::put('profile', [DeveloperDashboardController::class, 'updateProfile'])->name('profile.update');

            Route::get('projects', [DeveloperDashboardController::class, 'projects'])->name('projects.index');
            Route::get('projects/create', [DeveloperDashboardController::class, 'createProject'])->name('projects.create');
            Route::post('projects', [DeveloperDashboardController::class, 'storeProject'])->name('projects.store');
            Route::get('projects/{id}/edit', [DeveloperDashboardController::class, 'editProject'])->name('projects.edit')->wherePrimaryKey('id');
            Route::put('projects/{id}', [DeveloperDashboardController::class, 'updateProject'])->name('projects.update')->wherePrimaryKey('id');

            Route::get('leads', [DeveloperDashboardController::class, 'leads'])->name('leads.index');
        });
    });

    Route::group([
        'middleware' => ['web', 'core'],
    ], function (): void {

        Route::post('developer/{slug}/lead', [DeveloperPublicLeadController::class, 'store'])
            ->middleware('throttle:5,1')
            ->name('public.developer.lead.store');

        Route::fallback([SlugResolverController::class, 'resolve'])
            ->name('public.root-slug.resolve');
    });
}
