@php
    // Ensure we have a valid property object
    if (!is_object($property)) {
        return;
    }
    
    $propertyUrl = route('properties.show', $property->slug ?? $property->id ?? '#');
    $title = $property->title ?? $property->name ?? 'Property';
    $type = $property->type ?? null;
    $isRent = $type === 'rent';
    $isFeatured = $property->is_featured ?? false;
    $imageUrl = $property->main_image_url ?? null;
    $price = $property->compact_price ?? $property->formatted_price ?? 'Price on request';
    $location = $property->short_location ?? $property->location ?? null;
    $bedrooms = $property->bedrooms ?? $property->total_beds ?? null;
    $bathrooms = $property->bathrooms ?? null;
    $area = $property->carpet_area ?? $property->square ?? null;
@endphp

<article class="similar-property-card">
    {{-- Image Section --}}
    <div class="sp-image-wrap">
        <a href="{{ $propertyUrl }}" class="sp-image-link">
            @if($imageUrl)
                <img src="{{ $imageUrl }}" alt="{{ $title }}" class="sp-image" loading="lazy">
            @else
                <div class="sp-image-placeholder">
                    <i class="fas fa-home"></i>
                </div>
            @endif
            <div class="sp-image-overlay"></div>
        </a>

        {{-- Badges --}}
        <div class="sp-badges">
            @if($isFeatured)
                <span class="sp-badge sp-badge-featured">
                    <i class="fas fa-star"></i>
                    Featured
                </span>
            @endif
            <span class="sp-badge {{ $isRent ? 'sp-badge-rent' : 'sp-badge-sale' }}">
                {{ $isRent ? 'For Rent' : 'For Sale' }}
            </span>
        </div>

        {{-- Price Tag --}}
        <div class="sp-price-tag">
            <span class="sp-price">{{ $price }}</span>
            @if($isRent)
                <small class="sp-price-unit">/mo</small>
            @endif
        </div>
    </div>

    {{-- Content Section --}}
    <div class="sp-content">
        {{-- Title --}}
        <h3 class="sp-title">
            <a href="{{ $propertyUrl }}" title="{{ $title }}">
                {{ Str::limit($title, 55) }}
            </a>
        </h3>

        {{-- Location --}}
        @if($location)
            <div class="sp-location">
                <i class="fas fa-map-marker-alt"></i>
                <span>{{ Str::limit($location, 40) }}</span>
            </div>
        @endif

        {{-- Features --}}
        <div class="sp-features">
            @if($bedrooms)
                <div class="sp-feature" title="Bedrooms">
                    <div class="sp-feature-icon">
                        <i class="fas fa-bed"></i>
                    </div>
                    <span>{{ $bedrooms }}</span>
                </div>
            @endif
            @if($bathrooms)
                <div class="sp-feature" title="Bathrooms">
                    <div class="sp-feature-icon">
                        <i class="fas fa-bath"></i>
                    </div>
                    <span>{{ $bathrooms }}</span>
                </div>
            @endif
            @if($area)
                <div class="sp-feature" title="Area">
                    <div class="sp-feature-icon">
                        <i class="fas fa-ruler-combined"></i>
                    </div>
                    <span>{{ $area }} sq.ft</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Footer / CTA --}}
    <div class="sp-footer">
        <a href="{{ $propertyUrl }}" class="sp-view-btn">
            View Details
            <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</article>
