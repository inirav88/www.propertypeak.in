@extends(Theme::getThemeNamespace('layouts.default'))

@section('title', 'Developer Dashboard')

@section('content')
<div class="container-fluid py-4">
    <!-- Welcome Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Welcome, {{ $developer->company_name }}</h1>
            <p class="text-muted mb-0">
                @if(auth()->user()->isApproved())
                    <span class="badge bg-success">Account Approved</span>
                @else
                    <span class="badge bg-warning">Pending Approval</span>
                @endif
            </p>
        </div>
        <div>
            <a href="{{ route('developers.show', $developer->slug) }}" target="_blank" class="btn btn-outline-primary">
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
                                <i class="fas fa-building fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Projects</h6>
                            <h3 class="mb-0">{{ $stats['total_projects'] ?? 0 }}</h3>
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
                            <h6 class="text-muted mb-1">Completed</h6>
                            <h3 class="mb-0">{{ $stats['completed_projects'] ?? 0 }}</h3>
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
                                <i class="fas fa-spinner fa-2x text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Ongoing</h6>
                            <h3 class="mb-0">{{ $stats['ongoing_projects'] ?? 0 }}</h3>
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
                                <i class="fas fa-home fa-2x text-info"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Properties</h6>
                            <h3 class="mb-0">{{ $stats['total_properties'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Projects -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0">Recent Projects</h5>
                    <a href="{{ route('developer-portal.projects.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    @if($recentProjects->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentProjects as $project)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $project->name }}</h6>
                                        <small class="text-muted">
                                            <span class="badge bg-{{ $project->status === 'completed' ? 'success' : ($project->status === 'ongoing' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($project->status) }}
                                            </span>
                                            <span class="badge bg-{{ $project->approval_status === 'approved' ? 'success' : ($project->approval_status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($project->approval_status) }}
                                            </span>
                                        </small>
                                    </div>
                                    <a href="{{ route('developer-portal.projects.edit', $project->slug) }}" class="btn btn-sm btn-outline-secondary">
                                        Edit
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-building fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No projects yet.</p>
                            <a href="{{ route('developer-portal.projects.create') }}" class="btn btn-primary">Create Project</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Properties -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0">Recent Properties</h5>
                    <a href="{{ route('developer-portal.properties.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    @if($recentProperties->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentProperties as $property)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ Str::limit($property->title, 40) }}</h6>
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
                                    <a href="{{ route('developer-portal.properties.edit', $property->slug) }}" class="btn btn-sm btn-outline-secondary">
                                        Edit
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-home fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No properties yet.</p>
                            <a href="{{ route('developer-portal.properties.create') }}" class="btn btn-primary">Add Property</a>
                        </div>
                    @endif
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
                        <div class="col-md-3">
                            <a href="{{ route('developer-portal.projects.create') }}" class="btn btn-outline-primary w-100 py-3">
                                <i class="fas fa-plus-circle fa-2x mb-2 d-block"></i>
                                New Project
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('developer-portal.properties.create') }}" class="btn btn-outline-success w-100 py-3">
                                <i class="fas fa-home fa-2x mb-2 d-block"></i>
                                Add Property
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('developer-portal.profile') }}" class="btn btn-outline-info w-100 py-3">
                                <i class="fas fa-user-edit fa-2x mb-2 d-block"></i>
                                Edit Profile
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('developer-portal.stats') }}" class="btn btn-outline-warning w-100 py-3">
                                <i class="fas fa-chart-bar fa-2x mb-2 d-block"></i>
                                View Stats
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
