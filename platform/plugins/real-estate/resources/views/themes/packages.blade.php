@php
    Theme::layout('default');
@endphp

<section class="packages-section">
    <div class="container">
        <!-- Header -->
        <div class="packages-header text-center">
            <span class="section-badge">
                <i class="ti ti-crown"></i>
                {{ __('Pricing Plans') }}
            </span>
            <h1 class="section-title">{{ __('Choose Your Perfect Plan') }}</h1>
            <p class="section-description">
                {{ __('Select the ideal package to boost your real estate business. From individual property owners to large-scale developers, we have plans tailored for everyone.') }}
            </p>
        </div>

        <!-- Tabs Navigation -->
        <div class="packages-tabs">
            <ul class="nav nav-pills" id="pricingTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="builders-tab" data-bs-toggle="pill" data-bs-target="#builders" type="button" role="tab">
                        <i class="ti ti-building"></i>
                        {{ __('For Builders') }}
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="agents-tab" data-bs-toggle="pill" data-bs-target="#agents" type="button" role="tab">
                        <i class="ti ti-user-circle"></i>
                        {{ __('For Agents') }}
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="owners-tab" data-bs-toggle="pill" data-bs-target="#owners" type="button" role="tab">
                        <i class="ti ti-home"></i>
                        {{ __('For Owners') }}
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="addons-tab" data-bs-toggle="pill" data-bs-target="#addons" type="button" role="tab">
                        <i class="ti ti-package"></i>
                        {{ __('Add-ons') }}
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="pricingTabContent">
                <!-- Builder Packages -->
                <div class="tab-pane fade show active" id="builders" role="tabpanel">
                    <div class="category-intro">
                        <div class="row align-items-center g-4">
                            <div class="col-lg-6">
                                <div class="intro-content">
                                    <div class="intro-icon">
                                        <i class="ti ti-building"></i>
                                    </div>
                                    <h3>{{ __('Empower Your Development Projects') }}</h3>
                                    <p>{{ __('Our builder packages are designed for developers who need to showcase entire projects, manage multiple units, and track high-volume leads efficiently.') }}</p>
                                    <ul class="intro-features">
                                        <li>
                                            <i class="ti ti-check"></i>
                                            <div>
                                                <strong>{{ __('How it Helps') }}</strong>
                                                <span>{{ __('Centralize your project inventory and reach qualified buyers looking for modern developments.') }}</span>
                                            </div>
                                        </li>
                                        <li>
                                            <i class="ti ti-settings"></i>
                                            <div>
                                                <strong>{{ __('How it Works') }}</strong>
                                                <span>{{ __('Register as a Builder, choose a plan, and start listing your projects with interactive galleries and floor plans.') }}</span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-6 d-none d-lg-block">
                                <div class="intro-image">
                                    <img src="{{ RvMedia::getImageUrl(theme_option('builder_package_image', '')) }}" onerror="this.src='/vendor/core/plugins/real-estate/images/builder.png'" alt="Builders">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="packages-grid row g-4">
                        @foreach($builderPackages as $package)
                            {!! Theme::partial('real-estate.packages.card', compact('package')) !!}
                        @endforeach
                    </div>
                </div>

                <!-- Agent Packages -->
                <div class="tab-pane fade" id="agents" role="tabpanel">
                    <div class="category-intro">
                        <div class="row align-items-center g-4">
                            <div class="col-lg-6 order-lg-2">
                                <div class="intro-content">
                                    <div class="intro-icon">
                                        <i class="ti ti-user-circle"></i>
                                    </div>
                                    <h3>{{ __('Close More Deals, Faster') }}</h3>
                                    <p>{{ __('Built for real estate professionals who manage a diverse portfolio. Our agent tools help you stand out and manage leads like a pro.') }}</p>
                                    <ul class="intro-features">
                                        <li>
                                            <i class="ti ti-users"></i>
                                            <div>
                                                <strong>{{ __('How it Helps') }}</strong>
                                                <span>{{ __('Professional profile, verified badges, and priority search placement to build trust with clients.') }}</span>
                                            </div>
                                        </li>
                                        <li>
                                            <i class="ti ti-device-mobile"></i>
                                            <div>
                                                <strong>{{ __('How it Works') }}</strong>
                                                <span>{{ __('Subscribe to a monthly plan and get instant access to lead tracking, WhatsApp alerts, and premium badges.') }}</span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-6 order-lg-1 d-none d-lg-block">
                                <div class="intro-image">
                                    <img src="{{ RvMedia::getImageUrl(theme_option('agent_package_image', '')) }}" onerror="this.src='/vendor/core/plugins/real-estate/images/agent.png'" alt="Agents">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="packages-grid row g-4 justify-content-center">
                        @foreach($agentPackages as $package)
                            {!! Theme::partial('real-estate.packages.card', compact('package')) !!}
                        @endforeach
                    </div>
                </div>

                <!-- Owner Packages -->
                <div class="tab-pane fade" id="owners" role="tabpanel">
                    <div class="category-intro">
                        <div class="row align-items-center g-4">
                            <div class="col-lg-6">
                                <div class="intro-content">
                                    <div class="intro-icon">
                                        <i class="ti ti-home"></i>
                                    </div>
                                    <h3>{{ __('Sell Your Home with Confidence') }}</h3>
                                    <p>{{ __('Simple, one-time listing packages for individuals who want to sell or rent their property without the hassle.') }}</p>
                                    <ul class="intro-features">
                                        <li>
                                            <i class="ti ti-home"></i>
                                            <div>
                                                <strong>{{ __('How it Helps') }}</strong>
                                                <span>{{ __('Reach thousands of potential buyers instantly with zero commission on your private sale.') }}</span>
                                            </div>
                                        </li>
                                        <li>
                                            <i class="ti ti-credit-card"></i>
                                            <div>
                                                <strong>{{ __('How it Works') }}</strong>
                                                <span>{{ __('Post your property, choose a duration (30-180 days), and start receiving inquiries directly from buyers.') }}</span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-6 d-none d-lg-block">
                                <div class="intro-image">
                                    <img src="{{ RvMedia::getImageUrl(theme_option('owner_package_image', '')) }}" onerror="this.src='/vendor/core/plugins/real-estate/images/owner.png'" alt="Owners">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="packages-grid row g-4 justify-content-center">
                        @foreach($ownerPackages as $package)
                            {!! Theme::partial('real-estate.packages.card', compact('package')) !!}
                        @endforeach
                    </div>
                </div>

                <!-- Add-on Packages -->
                <div class="tab-pane fade" id="addons" role="tabpanel">
                    <div class="category-intro text-center">
                        <div class="intro-icon mx-auto">
                            <i class="ti ti-package"></i>
                        </div>
                        <h3>{{ __('Boost Your Visibility') }}</h3>
                        <p class="mx-auto" style="max-width: 600px;">{{ __('Optional services to give your listings an extra edge. From professional photography to homepage banners, we help you get noticed.') }}</p>
                    </div>
                    <div class="row g-4">
                        @foreach($addonPackages as $package)
                            <div class="col-lg-4 col-md-6">
                                <div class="addon-card">
                                    <div class="addon-header">
                                        <h6>{{ $package->name }}</h6>
                                        <span class="addon-price">{{ format_price($package->price) }}</span>
                                    </div>
                                    <p class="addon-desc">{{ $package->description }}</p>
                                    @if($package->formatted_features)
                                        <ul class="addon-features">
                                            @foreach($package->formatted_features as $feature)
                                                <li>
                                                    <i class="ti ti-check"></i>
                                                    {{ $feature }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    <a href="{{ route('public.account.packages') }}" class="addon-btn">{{ __('Add to Plan') }}</a>
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
    .packages-section {
        padding: 80px 0;
        background: linear-gradient(180deg, #f8fafc 0%, #fff 100%);
    }

    .packages-header {
        margin-bottom: 60px;
    }

    .section-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-radius: 50px;
        margin-bottom: 20px;
    }

    .section-badge i {
        font-size: 16px;
    }

    .section-title {
        font-size: 42px;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 16px;
        line-height: 1.2;
    }

    .section-description {
        font-size: 18px;
        color: #6b7280;
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* Tabs */
    .packages-tabs {
        margin-top: 50px;
    }

    .nav-pills {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin-bottom: 50px;
        flex-wrap: wrap;
        border: none;
    }

    .nav-pills .nav-link {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 14px 28px;
        background: #fff;
        color: #4b5563;
        font-size: 15px;
        font-weight: 600;
        border-radius: 12px;
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .nav-pills .nav-link i {
        font-size: 18px;
    }

    .nav-pills .nav-link:hover {
        border-color: #3b82f6;
        color: #3b82f6;
        transform: translateY(-2px);
    }

    .nav-pills .nav-link.active {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: #fff;
        border-color: transparent;
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
    }

    /* Category Intro */
    .category-intro {
        background: #fff;
        border-radius: 24px;
        padding: 48px;
        margin-bottom: 48px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    .intro-content {
        max-width: 540px;
    }

    .intro-icon {
        width: 64px;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: #fff;
        font-size: 28px;
        border-radius: 16px;
        margin-bottom: 24px;
    }

    .intro-content h3 {
        font-size: 28px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 16px;
    }

    .intro-content > p {
        font-size: 16px;
        color: #6b7280;
        line-height: 1.7;
        margin-bottom: 32px;
    }

    .intro-features {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .intro-features li {
        display: flex;
        gap: 16px;
    }

    .intro-features li > i {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f0f7ff;
        color: #3b82f6;
        font-size: 20px;
        border-radius: 12px;
        flex-shrink: 0;
    }

    .intro-features li div {
        flex: 1;
    }

    .intro-features li strong {
        display: block;
        font-size: 15px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 4px;
    }

    .intro-features li span {
        font-size: 14px;
        color: #6b7280;
        line-height: 1.5;
    }

    .intro-image {
        text-align: center;
    }

    .intro-image img {
        max-height: 320px;
        width: auto;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    /* Packages Grid */
    .packages-grid {
        margin: 0 -12px;
    }

    .packages-grid > div {
        padding: 12px;
    }

    /* Add-on Cards */
    .addon-card {
        background: #fff;
        border-radius: 20px;
        padding: 32px;
        height: 100%;
        display: flex;
        flex-direction: column;
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .addon-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        border-color: #3b82f6;
    }

    .addon-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .addon-header h6 {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
    }

    .addon-price {
        font-size: 18px;
        font-weight: 800;
        color: #3b82f6;
        background: #f0f7ff;
        padding: 6px 14px;
        border-radius: 20px;
    }

    .addon-desc {
        font-size: 14px;
        color: #6b7280;
        line-height: 1.6;
        margin-bottom: 24px;
    }

    .addon-features {
        list-style: none;
        padding: 0;
        margin: 0 0 24px;
        flex: 1;
    }

    .addon-features li {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 14px;
        color: #4b5563;
        margin-bottom: 10px;
    }

    .addon-features li i {
        color: #10b981;
        font-size: 18px;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .addon-btn {
        display: block;
        padding: 14px 24px;
        background: transparent;
        color: #3b82f6;
        font-size: 15px;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        border-radius: 12px;
        border: 2px solid #3b82f6;
        transition: all 0.3s ease;
    }

    .addon-btn:hover {
        background: #3b82f6;
        color: #fff;
    }

    /* Responsive */
    @media (max-width: 991px) {
        .packages-section {
            padding: 60px 0;
        }

        .section-title {
            font-size: 32px;
        }

        .category-intro {
            padding: 32px;
        }

        .intro-content h3 {
            font-size: 24px;
        }
    }

    @media (max-width: 768px) {
        .section-title {
            font-size: 28px;
        }

        .section-description {
            font-size: 16px;
        }

        .nav-pills .nav-link {
            padding: 12px 20px;
            font-size: 14px;
        }

        .category-intro {
            padding: 24px;
        }

        .intro-icon {
            width: 56px;
            height: 56px;
            font-size: 24px;
        }
    }
</style>
