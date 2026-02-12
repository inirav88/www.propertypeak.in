<div class="developer-shortcode-list">
    @forelse($profiles as $profile)
        <article class="developer-shortcode-item mb-3">
            <h3 class="h5 mb-1">
                <a href="{{ url($profile->slug) }}">{{ $profile->company_name }}</a>
            </h3>

            @if($profile->is_verified)
                <span class="badge bg-success">{{ __('Verified') }}</span>
            @endif

            @if($profile->is_featured)
                <span class="badge bg-primary">{{ __('Featured') }}</span>
            @endif

            @if($profile->about)
                <p class="mb-0 mt-2">{{ \Illuminate\Support\Str::limit(strip_tags($profile->about), 140) }}</p>
            @endif
        </article>
    @empty
        <p class="mb-0">{{ __('No developers found.') }}</p>
    @endforelse

    @if($profiles->hasPages())
        <div class="mt-3">{{ $profiles->links() }}</div>
    @endif
</div>
