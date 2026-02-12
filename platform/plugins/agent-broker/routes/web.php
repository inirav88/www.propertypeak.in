<?php

use Botble\AgentBroker\Http\Controllers\AgentApprovalController;
use Botble\AgentBroker\Http\Controllers\AgentBrokerSettingController;
use Botble\AgentBroker\Http\Controllers\AgentLeadController;
use Botble\AgentBroker\Http\Controllers\AgentProfileController;
use Botble\Base\Facades\BaseHelper;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Botble\\AgentBroker\\Http\\Controllers', 'middleware' => ['web', 'core']], function (): void {
    Route::group([
        'prefix' => BaseHelper::getAdminPrefix() . '/agent-broker',
        'middleware' => 'auth',
    ], function (): void {
        Route::resource('profiles', AgentProfileController::class)
            ->names('agent-broker.profiles')
            ->parameters(['profiles' => 'agent']);

        Route::get('leads', [AgentLeadController::class, 'index'])
            ->name('agent-broker.leads.index');

        Route::prefix('approvals')->name('agent-broker.approvals.')->group(function (): void {
            Route::get('/', [AgentApprovalController::class, 'index'])
                ->name('index');

            Route::post('{revision}/approve', [AgentApprovalController::class, 'approve'])
                ->name('approve')
                ->wherePrimaryKey('revision');

            Route::post('{revision}/reject', [AgentApprovalController::class, 'reject'])
                ->name('reject')
                ->wherePrimaryKey('revision');
        });

        Route::prefix('settings')->name('agent-broker.settings.')->group(function (): void {
            Route::get('/', [AgentBrokerSettingController::class, 'edit'])
                ->name('edit');

            Route::put('/', [AgentBrokerSettingController::class, 'update'])
                ->name('update');
        });
    });
});
