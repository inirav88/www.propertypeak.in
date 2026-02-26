@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('title', 'All Properties')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">All Properties</h1>
        <a href="{{ route('admin.properties.pending') }}" class="btn btn-warning">
            <i class="fas fa-clock me-2"></i>Pending Approval
            @if($stats['pending'] > 0)
                <span class="badge bg-danger ms-1">{{ $stats['pending'] }}</span>
            @endif
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Total</h6>
                    <h3>{{ $stats['total'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Approved</h6>
                    <h3 class="text-success">{{ $stats['approved'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Pending</h6>
                    <h3 class="text-warning">{{ $stats['pending'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Rejected</h6>
                    <h3 class="text-danger">{{ $stats['rejected'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.properties.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search properties..." value="{{ $search ?? '' }}">
                </div>
                <div class="col-md-3">
                    <select name="approval_status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ ($approval_status ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ ($approval_status ?? '') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ ($approval_status ?? '') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Properties Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Property</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Added By</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($properties as $property)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $property->main_image_url }}" alt="" class="rounded" width="60" height="45" style="object-fit: cover;">
                                        <div class="ms-3">
                                            <h6 class="mb-0">{{ Str::limit($property->title, 40) }}</h6>
                                            <small class="text-muted">{{ $property->location }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $property->type === 'sale' ? 'success' : ($property->type === 'rent' ? 'info' : 'secondary') }}">
                                        {{ ucfirst($property->type) }}
                                    </span>
                                </td>
                                <td>₹{{ number_format($property->price) }}</td>
                                <td>
                                    @if($property->added_by_role === 'developer' && $property->developer)
                                        {{ $property->developer->company_name }}
                                    @elseif($property->added_by_role === 'agent' && $property->agent)
                                        {{ $property->agent->full_name }}
                                    @else
                                        Admin
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $badgeClass = match($property->approval_status) {
                                            'approved' => 'success',
                                            'pending' => 'warning',
                                            'rejected' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badgeClass }}">{{ ucfirst($property->approval_status) }}</span>
                                </td>
                                <td>{{ $property->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.properties.show', $property->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        @if($property->approval_status === 'pending')
                                            <form action="{{ route('admin.properties.approve', $property->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $property->id }}">
                                                Reject
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">No properties found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $properties->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
