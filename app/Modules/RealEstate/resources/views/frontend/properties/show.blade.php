<!-- Property Header -->
<div class="bg-light py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="h2 mb-2">{{ $property->title ?? $property->name }}</h1>
                <p class="text-muted mb-0">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    {{ $property->location }}
                </p>
            </div>
            <div class="text-end">
                <h2 class="text-primary mb-2">{{ $property->formatted_price }}</h2>
                @if($property->is_featured)
                    <span class="badge bg-warning text-dark">Featured</span>
                @endif
                <span class="badge bg-{{ $property->type === 'sale' ? 'success' : ($property->type === 'rent' ? 'info' : 'secondary') }}">
                    For {{ ucfirst($property->type) }}
                </span>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Image Gallery -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    @if($property->images && count($property->images) > 0)
                        <img src="{{ $property->main_image_url }}" alt="{{ $property->title ?? $property->name }}" class="img-fluid rounded mb-3 w-100" style="max-height: 500px; object-fit: cover;">
                        @if(count($property->images) > 1)
                            <div class="row g-2">
                                @foreach(array_slice($property->images, 1, 4) as $image)
                                    <div class="col-3">
                                        <img src="{{ asset('storage/' . $image) }}" alt="" class="img-thumbnail w-100" style="height: 100px; object-fit: cover;">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-image fa-4x text-muted mb-3"></i>
                            <p class="text-muted">No images available</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Overview -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Overview</h4>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 col-md-3 mb-3">
                            <div class="p-3 bg-light rounded">
                                <i class="fas fa-bed fa-2x text-primary mb-2"></i>
                                <h5 class="mb-1">{{ $property->bedrooms ?? ($property->total_beds ?? 'N/A') }}</h5>
                                <small class="text-muted">Bedrooms</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="p-3 bg-light rounded">
                                <i class="fas fa-bath fa-2x text-primary mb-2"></i>
                                <h5 class="mb-1">{{ $property->bathrooms ?? 'N/A' }}</h5>
                                <small class="text-muted">Bathrooms</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="p-3 bg-light rounded">
                                <i class="fas fa-ruler-combined fa-2x text-primary mb-2"></i>
                                <h5 class="mb-1">{{ $property->carpet_area ? $property->carpet_area . ' sq.ft' : ($property->square ? $property->square . ' sq.ft' : 'N/A') }}</h5>
                                <small class="text-muted">Carpet Area</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="p-3 bg-light rounded">
                                <i class="fas fa-home fa-2x text-primary mb-2"></i>
                                <h5 class="mb-1">{{ $property->property_type ? ucfirst($property->property_type) : ($property->pg_category ? ucfirst($property->pg_category) : 'N/A') }}</h5>
                                <small class="text-muted">Property Type</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Description</h4>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $property->description }}</p>
                </div>
            </div>

            <!-- Amenities -->
            @if($property->amenities && is_array($property->amenities) && count($property->amenities) > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Amenities</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($property->amenities as $amenity)
                                <div class="col-md-4 col-sm-6 mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>{{ $amenity }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Location -->
            @if($property->latitude && $property->longitude)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Location</h4>
                    </div>
                    <div class="card-body">
                        <div class="ratio ratio-16x9">
                            <iframe 
                                src="https://maps.google.com/maps?q={{ $property->latitude }},{{ $property->longitude }}&z=15&output=embed"
                                width="100%" 
                                height="300" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy">
                            </iframe>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Similar Properties -->
            @if($similarProperties->count() > 0)
                <div class="similar-properties-wrapper">
                    <div class="sp-section-header">
                        <span class="sp-subtitle">
                            <i class="fas fa-compass"></i>
                            You May Also Like
                        </span>
                        <h3 class="sp-title-main">Similar Properties</h3>
                        <p class="sp-desc">Discover more properties that match your preferences</p>
                    </div>
                    
                    <div class="similar-properties-grid">
                        @foreach($similarProperties as $similar)
                            @include('realestate::frontend.partials.property-card', ['property' => $similar])
                        @endforeach
                    </div>
                </div>

                <style>
                    /* Similar Properties Section Styles */
                    .similar-properties-wrapper {
                        margin-top: 2rem;
                        padding: 2.5rem;
                        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
                        border-radius: 20px;
                        border: 1px solid #e2e8f0;
                    }

                    .sp-section-header {
                        text-align: center;
                        margin-bottom: 2rem;
                    }

                    .sp-subtitle {
                        display: inline-flex;
                        align-items: center;
                        gap: 0.5rem;
                        font-size: 0.75rem;
                        font-weight: 600;
                        text-transform: uppercase;
                        letter-spacing: 1.5px;
                        color: #3b82f6;
                        padding: 0.5rem 1rem;
                        background: rgba(59, 130, 246, 0.1);
                        border-radius: 50px;
                        margin-bottom: 0.75rem;
                    }

                    .sp-title-main {
                        font-size: 1.75rem;
                        font-weight: 700;
                        color: #1e293b;
                        margin-bottom: 0.5rem;
                    }

                    .sp-desc {
                        font-size: 0.95rem;
                        color: #64748b;
                    }

                    .similar-properties-grid {
                        display: grid;
                        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                        gap: 1.5rem;
                    }

                    /* Property Card Styles */
                    .similar-property-card {
                        background: #ffffff;
                        border-radius: 16px;
                        overflow: hidden;
                        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.03);
                        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                        border: 1px solid #e2e8f0;
                        display: flex;
                        flex-direction: column;
                    }

                    .similar-property-card:hover {
                        transform: translateY(-6px);
                        box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
                        border-color: #cbd5e1;
                    }

                    /* Image Section */
                    .sp-image-wrap {
                        position: relative;
                        height: 200px;
                        overflow: hidden;
                    }

                    .sp-image-link {
                        display: block;
                        width: 100%;
                        height: 100%;
                    }

                    .sp-image {
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
                    }

                    .similar-property-card:hover .sp-image {
                        transform: scale(1.08);
                    }

                    .sp-image-placeholder {
                        width: 100%;
                        height: 100%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
                        color: #94a3b8;
                        font-size: 3rem;
                    }

                    .sp-image-overlay {
                        position: absolute;
                        inset: 0;
                        background: linear-gradient(180deg, transparent 50%, rgba(0, 0, 0, 0.4) 100%);
                        opacity: 0;
                        transition: opacity 0.3s ease;
                    }

                    .similar-property-card:hover .sp-image-overlay {
                        opacity: 1;
                    }

                    /* Badges */
                    .sp-badges {
                        position: absolute;
                        top: 12px;
                        left: 12px;
                        display: flex;
                        flex-wrap: wrap;
                        gap: 6px;
                    }

                    .sp-badge {
                        display: inline-flex;
                        align-items: center;
                        gap: 4px;
                        padding: 5px 10px;
                        border-radius: 50px;
                        font-size: 0.7rem;
                        font-weight: 600;
                        text-transform: uppercase;
                        letter-spacing: 0.5px;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                    }

                    .sp-badge-featured {
                        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
                        color: #fff;
                    }

                    .sp-badge-sale {
                        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
                        color: #fff;
                    }

                    .sp-badge-rent {
                        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                        color: #fff;
                    }

                    /* Price Tag */
                    .sp-price-tag {
                        position: absolute;
                        bottom: 12px;
                        left: 12px;
                        padding: 8px 14px;
                        background: rgba(255, 255, 255, 0.95);
                        border-radius: 10px;
                        backdrop-filter: blur(8px);
                        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                    }

                    .sp-price {
                        font-size: 1.1rem;
                        font-weight: 700;
                        color: #1e293b;
                    }

                    .sp-price-unit {
                        font-size: 0.75rem;
                        color: #64748b;
                        margin-left: 2px;
                    }

                    /* Content */
                    .sp-content {
                        padding: 1.25rem;
                        flex: 1;
                        display: flex;
                        flex-direction: column;
                    }

                    .sp-title {
                        font-size: 1rem;
                        font-weight: 600;
                        color: #1e293b;
                        margin-bottom: 0.5rem;
                        line-height: 1.4;
                        display: -webkit-box;
                        -webkit-line-clamp: 2;
                        -webkit-box-orient: vertical;
                        overflow: hidden;
                    }

                    .sp-title a {
                        color: inherit;
                        text-decoration: none;
                        transition: color 0.2s ease;
                    }

                    .sp-title a:hover {
                        color: #3b82f6;
                    }

                    .sp-location {
                        display: flex;
                        align-items: center;
                        gap: 6px;
                        font-size: 0.85rem;
                        color: #64748b;
                        margin-bottom: 1rem;
                    }

                    .sp-location i {
                        color: #3b82f6;
                        font-size: 0.9rem;
                    }

                    /* Features */
                    .sp-features {
                        display: flex;
                        gap: 1rem;
                        margin-top: auto;
                        padding-top: 1rem;
                        border-top: 1px solid #f1f5f9;
                    }

                    .sp-feature {
                        display: flex;
                        align-items: center;
                        gap: 6px;
                        font-size: 0.85rem;
                        color: #475569;
                    }

                    .sp-feature-icon {
                        width: 28px;
                        height: 28px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        background: #f1f5f9;
                        border-radius: 8px;
                        color: #3b82f6;
                        font-size: 0.8rem;
                    }

                    .sp-feature span {
                        font-weight: 600;
                        color: #1e293b;
                    }

                    /* Footer */
                    .sp-footer {
                        padding: 1rem 1.25rem;
                        background: #f8fafc;
                        border-top: 1px solid #f1f5f9;
                    }

                    .sp-view-btn {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        gap: 8px;
                        width: 100%;
                        padding: 10px 20px;
                        background: #3b82f6;
                        color: #fff;
                        border-radius: 10px;
                        font-size: 0.9rem;
                        font-weight: 600;
                        text-decoration: none;
                        transition: all 0.3s ease;
                        border: none;
                        cursor: pointer;
                    }

                    .sp-view-btn:hover {
                        background: #2563eb;
                        color: #fff;
                        transform: translateX(4px);
                    }

                    .sp-view-btn i {
                        transition: transform 0.3s ease;
                    }

                    .sp-view-btn:hover i {
                        transform: translateX(4px);
                    }

                    /* Responsive */
                    @media (max-width: 768px) {
                        .similar-properties-wrapper {
                            padding: 1.5rem;
                        }

                        .similar-properties-grid {
                            grid-template-columns: 1fr;
                        }

                        .sp-title-main {
                            font-size: 1.5rem;
                        }
                    }
                </style>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Contact Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Contact Owner</h5>
                </div>
                <div class="card-body">
                    @if($property->added_by_role === 'developer' && $property->developer)
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $property->developer->logo_url }}" alt="" class="rounded" width="50" height="50">
                            <div class="ms-3">
                                <h6 class="mb-0">{{ $property->developer->company_name }}</h6>
                                <small class="text-muted">Developer</small>
                            </div>
                        </div>
                    @elseif($property->added_by_role === 'agent' && $property->agent)
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $property->agent->avatar_url }}" alt="" class="rounded-circle" width="50" height="50">
                            <div class="ms-3">
                                <h6 class="mb-0">{{ $property->agent->full_name }}</h6>
                                <small class="text-muted">Agent</small>
                            </div>
                        </div>
                    @else
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fas fa-user text-muted"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-0">Property Owner</h6>
                                <small class="text-muted">Contact for details</small>
                            </div>
                        </div>
                    @endif

                    <form action="#" method="POST">
                        @csrf
                        <div class="mb-3">
                            <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Your Email" required>
                        </div>
                        <div class="mb-3">
                            <input type="tel" name="phone" class="form-control" placeholder="Your Phone">
                        </div>
                        <div class="mb-3">
                            <textarea name="message" class="form-control" rows="3" placeholder="I'm interested in this property..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Send Message</button>
                    </form>
                </div>
            </div>

            <!-- Property Details -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Property Details</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Property ID</span>
                            <span class="text-muted">#{{ $property->id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Property Type</span>
                            <span class="text-muted">{{ $property->property_type ? ucfirst($property->property_type) : 'Property' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Listing Type</span>
                            <span class="text-muted">For {{ ucfirst($property->type) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Posted On</span>
                            <span class="text-muted">{{ $property->created_at->format('M d, Y') }}</span>
                        </li>
                        @if($property->rera_number)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>RERA Number</span>
                                <span class="text-muted">{{ $property->rera_number }}</span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Mortgage Calculator -->
            @include(Theme::getThemeNamespace('views.real-estate.single-layouts.partials.mortgage-calculator'), ['model' => $property])
        </div>
    </div>
</div>

@push('scripts')
<script type="application/ld+json">
{!! json_encode($schemaData) !!}
</script>
@endpush
