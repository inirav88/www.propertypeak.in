@php
    Theme::layout('default');
@endphp

<section class="flat-section flat-pricing-page">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="font-heading fw-bold mb-3 text-primary">{{ __('Packages & Pricing') }}</h1>
            <p class="body-2 text-variant-1 mx-auto" style="max-width: 700px;">
                {{ __('Choose the perfect plan to grow your real estate business. Whether you are a large-scale developer, an independent agent, or a property owner, we have the right tools for you.') }}
            </p>
        </div>

        <div class="pricing-tabs mt-5">
            <ul class="nav nav-pills mb-5 justify-content-center" id="pricingTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill px-4 mx-2" id="builders-tab" data-bs-toggle="pill"
                        data-bs-target="#builders" type="button" role="tab">{{ __('For Builders') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-4 mx-2" id="agents-tab" data-bs-toggle="pill"
                        data-bs-target="#agents" type="button" role="tab">{{ __('For Agents') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-4 mx-2" id="owners-tab" data-bs-toggle="pill"
                        data-bs-target="#owners" type="button" role="tab">{{ __('For Owners') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-4 mx-2" id="addons-tab" data-bs-toggle="pill"
                        data-bs-target="#addons" type="button" role="tab">{{ __('Add-ons') }}</button>
                </li>
            </ul>

            <div class="tab-content" id="pricingTabContent">
                <!-- Builder Packages -->
                <div class="tab-pane fade show active" id="builders" role="tabpanel">
                    <div class="row mb-5 align-items-center">
                        <div class="col-md-6">
                            <h3 class="fw-bold mb-3">{{ __('Empower Your Development Projects') }}</h3>
                            <p class="text-variant-1 mb-4">
                                {{ __('Our builder packages are designed for developers who need to showcase entire projects, manage multiple units, and track high-volume leads efficiently.') }}
                            </p>
                            <div class="d-flex mb-3">
                                <div class="icon-circle bg-primary-light text-primary me-3">
                                    <i class="ti ti-check"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">{{ __('How it Helps') }}</h6>
                                    <p class="text-variant-1 small">
                                        {{ __('Centralize your project inventory and reach qualified buyers looking for modern developments.') }}
                                    </p>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="icon-circle bg-secondary-light text-secondary me-3">
                                    <i class="ti ti-settings"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">{{ __('How it Works') }}</h6>
                                    <p class="text-variant-1 small">
                                        {{ __('Register as a Builder, choose a plan, and start listing your projects with interactive galleries and floor plans.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 text-center d-none d-md-block">
                            <img src="{{ RvMedia::getImageUrl(theme_option('builder_package_image', '')) }}"
                                onerror="this.src='/vendor/core/plugins/real-estate/images/builder.png'" alt="Builders"
                                class="img-fluid rounded-3 shadow-sm" style="max-height: 300px;">
                        </div>
                    </div>
                    <div class="row g-4">
                        @foreach($builderPackages as $package)
                            {!! Theme::partial('real-estate.packages.card', compact('package')) !!}
                        @endforeach
                    </div>
                </div>

                <!-- Agent Packages -->
                <div class="tab-pane fade" id="agents" role="tabpanel">
                    <div class="row mb-5 align-items-center">
                        <div class="col-md-6 order-md-2">
                            <h3 class="fw-bold mb-3">{{ __('Close More Deals, Faster') }}</h3>
                            <p class="text-variant-1 mb-4">
                                {{ __('Built for real estate professionals who manage a diverse portfolio. Our agent tools help you stand out and manage leads like a pro.') }}
                            </p>
                            <div class="d-flex mb-3">
                                <div class="icon-circle bg-primary-light text-primary me-3">
                                    <i class="ti ti-users"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">{{ __('How it Helps') }}</h6>
                                    <p class="text-variant-1 small">
                                        {{ __('Professional profile, verified badges, and priority search placement to build trust with clients.') }}
                                    </p>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="icon-circle bg-secondary-light text-secondary me-3">
                                    <i class="ti ti-device-mobile"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">{{ __('How it Works') }}</h6>
                                    <p class="text-variant-1 small">
                                        {{ __('Subscribe to a monthly plan and get instant access to lead tracking, WhatsApp alerts, and premium badges.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 text-center d-none d-md-block order-md-1">
                            <img src="{{ RvMedia::getImageUrl(theme_option('agent_package_image', '')) }}"
                                onerror="this.src='/vendor/core/plugins/real-estate/images/agent.png'" alt="Agents"
                                class="img-fluid rounded-3 shadow-sm" style="max-height: 300px;">
                        </div>
                    </div>
                    <div class="row g-4 justify-content-center">
                        @foreach($agentPackages as $package)
                            {!! Theme::partial('real-estate.packages.card', compact('package')) !!}
                        @endforeach
                    </div>
                </div>

                <!-- Owner Packages -->
                <div class="tab-pane fade" id="owners" role="tabpanel">
                    <div class="row mb-5 align-items-center">
                        <div class="col-md-6">
                            <h3 class="fw-bold mb-3">{{ __('Sell Your Home with Confidence') }}</h3>
                            <p class="text-variant-1 mb-4">
                                {{ __('Simple, one-time listing packages for individuals who want to sell or rent their property without the hassle.') }}
                            </p>
                            <div class="d-flex mb-3">
                                <div class="icon-circle bg-primary-light text-primary me-3">
                                    <i class="ti ti-home"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">{{ __('How it Helps') }}</h6>
                                    <p class="text-variant-1 small">
                                        {{ __('Reach thousands of potential buyers instantly with zero commission on your private sale.') }}
                                    </p>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="icon-circle bg-secondary-light text-secondary me-3">
                                    <i class="ti ti-credit-card"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">{{ __('How it Works') }}</h6>
                                    <p class="text-variant-1 small">
                                        {{ __('Post your property, choose a duration (30-180 days), and start receiving inquiries directly from buyers.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 text-center d-none d-md-block">
                            <img src="{{ RvMedia::getImageUrl(theme_option('owner_package_image', '')) }}"
                                onerror="this.src='/vendor/core/plugins/real-estate/images/owner.png'" alt="Owners"
                                class="img-fluid rounded-3 shadow-sm" style="max-height: 300px;">
                        </div>
                    </div>
                    <div class="row g-4 justify-content-center">
                        @foreach($ownerPackages as $package)
                            {!! Theme::partial('real-estate.packages.card', compact('package')) !!}
                        @endforeach
                    </div>
                </div>

                <!-- Add-on Packages -->
                <div class="tab-pane fade" id="addons" role="tabpanel">
                    <div class="row mb-4">
                        <div class="col-12 text-center">
                            <h3 class="fw-bold mb-3">{{ __('Boost Your Visibility') }}</h3>
                            <p class="text-variant-1 mx-auto" style="max-width: 600px;">
                                {{ __('Optional services to give your listings an extra edge. From professional photography to homepage banners, we help you get noticed.') }}
                            </p>
                        </div>
                    </div>
                    <div class="row g-4">
                        @foreach($addonPackages as $package)
                            <div class="col-lg-4 col-md-6">
                                <div class="card h-100 border-0 shadow-sm addon-card hover-up">
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="fw-bold mb-0 text-primary">{{ $package->name }}</h6>
                                            <span
                                                class="badge bg-secondary-light text-secondary">{{ format_price($package->price) }}</span>
                                        </div>
                                        <p class="small text-variant-1 mb-3">{{ $package->description }}</p>
                                        @if($package->formatted_features)
                                            <ul class="list-unstyled mb-4">
                                                @foreach($package->formatted_features as $feature)
                                                    <li class="small mb-2 d-flex align-items-center">
                                                        <i class="ti ti-check text-success me-2"></i>
                                                        {{ $feature }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        <a href="{{ route('public.account.packages') }}"
                                            class="btn btn-outline-primary btn-sm w-100 rounded-pill">{{ __('Add to Plan') }}</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .flat-pricing-page {
        padding: 80px 0;
        background: #f8f9fa;
    }

    .pricing-tabs .nav-pills .nav-link {
        background: #fff;
        color: #666;
        border: 1px solid #eee;
        transition: all 0.3s ease;
    }

    .pricing-tabs .nav-pills .nav-link.active {
        background: var(--primary-color, #1B316F);
        color: #fff;
        border-color: var(--primary-color, #1B316F);
        box-shadow: 0 4px 15px rgba(27, 49, 111, 0.2);
    }

    .icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .bg-primary-light {
        background: rgba(27, 49, 111, 0.1);
    }

    .bg-secondary-light {
        background: rgba(235, 12, 12, 0.1);
    }

    .text-secondary {
        color: #eb0c0c !important;
    }

    .addon-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 15px;
    }

    .addon-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
    }

    .hover-up:hover {
        transform: translateY(-5px);
    }
</style>