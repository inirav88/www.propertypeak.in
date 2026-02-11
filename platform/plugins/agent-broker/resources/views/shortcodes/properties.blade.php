<div class="agent-property-shortcode-list">
    @forelse($properties as $property)
        <article class="agent-property-shortcode-item mb-3">
            <h3 class="h5 mb-1">
                <a href="{{ $property->url }}">{{ $property->name }}</a>
            </h3>

            <p class="mb-1">{{ $property->price_html }}</p>

            @if($property->is_featured)
                <span class="badge bg-primary">{{ __('Featured') }}</span>
            @endif

            @if($property->location)
                <p class="mb-0 mt-2">{{ $property->location }}</p>
            @endif
        </article>
    @empty
        <p class="mb-0">{{ __('No properties found.') }}</p>
    @endforelse

    @if($properties->hasPages())
        <div class="mt-3">{{ $properties->links() }}</div>
    @endif
</div>
