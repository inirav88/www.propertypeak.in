@php
    $price = 0;
    if (isset($model) && $model->price) {
        $price = (float) preg_replace('/[^0-9.]/', '', (string) $model->price);
    }
    if ($price <= 0)
        $price = 1000000;
    $downPayment = $price * 0.2;
    $rent = $price * 0.004;
@endphp

<div class="widget-box bg-surface header-property-detail mc-widget-final">
    <h4 class="title" style="margin-bottom: 20px;">{{ __('Financial Calculator') }}</h4>
    <div class="form-comment">
        <h6 style="margin-bottom: 10px; font-size: 14px; color: var(--primary-color);">{{ __('Mortgage') }}</h6>
        <div class="cols">
            <fieldset class="message">
                <label>{{ __('Total Amount') }}</label>
                <input type="number" class="mc-total" value="{{ $price }}">
            </fieldset>
            <fieldset class="message">
                <label>{{ __('Down Payment') }}</label>
                <input type="number" class="mc-down" value="{{ $downPayment }}">
            </fieldset>
        </div>
        <div class="cols">
            <fieldset class="message">
                <label>{{ __('Interest Rate (%)') }}</label>
                <input type="number" class="mc-rate" value="8.5" step="0.1">
            </fieldset>
            <fieldset class="message">
                <label>{{ __('Loan Terms (Years)') }}</label>
                <input type="number" class="mc-term" value="20">
            </fieldset>
        </div>

        <div class="result-box"
            style="margin-top: 20px; padding: 15px; background: #fff; border-radius: 8px; text-align: center;">
            <div style="margin-bottom: 15px;">
                <p style="margin-bottom: 5px; font-size: 14px; color: #666;">{{ __('Monthly Payment (EMI)') }}</p>
                <h3 class="mc-emi" style="color: var(--primary-color);">0</h3>
            </div>

            <div style="display: flex; gap: 10px; border-top: 1px dashed #ddd; padding-top: 15px;">
                <div style="flex: 1; text-align: center;">
                    <p style="margin-bottom: 5px; font-size: 12px; color: #666;">{{ __('Total Interest') }}</p>
                    <h5 class="mc-interest" style="color: #333;">0</h5>
                </div>
                <div style="flex: 1; text-align: center; border-left: 1px solid #eee;">
                    <p style="margin-bottom: 5px; font-size: 12px; color: #666;">{{ __('Total Payback') }}</p>
                    <h5 class="mc-payback" style="color: #333;">0</h5>
                </div>
            </div>
        </div>

        <div class="separator" style="margin: 20px 0; border-top: 1px dashed #ddd;"></div>

        <h6 style="margin-bottom: 10px; font-size: 14px; color: var(--primary-color);">{{ __('Investment Analysis') }}
        </h6>
        <div class="cols">
            <fieldset class="message">
                <label>{{ __('Expected Monthly Rent') }}</label>
                <input type="number" class="mc-rent" value="{{ $rent }}">
            </fieldset>
        </div>
        <div class="roi-results" style="display: flex; gap: 10px; margin-top: 10px;">
            <div class="box"
                style="flex: 1; padding: 10px; background: #eefbf3; border-radius: 8px; text-align: center;">
                <p style="font-size: 12px; color: #666;">{{ __('Gross Yield') }}</p>
                <h4 class="mc-yield" style="color: #25d366;">0%</h4>
            </div>
            <div class="box"
                style="flex: 1; padding: 10px; background: #fff4f4; border-radius: 8px; text-align: center;">
                <p style="font-size: 12px; color: #666;">{{ __('Cash Flow') }}</p>
                <h4 class="mc-cashflow" style="color: #ff5a5f;">0</h4>
            </div>
        </div>

        <div class="button-submit" style="margin-top: 15px;">
            <button class="tf-btn primary" type="button" onclick="window.superCalc(this)">{{ __('Calculate') }}</button>
        </div>

        {{-- Amortization Schedule Toggle --}}
        <div class="amortization-toggle" style="margin-top: 15px; text-align: center;">
            <button class="tf-btn secondary" type="button" onclick="window.toggleAmortization(this)" style="background: transparent; border: 1px solid var(--primary-color); color: var(--primary-color);">
                <i class="fa fa-table"></i> {{ __('View Amortization Schedule') }}
            </button>
        </div>

        {{-- Amortization Schedule Table --}}
        <div class="amortization-schedule" style="display: none; margin-top: 20px; max-height: 400px; overflow-y: auto;">
            <h6 style="margin-bottom: 10px; font-size: 14px; color: var(--primary-color);">{{ __('Amortization Schedule') }}</h6>
            <table class="table table-bordered table-striped" style="width: 100%; font-size: 12px;">
                <thead style="background: var(--primary-color); color: white; position: sticky; top: 0;">
                    <tr>
                        <th style="padding: 8px;">{{ __('Year') }}</th>
                        <th style="padding: 8px;">{{ __('Interest') }}</th>
                        <th style="padding: 8px;">{{ __('Principal') }}</th>
                        <th style="padding: 8px;">{{ __('Balance') }}</th>
                    </tr>
                </thead>
                <tbody class="amortization-body">
                    {{-- Schedule rows will be inserted here --}}
                </tbody>
            </table>
        </div>
    </div>
</div>
