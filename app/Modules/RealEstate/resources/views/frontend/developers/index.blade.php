<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-5">
        <div class="col-lg-8">
            <h1 class="h2 mb-3">Real Estate Developers</h1>
            <p class="text-muted">Find verified real estate developers with their portfolio of completed, ongoing, and upcoming projects.</p>
        </div>
        <div class="col-lg-4">
            <!-- Search Form -->
            <form action="{{ route('developers.index') }}" method="GET" class="mb-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search developers..." value="{{ $search ?? '' }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Developers Grid -->
    <div class="row g-4">
        @forelse($developers as $developer)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <!-- Banner Image -->
                    <div class="position-relative" style="height: 150px;">
                        <img src="{{ $developer->banner_url }}" alt="{{ $developer->company_name }}" class="w-100 h-100 object-fit-cover">
                        <div class="position-absolute top-0 start-0 m-3">
                            <img src="{{ $developer->logo_url }}" alt="{{ $developer->company_name }}" class="rounded bg-white p-1" width="60" height="60">
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <h5 class="card-title mb-1">{{ $developer->company_name }}</h5>
                        
                        @if($developer->city)
                            <p class="text-muted small mb-2">
                                <i class="fas fa-map-marker-alt me-1"></i>{{ $developer->city }}
                            </p>
                        @endif

                        <!-- Stats -->
                        <div class="row text-center g-2 mb-3">
                            <div class="col-4">
                                <div class="bg-light rounded p-2">
                                    <div class="h5 mb-0">{{ $developer->total_projects }}</div>
                                    <small class="text-muted">Projects</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-light rounded p-2">
                                    <div class="h5 mb-0">{{ $developer->completed_projects }}</div>
                                    <small class="text-muted">Completed</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-light rounded p-2">
                                    <div class="h5 mb-0">{{ $developer->ongoing_projects }}</div>
                                    <small class="text-muted">Ongoing</small>
                                </div>
                            </div>
                        </div>

                        @if($developer->tagline)
                            <p class="card-text small text-muted">{{ Str::limit($developer->tagline, 100) }}</p>
                        @endif
                    </div>
                    
                    <div class="card-footer bg-transparent border-0">
                        <a href="{{ route('developers.show', $developer->slug) }}" class="btn btn-outline-primary w-100">
                            View Profile
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-building fa-3x text-muted"></i>
                </div>
                <h4>No developers found</h4>
                <p class="text-muted">Try adjusting your search criteria.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="row mt-5">
        <div class="col-12">
            {{ $developers->links() }}
        </div>
    </div>
</div>
