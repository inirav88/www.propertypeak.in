@php
    Theme::layout('full-width');
    Theme::set('pageTitle', __('Rent Agreement - Create Legal Rent Agreement Online'));
    Theme::set('breadcrumbEnabled', 'yes');
    Theme::set('breadcrumbBackgroundColor', '#f7f7f7');
@endphp

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .rent-card-red { background: white !important; border: 5px solid #dc3545 !important; border-radius: 12px !important; overflow: hidden !important; margin-bottom: 24px !important; }
    .rent-card-red .card-header { background: #dc3545 !important; color: white !important; padding: 20px !important; border-bottom: 5px solid #a71d2a !important; }
    .rent-card-green { background: white !important; border: 5px solid #28a745 !important; border-radius: 12px !important; overflow: hidden !important; margin-bottom: 24px !important; }
    .rent-card-green .card-header { background: #28a745 !important; color: white !important; padding: 20px !important; border-bottom: 5px solid #1e7e34 !important; }
    .rent-card-gray { background: white !important; border: 5px solid #6c757d !important; border-radius: 12px !important; overflow: hidden !important; margin-bottom: 24px !important; }
    .rent-card-gray .card-header { background: #6c757d !important; color: white !important; padding: 20px !important; border-bottom: 5px solid #495057 !important; }
    .rent-card-blue { background: white !important; border: 5px solid #17a2b8 !important; border-radius: 12px !important; overflow: hidden !important; margin-bottom: 24px !important; }
    .rent-card-blue .card-header { background: #17a2b8 !important; color: white !important; padding: 20px !important; border-bottom: 5px solid #138496 !important; }
</style>

<section class="section-rent-agreement py-5" style="background: #f7f7f7;">
    <div class="container">
        <!-- Header -->
        <div class="text-center mb-5">
            <h1 class="title">{{ __('Rent Agreement Generator') }}</h1>
            <p class="text-muted">{{ __('Create a legally valid rent agreement online. Fill in the details below to generate your agreement.') }}</p>
        </div>

        @if(session('error'))
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border: 2px solid #f5c6cb; border-radius: 8px; margin-bottom: 20px;">
                <strong>Error:</strong> {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border: 2px solid #f5c6cb; border-radius: 8px; margin-bottom: 20px;">
                <strong>Please fix these errors:</strong>
                <ul style="margin: 10px 0 0 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('rent-agreement.save') }}" method="POST">
            @csrf

            <!-- Progress Steps -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="d-flex justify-content-center align-items-center flex-wrap">
                        <div class="step-item active text-center px-2">
                            <div class="step-number" style="width: 50px; height: 50px; border-radius: 50%; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-weight: 600;">1</div>
                            <div class="step-label mt-2" style="font-size: 14px; font-weight: 600; color: var(--primary-color);">{{ __('Fill Details') }}</div>
                        </div>
                        <div class="step-line" style="width: 80px; height: 3px; background: linear-gradient(90deg, var(--primary-color), #ddd); margin: 0 10px;"></div>
                        <div class="step-item text-center px-2">
                            <div class="step-number" style="width: 50px; height: 50px; border-radius: 50%; background: #e4e4e4; color: #666; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-weight: 600;">2</div>
                            <div class="step-label mt-2" style="font-size: 14px; color: #666;">{{ __('Preview') }}</div>
                        </div>
                        <div class="step-line" style="width: 80px; height: 3px; background: #e4e4e4; margin: 0 10px;"></div>
                        <div class="step-item text-center px-2">
                            <div class="step-number" style="width: 50px; height: 50px; border-radius: 50%; background: #e4e4e4; color: #666; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-weight: 600;">3</div>
                            <div class="step-label mt-2" style="font-size: 14px; color: #666;">{{ __('Download') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Left Column - Parties Info -->
                <div class="col-lg-6">
                    <!-- Lessor (Landlord) Details -->
                    <div class="rent-card-red">
                        <div class="card-header">
                            <h5 style="color: #ffffff !important; margin: 0; display: flex; align-items: center; gap: 10px;">
                                <x-core::icon name="ti ti-user" style="width: 24px; height: 24px;" />
                                {{ __('Lessor (Landlord) Details') }}
                            </h5>
                        </div>
                        <div class="p-4">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">{{ __('Full Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="landlord_name" class="form-control @error('landlord_name') is-invalid @enderror" 
                                           value="{{ old('landlord_name') }}" placeholder="Enter lessor full name" required style="height: 48px; border-radius: 8px;">
                                    @error('landlord_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">{{ __('Mobile Number') }} <span class="text-danger">*</span></label>
                                    <input type="tel" name="landlord_phone" class="form-control @error('landlord_phone') is-invalid @enderror" 
                                           value="{{ old('landlord_phone') }}" placeholder="10-digit mobile" required style="height: 48px; border-radius: 8px;">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">{{ __('Email') }}</label>
                                    <input type="email" name="landlord_email" class="form-control" 
                                           value="{{ old('landlord_email') }}" placeholder="Email address" style="height: 48px; border-radius: 8px;">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">{{ __('Complete Address') }} <span class="text-danger">*</span></label>
                                    <textarea name="landlord_address" class="form-control @error('landlord_address') is-invalid @enderror" 
                                              rows="3" placeholder="House No, Street, Area" required style="border-radius: 8px;">{{ old('landlord_address') }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">{{ __('City') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="landlord_city" class="form-control @error('landlord_city') is-invalid @enderror" 
                                           value="{{ old('landlord_city') }}" placeholder="City" required style="height: 48px; border-radius: 8px;">
                                    @error('landlord_city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">{{ __('State') }} <span class="text-danger">*</span></label>
                                    <div class="select-wrapper">
                                        <select name="landlord_state" class="form-control @error('landlord_state') is-invalid @enderror" required style="height: 48px; border-radius: 8px;">
                                            <option value="">{{ __('Select State') }}</option>
                                            @foreach($states as $key => $state)
                                                <option value="{{ $key }}" {{ old('landlord_state') == $key ? 'selected' : '' }}>{{ $state }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">{{ __('PIN Code') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="landlord_pincode" class="form-control @error('landlord_pincode') is-invalid @enderror" 
                                           value="{{ old('landlord_pincode') }}" placeholder="PIN Code" maxlength="6" required style="height: 48px; border-radius: 8px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Lessee Info -->
                <div class="col-lg-6">
                    <!-- Lessee (Tenant) Details -->
                    <div class="rent-card-green">
                        <div class="card-header">
                            <h5 style="color: #ffffff !important; margin: 0; display: flex; align-items: center; gap: 10px;">
                                <x-core::icon name="ti ti-users" style="width: 24px; height: 24px;" />
                                {{ __('Lessee (Tenant) Details') }}
                            </h5>
                        </div>
                        <div class="p-4">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">{{ __('Full Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="tenant_name" class="form-control @error('tenant_name') is-invalid @enderror" 
                                           value="{{ old('tenant_name') }}" placeholder="Enter lessee full name" required style="height: 48px; border-radius: 8px;">
                                    @error('tenant_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">{{ __('Mobile Number') }} <span class="text-danger">*</span></label>
                                    <input type="tel" name="tenant_phone" class="form-control @error('tenant_phone') is-invalid @enderror" 
                                           value="{{ old('tenant_phone') }}" placeholder="10-digit mobile" required style="height: 48px; border-radius: 8px;">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">{{ __('Email') }}</label>
                                    <input type="email" name="tenant_email" class="form-control" 
                                           value="{{ old('tenant_email') }}" placeholder="Email address" style="height: 48px; border-radius: 8px;">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">{{ __('Complete Address') }} <span class="text-danger">*</span></label>
                                    <textarea name="tenant_address" class="form-control @error('tenant_address') is-invalid @enderror" 
                                              rows="3" placeholder="House No, Street, Area" required style="border-radius: 8px;">{{ old('tenant_address') }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">{{ __('City') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="tenant_city" class="form-control @error('tenant_city') is-invalid @enderror" 
                                           value="{{ old('tenant_city') }}" placeholder="City" required style="height: 48px; border-radius: 8px;">
                                    @error('tenant_city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">{{ __('State') }} <span class="text-danger">*</span></label>
                                    <div class="select-wrapper">
                                        <select name="tenant_state" class="form-control @error('tenant_state') is-invalid @enderror" required style="height: 48px; border-radius: 8px;">
                                            <option value="">{{ __('Select State') }}</option>
                                            @foreach($states as $key => $state)
                                                <option value="{{ $key }}" {{ old('tenant_state') == $key ? 'selected' : '' }}>{{ $state }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">{{ __('PIN Code') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="tenant_pincode" class="form-control @error('tenant_pincode') is-invalid @enderror" 
                                           value="{{ old('tenant_pincode') }}" placeholder="PIN Code" maxlength="6" required style="height: 48px; border-radius: 8px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Property Details -->
            <div class="rent-card-gray">
                <div class="card-header">
                    <h5 style="color: #ffffff !important; margin: 0; display: flex; align-items: center; gap: 10px;">
                        <x-core::icon name="ti ti-building" style="width: 24px; height: 24px;" />
                        {{ __('Property Details') }}
                    </h5>
                </div>
                <div class="p-4">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">{{ __('Property Address') }} <span class="text-danger">*</span></label>
                            <textarea name="property_address" class="form-control @error('property_address') is-invalid @enderror" 
                                      rows="2" placeholder="Property address with landmark" required style="border-radius: 8px;">{{ old('property_address') }}</textarea>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">{{ __('City') }} <span class="text-danger">*</span></label>
                            <input type="text" name="property_city" class="form-control @error('property_city') is-invalid @enderror" 
                                   value="{{ old('property_city') }}" placeholder="City" required style="height: 48px; border-radius: 8px;">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">{{ __('State') }} <span class="text-danger">*</span></label>
                            <div class="select-wrapper">
                                <select name="property_state" class="form-control @error('property_state') is-invalid @enderror" required style="height: 48px; border-radius: 8px;">
                                    <option value="">{{ __('Select') }}</option>
                                    @foreach($states as $key => $state)
                                        <option value="{{ $key }}" {{ old('property_state') == $key ? 'selected' : '' }}>{{ $state }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">{{ __('PIN Code') }} <span class="text-danger">*</span></label>
                            <input type="text" name="property_pincode" class="form-control @error('property_pincode') is-invalid @enderror" 
                                   value="{{ old('property_pincode') }}" placeholder="PIN" maxlength="6" required style="height: 48px; border-radius: 8px;">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">{{ __('Floor Number') }} <span class="text-danger">*</span></label>
                            <input type="text" name="floor_number" class="form-control @error('floor_number') is-invalid @enderror" 
                                   value="{{ old('floor_number') }}" placeholder="e.g., Ground, 1st, 2nd" required style="height: 48px; border-radius: 8px;">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">{{ __('Property Type') }} <span class="text-danger">*</span></label>
                            <div class="select-wrapper">
                                <select name="property_type" class="form-control @error('property_type') is-invalid @enderror" required style="height: 48px; border-radius: 8px;">
                                    <option value="">{{ __('Select') }}</option>
                                    @foreach($propertyTypes as $key => $type)
                                        <option value="{{ $key }}" {{ old('property_type') == $key ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">{{ __('Parking') }}</label>
                            <div class="select-wrapper">
                                <select name="parking" class="form-control" style="height: 48px; border-radius: 8px;">
                                    <option value="without_parking" {{ old('parking') == 'without_parking' ? 'selected' : '' }}>{{ __('Without Parking') }}</option>
                                    <option value="with_parking" {{ old('parking') == 'with_parking' ? 'selected' : '' }}>{{ __('With Parking') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">{{ __('Property Description') }}</label>
                            <input type="text" name="property_description" class="form-control" 
                                   value="{{ old('property_description') }}" placeholder="e.g., 2 BHK, 1000 sq.ft." style="height: 48px; border-radius: 8px;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agreement Terms -->
            <div class="rent-card-blue">
                <div class="card-header">
                    <h5 style="color: #ffffff !important; margin: 0; display: flex; align-items: center; gap: 10px;">
                        <x-core::icon name="ti ti-file-text" style="width: 24px; height: 24px;" />
                        {{ __('Agreement Terms') }}
                    </h5>
                </div>
                <div class="p-4">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">{{ __('Agreement Duration') }} <span class="text-danger">*</span></label>
                            <div class="select-wrapper">
                                <select name="agreement_duration" class="form-control @error('agreement_duration') is-invalid @enderror" required style="height: 48px; border-radius: 8px;">
                                    <option value="">{{ __('Select Duration') }}</option>
                                    @foreach($agreementTypes as $key => $type)
                                        <option value="{{ $key }}" {{ old('agreement_duration') == $key ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">{{ __('Start Date') }} <span class="text-danger">*</span></label>
                            <input type="date" name="agreement_start_date" 
                                   class="form-control @error('agreement_start_date') is-invalid @enderror" 
                                   value="{{ old('agreement_start_date') }}" required style="height: 48px; border-radius: 8px;">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">{{ __('Monthly Rent') }} (Rs.) <span class="text-danger">*</span></label>
                            <input type="number" name="rent_amount" id="rent_amount" 
                                   class="form-control @error('rent_amount') is-invalid @enderror" 
                                   value="{{ old('rent_amount') }}" placeholder="e.g., 15000" min="1" required style="height: 48px; border-radius: 8px;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">{{ __('Rent in Words') }} <span class="text-danger">*</span></label>
                            <input type="text" name="rent_amount_words" id="rent_amount_words" 
                                   class="form-control @error('rent_amount_words') is-invalid @enderror" 
                                   value="{{ old('rent_amount_words') }}" placeholder="Auto-generated" required style="height: 48px; border-radius: 8px; background: #f8f9fa;">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">{{ __('Security Deposit') }} (Rs.) <span class="text-danger">*</span></label>
                            <input type="number" name="security_deposit" id="security_deposit" 
                                   class="form-control @error('security_deposit') is-invalid @enderror" 
                                   value="{{ old('security_deposit') }}" placeholder="e.g., 50000" min="0" required style="height: 48px; border-radius: 8px;">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">{{ __('Deposit in Words') }} <span class="text-danger">*</span></label>
                            <input type="text" name="security_deposit_words" id="security_deposit_words" 
                                   class="form-control @error('security_deposit_words') is-invalid @enderror" 
                                   value="{{ old('security_deposit_words') }}" placeholder="Auto-generated" required style="height: 48px; border-radius: 8px; background: #f8f9fa;">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">{{ __('Rent Due Date') }} <span class="text-danger">*</span></label>
                            <div class="select-wrapper">
                                <select name="rent_due_date" class="form-control" required style="height: 48px; border-radius: 8px;">
                                    <option value="5th" {{ old('rent_due_date') == '5th' ? 'selected' : '' }}>{{ __('5th of every month') }}</option>
                                    <option value="10th" {{ old('rent_due_date') == '10th' ? 'selected' : '' }}>{{ __('10th of every month') }}</option>
                                    <option value="15th" {{ old('rent_due_date') == '15th' ? 'selected' : '' }}>{{ __('15th of every month') }}</option>
                                    <option value="1st" {{ old('rent_due_date') == '1st' ? 'selected' : '' }}>{{ __('1st of every month') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">{{ __('Notice Period') }} <span class="text-danger">*</span></label>
                            <div class="select-wrapper">
                                <select name="notice_period" class="form-control" required style="height: 48px; border-radius: 8px;">
                                    <option value="One month" {{ old('notice_period') == 'One month' ? 'selected' : '' }}>{{ __('One Month') }}</option>
                                    <option value="Two months" {{ old('notice_period') == 'Two months' ? 'selected' : '' }}>{{ __('Two Months') }}</option>
                                    <option value="Three months" {{ old('notice_period') == 'Three months' ? 'selected' : '' }}>{{ __('Three Months') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">{{ __('Purpose') }} <span class="text-danger">*</span></label>
                            <div class="select-wrapper">
                                <select name="purpose" class="form-control" required style="height: 48px; border-radius: 8px;">
                                    <option value="residential" {{ old('purpose') == 'residential' ? 'selected' : '' }}>{{ __('Residential') }}</option>
                                    <option value="commercial" {{ old('purpose') == 'commercial' ? 'selected' : '' }}>{{ __('Commercial') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-center mb-5">
                <button type="submit" style="background: #dc3545 !important; color: white !important; border: none; padding: 16px 48px; font-size: 18px; font-weight: 600; border-radius: 8px; cursor: pointer; min-width: 300px;">
                    {{ __('Preview Agreement') }} →
                </button>
            </div>
        </form>

        <!-- Features -->
        <div class="row mt-5">
            <div class="col-md-4 mb-4">
                <div class="text-center p-4 bg-white rounded-3 shadow-sm h-100" style="border-top: 4px solid var(--primary-color);">
                    <x-core::icon name="ti ti-shield-check" style="width: 3rem; height: 3rem; color: var(--primary-color);" />
                    <h5 class="mt-3">{{ __('Legally Valid') }}</h5>
                    <p class="text-muted small">{{ __('Our rent agreements are legally valid and enforceable in court.') }}</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="text-center p-4 bg-white rounded-3 shadow-sm h-100" style="border-top: 4px solid #28a745;">
                    <x-core::icon name="ti ti-bolt" style="width: 3rem; height: 3rem; color: #28a745;" />
                    <h5 class="mt-3">{{ __('Instant Generation') }}</h5>
                    <p class="text-muted small">{{ __('Generate your agreement instantly. No waiting required.') }}</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="text-center p-4 bg-white rounded-3 shadow-sm h-100" style="border-top: 4px solid #17a2b8;">
                    <x-core::icon name="ti ti-file-download" style="width: 3rem; height: 3rem; color: #17a2b8;" />
                    <h5 class="mt-3">{{ __('Download & Print') }}</h5>
                    <p class="text-muted small">{{ __('Download as PDF or print directly for signing.') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="{{ asset('js/number-to-words.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rentAmount = document.getElementById('rent_amount');
    const rentAmountWords = document.getElementById('rent_amount_words');
    const securityDeposit = document.getElementById('security_deposit');
    const securityDepositWords = document.getElementById('security_deposit_words');

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

    if (securityDeposit && securityDepositWords) {
        securityDeposit.addEventListener('input', function() {
            const value = parseInt(this.value) || 0;
            if (value > 0 && typeof numberToWords === 'function') {
                securityDepositWords.value = numberToWords(value) + ' Only';
            } else {
                securityDepositWords.value = '';
            }
        });
    }
});
</script>
