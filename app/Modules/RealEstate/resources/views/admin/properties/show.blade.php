@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('title', 'Property Details')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Property Details</h1>
        <a href="{{ route('admin.properties.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>

    <div class="row">
        <!-- Image Gallery -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    @if($property->images && count($property->images) > 0)
                        <img src="{{ $property->main_image_url }}" alt="" class="img-fluid rounded mb-3 w-100" style="max-height: 400px; object-fit: cover;">
                        <div class="row g-2">
                            @foreach($property->images as $image)
                                <div class="col-3">
                                    <img src="{{ asset('storage/' . $image) }}" alt="" class="img-thumbnail w-100" style="height: 80px; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-image fa-4x text-muted mb-3"></i>
                            <p class="text-muted">No images uploaded</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Property Info -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h3>{{ $property->title }}</h3>
                            <p class="text-muted mb-0">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                {{ $property->location }}
                            </p>
                        </div>
                        @php
                            $badgeClass = match($property->approval_status) {
                                'approved' => 'success',
                                'pending' => 'warning',
                                'rejected' => 'danger',
                                default => 'secondary'
                            };
                        @endphp
                        <span class="badge bg-{{ $badgeClass }} fs-6">{{ ucfirst($property->approval_status) }}</span>
                    </div>

                    <h4 class="text-primary mb-4">₹{{ number_format($property->price) }}</h4>

                    <div class="row mb-4">
                        <div class="col-6 col-md-3 mb-3">
                            <div class="text-center p-2 bg-light rounded">
                                <i class="fas fa-bed fa-2x text-primary mb-2"></i>
                                <div>{{ $property->bedrooms ?? 'N/A' }}</div>
                                <small class="text-muted">Bedrooms</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="text-center p-2 bg-light rounded">
                                <i class="fas fa-bath fa-2x text-primary mb-2"></i>
                                <div>{{ $property->bathrooms ?? 'N/A' }}</div>
                                <small class="text-muted">Bathrooms</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="text-center p-2 bg-light rounded">
                                <i class="fas fa-ruler-combined fa-2x text-primary mb-2"></i>
                                <div>{{ $property->carpet_area ? $property->carpet_area . ' sq.ft' : 'N/A' }}</div>
                                <small class="text-muted">Carpet Area</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="text-center p-2 bg-light rounded">
                                <i class="fas fa-home fa-2x text-primary mb-2"></i>
                                <div>{{ ucfirst($property->property_type) }}</div>
                                <small class="text-muted">Type</small>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h5>Description</h5>
                    <p>{{ $property->description }}</p>

                    <hr>

                    <h5>Added By</h5>
                    <p class="mb-1">
                        <strong>Role:</strong> {{ ucfirst($property->added_by_role) }}
                    </p>
                    @if($property->added_by_role === 'developer' && $property->developer)
                        <p class="mb-1"><strong>Name:</strong> {{ $property->developer->company_name }}</p>
                    @elseif($property->added_by_role === 'agent' && $property->agent)
                        <p class="mb-1"><strong>Name:</strong> {{ $property->agent->full_name }}</p>
                    @endif

                    <hr>

                    <div class="d-flex gap-2">
                        @if($property->approval_status === 'pending')
                            <form action="{{ route('admin.properties.approve', $property->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check me-1"></i>Approve Property
                                </button>
                            </form>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="fas fa-times me-1"></i>Reject Property
                            </button>
                        @endif
                        <a href="{{ route('properties.show', $property->slug) }}" target="_blank" class="btn btn-outline-primary">
                            <i class="fas fa-eye me-1"></i>View Public Page
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
@if($property->approval_status === 'pending')
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.properties.reject', $property->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reject Property</h5>
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
                    <button type="submit" class="btn btn-danger">Reject Property</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
