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
    // Area Converter
    Route::get('area-converter', [AreaConverterController::class, 'index'])->name('area-converter.index');
    Route::post('area-converter/convert', [AreaConverterController::class, 'convert'])->name('area-converter.convert');
    
    // Rent Receipt Generator
    Route::get('rent-receipt-generator', [RentReceiptController::class, 'index'])->name('rent-receipt.index');
    Route::post('rent-receipt-generator', [RentReceiptController::class, 'generate'])->name('rent-receipt.generate');
    
    // Rent Agreement Generator
    Route::get('rent-agreement-test', function() {
        return view('realestate::frontend.rent-agreement.test');
    });
    Route::get('rent-agreement-test-preview', function() {
        // Create test data
        session(['rent_agreement' => [
            'landlord_name' => 'John Doe',
            'landlord_phone' => '9876543210',
            'landlord_email' => 'john@example.com',
            'landlord_address' => '123 Main Street',
            'landlord_city' => 'Mumbai',
            'landlord_state' => 'maharashtra',
            'landlord_pincode' => '400001',
            'tenant_name' => 'Jane Smith',
            'tenant_phone' => '9876543211',
            'tenant_email' => 'jane@example.com',
            'tenant_address' => '456 Park Avenue',
            'tenant_city' => 'Mumbai',
            'tenant_state' => 'maharashtra',
            'tenant_pincode' => '400002',
            'property_address' => '789 Building, Main Road',
            'property_city' => 'Mumbai',
            'property_state' => 'maharashtra',
            'property_pincode' => '400001',
            'floor_number' => '3rd',
            'property_type' => 'residential',
            'parking' => 'without_parking',
            'property_description' => '2 BHK Apartment, 1000 sq.ft.',
            'rent_amount' => 25000,
            'rent_amount_words' => 'Twenty Five Thousand Only',
            'security_deposit' => 100000,
            'security_deposit_words' => 'One Lakh Only',
            'agreement_duration' => '11_months',
            'agreement_start_date' => date('Y-m-d'),
            'rent_due_date' => '5th',
            'notice_period' => 'One month',
            'purpose' => 'residential',
        ]]);
        return redirect()->route('rent-agreement.preview');
    });
    Route::get('rent-agreement', [RentAgreementController::class, 'index'])->name('rent-agreement.index');
    Route::post('rent-agreement/save', [RentAgreementController::class, 'save'])->name('rent-agreement.save');
    Route::get('rent-agreement/preview', [RentAgreementController::class, 'preview'])->name('rent-agreement.preview');
    Route::post('rent-agreement/download', [RentAgreementController::class, 'download'])->name('rent-agreement.download');
    
    // Property detail page
    Route::get('properties/{slug}', [PropertyPublicController::class, 'show'])->name('properties.show');
    
    // Project detail page
    Route::get('developers/{developerSlug}/projects/{projectSlug}', [DeveloperPublicController::class, 'showProject'])->name('developer-projects.show');
    
    // Developer contact form submission
    Route::post('developers/{slug}/contact', [DeveloperPublicController::class, 'contact'])->name('developer.contact');
    
    // Agent contact form submission  
    Route::post('agents/{slug}/contact', [AgentPublicController::class, 'contact'])->name('agent.contact');
});
