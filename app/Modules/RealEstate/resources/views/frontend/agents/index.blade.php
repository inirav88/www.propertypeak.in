<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-5">
        <div class="col-lg-8">
            <h1 class="h2 mb-3">Real Estate Agents</h1>
            <p class="text-muted">Connect with experienced real estate agents and property brokers for buying, selling, or renting properties.</p>
        </div>
        <div class="col-lg-4">
            <!-- Search Form -->
            <form action="{{ route('agents.index') }}" method="GET" class="mb-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search agents..." value="{{ $search ?? '' }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Agents Grid -->
    <div class="row g-4">
        @forelse($agents as $agent)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100 border-0 shadow-sm">
                    <!-- Avatar -->
                    <div class="position-relative text-center pt-4">
                        <img src="{{ $agent->avatar_url }}" alt="{{ $agent->full_name }}" class="rounded-circle" width="100" height="100">
                        @if($agent->is_verified)
                            <span class="position-absolute top-0 end-0 m-3 badge bg-success">
                                <i class="fas fa-check-circle"></i> Verified
                            </span>
                        @endif
                    </div>
                    
                    <div class="card-body text-center">
                        <h5 class="card-title mb-1">{{ $agent->full_name }}</h5>
                        @if($agent->designation)
                            <p class="text-muted small mb-2">{{ $agent->designation }}</p>
                        @endif

                        <!-- Specializations -->
                        @if($agent->specializations && count($agent->specializations) > 0)
                            <div class="mb-3">
                                @foreach(array_slice($agent->specializations, 0, 3) as $spec)
                                    <span class="badge bg-light text-dark me-1">{{ $spec }}</span>
                                @endforeach
                            </div>
                        @endif

                        <!-- Service Areas -->
                        @if($agent->service_areas && count($agent->service_areas) > 0)
                            <p class="small text-muted mb-2">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                {{ implode(', ', array_slice($agent->service_areas, 0, 3)) }}
                            </p>
                        @endif

                        <!-- Experience & Sales -->
                        <div class="row text-center g-2 mb-3">
                            <div class="col-6">
                                <div class="bg-light rounded p-2">
                                    <div class="h5 mb-0">{{ $agent->experience_years ?? 0 }}+</div>
                                    <small class="text-muted">Years Exp.</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded p-2">
                                    <div class="h5 mb-0">{{ $agent->total_sales ?? 0 }}</div>
                                    <small class="text-muted">Sales</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-transparent border-0">
                        <a href="{{ route('agents.show', $agent->slug) }}" class="btn btn-outline-primary w-100">
                            View Profile
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-user-tie fa-3x text-muted"></i>
                </div>
                <h4>No agents found</h4>
                <p class="text-muted">Try adjusting your search criteria.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="row mt-5">
        <div class="col-12">
            {{ $agents->links() }}
        </div>
    </div>
</div>
