@extends(Theme::getThemeNamespace('layouts.default'))

@section('title', 'My Projects')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">My Projects</h1>
        <a href="{{ route('developer-portal.projects.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>New Project
        </a>
    </div>

    <div class="row g-4">
        @forelse($projects as $project)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="position-relative" style="height: 180px;">
                        <img src="{{ $project->main_image_url }}" alt="{{ $project->name }}" class="w-100 h-100 object-fit-cover">
                        <div class="position-absolute top-0 start-0 m-2">
                            <span class="badge bg-{{ $project->status === 'completed' ? 'success' : ($project->status === 'ongoing' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($project->status) }}
                            </span>
                        </div>
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-{{ $project->approval_status === 'approved' ? 'success' : ($project->approval_status === 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($project->approval_status) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $project->name }}</h5>
                        <p class="text-muted small mb-2">
                            <i class="fas fa-map-marker-alt me-1"></i>{{ $project->city }}
                        </p>
                        <div class="d-flex justify-content-between text-muted small">
                            <span><i class="fas fa-home me-1"></i>{{ $project->total_units ?? 'N/A' }} Units</span>
                            @if($project->possession_date)
                                <span><i class="fas fa-calendar me-1"></i>{{ $project->possession_date->format('M Y') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="{{ route('developer-portal.projects.edit', $project->slug) }}" class="btn btn-outline-primary btn-sm w-100">
                            Manage Project
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-building fa-3x text-muted mb-3"></i>
                <h4>No projects yet</h4>
                <p class="text-muted">Start showcasing your development projects.</p>
                <a href="{{ route('developer-portal.projects.create') }}" class="btn btn-primary">Create Project</a>
            </div>
        @endforelse
    </div>

    @if($projects->count() > 0)
        <div class="mt-4">
            {{ $projects->links() }}
        </div>
    @endif
</div>
@endsection
