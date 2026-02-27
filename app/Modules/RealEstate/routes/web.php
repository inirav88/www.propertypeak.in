<?php

use App\Modules\RealEstate\Http\Controllers\Admin\AdminAgentController;
use App\Modules\RealEstate\Http\Controllers\Admin\AdminDeveloperController;
use App\Modules\RealEstate\Http\Controllers\Admin\AdminPropertyApprovalController;
use App\Modules\RealEstate\Http\Controllers\Dashboard\AgentDashboardController;
use App\Modules\RealEstate\Http\Controllers\Dashboard\DeveloperDashboardController;
use App\Modules\RealEstate\Http\Controllers\Frontend\AgentPublicController;
use App\Modules\RealEstate\Http\Controllers\Frontend\DeveloperPublicController;
use App\Modules\RealEstate\Http\Controllers\Frontend\AreaConverterController;
use App\Modules\RealEstate\Http\Controllers\Frontend\PropertyPublicController;
use App\Modules\RealEstate\Http\Controllers\Frontend\RentReceiptController;
use App\Modules\RealEstate\Http\Controllers\Frontend\RentAgreementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Real Estate Module Routes
| 
| NOTE: Public routes for /developers and /agents are defined in
| RealEstateServiceProvider::booted() to override Botble routes
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| DEVELOPER DASHBOARD ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['developer'])->prefix('developer')->name('developer-portal.')->group(function (): void {
    
    // Dashboard
    Route::get('/dashboard', [DeveloperDashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [DeveloperDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [DeveloperDashboardController::class, 'updateProfile'])->name('profile.update');
    
    // Projects
    Route::get('/projects', [DeveloperDashboardController::class, 'projects'])->name('projects.index');
    Route::get('/projects/create', [DeveloperDashboardController::class, 'createProject'])->name('projects.create');
    Route::post('/projects', [DeveloperDashboardController::class, 'storeProject'])->name('projects.store');
    Route::get('/projects/{slug}/edit', [DeveloperDashboardController::class, 'editProject'])->name('projects.edit');
    Route::put('/projects/{slug}', [DeveloperDashboardController::class, 'updateProject'])->name('projects.update');
    Route::delete('/projects/{slug}', [DeveloperDashboardController::class, 'destroyProject'])->name('projects.destroy');
    
    // Properties
    Route::get('/properties', [DeveloperDashboardController::class, 'properties'])->name('properties.index');
    Route::get('/properties/create', [DeveloperDashboardController::class, 'createProperty'])->name('properties.create');
    Route::post('/properties', [DeveloperDashboardController::class, 'storeProperty'])->name('properties.store');
    Route::get('/properties/{slug}/edit', [DeveloperDashboardController::class, 'editProperty'])->name('properties.edit');
    Route::put('/properties/{slug}', [DeveloperDashboardController::class, 'updateProperty'])->name('properties.update');
    Route::delete('/properties/{slug}', [DeveloperDashboardController::class, 'destroyProperty'])->name('properties.destroy');
    
    // Stats
    Route::get('/stats', [DeveloperDashboardController::class, 'stats'])->name('stats');
});

/*
|--------------------------------------------------------------------------
| AGENT DASHBOARD ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['agent'])->prefix('agent')->name('agent-portal.')->group(function (): void {
    
    // Dashboard
    Route::get('/dashboard', [AgentDashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [AgentDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [AgentDashboardController::class, 'updateProfile'])->name('profile.update');
    
    // Properties
    Route::get('/properties', [AgentDashboardController::class, 'properties'])->name('properties.index');
    Route::get('/properties/create', [AgentDashboardController::class, 'createProperty'])->name('properties.create');
    Route::post('/properties', [AgentDashboardController::class, 'storeProperty'])->name('properties.store');
    Route::get('/properties/{slug}/edit', [AgentDashboardController::class, 'editProperty'])->name('properties.edit');
    Route::put('/properties/{slug}', [AgentDashboardController::class, 'updateProperty'])->name('properties.update');
    Route::delete('/properties/{slug}', [AgentDashboardController::class, 'destroyProperty'])->name('properties.destroy');
    
    // Stats
    Route::get('/stats', [AgentDashboardController::class, 'stats'])->name('stats');
});

/*
|--------------------------------------------------------------------------
| PENDING ACCOUNT ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:developer|agent'])->group(function (): void {
    
    Route::get('/developer/pending', function () {
        return view('realestate::dashboard.developer.pending');
    })->name('developer.pending');
    
    Route::get('/agent/pending', function () {
        return view('realestate::dashboard.agent.pending');
    })->name('agent.pending');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['admin.auth'])->prefix('admin')->name('admin.')->group(function (): void {
    
    // Developer Management
    Route::get('/developers', [AdminDeveloperController::class, 'index'])->name('developers.index');
    Route::get('/developers/pending', [AdminDeveloperController::class, 'pending'])->name('developers.pending');
    Route::get('/developers/{id}', [AdminDeveloperController::class, 'show'])->name('developers.show');
    Route::post('/developers/{id}/approve', [AdminDeveloperController::class, 'approve'])->name('developers.approve');
    Route::post('/developers/{id}/reject', [AdminDeveloperController::class, 'reject'])->name('developers.reject');
    Route::post('/developers/bulk-approve', [AdminDeveloperController::class, 'bulkApprove'])->name('developers.bulk-approve');
    Route::post('/developers/{id}/suspend', [AdminDeveloperController::class, 'suspend'])->name('developers.suspend');
    Route::delete('/developers/{id}', [AdminDeveloperController::class, 'destroy'])->name('developers.destroy');
    
    // Agent Management
    Route::get('/agents', [AdminAgentController::class, 'index'])->name('agents.index');
    Route::get('/agents/pending', [AdminAgentController::class, 'pending'])->name('agents.pending');
    Route::get('/agents/{id}', [AdminAgentController::class, 'show'])->name('agents.show');
    Route::post('/agents/{id}/approve', [AdminAgentController::class, 'approve'])->name('agents.approve');
    Route::post('/agents/{id}/reject', [AdminAgentController::class, 'reject'])->name('agents.reject');
    Route::post('/agents/bulk-approve', [AdminAgentController::class, 'bulkApprove'])->name('agents.bulk-approve');
    Route::post('/agents/{id}/suspend', [AdminAgentController::class, 'suspend'])->name('agents.suspend');
    Route::delete('/agents/{id}', [AdminAgentController::class, 'destroy'])->name('agents.destroy');
    
    // Property Approval
    Route::get('/properties', [AdminPropertyApprovalController::class, 'allProperties'])->name('properties.index');
    Route::get('/properties/pending', [AdminPropertyApprovalController::class, 'pendingProperties'])->name('properties.pending');
    Route::get('/properties/{id}', [AdminPropertyApprovalController::class, 'showProperty'])->name('properties.show');
    Route::post('/properties/{id}/approve', [AdminPropertyApprovalController::class, 'approveProperty'])->name('properties.approve');
    Route::post('/properties/{id}/reject', [AdminPropertyApprovalController::class, 'rejectProperty'])->name('properties.reject');
    Route::post('/properties/bulk-approve', [AdminPropertyApprovalController::class, 'bulkApproveProperties'])->name('properties.bulk-approve');
    
    // Project Approval
    Route::get('/projects/pending', [AdminPropertyApprovalController::class, 'pendingProjects'])->name('projects.pending');
    Route::get('/projects/{id}', [AdminPropertyApprovalController::class, 'showProject'])->name('projects.show');
    Route::post('/projects/{id}/approve', [AdminPropertyApprovalController::class, 'approveProject'])->name('projects.approve');
    Route::post('/projects/{id}/reject', [AdminPropertyApprovalController::class, 'rejectProject'])->name('projects.reject');
    Route::post('/projects/bulk-approve', [AdminPropertyApprovalController::class, 'bulkApproveProjects'])->name('projects.bulk-approve');
});

/*
|--------------------------------------------------------------------------
| PUBLIC PROPERTY ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('web')->group(function (): void {
    
    // Property detail page
    Route::get('properties/{slug}', [PropertyPublicController::class, 'show'])->name('properties.show');
    
    // Project detail page
    Route::get('developers/{developerSlug}/projects/{projectSlug}', [DeveloperPublicController::class, 'showProject'])->name('developer-projects.show');
    
    // Developer contact form submission
    Route::post('developers/{slug}/contact', [DeveloperPublicController::class, 'contact'])->name('developer.contact');
    
    // Agent contact form submission  
    Route::post('agents/{slug}/contact', [AgentPublicController::class, 'contact'])->name('agent.contact');
});
