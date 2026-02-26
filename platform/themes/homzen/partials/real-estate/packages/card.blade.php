<div class="col-lg-4 col-md-6 mb-4">
    <div @class(['box-pricing shadow-sm border-0 h-100 d-flex flex-column', 'active' => $package->is_default])>
        @if($package->is_default)
            <div class="popular-badge">{{ __('Most Popular') }}</div>
        @endif
        
        <div class="price d-flex align-items-end mb-3">
            <h4 class="fw-bold price-text">{{ $package->price == 0 ? __('Free') : format_price($package->price) }}</h4>
            <span class="body-2 text-variant-1 ms-1 duration-text">
                /
                @if ($package->is_recurring)
                    {{ __('month') }}
                @else
                    {{ __(':days days', ['days' => $package->duration_days]) }}
                @endif
            </span>
        </div>

        <div class="box-title-price mb-4">
            <h5 class="title fw-bold card-title">{!! BaseHelper::clean($package->name) !!}</h5>
            @if ($package->description)
                <p class="desc small text-variant-1 card-desc">{{ $package->description }}</p>
            @endif
        </div>

        <div class="package-limits mb-3">
            @if($package->number_of_listings)
                <div class="small fw-semibold mb-2 limit-item">
                    <i class="ti ti-list me-2"></i>
                    {{ __('Listings: :number', ['number' => $package->number_of_listings >= 999999 ? __('Unlimited') : number_format($package->number_of_listings)]) }}
                </div>
            @endif
            @if($package->number_of_projects)
                <div class="small fw-semibold limit-item">
                    <i class="ti ti-layout-grid me-2"></i>
                    {{ __('Projects: :number', ['number' => $package->number_of_projects >= 999999 ? __('Unlimited') : number_format($package->number_of_projects)]) }}
                </div>
            @endif
        </div>

        @if ($package->formatted_features)
            <div class="small fw-bold text-dark mb-2 feature-header">{{ __('Key Features:') }}</div>
            <ul class="list-price flex-grow-1 mb-4">
                @foreach ($package->formatted_features as $feature)
                    <li class="item d-flex align-items-start mb-2">
                        <span class="check-icon icon-tick text-success me-2 mt-1 feature-icon" style="font-size: 14px;"></span>
                        <span class="small text-variant-1 feature-text">{!! BaseHelper::clean($feature) !!}</span>
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="mt-auto">
            <a href="{{ route('public.account.packages') }}" class="tf-btn w-100 rounded-pill py-2 subscribe-btn">
                {{ __('Choose This Plan') }}
            </a>
        </div>
    </div>
</div>

<style>
    .box-pricing {
        padding: 30px;
        border-radius: 16px;
        background-color: #f8f9fa;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .box-pricing:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1) !important;
    }

    .box-pricing.active {
        background-color: var(--primary-color, #1B316F);
        border: none !important;
    }

    .popular-badge {
        position: absolute;
        top: 15px;
        right: -35px;
        background: #10b981;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 5px 40px;
        transform: rotate(45deg);
    }

    /* Default state (non-active) */
    .box-pricing:not(.active) .price-text {
        color: var(--primary-color, #1B316F);
    }

    .box-pricing:not(.active) .limit-item {
        color: var(--primary-color, #1B316F);
    }

    /* Active state - all white text */
    .box-pricing.active .price-text,
    .box-pricing.active .duration-text,
    .box-pricing.active .card-title,
    .box-pricing.active .card-desc,
    .box-pricing.active .limit-item,
    .box-pricing.active .feature-header,
    .box-pricing.active .feature-text,
    .box-pricing.active .feature-icon,
    .box-pricing.active i {
        color: #fff !important;
    }

    .box-pricing.active .check-icon {
        color: #10b981 !important;
    }

    /* Button styles */
    .box-pricing:not(.active) .subscribe-btn {
        background-color: var(--primary-color, #1B316F);
        color: #fff;
    }

    .box-pricing:not(.active) .subscribe-btn:hover {
        background-color: #fff;
        color: var(--primary-color, #1B316F);
        border: 1px solid var(--primary-color, #1B316F);
    }

    .box-pricing.active .subscribe-btn {
        background-color: #fff;
        color: var(--primary-color, #1B316F);
    }

    .box-pricing.active .subscribe-btn:hover {
        background-color: transparent;
        color: #fff;
        border: 1px solid #fff;
    }

    .list-price {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .limit-item i {
        color: var(--primary-color, #1B316F);
    }

    .box-pricing.active .limit-item i {
        color: #fff !important;
    }
</style>
