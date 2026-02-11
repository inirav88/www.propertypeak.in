<div class="developer-project-shortcode-list">
    @forelse($projects as $project)
        <article class="developer-project-shortcode-item mb-3">
            <h3 class="h5 mb-1">{{ $project->name }}</h3>

            @if($project->developerProfile)
                <p class="text-muted mb-1">{{ $project->developerProfile->company_name }}</p>
            @endif

            @if($project->project_status)
                <span class="badge bg-secondary">{{ ucfirst($project->project_status) }}</span>
            @endif

            @if($project->is_featured)
                <span class="badge bg-primary">{{ __('Featured') }}</span>
            @endif

            @if($project->location)
                <p class="mb-0 mt-2">{{ $project->location }}</p>
            @endif
        </article>
    @empty
        <p class="mb-0">{{ __('No projects found.') }}</p>
    @endforelse

    @if($projects->hasPages())
        <div class="mt-3">{{ $projects->links() }}</div>
    @endif
</div>
