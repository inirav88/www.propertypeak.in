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
    <h4 class="title" style="margin-bottom: 20px;">{{ __('Financial Calculator') }} <span
            style="font-size:10px; color:red;">(v5.0)</span></h4>
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
            <button class="tf-btn primary" type="button"
                onclick="alert('CLICKED'); window.superCalc(this);">{{ __('Calculate') }}</button>
        </div>
    </div>
</div>

<script>
    // Define Global Function Explicitly
    window.superCalc = function (btn) {
        var container = btn.closest('.mc-widget-final');
        if (!container) return;

        var total = parseFloat(container.querySelector('.mc-total').value) || 0;
        var down = parseFloat(container.querySelector('.mc-down').value) || 0;
        var rate = parseFloat(container.querySelector('.mc-rate').value) || 0;
        var term = parseFloat(container.querySelector('.mc-term').value) || 0;
        var rent = parseFloat(container.querySelector('.mc-rent').value) || 0;

        var principal = total - down;
        var monthlyRate = rate / 100 / 12;
        var numberOfPayments = term * 12;
        var emi = 0;
        var totalInterest = 0;
        var totalPayback = 0;

        if (principal > 0 && monthlyRate > 0 && numberOfPayments > 0) {
            emi = principal * monthlyRate * Math.pow(1 + monthlyRate, numberOfPayments) / (Math.pow(1 + monthlyRate, numberOfPayments) - 1);
            var totalPaid = emi * numberOfPayments;
            totalInterest = totalPaid - principal;
            totalPayback = totalPaid;
            if (totalInterest < 0) totalInterest = 0;
        }

        var yieldVal = (total > 0) ? ((rent * 12) / total) * 100 : 0;
        var cashFlow = rent - emi;

        if (container.querySelector('.mc-emi')) container.querySelector('.mc-emi').innerText = Math.round(emi).toLocaleString('en-US');
        if (container.querySelector('.mc-interest')) container.querySelector('.mc-interest').innerText = Math.round(totalInterest).toLocaleString('en-US');
        if (container.querySelector('.mc-payback')) container.querySelector('.mc-payback').innerText = Math.round(totalPayback).toLocaleString('en-US');
        if (container.querySelector('.mc-yield')) container.querySelector('.mc-yield').innerText = yieldVal.toFixed(2) + '%';

        var cfEl = container.querySelector('.mc-cashflow');
        if (cfEl) {
            cfEl.innerText = Math.round(cashFlow).toLocaleString('en-US');
            cfEl.style.color = cashFlow >= 0 ? '#25d366' : '#ff5a5f';
        }
    };

    // Auto-Run safely
    setTimeout(function () {
        var btns = document.querySelectorAll('.mc-widget-final button');
        for (var i = 0; i < btns.length; i++) {
            window.superCalc(btns[i]);
        }
    }, 1500);
</script>