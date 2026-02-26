@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('title', 'Pending Projects')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Pending Projects</h1>
        <a href="{{ route('admin.projects.pending') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Total Pending</h6>
                    <h3 class="text-warning">{{ $stats['total_pending'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Upcoming</h6>
                    <h3>{{ $stats['upcoming_pending'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Ongoing</h6>
                    <h3>{{ $stats['ongoing_pending'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Completed</h6>
                    <h3>{{ $stats['completed_pending'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.projects.pending') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="upcoming" {{ ($status ?? '') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                        <option value="ongoing" {{ ($status ?? '') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="completed" {{ ($status ?? '') === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Projects Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Project</th>
                            <th>Developer</th>
                            <th>Status</th>
                            <th>Location</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $project)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $project->main_image_url }}" alt="" class="rounded" width="60" height="45" style="object-fit: cover;">
                                        <div class="ms-3">
                                            <h6 class="mb-0">{{ Str::limit($project->name, 40) }}</h6>
                                            <small class="text-muted">{{ $project->rera_number ?? 'No RERA' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{ $project->developer->company_name ?? 'N/A' }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $project->status === 'completed' ? 'success' : ($project->status === 'ongoing' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($project->status) }}
                                    </span>
                                </td>
                                <td>{{ $project->city }}, {{ $project->state }}</td>
                                <td>{{ $project->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.projects.show', $project->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        <form action="{{ route('admin.projects.approve', $project->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $project->id }}">
                                            Reject
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                    <p class="mb-0">No pending projects. All caught up!</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $projects->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
