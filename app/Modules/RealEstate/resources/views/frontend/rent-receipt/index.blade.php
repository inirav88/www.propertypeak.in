@if(empty($isShortcode))
@php
    Theme::layout('full-width');
    Theme::set('pageTitle', __('Rent Receipt Generator - Free House Rent Receipt for Income Tax'));
    Theme::set('breadcrumbEnabled', 'yes');
    Theme::set('breadcrumbBackgroundColor', '#f7f7f7');
@endphp
@endif

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="bg-light py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                @if(empty($isShortcode))
                <div class="text-center mb-5">
                    <h1 class="h2 mb-3">Rent Receipt Generator</h1>
                    <p class="text-muted">Generate professional house rent receipts for income tax HRA claims. Free, easy, and instant!</p>
                </div>
                @endif

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white p-3">
                        <h5 class="mb-0"><i class="fas fa-file-invoice me-2"></i>Generate Rent Receipt</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('rent-receipt.generate') }}" method="POST" target="_blank">
                            @csrf
                            
                            <div class="row">
                                <!-- Tenant Details -->
                                <div class="col-md-6 mb-4">
                                    <h6 class="text-primary mb-3 border-bottom pb-2">Tenant Details</h6>
                                    <div class="mb-3">
                                        <label class="form-label">Tenant Name <span class="text-danger">*</span></label>
                                        <input type="text" name="tenant_name" class="form-control @error('tenant_name') is-invalid @enderror" 
                                               value="{{ old('tenant_name') }}" placeholder="Enter tenant full name" required>
                                        @error('tenant_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email (Optional)</label>
                                        <input type="email" name="tenant_email" class="form-control @error('tenant_email') is-invalid @enderror" 
                                               value="{{ old('tenant_email') }}" placeholder="For sending receipt">
                                        @error('tenant_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <!-- Landlord Details -->
                                <div class="col-md-6 mb-4">
                                    <h6 class="text-primary mb-3 border-bottom pb-2">Landlord Details</h6>
                                    <div class="mb-3">
                                        <label class="form-label">Landlord Name <span class="text-danger">*</span></label>
                                        <input type="text" name="landlord_name" class="form-control @error('landlord_name') is-invalid @enderror" 
                                               value="{{ old('landlord_name') }}" placeholder="Enter landlord full name" required>
                                        @error('landlord_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Landlord PAN <small class="text-muted">(if rent > ₹8,300/month)</small></label>
                                        <input type="text" name="landlord_pan" class="form-control @error('landlord_pan') is-invalid @enderror" 
                                               value="{{ old('landlord_pan') }}" placeholder="ABCDE1234F" maxlength="10">
                                        @error('landlord_pan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Property Details -->
                            <div class="mb-4">
                                <h6 class="text-primary mb-3 border-bottom pb-2">Property Details</h6>
                                <div class="mb-3">
                                    <label class="form-label">Property Address <span class="text-danger">*</span></label>
                                    <textarea name="property_address" class="form-control @error('property_address') is-invalid @enderror" 
                                              rows="3" placeholder="Complete rental property address" required>{{ old('property_address') }}</textarea>
                                    @error('property_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Rent Amount -->
                                <div class="col-md-6 mb-4">
                                    <h6 class="text-primary mb-3 border-bottom pb-2">Rent Details</h6>
                                    <div class="mb-3">
                                        <label class="form-label">Monthly Rent Amount (₹) <span class="text-danger">*</span></label>
                                        <input type="number" name="rent_amount" id="rent_amount" 
                                               class="form-control @error('rent_amount') is-invalid @enderror" 
                                               value="{{ old('rent_amount') }}" placeholder="e.g., 15000" min="1" required>
                                        @error('rent_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Amount in Words <span class="text-danger">*</span></label>
                                        <input type="text" name="rent_amount_words" id="rent_amount_words"
                                               class="form-control @error('rent_amount_words') is-invalid @enderror" 
                                               value="{{ old('rent_amount_words') }}" placeholder="e.g., Fifteen Thousand Only" required>
                                        @error('rent_amount_words')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Receipt Number <small class="text-muted">(Optional)</small></label>
                                        <input type="text" name="receipt_number" class="form-control" 
                                               value="{{ old('receipt_number') }}" placeholder="Auto-generated if blank">
                                    </div>
                                </div>

                                <!-- Period & Payment -->
                                <div class="col-md-6 mb-4">
                                    <h6 class="text-primary mb-3 border-bottom pb-2">Period & Payment</h6>
                                    <div class="row">
                                        <div class="col-6 mb-3">
                                            <label class="form-label">From Date <span class="text-danger">*</span></label>
                                            <input type="date" name="rent_from" class="form-control @error('rent_from') is-invalid @enderror" 
                                                   value="{{ old('rent_from') }}" required>
                                            @error('rent_from')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label class="form-label">To Date <span class="text-danger">*</span></label>
                                            <input type="date" name="rent_to" class="form-control @error('rent_to') is-invalid @enderror" 
                                                   value="{{ old('rent_to') }}" required>
                                            @error('rent_to')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                                        <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                                            <option value="">Select Payment Method</option>
                                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                            <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                            <option value="online" {{ old('payment_method') == 'online' ? 'selected' : '' }}>Online Payment</option>
                                            <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        </select>
                                        @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        <small class="text-muted">Note: Revenue stamp required for cash payments above ₹5,000</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Payment Date <span class="text-danger">*</span></label>
                                        <input type="date" name="payment_date" class="form-control @error('payment_date') is-invalid @enderror" 
                                               value="{{ old('payment_date', date('Y-m-d')) }}" required>
                                        @error('payment_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Options -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input type="checkbox" name="include_revenue_stamp" class="form-check-input" id="revenue_stamp" value="1">
                                    <label class="form-check-label" for="revenue_stamp">
                                        Include Revenue Stamp (₹1 stamp for cash payments above ₹5,000)
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex gap-3 justify-content-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-eye me-2"></i>Preview Receipt
                                </button>
                                <button type="submit" name="download_pdf" value="1" class="btn btn-success btn-lg px-5">
                                    <i class="fas fa-download me-2"></i>Download PDF
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Information Section -->
                <div class="row mt-5">
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                                <h5>Tax Compliant</h5>
                                <p class="text-muted small">Generate rent receipts that comply with Income Tax regulations for HRA claims.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="fas fa-bolt fa-3x text-primary mb-3"></i>
                                <h5>Instant & Free</h5>
                                <p class="text-muted small">Create professional rent receipts in seconds. Completely free to use.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="fas fa-file-pdf fa-3x text-primary mb-3"></i>
                                <h5>Download as PDF</h5>
                                <p class="text-muted small">Download your rent receipt as a PDF or print directly from browser.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ Section -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-question-circle me-2"></i>Frequently Asked Questions</h5>
                    </div>
                    <div class="card-body">
                        <div class="accordion" id="rentReceiptFAQ">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                        Why do I need a rent receipt?
                                    </button>
                                </h2>
                                <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#rentReceiptFAQ">
                                    <div class="accordion-body">
                                        Rent receipts are required to claim House Rent Allowance (HRA) exemption from your taxable income. Your employer needs these receipts as proof of rent payment to process your HRA exemption.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                        When is a revenue stamp required?
                                    </button>
                                </h2>
                                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#rentReceiptFAQ">
                                    <div class="accordion-body">
                                        A revenue stamp of ₹1 is required when the monthly rent is paid in cash and exceeds ₹5,000 per receipt. For online payments, cheque, or bank transfer, revenue stamp is not mandatory.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                        Is landlord's PAN mandatory?
                                    </button>
                                </h2>
                                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#rentReceiptFAQ">
                                    <div class="accordion-body">
                                        Landlord's PAN is mandatory if your annual rent exceeds ₹1,00,000 (approximately ₹8,300 per month). Without PAN, you cannot claim HRA exemption for rent above this limit.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-convert rent amount to words (basic version)
    document.getElementById('rent_amount').addEventListener('input', function() {
        const amount = this.value;
        if (amount) {
            // Simple conversion - in production, use a proper number-to-words library
            const words = convertNumberToWords(parseInt(amount));
            document.getElementById('rent_amount_words').value = words + ' Only';
        }
    });

    function convertNumberToWords(num) {
        const ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
        const tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
        const teens = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
        
        if (num === 0) return 'Zero';
        if (num < 10) return ones[num];
        if (num < 20) return teens[num - 10];
        if (num < 100) return tens[Math.floor(num / 10)] + ' ' + ones[num % 10];
        if (num < 1000) return ones[Math.floor(num / 100)] + ' Hundred ' + convertNumberToWords(num % 100);
        if (num < 100000) return convertNumberToWords(Math.floor(num / 1000)) + ' Thousand ' + convertNumberToWords(num % 1000);
        return convertNumberToWords(Math.floor(num / 100000)) + ' Lakh ' + convertNumberToWords(num % 100000);
    }
</script>
