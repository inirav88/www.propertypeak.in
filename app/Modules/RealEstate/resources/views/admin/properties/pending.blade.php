@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('title', 'Pending Properties')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Pending Properties</h1>
        <div>
            <a href="{{ route('admin.properties.index') }}" class="btn btn-outline-primary">
                All Properties
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Total Pending</h6>
                    <h3>{{ $stats['total_pending'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">For Sale</h6>
                    <h3 class="text-success">{{ $stats['sale_pending'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">For Rent</h6>
                    <h3 class="text-info">{{ $stats['rent_pending'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">PG/Hostel</h6>
                    <h3 class="text-secondary">{{ $stats['pg_pending'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.properties.pending') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="sale" {{ ($type ?? '') === 'sale' ? 'selected' : '' }}>For Sale</option>
                        <option value="rent" {{ ($type ?? '') === 'rent' ? 'selected' : '' }}>For Rent</option>
                        <option value="pg" {{ ($type ?? '') === 'pg' ? 'selected' : '' }}>PG/Hostel</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="added_by" class="form-select">
                        <option value="">Added By</option>
                        <option value="developer" {{ ($addedBy ?? '') === 'developer' ? 'selected' : '' }}>Developer</option>
                        <option value="agent" {{ ($addedBy ?? '') === 'agent' ? 'selected' : '' }}>Agent</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions -->
    <form action="{{ route('admin.properties.bulk-approve') }}" method="POST" id="bulkForm">
        @csrf
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAll">
                        <label class="form-check-label" for="selectAll">Select All</label>
                    </div>
                    <button type="submit" class="btn btn-success" onclick="return confirm('Approve selected properties?')">
                        <i class="fas fa-check me-2"></i>Bulk Approve
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="40"><span class="visually-hidden">Select</span></th>
                                <th>Property</th>
                                <th>Type</th>
                                <th>Price</th>
                                <th>Added By</th>
                                <th>Submitted</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($properties as $property)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="ids[]" value="{{ $property->id }}" class="form-check-input property-checkbox">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $property->main_image_url }}" alt="" class="rounded" width="60" height="60" style="object-fit: cover;">
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
                                    <td>{{ $property->formatted_price }}</td>
                                    <td>
                                        @if($property->added_by_role === 'developer' && $property->developer)
                                            <span class="badge bg-primary">Developer</span>
                                            <div class="small">{{ $property->developer->company_name }}</div>
                                        @elseif($property->added_by_role === 'agent' && $property->agent)
                                            <span class="badge bg-info">Agent</span>
                                            <div class="small">{{ $property->agent->full_name }}</div>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($property->added_by_role) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $property->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.properties.show', $property->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                            <form action="{{ route('admin.properties.approve', $property->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $property->id }}">Reject</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                        <p class="mb-0">No pending properties. All caught up!</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>

    <div class="mt-4">
        {{ $properties->links() }}
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('.property-checkbox').forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
</script>
@endpush
@endsection
