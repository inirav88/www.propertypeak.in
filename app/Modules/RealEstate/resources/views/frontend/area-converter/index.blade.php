@if(empty($isShortcode))
@php
    Theme::layout('full-width');
    Theme::set('pageTitle', __('Land Area Converter'));
    Theme::set('breadcrumbEnabled', 'yes');
    Theme::set('breadcrumbBackgroundColor', '#f7f7f7');
@endphp
@endif

<section class="section-converter py-5 bg-surface">
    <div class="container">
        <!-- Converter Card -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="widget-box">
                    <div class="wg-title">
                        <h5><x-core::icon name="ti ti-calculator" /> {{ __('Land Area Conversion') }}</h5>
                    </div>
                    <div class="wg-content">
                        <!-- State Selection -->
                        <div class="mb-4">
                            <label class="label-form">{{ __('Select State') }} <small class="text-muted">({{ __('for region-specific conversions') }})</small></label>
                            <div class="select-wrapper">
                                <select class="form-control" id="state_select">
                                    <option value="">{{ __('Select State') }}</option>
                                    @foreach($states as $key => $name)
                                        <option value="{{ $key }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Converter Form -->
                        <div class="row align-items-end">
                            <!-- From Section -->
                            <div class="col-md-5">
                                <div class="mb-3">
                                    <label class="label-form">{{ __('From') }}</label>
                                    <div class="select-wrapper">
                                        <select class="form-control" id="from_unit">
                                            @foreach($units as $key => $unit)
                                                <option value="{{ $key }}" {{ $key == 'square_meter' ? 'selected' : '' }}>{{ $unit['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <input type="number" class="form-control" id="from_value" placeholder="{{ __('Enter value') }}" value="1" min="0" step="any">
                                </div>
                            </div>

                            <!-- Swap Button -->
                            <div class="col-md-2 text-center mb-3">
                                <button type="button" class="tf-btn secondary" id="swap_units" style="width: 50px; height: 50px; padding: 0; border-radius: 50%;">
                                    <x-core::icon name="ti ti-arrows-exchange" />
                                </button>
                            </div>

                            <!-- To Section -->
                            <div class="col-md-5">
                                <div class="mb-3">
                                    <label class="label-form">{{ __('To') }}</label>
                                    <div class="select-wrapper">
                                        <select class="form-control" id="to_unit">
                                            @foreach($units as $key => $unit)
                                                <option value="{{ $key }}" {{ $key == 'square_feet' ? 'selected' : '' }}>{{ $unit['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-control bg-light fw-bold text-primary" id="to_value" style="min-height: 48px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                                        10.763915
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Result Display -->
                        <div class="text-center mt-4 p-4 bg-surface rounded-3">
                            <h4 class="mb-2">
                                <span id="result_from_value">1</span> 
                                <span id="result_from_unit">{{ __('Square Meter') }}</span> =
                            </h4>
                            <h3 class="text-primary mb-0" style="font-size: 2rem; font-weight: 700;">
                                <span id="result_to_value">10.763915</span> 
                                <span id="result_to_unit">{{ __('Square Feet') }}</span>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Most Searched Units -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="widget-box">
                    <div class="wg-title">
                        <h5><x-core::icon name="ti ti-star" /> {{ __('Most Searched Conversion Units') }}</h5>
                    </div>
                    <div class="wg-content">
                        <div class="row">
                            @php
                                $unitDescriptions = [
                                    'Square Feet' => 'Square feet also denoted as sq.ft., or ft² is an area of a square with sides measuring 1 foot. This is a standard measurement unit used in all the cities of India.',
                                    'Square Meter' => 'A square metre is a unit of area measurement that is used all over the world. A square meter, often referred to as sq.m. or M², is a measuring unit of area equivalent to a one metre on each side.',
                                    'Hectare' => 'Hectare is one of the few commonly used land measurement units of the metric system across the world. It is denoted as ha.',
                                    'Acre' => 'Originally used in the imperial system of units, Acre is one of the oldest measuring units used across the world to measure land.',
                                    'Bigha' => 'Bigha is one of the traditional units of measurement used in the North Indian States for measurement of land.',
                                    'Guntha' => 'Guntha is a traditional unit of land measurement used primarily in the western and southern states of India.',
                                ];
                            @endphp
                            @foreach($unitDescriptions as $unitName => $description)
                                <div class="col-md-6 mb-4">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <span class="badge bg-primary rounded-circle p-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">{{ $loop->iteration }}</span>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="fw-bold">{{ __($unitName) }}</h6>
                                            <p class="text-muted small mb-0">{{ __($description) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Common Conversions Table -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="widget-box">
                    <div class="wg-title">
                        <h5><x-core::icon name="ti ti-table" /> {{ __('Common Land Area Conversions') }}</h5>
                    </div>
                    <div class="wg-content">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('Unit') }}</th>
                                        <th>{{ __('Square Feet') }}</th>
                                        <th>{{ __('Square Meter') }}</th>
                                        <th>{{ __('Acre') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td>1 {{ __('Square Feet') }}</td><td>1</td><td>0.092903</td><td>0.00002296</td></tr>
                                    <tr><td>1 {{ __('Square Meter') }}</td><td>10.763915</td><td>1</td><td>0.0002471</td></tr>
                                    <tr><td>1 {{ __('Acre') }}</td><td>43,560</td><td>4,046.86</td><td>1</td></tr>
                                    <tr><td>1 {{ __('Hectare') }}</td><td>107,639</td><td>10,000</td><td>2.47105</td></tr>
                                    <tr><td>1 {{ __('Bigha') }} ({{ __('Standard') }})</td><td>14,400</td><td>1,337.8</td><td>0.3306</td></tr>
                                    <tr><td>1 {{ __('Guntha') }}</td><td>1,089</td><td>101.17</td><td>0.025</td></tr>
                                    <tr><td>1 {{ __('Marla') }}</td><td>272.25</td><td>25.29</td><td>0.00625</td></tr>
                                    <tr><td>1 {{ __('Cent') }}</td><td>435.6</td><td>40.47</td><td>0.01</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="widget-box">
                    <div class="wg-title">
                        <h5><x-core::icon name="ti ti-help-circle" /> {{ __('Frequently Asked Questions') }}</h5>
                    </div>
                    <div class="wg-content">
                        <div class="accordion" id="areaConverterFAQ">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">{{ __('How is land area calculated?') }}</button>
                                </h2>
                                <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#areaConverterFAQ">
                                    <div class="accordion-body">{{ __('The area of a piece of land can be calculated in any unit. The length and breadth of the land is measured at first and the results are multiplied. The product of the length and breadth of the land is the area of the land.') }}</div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">{{ __('How to calculate land area from Google Maps?') }}</button>
                                </h2>
                                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#areaConverterFAQ">
                                    <div class="accordion-body">{{ __('There are a number of online tools which help you calculate the area of a piece of land from Google Maps. All you would need to do is, zoom in to the map and draw a line around the edge of the piece of land on the map.') }}</div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">{{ __('What is the difference between Bigha and Acre?') }}</button>
                                </h2>
                                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#areaConverterFAQ">
                                    <div class="accordion-body">{{ __('Bigha is a traditional unit of measurement primarily used in North India, while Acre is an imperial unit used worldwide. The value of Bigha varies from state to state in India, whereas an Acre is standardized at 43,560 square feet.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="row mt-5 mb-5">
            <div class="col-md-4 mb-4">
                <div class="text-center p-4 bg-white rounded-3 shadow-sm h-100">
                    <x-core::icon name="ti ti-bolt" style="width: 3rem; height: 3rem; color: var(--primary-color);" />
                    <h5 class="mt-3">{{ __('Instant Conversion') }}</h5>
                    <p class="text-muted small">{{ __('Get accurate results instantly as you type. No waiting, no delays.') }}</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="text-center p-4 bg-white rounded-3 shadow-sm h-100">
                    <x-core::icon name="ti ti-map-pin" style="width: 3rem; height: 3rem; color: var(--primary-color);" />
                    <h5 class="mt-3">{{ __('State-Specific') }}</h5>
                    <p class="text-muted small">{{ __('Select your state for accurate Bigha and regional unit conversions.') }}</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="text-center p-4 bg-white rounded-3 shadow-sm h-100">
                    <x-core::icon name="ti ti-calculator" style="width: 3rem; height: 3rem; color: var(--primary-color);" />
                    <h5 class="mt-3">{{ __('25+ Units') }}</h5>
                    <p class="text-muted small">{{ __('Convert between all major Indian and international land measurement units.') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="{{ asset('js/area-converter.js') }}"></script>
