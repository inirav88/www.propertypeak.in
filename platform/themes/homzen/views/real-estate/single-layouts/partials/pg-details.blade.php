@php
    use Botble\RealEstate\Enums\PropertyTypeEnum;
@endphp

@if($model->is_pg_property)
    <div class="{{ $class ?? '' }} single-property-element single-property-pg-details">
        <div class="h7 title fw-7">{{ __('PG Details') }}</div>
        <div class="row">
            {{-- PG Category & Occupancy --}}
            @if($model->pg_category || $model->pg_occupancy_type)
                <div class="col-md-12">
                    <div class="info-box">
                        <div class="title-info-box">{{ __('Property Type') }}</div>
                        <div class="content-info-box">
                            @if($model->pg_category)
                                <span class="badge bg-primary me-2">
                                    {{ \Botble\RealEstate\Enums\PgCategoryEnum::getLabel($model->pg_category) }}
                                </span>
                            @endif
                            @if(!empty($model->pg_occupancy_type))
                                @foreach((array) $model->pg_occupancy_type as $occupancy)
                                    <span class="badge bg-info">
                                        {{ \Botble\RealEstate\Enums\PgOccupancyTypeEnum::getLabel($occupancy) }}
                                    </span>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Pricing Information --}}
            <div class="col-md-12">
                <div class="info-box">
                    <div class="title-info-box">{{ __('Pricing') }}</div>
                    <div class="content-info-box">
                        <div class="pricing-details">
                            @if($model->price_per_bed && in_array($model->pricing_model, ['per_bed', 'both']))
                                <div class="price-item">
                                    <strong>{{ __('Per Bed:') }}</strong>
                                    <span
                                        class="text-primary fw-bold">{{ format_price($model->price_per_bed, $model->currency) }}/{{ __('month') }}</span>
                                </div>
                            @endif

                            @if($model->price_per_room && in_array($model->pricing_model, ['per_room', 'both']))
                                <div class="price-item">
                                    <strong>{{ __('Per Room:') }}</strong>
                                    <span
                                        class="text-primary fw-bold">{{ format_price($model->price_per_room, $model->currency) }}/{{ __('month') }}</span>
                                </div>
                            @endif

                            @if($model->security_deposit)
                                <div class="price-item">
                                    <strong>{{ __('Security Deposit:') }}</strong>
                                    <span>{{ format_price($model->security_deposit, $model->currency) }}</span>
                                </div>
                            @endif

                            @if($model->maintenance_charges)
                                <div class="price-item">
                                    <strong>{{ __('Maintenance:') }}</strong>
                                    <span>{{ format_price($model->maintenance_charges, $model->currency) }}/{{ __('month') }}</span>
                                </div>
                            @endif

                            @if($model->notice_period_days)
                                <div class="price-item">
                                    <strong>{{ __('Notice Period:') }}</strong>
                                    <span>{{ $model->notice_period_days }} {{ __('days') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Availability --}}
            @if($model->total_beds || $model->available_beds)
                <div class="col-md-6">
                    <div class="info-box">
                        <div class="title-info-box">{{ __('Availability') }}</div>
                        <div class="content-info-box">
                            @if($model->total_beds)
                                <div><strong>{{ __('Total Beds:') }}</strong> {{ $model->total_beds }}</div>
                            @endif
                            @if($model->available_beds !== null)
                                <div>
                                    <strong>{{ __('Available:') }}</strong>
                                    @if($model->available_beds > 0)
                                        <span class="text-success">{{ $model->available_beds }} {{ __('beds') }}</span>
                                    @else
                                        <span class="text-danger">{{ __('Fully Occupied') }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Food & Meals --}}
            @if($model->food_included)
                <div class="col-md-6">
                    <div class="info-box">
                        <div class="title-info-box">{{ __('Food & Meals') }}</div>
                        <div class="content-info-box">
                            <div class="mb-2">
                                <span class="badge bg-success">{{ __('Food Included') }}</span>
                            </div>
                            @if(!empty($model->food_type))
                                        <div>
                                            <strong>{{ __('Type:') }}</strong>
                                            {{ implode(', ', array_map(function ($type) {
                                    return trans('plugins/real-estate::property.pg.food_types.' . $type);
                                }, (array) $model->food_type)) }}
                                        </div>
                            @endif
                            @if($model->meals_provided && is_array($model->meals_provided))
                                <div>
                                    <strong>{{ __('Meals:') }}</strong>
                                    {{ implode(', ', array_map('ucfirst', $model->meals_provided)) }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Amenities --}}
        @php
            $amenities = [];
            if ($model->ac_available)
                $amenities[] = __('AC Available');
            if ($model->wifi_included)
                $amenities[] = __('WiFi');
            if ($model->laundry_included)
                $amenities[] = __('Laundry Service');
            if ($model->parking_available)
                $amenities[] = __('Parking');
        @endphp

        @if(count($amenities) > 0)
            <div class="row mt-3">
                <div class="col-12">
                    <div class="info-box">
                        <div class="title-info-box">{{ __('Amenities') }}</div>
                        <div class="content-info-box">
                            <ul class="list-amenities">
                                @foreach($amenities as $amenity)
                                    <li><i class="icon icon-check-circle"></i> {{ $amenity }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Rules & Preferences --}}
        <div class="row mt-3">
            <div class="col-12">
                <div class="info-box">
                    <div class="title-info-box">{{ __('Rules & Preferences') }}</div>
                    <div class="content-info-box">
                        <div class="row">
                            @if($model->gender_preference)
                                <div class="col-md-6">
                                    <strong>{{ __('Gender:') }}</strong> {{ ucfirst($model->gender_preference) }}
                                </div>
                            @endif

                            @if($model->preferred_tenants && is_array($model->preferred_tenants))
                                                <div class="col-md-6">
                                                    <strong>{{ __('Preferred For:') }}</strong>
                                                    {{ implode(', ', array_map(function ($tenant) {
                                    return ucwords(str_replace('_', ' ', $tenant));
                                }, $model->preferred_tenants)) }}
                                                </div>
                            @endif

                            @if($model->gate_closing_time)
                                <div class="col-md-6 mt-2">
                                    <strong>{{ __('Gate Closing:') }}</strong> {{ $model->gate_closing_time }}
                                </div>
                            @endif

                            <div class="col-md-6 mt-2">
                                <strong>{{ __('Visitors:') }}</strong>
                                @if($model->visitors_allowed)
                                    <span class="text-success">{{ __('Allowed') }}</span>
                                @else
                                    <span class="text-danger">{{ __('Not Allowed') }}</span>
                                @endif
                            </div>

                            <div class="col-md-6 mt-2">
                                <strong>{{ __('Smoking:') }}</strong>
                                @if($model->smoking_allowed)
                                    <span class="text-success">{{ __('Allowed') }}</span>
                                @else
                                    <span class="text-danger">{{ __('Not Allowed') }}</span>
                                @endif
                            </div>

                            <div class="col-md-6 mt-2">
                                <strong>{{ __('Drinking:') }}</strong>
                                @if($model->drinking_allowed)
                                    <span class="text-success">{{ __('Allowed') }}</span>
                                @else
                                    <span class="text-danger">{{ __('Not Allowed') }}</span>
                                @endif
                            </div>
                        </div>

                        @if($model->house_rules)
                            <div class="mt-3">
                                <strong>{{ __('House Rules:') }}</strong>
                                <p class="mt-2">{!! nl2br(e($model->house_rules)) !!}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Additional Information --}}
        @if($model->nearby_landmarks || $model->virtual_tour_url)
            <div class="row mt-3">
                <div class="col-12">
                    <div class="info-box">
                        <div class="title-info-box">{{ __('Additional Information') }}</div>
                        <div class="content-info-box">
                            @if($model->nearby_landmarks)
                                <div class="mb-3">
                                    <strong>{{ __('Nearby Landmarks:') }}</strong>
                                    <p class="mt-2">{!! nl2br(e($model->nearby_landmarks)) !!}</p>
                                </div>
                            @endif

                            @if($model->virtual_tour_url)
                                <div>
                                    <a href="{{ $model->virtual_tour_url }}" target="_blank" class="btn btn-primary">
                                        <i class="icon icon-360"></i> {{ __('View Virtual Tour') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Badges --}}
        @php
            $badges = [];
            if ($model->instant_booking)
                $badges[] = ['text' => __('Instant Booking'), 'class' => 'bg-success'];
            if ($model->verified_pg)
                $badges[] = ['text' => __('Verified PG'), 'class' => 'bg-primary'];
            if ($model->owner_stays)
                $badges[] = ['text' => __('Owner Stays'), 'class' => 'bg-info'];
        @endphp

        @if(count($badges) > 0)
            <div class="row mt-3">
                <div class="col-12">
                    <div class="pg-badges">
                        @foreach($badges as $badge)
                            <span class="badge {{ $badge['class'] }} me-2">{{ $badge['text'] }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    <style>
        .single-property-pg-details .info-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .single-property-pg-details .title-info-box {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #333;
        }

        .single-property-pg-details .pricing-details .price-item {
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .single-property-pg-details .pricing-details .price-item:last-child {
            border-bottom: none;
        }

        .single-property-pg-details .list-amenities {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
        }

        .single-property-pg-details .list-amenities li {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .single-property-pg-details .list-amenities i {
            color: #28a745;
        }

        .single-property-pg-details .pg-badges {
            padding: 15px;
            background: #fff;
            border-radius: 8px;
            border: 2px dashed #dee2e6;
        }

        .single-property-pg-details .badge {
            padding: 8px 15px;
            font-size: 14px;
        }
    </style>
@endif