<?php

use Botble\Base\Facades\BaseHelper;
use Botble\Developer\Http\Controllers\DeveloperApprovalController;
use Botble\Developer\Http\Controllers\DeveloperLeadController;
use Botble\Developer\Http\Controllers\DeveloperProfileController;
use Botble\Developer\Http\Controllers\DeveloperProjectController;
use Botble\Developer\Http\Controllers\DeveloperSettingController;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Botble\\Developer\\Http\\Controllers', 'middleware' => ['web', 'core']], function (): void {
    Route::group([
        'prefix' => BaseHelper::getAdminPrefix() . '/developer',
        'middleware' => 'auth',
    ], function (): void {
        Route::resource('profiles', DeveloperProfileController::class)
            ->names('developer.profiles')
            ->parameters(['profiles' => 'developer']);

        Route::resource('projects', DeveloperProjectController::class)
            ->names('developer.projects')
            ->parameters(['projects' => 'project']);

        Route::get('leads', [DeveloperLeadController::class, 'index'])
            ->name('developer.leads.index');

        Route::prefix('approvals')->name('developer.approvals.')->group(function (): void {
            Route::get('/', [DeveloperApprovalController::class, 'index'])
                ->name('index');

            Route::post('profiles/{revision}/approve', [DeveloperApprovalController::class, 'approveProfile'])
                ->name('profiles.approve')
                ->wherePrimaryKey('revision');

            Route::post('profiles/{revision}/reject', [DeveloperApprovalController::class, 'rejectProfile'])
                ->name('profiles.reject')
                ->wherePrimaryKey('revision');

            Route::post('projects/{revision}/approve', [DeveloperApprovalController::class, 'approveProject'])
                ->name('projects.approve')
                ->wherePrimaryKey('revision');

            Route::post('projects/{revision}/reject', [DeveloperApprovalController::class, 'rejectProject'])
                ->name('projects.reject')
                ->wherePrimaryKey('revision');
        });

        Route::prefix('settings')->name('developer.settings.')->group(function (): void {
            Route::get('/', [DeveloperSettingController::class, 'edit'])
                ->name('edit');

            Route::put('/', [DeveloperSettingController::class, 'update'])
                ->name('update');
        });
    });
});
