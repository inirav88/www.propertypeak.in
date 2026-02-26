@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('title', 'Agent Details')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Agent Details</h1>
        <a href="{{ route('admin.agents.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>

    <div class="row">
        <!-- Profile Card -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header p-0 position-relative" style="height: 150px;">
                    <img src="{{ $agent->banner_url }}" alt="" class="w-100 h-100 object-fit-cover">
                </div>
                <div class="card-body text-center">
                    <img src="{{ $agent->avatar_url }}" alt="{{ $agent->full_name }}" class="rounded-circle bg-white p-2 shadow" 
                        style="width: 100px; height: 100px; margin-top: -70px; object-fit: cover;">
                    <h4 class="mt-3 mb-1">{{ $agent->full_name }}</h4>
                    <p class="text-muted">{{ $agent->designation ?? 'Real Estate Agent' }}</p>
                    
                    @php
                        $status = $agent->user->status;
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
                        @if($agent->user->isPending())
                            <form action="{{ route('admin.agents.approve', $agent->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-check me-1"></i>Approve
                                </button>
                            </form>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="fas fa-times me-1"></i>Reject
                            </button>
                        @endif
                        @if($agent->user->isApproved())
                            <form action="{{ route('admin.agents.suspend', $agent->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Suspend this agent?')">
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
                            {{ $agent->email ?? $agent->user->email }}
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-phone text-primary me-2"></i>
                            {{ $agent->phone ?? 'N/A' }}
                        </li>
                        @if($agent->whatsapp)
                            <li class="mb-2">
                                <i class="fab fa-whatsapp text-success me-2"></i>
                                {{ $agent->whatsapp }}
                            </li>
                        @endif
                        @if($agent->website)
                            <li class="mb-2">
                                <i class="fas fa-globe text-primary me-2"></i>
                                <a href="{{ $agent->website }}" target="_blank">{{ $agent->website }}</a>
                            </li>
                        @endif
                        @if($agent->office_address)
                            <li>
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                {{ $agent->office_address }}
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
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h3>{{ $agent->properties()->count() }}</h3>
                            <small class="text-muted">Total Properties</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h3>{{ $agent->experience_years }}</h3>
                            <small class="text-muted">Years Exp.</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h3>{{ $agent->total_sales }}</h3>
                            <small class="text-muted">Total Sales</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h3>{{ $agent->user->created_at->format('M d, Y') }}</h3>
                            <small class="text-muted">Registered</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- About -->
            @if($agent->bio)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">About</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $agent->bio }}</p>
                    </div>
                </div>
            @endif

            <!-- Specializations -->
            @if($agent->specializations)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Specializations</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($agent->specializations as $specialization)
                                <span class="badge bg-primary">{{ $specialization }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- RERA Info -->
            @if($agent->rera_id || $agent->license_number)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Registration Details</h5>
                    </div>
                    <div class="card-body">
                        @if($agent->rera_id)
                            <p><strong>RERA ID:</strong> {{ $agent->rera_id }}</p>
                        @endif
                        @if($agent->license_number)
                            <p><strong>License Number:</strong> {{ $agent->license_number }}</p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Recent Properties -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Properties</h5>
                    <span class="badge bg-primary">{{ $agent->properties()->count() }} Total</span>
                </div>
                <div class="card-body p-0">
                    @if($agent->properties()->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Approval</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($agent->properties()->latest()->take(5)->get() as $property)
                                        <tr>
                                            <td>{{ Str::limit($property->title, 40) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $property->type === 'sale' ? 'success' : ($property->type === 'rent' ? 'info' : 'secondary') }}">
                                                    {{ ucfirst($property->type) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $property->approval_status === 'approved' ? 'success' : ($property->approval_status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($property->approval_status) }}
                                                </span>
                                            </td>
                                            <td>{{ $property->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">No properties yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
@if($agent->user->isPending())
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.agents.reject', $agent->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reject Agent</h5>
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
                    <button type="submit" class="btn btn-danger">Reject Agent</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
