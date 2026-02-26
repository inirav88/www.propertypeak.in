@php
    Theme::layout('full-width');
    Theme::set('pageTitle', __('Rent Agreement Preview'));
    Theme::set('breadcrumbEnabled', 'yes');
    Theme::set('breadcrumbBackgroundColor', '#f7f7f7');
@endphp

<section class="section-rent-agreement py-5 bg-surface">
    <div class="container">
        <!-- Header -->
        <div class="text-center mb-5">
            <h1 class="title">{{ __('Agreement Preview') }}</h1>
            <p class="text-muted">{{ __('Review your rent agreement before downloading.') }}</p>
        </div>

        <!-- Progress Steps -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="d-flex justify-content-center align-items-center flex-wrap">
                    <div class="step-item text-center px-2">
                        <div class="step-number" style="width: 50px; height: 50px; border-radius: 50%; background: #28a745; color: white; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-weight: 600;"><x-core::icon name="ti ti-check" /></div>
                        <div class="step-label mt-2" style="font-size: 14px; font-weight: 600; color: #28a745;">{{ __('Fill Details') }}</div>
                    </div>
                    <div class="step-line" style="width: 80px; height: 3px; background: linear-gradient(90deg, #28a745, var(--primary-color)); margin: 0 10px;"></div>
                    <div class="step-item text-center px-2">
                        <div class="step-number" style="width: 50px; height: 50px; border-radius: 50%; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-weight: 600;">2</div>
                        <div class="step-label mt-2" style="font-size: 14px; font-weight: 600; color: var(--primary-color);">{{ __('Preview') }}</div>
                    </div>
                    <div class="step-line" style="width: 80px; height: 3px; background: #e4e4e4; margin: 0 10px;"></div>
                    <div class="step-item text-center px-2">
                        <div class="step-number" style="width: 50px; height: 50px; border-radius: 50%; background: #e4e4e4; color: #666; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-weight: 600;">3</div>
                        <div class="step-label mt-2" style="font-size: 14px; color: #666;">{{ __('Download') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview Card -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="widget-box bg-white" style="border: 1px solid #e4e4e4; border-radius: 12px; overflow: hidden;">
                    <div style="background: linear-gradient(135deg, var(--primary-color), #cd380f); padding: 20px; display: flex; justify-content: space-between; align-items: center;">
                        <h5 style="color: white; margin: 0; display: flex; align-items: center; gap: 10px;">
                            <x-core::icon name="ti ti-file-text" style="width: 24px; height: 24px;" />
                            {{ __('Rent Agreement Preview') }}
                        </h5>
                        <span class="badge" style="background: white; color: var(--primary-color); padding: 8px 16px; border-radius: 20px; font-weight: 600;">{{ $duration }}</span>
                    </div>
                    <div class="p-4">
                        <!-- Agreement Document -->
                        <div id="agreement-document" class="p-4 bg-white" style="font-family: 'Times New Roman', Times, serif; line-height: 1.8; font-size: 14px; border: 1px solid #ddd; border-radius: 8px;">
                            
                            <!-- Stamp Paper Space Indicator -->
                            <div class="stamp-paper-indicator" style="height: 450px; border: 2px dashed #dc3545; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: 30px; background: #fff5f5;">
                                <div style="text-align: center; color: #dc3545;">
                                    <x-core::icon name="ti ti-file-certificate" style="width: 48px; height: 48px; margin-bottom: 10px;" />
                                    <div style="font-size: 16px; font-weight: bold;">STAMP PAPER SPACE</div>
                                    <div style="font-size: 13px;">Leave this area blank for physical stamp paper attachment</div>
                                    <div style="font-size: 11px; margin-top: 5px; opacity: 0.8;">(Half page blank space - stamp paper area)</div>
                                </div>
                            </div>
                            
                            <div class="text-center mb-4">
                                <h2 style="font-size: 20px; font-weight: bold; text-transform: uppercase; margin-bottom: 5px;">RENT AGREEMENT</h2>
                                <p style="font-size: 12px; color: #666;">Generated on {{ date('F d, Y') }}</p>
                            </div>

                            <p style="text-align: justify; margin-bottom: 15px;">
                                This agreement made on this <strong>{{ date('jS \d\a\y \o\f F, Y', strtotime($agreement_start_date)) }}</strong> between <strong>{{ $landlord_name }}</strong>, with <strong>{{ $landlord_phone }}</strong> as Mobile Number, residing at <strong>{{ $landlord_address }}, {{ $landlord_city }}, {{ $states[$landlord_state] ?? $landlord_state }} - {{ $landlord_pincode }}</strong>, hereinafter referred to as the <strong>'LESSOR'</strong> of the One Part AND <strong>{{ $tenant_name }}</strong>, with <strong>{{ $tenant_phone }}</strong> as Mobile Number, residing at <strong>{{ $tenant_address }}, {{ $tenant_city }}, {{ $states[$tenant_state] ?? $tenant_state }} - {{ $tenant_pincode }}</strong>, hereinafter referred to as the <strong>'LESSEE(s)'</strong> of the other Part;
                            </p>

                            <p style="text-align: justify; margin-bottom: 15px;">
                                <strong>WHEREAS</strong> the Lessor is the lawful owner of, and otherwise well sufficiently entitled to, <strong>{{ $property_address }}, {{ $property_city }}, {{ $states[$property_state] ?? $property_state }} - {{ $property_pincode }}</strong>, and comprising of <strong>{{ $property_description ?: 'the property' }}</strong> present in <strong>Floor {{ $floor_number }}</strong>, {{ $parking == 'with_parking' ? 'with Parking' : 'without Parking' }} hereinafter referred to as the <strong>'said premises'</strong>.
                            </p>

                            <p style="text-align: justify; margin-bottom: 15px;">
                                <strong>AND WHEREAS</strong> at the request of the Lessee, the Lessor has agreed to let the said premises to the tenant for a term of <strong>{{ $duration }}</strong> commencing from <strong>{{ date('Y-m-d', strtotime($agreement_start_date)) }}</strong> in the manner hereinafter appearing.
                            </p>

                            <p style="text-align: justify; margin-bottom: 15px;">
                                <strong>NOW THIS AGREEMENT WITNESSETH AND IT IS HEREBY AGREED BY AND BETWEEN THE PARTIES AS UNDER:</strong>
                            </p>

                            <p style="text-align: justify; margin-bottom: 15px;">
                                That the Lessor hereby grant to the Lessee, the right to enter and use and remain in the said premises along with the existing fixtures and fittings listed in Annexure 1 to this Agreement and that the Lessee shall be entitled to peacefully possess and enjoy possession of the said premises for <strong>{{ $purpose }}</strong> use, and the other rights herein.
                            </p>

                            <p style="text-align: justify; margin-bottom: 15px;">
                                That the lease hereby granted shall, unless cancelled earlier under any provision of this Agreement, remain in force for a period of <strong>{{ $duration }}</strong>.
                            </p>

                            <p style="text-align: justify; margin-bottom: 15px;">
                                That the Lessee will have the option to terminate this lease by giving <strong>{{ $notice_period }}</strong> notice in writing to the Lessor.
                            </p>

                            <p style="text-align: justify; margin-bottom: 15px;">
                                That the Lessee shall have no right to create any sub-lease or assign or transfer in any manner the lease or give to any one the possession of the said premises or any part thereof.
                            </p>

                            <p style="text-align: justify; margin-bottom: 15px;">
                                That the Lessee shall use the said premises only for <strong>{{ $purpose }}</strong> purposes.
                            </p>

                            <p style="text-align: justify; margin-bottom: 15px;">
                                That the Lessor shall, before handing over the said premises, ensure the working of sanitary, electrical and water supply connections and other fittings pertaining to the said premises. It is agreed that it shall be the responsibility of the Lessor for their return in the working condition at the time of re-possession of the said premises, subject to normal wear and tear.
                            </p>

                            <p style="text-align: justify; margin-bottom: 15px;">
                                That the Lessee is not authorized to make any alteration in the construction of the said premises.
                            </p>

                            <p style="text-align: justify; margin-bottom: 15px;">
                                That the day-to-day repair jobs shall be affected by the Lessee at his own cost, and any major repairs, either structural or to the electrical or water connection, plumbing leaks, water seepage shall be attended to by the Lessor. In the event of the Lessor failing to carry out the repairs on receiving notice from the Lessee, the Lessee shall undertake the necessary repairs and the Lessor will be liable to immediately reimburse costs incurred by the Lessee.
                            </p>

                            <p style="text-align: justify; margin-bottom: 15px;">
                                That the Lessor or its duly authorized agent shall have the right to enter or upon the said premises or any part thereof at a mutually arranged convenient time for the purpose of inspection.
                            </p>

                            <p style="text-align: justify; margin-bottom: 15px;">
                                That in consideration of use of the said premises the Lessee agrees that he shall pay to the Lessor during the period of this agreement, a monthly rent at the rate of <strong>Rs. {{ number_format($rent_amount) }} ({{ $rent_amount_words }})</strong>. The amount will be paid in advance on or before the <strong>{{ $rent_due_date }}</strong> of every English calendar month.
                            </p>

                            <p style="text-align: justify; margin-bottom: 15px;">
                                It is hereby agreed that in the event of default in payment of the rent for a consecutive period of three months the lessor shall be entitled to terminate the lease.
                            </p>

                            <p style="text-align: justify; margin-bottom: 15px;">
                                That the Lessee has paid to the Lessor a sum of <strong>Rs. {{ number_format($security_deposit) }} ({{ $security_deposit_words }})</strong> as deposit, free of interest. The said deposit shall be returned to the Lessee simultaneously with the Lessee vacating the said premises. In the event of failure on the part of the Lessor to refund the said deposit amount to the Lessee as aforesaid, the Lessee shall be entitled to continue to use and occupy the said premises without payment of any rent until the Lessor refunds the said amount.
                            </p>

                            <p style="text-align: justify; margin-bottom: 15px;">
                                That the Lessor shall be responsible for the payment of all taxes and levies pertaining to the said premises including but not limited to House Tax, Property Tax, other cesses, if any, and any other statutory taxes, levied by the Government or Governmental Departments. During the term of this Agreement, the Lessor shall comply with all rules, regulations and requirements of any statutory authority, local, state, and central government, and governmental departments in relation to the said premises.
                            </p>

                            <p style="text-align: justify; margin-bottom: 30px;">
                                <strong>IN WITNESS WHEREOF</strong>, the parties hereto have set their hands on the day and year first hereinabove mentioned.
                            </p>

                            <div style="margin-top: 50px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 40px;">
                                    <div style="width: 45%;">
                                        <p style="font-weight: bold; margin-bottom: 60px; border-bottom: 1px solid #333; padding-bottom: 5px;">Agreed & Accepted by the Lessor</p>
                                        <p style="margin: 0;"><strong>{{ $landlord_name }}</strong></p>
                                        <p style="margin: 0; font-size: 12px; color: #666;">Date: _______________</p>
                                    </div>
                                    <div style="width: 45%;">
                                        <p style="font-weight: bold; margin-bottom: 60px; border-bottom: 1px solid #333; padding-bottom: 5px;">Agreed & Accepted by the Lessee</p>
                                        <p style="margin: 0;"><strong>{{ $tenant_name }}</strong></p>
                                        <p style="margin: 0; font-size: 12px; color: #666;">Date: _______________</p>
                                    </div>
                                </div>

                                <div style="margin-top: 30px;">
                                    <p style="font-weight: bold; margin-bottom: 15px;">WITNESS:</p>
                                    <div style="display: flex; gap: 50px;">
                                        <div>
                                            <p style="margin-bottom: 30px;">1. _______________________</p>
                                            <p>Name: _______________________</p>
                                            <p>Address: _______________________</p>
                                        </div>
                                        <div>
                                            <p style="margin-bottom: 30px;">2. _______________________</p>
                                            <p>Name: _______________________</p>
                                            <p>Address: _______________________</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-center gap-3 mt-4 flex-wrap">
                            <a href="{{ route('rent-agreement.index') }}" class="tf-btn secondary" style="min-width: 180px;">
                                <x-core::icon name="ti ti-arrow-left" /> {{ __('Edit Details') }}
                            </a>
                            <button type="button" class="tf-btn" style="background: #17a2b8; color: white; min-width: 180px;" onclick="window.print()">
                                <x-core::icon name="ti ti-printer" /> {{ __('Print Agreement') }}
                            </button>
                            <form action="{{ route('rent-agreement.download') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="tf-btn primary" style="min-width: 180px;">
                                    <x-core::icon name="ti ti-download" /> {{ __('Download PDF') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #agreement-document, #agreement-document * {
        visibility: visible;
    }
    #agreement-document {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        padding: 20px !important;
        border: none !important;
    }
    /* Hide stamp paper indicator content but keep half page space */
    .stamp-paper-indicator {
        border: none !important;
        background: transparent !important;
        height: 450px !important;
    }
    .stamp-paper-indicator > div {
        display: none !important;
    }
}
</style>
