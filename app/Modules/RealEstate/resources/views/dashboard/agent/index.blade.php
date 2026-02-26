@extends(Theme::getThemeNamespace('layouts.default'))

@section('title', 'Agent Dashboard')

@section('content')
<div class="container-fluid py-4">
    <!-- Welcome Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Welcome, {{ $agent->full_name }}</h1>
            <p class="text-muted mb-0">
                @if(auth()->user()->isApproved())
                    <span class="badge bg-success">Account Approved</span>
                @else
                    <span class="badge bg-warning">Pending Approval</span>
                @endif
            </p>
        </div>
        <div>
            <a href="{{ route('agents.show', $agent->slug) }}" target="_blank" class="btn btn-outline-primary">
                <i class="fas fa-eye me-2"></i>View Public Profile
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
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
                                <i class="fas fa-clock fa-2x text-info"></i>
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
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded p-3">
                                <i class="fas fa-handshake fa-2x text-warning"></i>
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
    </div>

    <div class="row">
        <!-- Recent Properties -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0">Recent Properties</h5>
                    <a href="{{ route('agent-portal.properties.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    @if($recentProperties->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentProperties as $property)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ Str::limit($property->title, 50) }}</h6>
                                        <small class="text-muted">
                                            <span class="badge bg-{{ $property->type === 'sale' ? 'success' : ($property->type === 'rent' ? 'info' : 'secondary') }}">
                                                {{ ucfirst($property->type) }}
                                            </span>
                                            <span class="badge bg-{{ $property->approval_status === 'approved' ? 'success' : ($property->approval_status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($property->approval_status) }}
                                            </span>
                                            <span class="ms-2">{{ $property->compact_price }}</span>
                                        </small>
                                    </div>
                                    <a href="{{ route('agent-portal.properties.edit', $property->slug) }}" class="btn btn-sm btn-outline-secondary">
                                        Edit
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-home fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No properties yet.</p>
                            <a href="{{ route('agent-portal.properties.create') }}" class="btn btn-primary">Add Property</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Profile Summary -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Profile Summary</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="{{ $agent->avatar_url }}" alt="{{ $agent->full_name }}" class="rounded-circle mb-3" width="100" height="100">
                        <h5 class="mb-1">{{ $agent->full_name }}</h5>
                        <p class="text-muted mb-0">{{ $agent->designation ?? 'Real Estate Agent' }}</p>
                    </div>

                    <ul class="list-unstyled mb-0">
                        @if($agent->email)
                            <li class="mb-2">
                                <i class="fas fa-envelope text-primary me-2"></i>
                                {{ $agent->email }}
                            </li>
                        @endif
                        @if($agent->phone)
                            <li class="mb-2">
                                <i class="fas fa-phone text-primary me-2"></i>
                                {{ $agent->phone }}
                            </li>
                        @endif
                        @if($agent->service_areas)
                            <li class="mb-2">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                {{ implode(', ', array_slice($agent->service_areas, 0, 3)) }}
                            </li>
                        @endif
                    </ul>

                    <hr>

                    <div class="d-grid">
                        <a href="{{ route('agent-portal.profile') }}" class="btn btn-outline-primary">
                            Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="{{ route('agent-portal.properties.create') }}" class="btn btn-outline-success w-100 py-3">
                                <i class="fas fa-plus-circle fa-2x mb-2 d-block"></i>
                                Add New Property
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('agent-portal.profile') }}" class="btn btn-outline-info w-100 py-3">
                                <i class="fas fa-user-edit fa-2x mb-2 d-block"></i>
                                Update Profile
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('agent-portal.stats') }}" class="btn btn-outline-warning w-100 py-3">
                                <i class="fas fa-chart-bar fa-2x mb-2 d-block"></i>
                                View Analytics
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
