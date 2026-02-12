<?php

use Botble\AgentBroker\Http\Controllers\Fronts\AgentDashboardController;
use Botble\AgentBroker\Http\Controllers\Fronts\AgentPublicLeadController;
use Botble\RealEstate\Http\Middleware\EnsureAccountIsApproved;
use Botble\RealEstate\Http\Middleware\LocaleMiddleware;
use Illuminate\Support\Facades\Route;

if (defined('THEME_MODULE_SCREEN_NAME')) {
    Route::group([
        'middleware' => ['web', 'core', 'account', EnsureAccountIsApproved::class, 'account.not_blocked', LocaleMiddleware::class],
    ], function (): void {
        Route::prefix('account/agent')->name('public.account.agent.')->group(function (): void {
            Route::get('profile', [AgentDashboardController::class, 'editProfile'])->name('profile.edit');
            Route::put('profile', [AgentDashboardController::class, 'updateProfile'])->name('profile.update');
            Route::get('properties', [AgentDashboardController::class, 'properties'])->name('properties.index');
            Route::get('leads', [AgentDashboardController::class, 'leads'])->name('leads.index');
        });
    });

    Route::group([
        'middleware' => ['web', 'core'],
    ], function (): void {
        Route::post('agent/{slug}/lead', [AgentPublicLeadController::class, 'store'])
            ->middleware('throttle:5,1')
            ->name('public.agent.lead.store');
    });
}
