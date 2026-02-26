<div class="col-lg-4 col-md-6 mb-4">
    <div @class(['package-card', 'active' => $package->is_default])>
        @if($package->is_default)
            <div class="popular-badge">{{ __('Most Popular') }}</div>
        @endif
        
        <div class="package-header">
            <h5 class="package-name">{!! BaseHelper::clean($package->name) !!}</h5>
            @if ($package->description)
                <p class="package-desc">{{ $package->description }}</p>
            @endif
        </div>

        <div class="package-price">
            <span class="price-amount">{{ $package->price == 0 ? __('Free') : format_price($package->price) }}</span>
            <span class="price-duration">
                @if ($package->is_recurring)
                    / {{ __('month') }}
                @else
                    / {{ __(':days days', ['days' => $package->duration_days]) }}
                @endif
            </span>
        </div>

        <div class="package-limits">
            @if($package->number_of_listings)
                <div class="limit-item">
                    <i class="ti ti-list"></i>
                    <span>{{ __('Listings: :number', ['number' => $package->number_of_listings >= 999999 ? __('Unlimited') : number_format($package->number_of_listings)]) }}</span>
                </div>
            @endif
            @if($package->number_of_projects)
                <div class="limit-item">
                    <i class="ti ti-layout-grid"></i>
                    <span>{{ __('Projects: :number', ['number' => $package->number_of_projects >= 999999 ? __('Unlimited') : number_format($package->number_of_projects)]) }}</span>
                </div>
            @endif
        </div>

        @if ($package->formatted_features)
            <div class="package-features">
                <div class="features-title">{{ __('Key Features') }}</div>
                <ul class="features-list">
                    @foreach ($package->formatted_features as $feature)
                        <li>
                            <i class="ti ti-check"></i>
                            <span>{!! BaseHelper::clean($feature) !!}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="package-action">
            <a href="{{ route('public.account.packages') }}" class="btn-choose">
                {{ __('Choose This Plan') }}
            </a>
        </div>
    </div>
</div>

<style>
    .package-card {
        background: #fff;
        border-radius: 20px;
        padding: 32px;
        height: 100%;
        display: flex;
        flex-direction: column;
        border: 2px solid #e5e7eb;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .package-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border-color: var(--primary-color, #3b82f6);
    }

    .package-card.active {
        border-color: var(--primary-color, #3b82f6);
        background: linear-gradient(135deg, #fff 0%, #f0f7ff 100%);
    }

    .popular-badge {
        position: absolute;
        top: 16px;
        right: -32px;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 6px 40px;
        transform: rotate(45deg);
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
    }

    .package-header {
        margin-bottom: 20px;
    }

    .package-name {
        font-size: 20px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 8px;
        line-height: 1.3;
    }

    .package-desc {
        font-size: 13px;
        color: #6b7280;
        line-height: 1.5;
        margin: 0;
    }

    .package-price {
        display: flex;
        align-items: baseline;
        gap: 4px;
        margin-bottom: 24px;
        padding-bottom: 24px;
        border-bottom: 1px solid #e5e7eb;
    }

    .price-amount {
        font-size: 32px;
        font-weight: 800;
        color: var(--primary-color, #3b82f6);
        line-height: 1;
    }

    .price-duration {
        font-size: 14px;
        color: #6b7280;
        font-weight: 500;
    }

    .package-limits {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 24px;
    }

    .limit-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        color: #374151;
        font-weight: 500;
        padding: 10px 14px;
        background: #f8fafc;
        border-radius: 10px;
    }

    .limit-item i {
        color: var(--primary-color, #3b82f6);
        font-size: 18px;
    }

    .package-features {
        flex: 1;
        margin-bottom: 24px;
    }

    .features-title {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280;
        margin-bottom: 12px;
    }

    .features-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .features-list li {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 14px;
        color: #4b5563;
        line-height: 1.5;
    }

    .features-list li i {
        color: #10b981;
        font-size: 18px;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .package-action {
        margin-top: auto;
    }

    .btn-choose {
        display: block;
        width: 100%;
        padding: 14px 24px;
        background: var(--primary-color, #3b82f6);
        color: #fff;
        font-size: 15px;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        border-radius: 12px;
        transition: all 0.3s ease;
        border: 2px solid var(--primary-color, #3b82f6);
    }

    .btn-choose:hover {
        background: transparent;
        color: var(--primary-color, #3b82f6);
    }

    .package-card.active .btn-choose {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border-color: transparent;
    }

    .package-card.active .btn-choose:hover {
        background: transparent;
        border-color: var(--primary-color, #3b82f6);
    }

    @media (max-width: 768px) {
        .package-card {
            padding: 24px;
        }

        .price-amount {
            font-size: 28px;
        }

        .popular-badge {
            padding: 5px 35px;
            font-size: 10px;
        }
    }
</style>
