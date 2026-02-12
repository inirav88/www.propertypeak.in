<div class="agent-shortcode-list">
    @forelse($profiles as $profile)
        <article class="agent-shortcode-item mb-3">
            <h3 class="h5 mb-1">
                <a href="{{ url($profile->slug) }}">{{ $profile->account?->name ?: __('Agent') }}</a>
            </h3>

            @if($profile->designation)
                <p class="text-muted mb-1">{{ $profile->designation }}</p>
            @endif

            @if($profile->is_verified)
                <span class="badge bg-success">{{ __('Verified') }}</span>
            @endif

            @if($profile->is_featured)
                <span class="badge bg-primary">{{ __('Featured') }}</span>
            @endif
        </article>
    @empty
        <p class="mb-0">{{ __('No agents found.') }}</p>
    @endforelse

    @if($profiles->hasPages())
        <div class="mt-3">{{ $profiles->links() }}</div>
    @endif
</div>
