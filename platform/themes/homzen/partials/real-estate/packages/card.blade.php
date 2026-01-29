<div class="col-lg-4 col-md-6">
    <div @class(['box-pricing shadow-sm border-0 h-100 d-flex flex-column', 'active' => $package->is_default])>
        <div class="price d-flex align-items-end mb-3">
            <h4 class="fw-bold price-text" style="color: var(--primary-color, #1B316F);">
                {{ $package->price == 0 ? __('Free') : format_price($package->price) }}</h4>
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
                <div class="small fw-semibold mb-1 limit-item" style="color: var(--primary-color, #1B316F);">
                    <i class="ti ti-list me-1"></i>
                    {{ __('Limits: :number Listings', ['number' => $package->number_of_listings >= 999999 ? __('Unlimited') : number_format($package->number_of_listings)]) }}
                </div>
            @endif
            @if($package->number_of_projects)
                <div class="small fw-semibold limit-item" style="color: var(--primary-color, #1B316F);">
                    <i class="ti ti-layout-grid me-1"></i>
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
        background: #fff;
        border-radius: 20px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .box-pricing.active {
        border: 2px solid var(--primary-color, #1B316F) !important;
        /* Force background for active items if they are intended to be highlighted, but ensure text contrast */
        /* If we want active items to just have a border, keep it simple. But if theme sets background, we must override text color. */
    }

    /* Ensure text colors are correct on hover */
    .box-pricing:hover {
        background-color: var(--primary-color, #1B316F) !important;
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(27, 49, 111, 0.3) !important;
    }

    .box-pricing:hover .price-text,
    .box-pricing:hover .duration-text,
    .box-pricing:hover .card-title,
    .box-pricing:hover .card-desc,
    .box-pricing:hover .limit-item,
    .box-pricing:hover .feature-header,
    .box-pricing:hover .feature-text,
    .box-pricing:hover .feature-icon,
    .box-pricing:hover i {
        color: #fff !important;
        opacity: 1;
        /* Safety */
    }

    /* Button changes on hover */
    .box-pricing:hover .subscribe-btn {
        background-color: #fff !important;
        color: var(--primary-color, #1B316F) !important;
        border-color: #fff !important;
    }

    .list-price {
        list-style: none;
        padding: 0;
    }
</style>