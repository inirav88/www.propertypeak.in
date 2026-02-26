@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('title', 'Project Details')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Project Details</h1>
        <a href="{{ route('admin.projects.pending') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>

    <div class="row">
        <!-- Image Gallery -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    @if($project->gallery_images && count($project->gallery_images) > 0)
                        <img src="{{ $project->main_image_url }}" alt="" class="img-fluid rounded mb-3 w-100" style="max-height: 400px; object-fit: cover;">
                        <div class="row g-2">
                            @foreach($project->gallery_images as $image)
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

        <!-- Project Info -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h3>{{ $project->name }}</h3>
                            <p class="text-muted mb-0">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                {{ $project->address }}, {{ $project->city }}, {{ $project->state }}
                            </p>
                        </div>
                        @php
                            $badgeClass = match($project->approval_status) {
                                'approved' => 'success',
                                'pending' => 'warning',
                                'rejected' => 'danger',
                                default => 'secondary'
                            };
                        @endphp
                        <span class="badge bg-{{ $badgeClass }} fs-6">{{ ucfirst($project->approval_status) }}</span>
                    </div>

                    @if($project->price_starting_from)
                        <h4 class="text-primary mb-4">Starting from ₹{{ number_format($project->price_starting_from) }}</h4>
                    @endif

                    <div class="row mb-4">
                        <div class="col-6 col-md-3 mb-3">
                            <div class="text-center p-2 bg-light rounded">
                                <i class="fas fa-building fa-2x text-primary mb-2"></i>
                                <div>{{ ucfirst($project->type) }}</div>
                                <small class="text-muted">Type</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="text-center p-2 bg-light rounded">
                                <i class="fas fa-info-circle fa-2x text-primary mb-2"></i>
                                <div>{{ ucfirst($project->status) }}</div>
                                <small class="text-muted">Status</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="text-center p-2 bg-light rounded">
                                <i class="fas fa-home fa-2x text-primary mb-2"></i>
                                <div>{{ $project->total_units ?? 'N/A' }}</div>
                                <small class="text-muted">Units</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="text-center p-2 bg-light rounded">
                                <i class="fas fa-calendar fa-2x text-primary mb-2"></i>
                                <div>{{ $project->possession_date ? $project->possession_date->format('M Y') : 'N/A' }}</div>
                                <small class="text-muted">Possession</small>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h5>Description</h5>
                    <p>{{ $project->description }}</p>

                    @if($project->short_description)
                        <p class="text-muted">{{ $project->short_description }}</p>
                    @endif

                    <hr>

                    <h5>Developer Information</h5>
                    <p class="mb-1"><strong>Company:</strong> {{ $project->developer->company_name ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Contact:</strong> {{ $project->developer->email ?? 'N/A' }}</p>
                    <p class="mb-0"><strong>Phone:</strong> {{ $project->developer->phone ?? 'N/A' }}</p>

                    @if($project->rera_number)
                        <hr>
                        <p class="mb-0"><strong>RERA Number:</strong> {{ $project->rera_number }}</p>
                    @endif

                    @if($project->brochure)
                        <hr>
                        <a href="{{ asset('storage/' . $project->brochure) }}" target="_blank" class="btn btn-outline-info">
                            <i class="fas fa-file-pdf me-2"></i>Download Brochure
                        </a>
                    @endif

                    <hr>

                    <div class="d-flex gap-2">
                        @if($project->approval_status === 'pending')
                            <form action="{{ route('admin.projects.approve', $project->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check me-1"></i>Approve Project
                                </button>
                            </form>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="fas fa-times me-1"></i>Reject Project
                            </button>
                        @endif
                        <a href="{{ route('developer-projects.show', ['developerSlug' => $project->developer->slug, 'projectSlug' => $project->slug]) }}" target="_blank" class="btn btn-outline-primary">
                            <i class="fas fa-eye me-1"></i>View Public Page
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Properties -->
    @if($project->properties()->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Properties in this Project ({{ $project->properties()->count() }})</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($project->properties()->latest()->take(10)->get() as $property)
                                        <tr>
                                            <td>{{ Str::limit($property->title, 50) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $property->type === 'sale' ? 'success' : ($property->type === 'rent' ? 'info' : 'secondary') }}">
                                                    {{ ucfirst($property->type) }}
                                                </span>
                                            </td>
                                            <td>₹{{ number_format($property->price) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $property->approval_status === 'approved' ? 'success' : ($property->approval_status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($property->approval_status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Reject Modal -->
@if($project->approval_status === 'pending')
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.projects.reject', $project->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reject Project</h5>
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
                    <button type="submit" class="btn btn-danger">Reject Project</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
