@extends(Theme::getThemeNamespace('layouts.default'))

@section('title', $metaTitle)

@section('content')
<!-- Project Header -->
<div class="position-relative" style="height: 400px;">
    <img src="{{ $project->main_image_url }}" alt="{{ $project->name }}" class="w-100 h-100 object-fit-cover">
    <div class="position-absolute bottom-0 start-0 w-100 bg-gradient-dark p-4" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
        <div class="container">
            <div class="text-white">
                <div class="d-flex gap-2 mb-2">
                    <span class="badge bg-{{ $project->status === 'completed' ? 'success' : ($project->status === 'ongoing' ? 'warning' : 'secondary') }}">
                        {{ ucfirst($project->status) }}
                    </span>
                    <span class="badge bg-info">{{ ucfirst($project->type) }}</span>
                    @if($project->is_featured)
                        <span class="badge bg-warning text-dark">Featured</span>
                    @endif
                </div>
                <h1 class="h2 mb-2">{{ $project->name }}</h1>
                <p class="mb-0 opacity-75">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    {{ $project->address }}, {{ $project->city }}, {{ $project->state }}
                </p>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Quick Info -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card border-0 bg-light text-center py-3">
                        <i class="fas fa-home fa-2x text-primary mb-2"></i>
                        <div class="h5 mb-1">{{ $project->total_units ?? 'N/A' }}</div>
                        <small class="text-muted">Total Units</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 bg-light text-center py-3">
                        <i class="fas fa-calendar fa-2x text-primary mb-2"></i>
                        <div class="h5 mb-1">{{ $project->launch_date ? $project->launch_date->format('M Y') : 'N/A' }}</div>
                        <small class="text-muted">Launch Date</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 bg-light text-center py-3">
                        <i class="fas fa-key fa-2x text-primary mb-2"></i>
                        <div class="h5 mb-1">{{ $project->possession_date ? $project->possession_date->format('M Y') : 'N/A' }}</div>
                        <small class="text-muted">Possession</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 bg-light text-center py-3">
                        <i class="fas fa-tag fa-2x text-primary mb-2"></i>
                        <div class="h5 mb-1">{{ $project->formatted_price ?? 'On Request' }}</div>
                        <small class="text-muted">Starting Price</small>
                    </div>
                </div>
            </div>

            <!-- About Project -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h4 class="mb-0">About Project</h4>
                </div>
                <div class="card-body">
                    <p>{{ $project->description }}</p>
                    @if($project->short_description)
                        <p class="text-muted">{{ $project->short_description }}</p>
                    @endif
                </div>
            </div>

            <!-- Gallery -->
            @if($project->gallery_images && count($project->gallery_images) > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Gallery</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach($project->gallery_images as $image)
                                <div class="col-md-4 col-6">
                                    <img src="{{ asset('storage/' . $image) }}" alt="" class="img-fluid rounded" style="height: 200px; object-fit: cover; width: 100%;">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Amenities -->
            @if($project->amenities && count($project->amenities) > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Amenities</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($project->amenities as $amenity)
                                <div class="col-md-4 col-sm-6 mb-3">
                                    <div class="d-flex align-items-center p-2 bg-light rounded">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>{{ $amenity }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Location Map -->
            @if($project->latitude && $project->longitude)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Location Map</h4>
                    </div>
                    <div class="card-body">
                        <div class="ratio ratio-16x9">
                            <iframe 
                                src="https://maps.google.com/maps?q={{ $project->latitude }},{{ $project->longitude }}&z=15&output=embed"
                                width="100%" 
                                height="350" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy">
                            </iframe>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Properties in Project -->
            @if($project->approvedProperties && $project->approvedProperties->count() > 0)
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Properties in {{ $project->name }}</h4>
                        <span class="badge bg-primary">{{ $project->approvedProperties->count() }} Available</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach($project->approvedProperties as $property)
                                @include('realestate::frontend.partials.property-card', ['property' => $property])
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Developer Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Developer</h5>
                </div>
                <div class="card-body">
                    @if($project->developer)
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $project->developer->logo_url }}" alt="{{ $project->developer->company_name }}" class="rounded" width="60" height="60">
                            <div class="ms-3">
                                <h6 class="mb-0">{{ $project->developer->company_name }}</h6>
                                <small class="text-muted">{{ $project->developer->total_projects }} Projects</small>
                            </div>
                        </div>
                        <a href="{{ route('developers.show', $project->developer->slug) }}" class="btn btn-outline-primary w-100">
                            View Developer Profile
                        </a>
                    @else
                        <p class="text-muted mb-0">Developer information not available.</p>
                    @endif
                </div>
            </div>

            <!-- Contact Form -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Interested? Contact Us</h5>
                </div>
                <div class="card-body">
                    <form action="#" method="POST">
                        @csrf
                        <div class="mb-3">
                            <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Your Email" required>
                        </div>
                        <div class="mb-3">
                            <input type="tel" name="phone" class="form-control" placeholder="Your Phone" required>
                        </div>
                        <div class="mb-3">
                            <select name="interest" class="form-select">
                                <option value="">I'm interested in...</option>
                                <option value="2bhk">2 BHK</option>
                                <option value="3bhk">3 BHK</option>
                                <option value="4bhk">4 BHK</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <textarea name="message" class="form-control" rows="3" placeholder="Your Message"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Request Information</button>
                    </form>
                </div>
            </div>

            <!-- Brochure Download -->
            @if($project->brochure)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body text-center">
                        <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                        <h5>Download Brochure</h5>
                        <p class="text-muted small">Get detailed information about this project</p>
                        <a href="{{ asset('storage/' . $project->brochure) }}" target="_blank" class="btn btn-outline-danger w-100">
                            <i class="fas fa-download me-2"></i>Download PDF
                        </a>
                    </div>
                </div>
            @endif

            <!-- Project Details -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Project Details</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Project ID</span>
                            <span class="text-muted">#{{ $project->id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Project Type</span>
                            <span class="text-muted">{{ ucfirst($project->type) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Status</span>
                            <span class="text-muted">{{ ucfirst($project->status) }}</span>
                        </li>
                        @if($project->rera_number)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>RERA Number</span>
                                <span class="text-muted">{{ $project->rera_number }}</span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script type="application/ld+json">
{!! json_encode($schemaData) !!}
</script>
@endpush
@endsection
