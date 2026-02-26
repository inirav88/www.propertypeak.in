@extends(Theme::getThemeNamespace('layouts.default'))

@section('title', 'Statistics - Agent Dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Analytics & Statistics</h1>
        <a href="{{ route('agent-portal.dashboard') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>

    <!-- Overview Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded p-3">
                                <i class="fas fa-home fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Properties</h6>
                            <h3 class="mb-0">{{ $stats['total_properties'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded p-3">
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Active Listings</h6>
                            <h3 class="mb-0">{{ $stats['active_listings'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded p-3">
                                <i class="fas fa-handshake fa-2x text-info"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Sales</h6>
                            <h3 class="mb-0">{{ $stats['total_sales'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded p-3">
                                <i class="fas fa-briefcase fa-2x text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Experience</h6>
                            <h3 class="mb-0">{{ $stats['experience_years'] ?? 0 }} yrs</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Property Status Breakdown -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Property Approval Status</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="p-3">
                                <div class="display-4 text-success">{{ $stats['approved_properties'] ?? 0 }}</div>
                                <small class="text-muted">Approved</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3">
                                <div class="display-4 text-warning">{{ $stats['pending_properties'] ?? 0 }}</div>
                                <small class="text-muted">Pending</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3">
                                <div class="display-4 text-danger">{{ $stats['rejected_properties'] ?? 0 }}</div>
                                <small class="text-muted">Rejected</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Performance Metrics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="p-3">
                                <div class="display-4 text-primary">{{ $stats['total_views'] ?? 0 }}</div>
                                <small class="text-muted">Total Views</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3">
                                <div class="display-4 text-info">{{ $stats['total_leads'] ?? 0 }}</div>
                                <small class="text-muted">Total Leads</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Placeholder -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Property Views Analytics</h5>
        </div>
        <div class="card-body">
            <div class="text-center py-5">
                <i class="fas fa-chart-line fa-4x text-muted mb-3"></i>
                <h5>Analytics Coming Soon</h5>
                <p class="text-muted">Detailed analytics about property views, leads, and engagement will be available here.</p>
            </div>
        </div>
    </div>
</div>
@endsection
