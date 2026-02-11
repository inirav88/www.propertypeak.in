<?php

use Botble\Base\Facades\BaseHelper;
use Botble\Crm\Http\Controllers\CrmDashboardController;
use Botble\Crm\Http\Controllers\CrmLeadController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => BaseHelper::getAdminPrefix() . '/crm',
    'as' => 'crm.',
    'middleware' => ['web', 'core', 'auth'],
], function (): void {
    Route::get('dashboard', [CrmDashboardController::class, 'index'])
        ->name('dashboard')
        ->permission('crm.view');

    Route::group(['prefix' => 'leads', 'as' => 'leads.'], function (): void {
        Route::get('board', [CrmLeadController::class, 'board'])
            ->name('board')
            ->permission('crm.view');

        Route::get('', [CrmLeadController::class, 'index'])
            ->name('index')
            ->permission('crm.view');

        Route::post('{lead}/stage', [CrmLeadController::class, 'updateStage'])
            ->name('stage')
            ->wherePrimaryKey()
            ->permission('crm.update');

        Route::post('{lead}/assign', [CrmLeadController::class, 'assign'])
            ->name('assign')
            ->wherePrimaryKey()
            ->permission('crm.assign');

        Route::post('{lead}/follow-up', [CrmLeadController::class, 'scheduleFollowUp'])
            ->name('follow-up')
            ->wherePrimaryKey()
            ->permission('crm.update');
    });
});
