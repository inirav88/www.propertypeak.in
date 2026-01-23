<div class="widget-box bg-surface header-property-detail" style="margin-top: 30px;">
    <h4 class="title" style="margin-bottom: 20px;">{{ __('Financial Calculator') }}</h4>
    <form id="mortgage-calculator" class="form-comment">
        <h6 style="margin-bottom: 10px; font-size: 14px; color: var(--primary-color);">{{ __('Mortgage') }}</h6>
        <div class="cols">
            <fieldset class="message">
                <label>{{ __('Total Amount') }}</label>
                <input type="number" id="mc-total-amount" value="{{ $model->price ?? 1000000 }}" placeholder="{{ __('Total Amount') }}">
            </fieldset>
            <fieldset class="message">
                <label>{{ __('Down Payment') }}</label>
                <input type="number" id="mc-down-payment" value="{{ ($model->price ?? 1000000) * 0.2 }}" placeholder="{{ __('Down Payment (20%)') }}">
            </fieldset>
        </div>
        <div class="cols">
            <fieldset class="message">
                <label>{{ __('Interest Rate (%)') }}</label>
                <input type="number" id="mc-interest-rate" value="8.5" step="0.1" placeholder="{{ __('Interest Rate') }}">
            </fieldset>
            <fieldset class="message">
                <label>{{ __('Loan Terms (Years)') }}</label>
                <input type="number" id="mc-loan-term" value="20" placeholder="{{ __('Years') }}">
            </fieldset>
        </div>
        <div class="result-box" style="margin-top: 20px; padding: 15px; background: #fff; border-radius: 8px; text-align: center;">
            <p style="margin-bottom: 5px; font-size: 14px; color: #666;">{{ __('Monthly Payment (EMI)') }}</p>
            <h3 id="mc-emi-result" style="color: var(--primary-color);">0</h3>
        </div>

        <div class="separator" style="margin: 20px 0; border-top: 1px dashed #ddd;"></div>

        <h6 style="margin-bottom: 10px; font-size: 14px; color: var(--primary-color);">{{ __('Investment Analysis') }}</h6>
        <div class="cols">
            <fieldset class="message">
                <label>{{ __('Expected Monthly Rent') }}</label>
                <input type="number" id="mc-rent" value="{{ ($model->price ?? 1000000) * 0.004 }}" placeholder="{{ __('Monthly Rent') }}">
            </fieldset>
        </div>
        <div class="roi-results" style="display: flex; gap: 10px; margin-top: 10px;">
            <div class="box" style="flex: 1; padding: 10px; background: #eefbf3; border-radius: 8px; text-align: center;">
                <p style="font-size: 12px; color: #666;">{{ __('Gross Yield') }}</p>
                <h4 id="mc-yield" style="color: #25d366;">0%</h4>
            </div>
            <div class="box" style="flex: 1; padding: 10px; background: #fff4f4; border-radius: 8px; text-align: center;">
                <p style="font-size: 12px; color: #666;">{{ __('Cash Flow') }}</p>
                <h4 id="mc-cashflow" style="color: #ff5a5f;">0</h4>
            </div>
        </div>

        <div class="button-submit" style="margin-top: 15px;">
            <button class="tf-btn primary" type="button" onclick="calculateMortgage()">{{ __('Calculate') }}</button>
        </div>
    </form>
</div>

<script>
    function calculateMortgage() {
        // Mortgage Inputs
        const total = parseFloat(document.getElementById('mc-total-amount').value) || 0;
        const down = parseFloat(document.getElementById('mc-down-payment').value) || 0;
        const rate = parseFloat(document.getElementById('mc-interest-rate').value) || 0;
        const term = parseFloat(document.getElementById('mc-loan-term').value) || 0;
        
        // ROI Inputs
        const rent = parseFloat(document.getElementById('mc-rent').value) || 0;

        // EMI Calculation
        const principal = total - down;
        const monthlyRate = rate / 100 / 12;
        const numberOfPayments = term * 12;

        let emi = 0;
        if (principal > 0 && monthlyRate > 0 && numberOfPayments > 0) {
            emi = principal * monthlyRate * Math.pow(1 + monthlyRate, numberOfPayments) / (Math.pow(1 + monthlyRate, numberOfPayments) - 1);
        }
        
        // Yield Calculation
        // Gross Yield = (Annual Rent / Total Price) * 100
        let yieldVal = 0;
        if (total > 0) {
            yieldVal = ((rent * 12) / total) * 100;
        }

        // Cash Flow = Rent - EMI
        let cashFlow = rent - emi;

        // Display Results
        const formatter = new Intl.NumberFormat();
        document.getElementById('mc-emi-result').innerText = formatter.format(Math.round(emi));
        
        document.getElementById('mc-yield').innerText = yieldVal.toFixed(2) + '%';
        
        const cfElement = document.getElementById('mc-cashflow');
        cfElement.innerText = formatter.format(Math.round(cashFlow));
        cfElement.style.color = cashFlow >= 0 ? '#25d366' : '#ff5a5f';
    }
    
    // Auto calculate on load
    document.addEventListener('DOMContentLoaded', calculateMortgage);
</script>