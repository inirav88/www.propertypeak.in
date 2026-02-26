<div class="col-md-6">
    <div class="card h-100 border-0 shadow-sm">
        <!-- Project Image -->
        <div class="position-relative" style="height: 180px;">
            <img src="{{ $project->main_image_url }}" alt="{{ $project->name }}" class="w-100 h-100 object-fit-cover">
            
            <!-- Status Badge -->
            <div class="position-absolute top-0 start-0 m-2">
                @php
                    $statusColors = [
                        'upcoming' => 'secondary',
                        'ongoing' => 'warning',
                        'completed' => 'success',
                        'sold_out' => 'danger',
                    ];
                @endphp
                <span class="badge bg-{{ $statusColors[$project->status] ?? 'secondary' }}">
                    {{ ucfirst($project->status) }}
                </span>
            </div>
            
            <!-- Featured Badge -->
            @if($project->is_featured)
                <div class="position-absolute top-0 end-0 m-2">
                    <span class="badge bg-warning text-dark">Featured</span>
                </div>
            @endif

            <!-- Price -->
            @if($project->price_starting_from)
                <div class="position-absolute bottom-0 start-0 m-2">
                    <span class="badge bg-primary fs-6">From {{ $project->formatted_price }}</span>
                </div>
            @endif
        </div>
        
        <div class="card-body">
            <h5 class="card-title h6 mb-2">{{ $project->name }}</h5>
            
            <p class="text-muted small mb-2">
                <i class="fas fa-map-marker-alt me-1"></i>
                {{ $project->location ?? $project->city }}
            </p>

            <!-- Configurations -->
            @if($project->unit_configurations)
                <div class="d-flex flex-wrap gap-1 mb-2">
                    @foreach(array_slice($project->unit_configurations, 0, 3) as $config)
                        <span class="badge bg-light text-dark">{{ $config }}</span>
                    @endforeach
                </div>
            @endif

            <!-- Key Info -->
            <div class="d-flex gap-3 text-muted small mb-2">
                @if($project->total_units)
                    <span><i class="fas fa-home me-1"></i>{{ $project->total_units }} Units</span>
                @endif
                @if($project->possession_date)
                    <span><i class="fas fa-calendar me-1"></i>{{ $project->possession_date->format('M Y') }}</span>
                @endif
            </div>

            <!-- RERA -->
            @if($project->rera_number)
                <p class="small text-success mb-0">
                    <i class="fas fa-check-circle me-1"></i>RERA Approved
                </p>
            @endif
        </div>
        
        <div class="card-footer bg-transparent border-0">
            <a href="{{ route('developer-projects.show', ['developerSlug' => $project->developer->slug, 'projectSlug' => $project->slug]) }}" class="btn btn-outline-primary btn-sm w-100">
                View Project
            </a>
        </div>
    </div>
</div>
