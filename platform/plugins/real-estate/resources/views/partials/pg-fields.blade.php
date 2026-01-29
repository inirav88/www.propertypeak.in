@php
    use Botble\RealEstate\Enums\PgCategoryEnum;
    use Botble\RealEstate\Enums\PgOccupancyTypeEnum;
    use Botble\RealEstate\Enums\PgPricingModelEnum;
    use Botble\RealEstate\Enums\PropertyTypeEnum;
    
    $isPgProperty = $model && $model->type === PropertyTypeEnum::PG;
    $displayClass = $isPgProperty ? '' : 'hidden';
@endphp

<div class="pg-fields-container {{ $displayClass }}" style="{{ $isPgProperty ? '' : 'display: none;' }}">
    <hr>
    <h4 class="mb-3">{{ trans('plugins/real-estate::property.pg.title') }}</h4>
    
    {{-- PG Basic Information --}}
    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="pg_category" class="control-label">
                    {{ trans('plugins/real-estate::property.pg.category') }}
                    <span class="text-danger">*</span>
                </label>
                <select name="pg_category" id="pg_category" class="form-control select-search-full" data-pg-required>
                    <option value="">{{ __('-- Select --') }}</option>
                    @foreach(PgCategoryEnum::labels() as $key => $label)
                        <option value="{{ $key }}" @selected(old('pg_category', $model->pg_category ?? '') === $key)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="pg_occupancy_type" class="control-label">
                    {{ trans('plugins/real-estate::property.pg.occupancy_type') }}
                    <span class="text-danger">*</span>
                </label>
                <select name="pg_occupancy_type[]" id="pg_occupancy_type" class="form-control select-search-full" data-pg-required multiple>
                    @php
                        $selectedOccupancy = old('pg_occupancy_type', $model->pg_occupancy_type ?? []);
                        if (!is_array($selectedOccupancy)) {
                            $selectedOccupancy = $selectedOccupancy ? [$selectedOccupancy] : [];
                        }
                    @endphp
                    @foreach(PgOccupancyTypeEnum::labels() as $key => $label)
                        <option value="{{ $key }}" @selected(in_array($key, $selectedOccupancy))>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="total_beds" class="control-label">
                    {{ trans('plugins/real-estate::property.pg.total_beds') }}
                </label>
                <input type="number" name="total_beds" id="total_beds" class="form-control" 
                       value="{{ old('total_beds', $model->total_beds ?? '') }}" 
                       placeholder="{{ trans('plugins/real-estate::property.pg.total_beds') }}" min="1">
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="available_beds" class="control-label">
                    {{ trans('plugins/real-estate::property.pg.available_beds') }}
                </label>
                <input type="number" name="available_beds" id="available_beds" class="form-control" 
                       value="{{ old('available_beds', $model->available_beds ?? '') }}" 
                       placeholder="{{ trans('plugins/real-estate::property.pg.available_beds') }}" min="0">
            </div>
        </div>
    </div>
    
    {{-- Pricing Information --}}
    <h5 class="mt-4 mb-3">{{ trans('plugins/real-estate::property.pg.pricing_section') }}</h5>
    
    <div class="row">
        <div class="col-md-12">
            <div class="form-group mb-3">
                <label for="pricing_model" class="control-label">
                    {{ trans('plugins/real-estate::property.pg.pricing_model') }}
                    <span class="text-danger">*</span>
                </label>
                <select name="pricing_model" id="pricing_model" class="form-control select-search-full" data-pg-required>
                    <option value="">{{ __('-- Select --') }}</option>
                    @foreach(PgPricingModelEnum::labels() as $key => $label)
                        <option value="{{ $key }}" @selected(old('pricing_model', $model->pricing_model ?? '') === $key)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6 price-per-bed-group">
            <div class="form-group mb-3">
                <label for="price_per_bed" class="control-label">
                    {{ trans('plugins/real-estate::property.pg.price_per_bed') }}
                </label>
                <input type="text" name="price_per_bed" id="price_per_bed" 
                       class="form-control input-mask-number" 
                       value="{{ old('price_per_bed', $model->price_per_bed ?? '') }}" 
                       placeholder="{{ trans('plugins/real-estate::property.pg.price_per_bed') }}">
            </div>
        </div>
        
        <div class="col-md-6 price-per-room-group">
            <div class="form-group mb-3">
                <label for="price_per_room" class="control-label">
                    {{ trans('plugins/real-estate::property.pg.price_per_room') }}
                </label>
                <input type="text" name="price_per_room" id="price_per_room" 
                       class="form-control input-mask-number" 
                       value="{{ old('price_per_room', $model->price_per_room ?? '') }}" 
                       placeholder="{{ trans('plugins/real-estate::property.pg.price_per_room') }}">
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <div class="form-group mb-3">
                <label for="security_deposit" class="control-label">
                    {{ trans('plugins/real-estate::property.pg.security_deposit') }}
                </label>
                <input type="text" name="security_deposit" id="security_deposit" 
                       class="form-control input-mask-number" 
                       value="{{ old('security_deposit', $model->security_deposit ?? '') }}" 
                       placeholder="{{ trans('plugins/real-estate::property.pg.security_deposit') }}">
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="form-group mb-3">
                <label for="maintenance_charges" class="control-label">
                    {{ trans('plugins/real-estate::property.pg.maintenance_charges') }}
                </label>
                <input type="text" name="maintenance_charges" id="maintenance_charges" 
                       class="form-control input-mask-number" 
                       value="{{ old('maintenance_charges', $model->maintenance_charges ?? '') }}" 
                       placeholder="{{ trans('plugins/real-estate::property.pg.maintenance_charges') }}">
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="form-group mb-3">
                <label for="notice_period_days" class="control-label">
                    {{ trans('plugins/real-estate::property.pg.notice_period_days') }}
                </label>
                <input type="number" name="notice_period_days" id="notice_period_days" 
                       class="form-control" 
                       value="{{ old('notice_period_days', $model->notice_period_days ?? 30) }}" 
                       placeholder="30" min="0">
            </div>
        </div>
    </div>
    
    {{-- Food & Meals --}}
    <h5 class="mt-4 mb-3">{{ trans('plugins/real-estate::property.pg.food_section') }}</h5>
    
    <div class="row">
        <div class="col-md-12">
            <div class="form-group mb-3">
                <label class="control-label">
                    <input type="checkbox" name="food_included" id="food_included" value="1" 
                           @checked(old('food_included', $model->food_included ?? false))>
                    {{ trans('plugins/real-estate::property.pg.food_included') }}
                </label>
            </div>
        </div>
    </div>
    
    <div class="food-details-group" style="{{ old('food_included', $model->food_included ?? false) ? '' : 'display: none;' }}">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="food_type" class="control-label">
                        {{ trans('plugins/real-estate::property.pg.food_type') }}
                    </label>
                    <select name="food_type[]" id="food_type" class="form-control select-search-full" multiple>
                        @php
                            $selectedFoodType = old('food_type', $model->food_type ?? []);
                            if (!is_array($selectedFoodType)) {
                                $selectedFoodType = $selectedFoodType ? [$selectedFoodType] : [];
                            }
                        @endphp
                        @foreach(trans('plugins/real-estate::property.pg.food_types') as $key => $label)
                            <option value="{{ $key }}" @selected(in_array($key, $selectedFoodType))>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="control-label">{{ trans('plugins/real-estate::property.pg.meals_provided') }}</label>
                    <div>
                        @php
                            $mealsProvided = old('meals_provided', $model->meals_provided ?? []);
                            if (is_string($mealsProvided)) {
                                $mealsProvided = json_decode($mealsProvided, true) ?? [];
                            }
                        @endphp
                        @foreach(trans('plugins/real-estate::property.pg.meals') as $key => $label)
                            <label class="me-3">
                                <input type="checkbox" name="meals_provided[]" value="{{ $key }}" 
                                       @checked(in_array($key, $mealsProvided))>
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Amenities --}}
    <h5 class="mt-4 mb-3">{{ trans('plugins/real-estate::property.pg.amenities_section') }}</h5>
    
    <div class="row">
        <div class="col-md-3">
            <div class="form-group mb-3">
                <label>
                    <input type="checkbox" name="ac_available" value="1" 
                           @checked(old('ac_available', $model->ac_available ?? false))>
                    {{ trans('plugins/real-estate::property.pg.ac_available') }}
                </label>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group mb-3">
                <label>
                    <input type="checkbox" name="wifi_included" value="1" 
                           @checked(old('wifi_included', $model->wifi_included ?? false))>
                    {{ trans('plugins/real-estate::property.pg.wifi_included') }}
                </label>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group mb-3">
                <label>
                    <input type="checkbox" name="laundry_included" value="1" 
                           @checked(old('laundry_included', $model->laundry_included ?? false))>
                    {{ trans('plugins/real-estate::property.pg.laundry_included') }}
                </label>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group mb-3">
                <label>
                    <input type="checkbox" name="parking_available" value="1" 
                           @checked(old('parking_available', $model->parking_available ?? false))>
                    {{ trans('plugins/real-estate::property.pg.parking_available') }}
                </label>
            </div>
        </div>
    </div>
    
    {{-- Rules & Preferences --}}
    <h5 class="mt-4 mb-3">{{ trans('plugins/real-estate::property.pg.rules_section') }}</h5>
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="gender_preference" class="control-label">
                    {{ trans('plugins/real-estate::property.pg.gender_preference') }}
                </label>
                <select name="gender_preference" id="gender_preference" class="form-control select-search-full">
                    <option value="">{{ __('-- Select --') }}</option>
                    @foreach(trans('plugins/real-estate::property.pg.gender_options') as $key => $label)
                        <option value="{{ $key }}" @selected(old('gender_preference', $model->gender_preference ?? '') === $key)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="control-label">{{ trans('plugins/real-estate::property.pg.preferred_tenants') }}</label>
                <div>
                    @php
                        $preferredTenants = old('preferred_tenants', $model->preferred_tenants ?? []);
                        if (is_string($preferredTenants)) {
                            $preferredTenants = json_decode($preferredTenants, true) ?? [];
                        }
                    @endphp
                    @foreach(trans('plugins/real-estate::property.pg.tenant_types') as $key => $label)
                        <label class="me-3">
                            <input type="checkbox" name="preferred_tenants[]" value="{{ $key }}" 
                                   @checked(in_array($key, $preferredTenants))>
                            {{ $label }}
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3">
            <div class="form-group mb-3">
                <label for="gate_closing_time" class="control-label">
                    {{ trans('plugins/real-estate::property.pg.gate_closing_time') }}
                </label>
                <input type="time" name="gate_closing_time" id="gate_closing_time" 
                       class="form-control" 
                       value="{{ old('gate_closing_time', $model->gate_closing_time ?? '') }}">
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group mb-3">
                <label>
                    <input type="checkbox" name="visitors_allowed" value="1" 
                           @checked(old('visitors_allowed', $model->visitors_allowed ?? true))>
                    {{ trans('plugins/real-estate::property.pg.visitors_allowed') }}
                </label>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group mb-3">
                <label>
                    <input type="checkbox" name="smoking_allowed" value="1" 
                           @checked(old('smoking_allowed', $model->smoking_allowed ?? false))>
                    {{ trans('plugins/real-estate::property.pg.smoking_allowed') }}
                </label>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group mb-3">
                <label>
                    <input type="checkbox" name="drinking_allowed" value="1" 
                           @checked(old('drinking_allowed', $model->drinking_allowed ?? false))>
                    {{ trans('plugins/real-estate::property.pg.drinking_allowed') }}
                </label>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="form-group mb-3">
                <label for="house_rules" class="control-label">
                    {{ trans('plugins/real-estate::property.pg.house_rules') }}
                </label>
                <textarea name="house_rules" id="house_rules" class="form-control" rows="4" 
                          placeholder="{{ trans('plugins/real-estate::property.pg.house_rules') }}">{{ old('house_rules', $model->house_rules ?? '') }}</textarea>
            </div>
        </div>
    </div>
    
    {{-- Additional Information --}}
    <h5 class="mt-4 mb-3">{{ trans('plugins/real-estate::property.pg.additional_section') }}</h5>
    
    <div class="row">
        <div class="col-md-12">
            <div class="form-group mb-3">
                <label for="nearby_landmarks" class="control-label">
                    {{ trans('plugins/real-estate::property.pg.nearby_landmarks') }}
                </label>
                <textarea name="nearby_landmarks" id="nearby_landmarks" class="form-control" rows="3" 
                          placeholder="{{ trans('plugins/real-estate::property.pg.nearby_landmarks') }}">{{ old('nearby_landmarks', $model->nearby_landmarks ?? '') }}</textarea>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="form-group mb-3">
                <label for="virtual_tour_url" class="control-label">
                    {{ trans('plugins/real-estate::property.pg.virtual_tour_url') }}
                </label>
                <input type="url" name="virtual_tour_url" id="virtual_tour_url" 
                       class="form-control" 
                       value="{{ old('virtual_tour_url', $model->virtual_tour_url ?? '') }}" 
                       placeholder="https://...">
                <small class="form-text text-muted">Enter 360° virtual tour URL (Matterport, YouTube 360, etc.)</small>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <div class="form-group mb-3">
                <label>
                    <input type="checkbox" name="instant_booking" value="1" 
                           @checked(old('instant_booking', $model->instant_booking ?? false))>
                    {{ trans('plugins/real-estate::property.pg.instant_booking') }}
                </label>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="form-group mb-3">
                <label>
                    <input type="checkbox" name="verified_pg" value="1" 
                           @checked(old('verified_pg', $model->verified_pg ?? false))>
                    {{ trans('plugins/real-estate::property.pg.verified_pg') }}
                </label>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="form-group mb-3">
                <label>
                    <input type="checkbox" name="owner_stays" value="1" 
                           @checked(old('owner_stays', $model->owner_stays ?? false))>
                    {{ trans('plugins/real-estate::property.pg.owner_stays') }}
                </label>
            </div>
        </div>
    </div>
</div>
