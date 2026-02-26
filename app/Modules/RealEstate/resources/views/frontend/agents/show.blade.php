<!-- Banner Section -->
<div class="position-relative" style="height: 250px;">
    <img src="{{ $agent->banner_url }}" alt="{{ $agent->full_name }}" class="w-100 h-100 object-fit-cover">
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient-dark" style="background: linear-gradient(to bottom, rgba(0,0,0,0.3), rgba(0,0,0,0.7));"></div>
</div>

<div class="container" style="margin-top: -75px;">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Profile Header Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start">
                        <img src="{{ $agent->avatar_url }}" alt="{{ $agent->full_name }}" class="rounded-circle bg-white p-2 shadow" width="150" height="150">
                        <div class="ms-4 flex-grow-1">
                            <h1 class="h3 mb-1">{{ $agent->full_name }}</h1>
                            @if($agent->designation)
                                <p class="text-primary mb-2">{{ $agent->designation }}</p>
                            @endif
                            
                            <!-- Experience & Stats -->
                            <div class="d-flex gap-4 mb-3">
                                @if($agent->experience_years > 0)
                                    <span class="text-muted">
                                        <i class="fas fa-briefcase me-1"></i>
                                        {{ $agent->experience_years }} years experience
                                    </span>
                                @endif
                                @if($agent->total_sales > 0)
                                    <span class="text-muted">
                                        <i class="fas fa-home me-1"></i>
                                        {{ $agent->total_sales }} properties sold
                                    </span>
                                @endif
                            </div>

                            <!-- Service Areas -->
                            @if($agent->service_areas)
                                <div class="mb-2">
                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                    <span class="text-muted">Serves:</span>
                                    {{ implode(', ', $agent->service_areas) }}
                                </div>
                            @endif

                            <!-- Social Links -->
                            @if($agent->social_links)
                                <div class="d-flex gap-2 mt-3">
                                    @foreach($agent->social_links as $platform => $url)
                                        <a href="{{ $url }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                                            <i class="fab fa-{{ $platform }}"></i>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- About Section -->
            @if($agent->bio)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h2 class="h4 mb-3">About {{ $agent->first_name }}</h2>
                        <div class="text-muted">
                            {!! nl2br(e($agent->bio)) !!}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Specializations -->
            @if($agent->specializations)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h2 class="h4 mb-3">Specializations</h2>
                        <div class="row g-3">
                            @foreach($agent->specializations as $specialization)
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-3 bg-light rounded">
                                        <i class="fas fa-check-circle text-primary me-3"></i>
                                        <span>{{ $specialization }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Languages -->
            @if($agent->languages_spoken)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h2 class="h4 mb-3">Languages</h2>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($agent->languages_spoken as $language)
                                <span class="badge bg-light text-dark p-2">{{ $language }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Properties Section -->
            @if($agent->approvedProperties && $agent->approvedProperties->count() > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h2 class="h4 mb-4">Active Listings</h2>
                        <div class="row g-3">
                            @foreach($agent->approvedProperties as $property)
                                @include('realestate::frontend.partials.property-card', ['property' => $property])
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Contact Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="h5 mb-3">Contact Information</h3>
                    
                    <ul class="list-unstyled mb-0">
                        @if($agent->phone)
                            <li class="mb-3">
                                <i class="fas fa-phone text-primary me-2"></i>
                                <a href="tel:{{ $agent->phone }}">{{ $agent->phone }}</a>
                            </li>
                        @endif
                        
                        @if($agent->whatsapp)
                            <li class="mb-3">
                                <i class="fab fa-whatsapp text-success me-2"></i>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $agent->whatsapp) }}" target="_blank">{{ $agent->whatsapp }}</a>
                            </li>
                        @endif
                        
                        @if($agent->email)
                            <li class="mb-3">
                                <i class="fas fa-envelope text-primary me-2"></i>
                                <a href="mailto:{{ $agent->email }}">{{ $agent->email }}</a>
                            </li>
                        @endif
                        
                        @if($agent->website)
                            <li class="mb-3">
                                <i class="fas fa-globe text-primary me-2"></i>
                                <a href="{{ $agent->website }}" target="_blank">{{ parse_url($agent->website, PHP_URL_HOST) }}</a>
                            </li>
                        @endif
                        
                        @if($agent->office_address)
                            <li class="mb-0">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                {{ $agent->office_address }}
                            </li>
                        @endif
                        
                        @if($agent->rera_id)
                            <li class="mt-3 mb-0">
                                <i class="fas fa-certificate text-primary me-2"></i>
                                RERA: {{ $agent->rera_id }}
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Stats Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="h5 mb-3">Agent Stats</h3>
                    
                    <div class="row text-center g-3">
                        <div class="col-6">
                            <div class="bg-light rounded p-3">
                                <div class="h3 mb-0 text-primary">{{ $agent->experience_years }}</div>
                                <small class="text-muted">Years Exp.</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light rounded p-3">
                                <div class="h3 mb-0 text-success">{{ $agent->total_sales }}</div>
                                <small class="text-muted">Properties Sold</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light rounded p-3">
                                <div class="h3 mb-0 text-warning">{{ $agent->active_listings }}</div>
                                <small class="text-muted">Active Listings</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light rounded p-3">
                                <div class="h3 mb-0 text-info">{{ $agent->approvedProperties ? $agent->approvedProperties->count() : 0 }}</div>
                                <small class="text-muted">Total Listings</small>
                            </div>
                        </div>
                    </div>

                    @if($agent->license_number)
                        <hr class="my-3">
                        <p class="mb-0 text-center">
                            <i class="fas fa-id-card text-primary me-2"></i>
                            License: {{ $agent->license_number }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Working Hours -->
            @if($agent->working_hours)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h3 class="h5 mb-3">Working Hours</h3>
                        <ul class="list-unstyled mb-0">
                            @foreach($agent->working_hours as $day => $hours)
                                <li class="d-flex justify-content-between mb-2">
                                    <span>{{ ucfirst($day) }}</span>
                                    <span class="text-muted">{{ $hours }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Contact Form -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h3 class="h5 mb-3">Contact Agent</h3>
                    <form action="#" method="POST">
                        @csrf
                        <div class="mb-3">
                            <input type="text" class="form-control" placeholder="Your Name" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" class="form-control" placeholder="Your Email" required>
                        </div>
                        <div class="mb-3">
                            <input type="tel" class="form-control" placeholder="Your Phone">
                        </div>
                        <div class="mb-3">
                            <select class="form-select">
                                <option value="">I'm interested in...</option>
                                <option value="buy">Buying a property</option>
                                <option value="sell">Selling a property</option>
                                <option value="rent">Renting a property</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" rows="4" placeholder="Your Message" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
