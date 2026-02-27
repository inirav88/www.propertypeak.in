@if(empty($isShortcode))
@php
    Theme::layout('full-width');
    Theme::set('pageTitle', __('Rent Receipt Generator'));
    Theme::set('breadcrumbEnabled', 'yes');
    Theme::set('breadcrumbBackgroundColor', '#f7f7f7');
@endphp
@endif

<div class="bg-surface py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="widget-box">
                    <div class="wg-title">
                        <h5><x-core::icon name="ti ti-file-text" /> {{ __('Generate Rent Receipt') }}</h5>
                    </div>
                    <div class="wg-content">
                        <form action="{{ route('rent-receipt.generate') }}" method="POST" target="_blank">
                            @csrf
                            
                            <div class="row">
                                <!-- Tenant Details -->
                                <div class="col-md-6 mb-4">
                                    <h6 class="fw-bold text-primary mb-3">{{ __('Tenant Details') }}</h6>
                                    <div class="mb-3">
                                        <label class="label-form">{{ __('Tenant Name') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="tenant_name" class="form-control @error('tenant_name') is-invalid @enderror" 
                                               value="{{ old('tenant_name') }}" placeholder="{{ __('Enter tenant full name') }}" required>
                                        @error('tenant_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="label-form">{{ __('Email') }} <small class="text-muted">({{ __('Optional') }})</small></label>
                                        <input type="email" name="tenant_email" class="form-control @error('tenant_email') is-invalid @enderror" 
                                               value="{{ old('tenant_email') }}" placeholder="{{ __('For sending receipt') }}">
                                    </div>
                                </div>

                                <!-- Landlord Details -->
                                <div class="col-md-6 mb-4">
                                    <h6 class="fw-bold text-primary mb-3">{{ __('Landlord Details') }}</h6>
                                    <div class="mb-3">
                                        <label class="label-form">{{ __('Landlord Name') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="landlord_name" class="form-control @error('landlord_name') is-invalid @enderror" 
                                               value="{{ old('landlord_name') }}" placeholder="{{ __('Enter landlord full name') }}" required>
                                        @error('landlord_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="label-form">{{ __('Landlord PAN') }} <small class="text-muted">({{ __('if rent > ₹8,300/month') }})</small></label>
                                        <input type="text" name="landlord_pan" class="form-control" 
                                               value="{{ old('landlord_pan') }}" placeholder="ABCDE1234F" maxlength="10">
                                    </div>
                                </div>
                            </div>

                            <!-- Property Details -->
                            <div class="mb-4">
                                <h6 class="fw-bold text-primary mb-3">{{ __('Property Details') }}</h6>
                                <div class="mb-3">
                                    <label class="label-form">{{ __('Property Address') }} <span class="text-danger">*</span></label>
                                    <textarea name="property_address" class="form-control @error('property_address') is-invalid @enderror" 
                                              rows="2" placeholder="{{ __('Complete rental property address') }}" required>{{ old('property_address') }}</textarea>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Rent Details -->
                                <div class="col-md-6 mb-4">
                                    <h6 class="fw-bold text-primary mb-3">{{ __('Rent Details') }}</h6>
                                    <div class="mb-3">
                                        <label class="label-form">{{ __('Monthly Rent Amount (₹)') }} <span class="text-danger">*</span></label>
                                        <input type="number" name="rent_amount" id="rent_amount" 
                                               class="form-control @error('rent_amount') is-invalid @enderror" 
                                               value="{{ old('rent_amount') }}" placeholder="e.g., 15000" min="1" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="label-form">{{ __('Amount in Words') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="rent_amount_words" id="rent_amount_words"
                                               class="form-control @error('rent_amount_words') is-invalid @enderror" 
                                               value="{{ old('rent_amount_words') }}" placeholder="{{ __('e.g., Fifteen Thousand Only') }}" required>
                                    </div>
                                </div>

                                <!-- Period & Payment -->
                                <div class="col-md-6 mb-4">
                                    <h6 class="fw-bold text-primary mb-3">{{ __('Period & Payment') }}</h6>
                                    <div class="row">
                                        <div class="col-6 mb-3">
                                            <label class="label-form">{{ __('From Date') }} <span class="text-danger">*</span></label>
                                            <input type="date" name="rent_from" class="form-control" value="{{ old('rent_from') }}" required>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label class="label-form">{{ __('To Date') }} <span class="text-danger">*</span></label>
                                            <input type="date" name="rent_to" class="form-control" value="{{ old('rent_to') }}" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="label-form">{{ __('Payment Method') }} <span class="text-danger">*</span></label>
                                        <div class="select-wrapper">
                                            <select name="payment_method" class="form-control" required>
                                                <option value="">{{ __('Select Method') }}</option>
                                                <option value="cash">{{ __('Cash') }}</option>
                                                <option value="cheque">{{ __('Cheque') }}</option>
                                                <option value="online">{{ __('Online Payment') }}</option>
                                                <option value="bank_transfer">{{ __('Bank Transfer') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="label-form">{{ __('Payment Date') }} <span class="text-danger">*</span></label>
                                        <input type="date" name="payment_date" class="form-control" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Options -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input type="checkbox" name="include_revenue_stamp" class="form-check-input" id="revenue_stamp" value="1">
                                    <label class="form-check-label" for="revenue_stamp">
                                        {{ __('Include Revenue Stamp (₹1 stamp for cash payments above ₹5,000)') }}
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex gap-3 justify-content-center mt-4">
                                <button type="submit" class="tf-btn primary">
                                    {{ __('Preview Receipt') }}
                                </button>
                                <button type="submit" name="download_pdf" value="1" class="tf-btn secondary">
                                    {{ __('Download PDF') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Features -->
                <div class="row mt-5">
                    <div class="col-md-4 mb-4">
                        <div class="text-center p-4 bg-white rounded-3 shadow-sm h-100">
                            <x-core::icon name="ti ti-shield-check" style="width: 2.5rem; height: 2.5rem; color: var(--primary-color);" />
                            <h6 class="mt-3 fw-bold">{{ __('Tax Compliant') }}</h6>
                            <p class="text-muted small mb-0">{{ __('Generate receipts that comply with IT regulations for HRA claims.') }}</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="text-center p-4 bg-white rounded-3 shadow-sm h-100">
                            <x-core::icon name="ti ti-bolt" style="width: 2.5rem; height: 2.5rem; color: var(--primary-color);" />
                            <h6 class="mt-3 fw-bold">{{ __('Instant & Free') }}</h6>
                            <p class="text-muted small mb-0">{{ __('Create professional rent receipts in seconds. Completely free.') }}</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="text-center p-4 bg-white rounded-3 shadow-sm h-100">
                            <x-core::icon name="ti ti-file-download" style="width: 2.5rem; height: 2.5rem; color: var(--primary-color);" />
                            <h6 class="mt-3 fw-bold">{{ __('Download PDF') }}</h6>
                            <p class="text-muted small mb-0">{{ __('Download your receipt as a PDF or print directly from browser.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/number-to-words.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rentAmount = document.getElementById('rent_amount');
        const rentAmountWords = document.getElementById('rent_amount_words');

        if (rentAmount && rentAmountWords) {
            rentAmount.addEventListener('input', function() {
                const value = parseInt(this.value) || 0;
                if (value > 0 && typeof numberToWords === 'function') {
                    rentAmountWords.value = numberToWords(value) + ' Only';
                } else {
                    rentAmountWords.value = '';
                }
            });
        }
    });
</script>
