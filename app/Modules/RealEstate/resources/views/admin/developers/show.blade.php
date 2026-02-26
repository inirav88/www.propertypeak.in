@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('title', 'Developer Details')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Developer Details</h1>
        <a href="{{ route('admin.developers.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>

    <div class="row">
        <!-- Profile Card -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header p-0 position-relative" style="height: 150px;">
                    <img src="{{ $developer->banner_url }}" alt="" class="w-100 h-100 object-fit-cover">
                </div>
                <div class="card-body text-center">
                    <img src="{{ $developer->logo_url }}" alt="{{ $developer->company_name }}" class="rounded bg-white p-2 shadow" 
                        style="width: 100px; height: 100px; margin-top: -70px; object-fit: cover;">
                    <h4 class="mt-3 mb-1">{{ $developer->company_name }}</h4>
                    <p class="text-muted">{{ $developer->tagline }}</p>
                    
                    @php
                        $status = $developer->user->status;
                        $badgeClass = match($status) {
                            'approved' => 'success',
                            'pending' => 'warning',
                            'rejected' => 'danger',
                            'suspended' => 'secondary',
                            default => 'secondary'
                        };
                    @endphp
                    <span class="badge bg-{{ $badgeClass }} mb-3">{{ ucfirst($status) }}</span>

                    <div class="d-flex justify-content-center gap-2">
                        @if($developer->user->isPending())
                            <form action="{{ route('admin.developers.approve', $developer->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-check me-1"></i>Approve
                                </button>
                            </form>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="fas fa-times me-1"></i>Reject
                            </button>
                        @endif
                        @if($developer->user->isApproved())
                            <form action="{{ route('admin.developers.suspend', $developer->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Suspend this developer?')">
                                    <i class="fas fa-ban me-1"></i>Suspend
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Contact Information</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-envelope text-primary me-2"></i>
                            {{ $developer->email ?? $developer->user->email }}
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-phone text-primary me-2"></i>
                            {{ $developer->phone ?? 'N/A' }}
                        </li>
                        @if($developer->website)
                            <li class="mb-2">
                                <i class="fas fa-globe text-primary me-2"></i>
                                <a href="{{ $developer->website }}" target="_blank">{{ $developer->website }}</a>
                            </li>
                        @endif
                        @if($developer->office_address)
                            <li>
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                {{ $developer->office_address }}
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <!-- Details -->
        <div class="col-lg-8">
            <!-- Stats -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h3>{{ $developer->total_projects }}</h3>
                            <small class="text-muted">Total Projects</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h3>{{ $developer->properties()->count() }}</h3>
                            <small class="text-muted">Total Properties</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h3>{{ $developer->user->created_at->format('M d, Y') }}</h3>
                            <small class="text-muted">Registered On</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- About -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">About Company</h5>
                </div>
                <div class="card-body">
                    <p>{{ $developer->description ?? 'No description provided.' }}</p>
                </div>
            </div>

            <!-- RERA Info -->
            @if($developer->rera_id || $developer->registration_number)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Registration Details</h5>
                    </div>
                    <div class="card-body">
                        @if($developer->rera_id)
                            <p><strong>RERA ID:</strong> {{ $developer->rera_id }}</p>
                        @endif
                        @if($developer->registration_number)
                            <p><strong>Registration Number:</strong> {{ $developer->registration_number }}</p>
                        @endif
                        @if($developer->established_year)
                            <p><strong>Established:</strong> {{ $developer->established_year }}</p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Recent Projects -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Projects</h5>
                    <span class="badge bg-primary">{{ $developer->projects()->count() }} Total</span>
                </div>
                <div class="card-body p-0">
                    @if($developer->projects()->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Approval</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($developer->projects()->latest()->take(5)->get() as $project)
                                        <tr>
                                            <td>{{ $project->name }}</td>
                                            <td>
                                                <span class="badge bg-{{ $project->status === 'completed' ? 'success' : ($project->status === 'ongoing' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($project->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $project->approval_status === 'approved' ? 'success' : ($project->approval_status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($project->approval_status) }}
                                                </span>
                                            </td>
                                            <td>{{ $project->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">No projects yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
@if($developer->user->isPending())
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.developers.reject', $developer->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reject Developer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Rejection Reason</label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="Enter reason for rejection..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Developer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
