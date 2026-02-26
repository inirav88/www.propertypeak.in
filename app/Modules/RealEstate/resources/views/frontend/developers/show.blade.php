<!-- Banner Section -->
<div class="position-relative" style="height: 300px;">
    <img src="{{ $developer->banner_url }}" alt="{{ $developer->company_name }}" class="w-100 h-100 object-fit-cover">
    <div class="position-absolute bottom-0 start-0 w-100 bg-gradient-dark p-4">
        <div class="container">
            <div class="d-flex align-items-end">
                <img src="{{ $developer->logo_url }}" alt="{{ $developer->company_name }}" class="rounded bg-white p-2 me-4" width="100" height="100">
                <div class="text-white">
                    <h1 class="h2 mb-1">{{ $developer->company_name }}</h1>
                    @if($developer->tagline)
                        <p class="mb-0 opacity-75">{{ $developer->tagline }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Stats Cards -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card border-0 bg-light text-center py-3">
                        <div class="h3 mb-1">{{ $developer->total_projects }}</div>
                        <small class="text-muted">Total Projects</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 bg-light text-center py-3">
                        <div class="h3 mb-1">{{ $developer->completed_projects }}</div>
                        <small class="text-muted">Completed</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 bg-light text-center py-3">
                        <div class="h3 mb-1">{{ $developer->ongoing_projects }}</div>
                        <small class="text-muted">Ongoing</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 bg-light text-center py-3">
                        <div class="h3 mb-1">{{ $developer->upcoming_projects }}</div>
                        <small class="text-muted">Upcoming</small>
                    </div>
                </div>
            </div>

            <!-- About -->
            @if($developer->description)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">About</h5>
                        <p class="card-text">{{ $developer->description }}</p>
                    </div>
                </div>
            @endif

            <!-- Projects Section -->
            @if($developer->approvedProjects && $developer->approvedProjects->count() > 0)
                <div class="mb-4">
                    <h3 class="h4 mb-3">Projects</h3>
                    <div class="row g-3">
                        @foreach($developer->approvedProjects as $project)
                            <div class="col-md-6">
                                @include('realestate::frontend.partials.project-card', ['project' => $project])
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Properties Section -->
            @if($developer->approvedProperties && $developer->approvedProperties->count() > 0)
                <div class="mb-4">
                    <h3 class="h4 mb-3">Properties</h3>
                    <div class="row g-3">
                        @foreach($developer->approvedProperties as $property)
                            <div class="col-md-6">
                                @include('realestate::frontend.partials.property-card', ['property' => $property])
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Contact Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Contact Information</h5>
                </div>
                <div class="card-body">
                    @if($developer->office_address)
                        <div class="d-flex mb-3">
                            <i class="fas fa-map-marker-alt text-primary mt-1 me-3"></i>
                            <div>
                                <small class="text-muted d-block">Address</small>
                                <span>{{ $developer->office_address }}</span>
                            </div>
                        </div>
                    @endif
                    @if($developer->city)
                        <div class="d-flex mb-3">
                            <i class="fas fa-city text-primary mt-1 me-3"></i>
                            <div>
                                <small class="text-muted d-block">City</small>
                                <span>{{ $developer->city }}</span>
                            </div>
                        </div>
                    @endif
                    @if($developer->website)
                        <div class="d-flex mb-3">
                            <i class="fas fa-globe text-primary mt-1 me-3"></i>
                            <div>
                                <small class="text-muted d-block">Website</small>
                                <a href="{{ $developer->website }}" target="_blank" rel="nofollow">{{ $developer->website }}</a>
                            </div>
                        </div>
                    @endif
                    @if($developer->user && $developer->user->email)
                        <div class="d-flex mb-3">
                            <i class="fas fa-envelope text-primary mt-1 me-3"></i>
                            <div>
                                <small class="text-muted d-block">Email</small>
                                <a href="mailto:{{ $developer->user->email }}">{{ $developer->user->email }}</a>
                            </div>
                        </div>
                    @endif
                    @if($developer->user && $developer->user->phone)
                        <div class="d-flex">
                            <i class="fas fa-phone text-primary mt-1 me-3"></i>
                            <div>
                                <small class="text-muted d-block">Phone</small>
                                <a href="tel:{{ $developer->user->phone }}">{{ $developer->user->phone }}</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- RERA Info -->
            @if($developer->rera_id)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-certificate text-success fa-2x me-3"></i>
                            <div>
                                <small class="text-muted d-block">RERA Registered</small>
                                <strong>{{ $developer->rera_id }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Social Links -->
            @if($developer->social_links && count($developer->social_links) > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Follow Us</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex gap-2">
                            @foreach($developer->social_links as $platform => $url)
                                <a href="{{ $url }}" target="_blank" rel="nofollow" class="btn btn-outline-primary">
                                    <i class="fab fa-{{ $platform }}"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Contact Form -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Contact Developer</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('developer.contact', $developer->slug) }}" method="POST">
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
                            <textarea name="message" class="form-control" rows="4" placeholder="Your Message" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
