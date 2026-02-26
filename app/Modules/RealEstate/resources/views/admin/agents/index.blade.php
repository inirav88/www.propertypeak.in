@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('title', 'Manage Agents')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Manage Agents</h1>
        <a href="{{ route('admin.agents.pending') }}" class="btn btn-warning">
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
            <form action="{{ route('admin.agents.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search agents..." value="{{ $search ?? '' }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ ($status ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ ($status ?? '') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ ($status ?? '') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Agents Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Agent</th>
                            <th>Contact</th>
                            <th>Experience</th>
                            <th>Status</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($agents as $agent)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $agent->avatar_url }}" alt="" class="rounded-circle" width="40" height="40">
                                        <div class="ms-3">
                                            <h6 class="mb-0">{{ $agent->full_name }}</h6>
                                            <small class="text-muted">{{ $agent->designation ?? 'Agent' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $agent->email }}</div>
                                    <small class="text-muted">{{ $agent->phone }}</small>
                                </td>
                                <td>
                                    {{ $agent->experience_years }} years
                                </td>
                                <td>
                                    @php
                                        $userStatus = $agent->user->status;
                                        $badgeClass = match($userStatus) {
                                            'approved' => 'success',
                                            'pending' => 'warning',
                                            'rejected' => 'danger',
                                            'suspended' => 'secondary',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badgeClass }}">{{ ucfirst($userStatus) }}</span>
                                </td>
                                <td>{{ $agent->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.agents.show', $agent->id) }}" class="btn btn-sm btn-outline-primary">
                                            View
                                        </a>
                                        @if($agent->user->isPending())
                                            <form action="{{ route('admin.agents.approve', $agent->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $agent->id }}">
                                                Reject
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No agents found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $agents->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
