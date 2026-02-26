<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rent Agreement - {{ $landlord_name }} & {{ $tenant_name }}</title>
    <style>
        @page {
            margin: 20px;
        }
        @page :first {
            margin-top: 520px; /* Half page space for stamp paper on first page */
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 14px;
            line-height: 1.8;
            color: #333;
            padding: 40px;
            margin: 0;
        }
        .stamp-paper-space {
            height: 480px; /* Half page - approx 170mm for stamp paper */
            width: 100%;
            border: 2px dashed #999;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            page-break-after: avoid;
        }
        .stamp-paper-label {
            color: #666;
            font-size: 16px;
            text-align: center;
        }
        .stamp-paper-label .main-text {
            font-size: 18px;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        .content-start {
            page-break-before: auto;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 12px;
            color: #666;
        }
        p {
            text-align: justify;
            margin-bottom: 15px;
        }
        strong {
            font-weight: bold;
        }
        .signature-section {
            margin-top: 50px;
        }
        .signature-block {
            width: 45%;
            display: inline-block;
            vertical-align: top;
        }
        .signature-line {
            border-bottom: 1px solid #333;
            padding-bottom: 5px;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .witness-section {
            margin-top: 30px;
        }
        @media print {
            body {
                padding: 20px;
            }
            .stamp-paper-space {
                border: none; /* Hide border when printing */
                height: 480px; /* Keep half page space */
            }
            .stamp-paper-label {
                display: none; /* Hide label when printing */
            }
        }
    </style>
</head>
<body>
    <!-- Stamp Paper Space - First Page Only -->
    <div class="stamp-paper-space">
        <div class="stamp-paper-label">
            <span class="main-text">📜 STAMP PAPER SPACE</span>
            <span>Leave this area blank for physical stamp paper attachment</span><br>
            <span style="font-size: 12px;">(Approx. half page - attach stamp paper here)</span>
        </div>
    </div>

    <!-- Agreement Content Starts Here -->
    <div class="content-start">
        <div class="header">
            <h1>RENT AGREEMENT</h1>
            <p>Generated on {{ $generatedDate ?? now()->format('F d, Y') }}</p>
        </div>

    <p>
        This agreement made on this <strong>{{ date('jS \d\a\y \o\f F, Y', strtotime($agreement_start_date)) }}</strong> between <strong>{{ $landlord_name }}</strong>, with <strong>{{ $landlord_phone }}</strong> as Mobile Number, residing at <strong>{{ $landlord_address }}, {{ $landlord_city }}, {{ $states[$landlord_state] ?? $landlord_state }} - {{ $landlord_pincode }}</strong>, hereinafter referred to as the <strong>'LESSOR'</strong> of the One Part AND <strong>{{ $tenant_name }}</strong>, with <strong>{{ $tenant_phone }}</strong> as Mobile Number, residing at <strong>{{ $tenant_address }}, {{ $tenant_city }}, {{ $states[$tenant_state] ?? $tenant_state }} - {{ $tenant_pincode }}</strong>, hereinafter referred to as the <strong>'LESSEE(s)'</strong> of the other Part;
    </p>

    <p>
        <strong>WHEREAS</strong> the Lessor is the lawful owner of, and otherwise well sufficiently entitled to, <strong>{{ $property_address }}, {{ $property_city }}, {{ $states[$property_state] ?? $property_state }} - {{ $property_pincode }}</strong>, and comprising of <strong>{{ $property_description ?: 'the property' }}</strong> present in <strong>Floor {{ $floor_number ?? 'N/A' }}</strong>, {{ ($parking ?? 'without_parking') == 'with_parking' ? 'with Parking' : 'without Parking' }} hereinafter referred to as the <strong>'said premises'</strong>.
    </p>

    <p>
        <strong>AND WHEREAS</strong> at the request of the Lessee, the Lessor has agreed to let the said premises to the tenant for a term of <strong>{{ $duration }}</strong> commencing from <strong>{{ date('Y-m-d', strtotime($agreement_start_date)) }}</strong> in the manner hereinafter appearing.
    </p>

    <p>
        <strong>NOW THIS AGREEMENT WITNESSETH AND IT IS HEREBY AGREED BY AND BETWEEN THE PARTIES AS UNDER:</strong>
    </p>

    <p>
        That the Lessor hereby grant to the Lessee, the right to enter and use and remain in the said premises along with the existing fixtures and fittings listed in Annexure 1 to this Agreement and that the Lessee shall be entitled to peacefully possess and enjoy possession of the said premises for <strong>{{ $purpose ?? 'residential' }}</strong> use, and the other rights herein.
    </p>

    <p>
        That the lease hereby granted shall, unless cancelled earlier under any provision of this Agreement, remain in force for a period of <strong>{{ $duration }}</strong>.
    </p>

    <p>
        That the Lessee will have the option to terminate this lease by giving <strong>{{ $notice_period ?? 'One month' }}</strong> notice in writing to the Lessor.
    </p>

    <p>
        That the Lessee shall have no right to create any sub-lease or assign or transfer in any manner the lease or give to any one the possession of the said premises or any part thereof.
    </p>

    <p>
        That the Lessee shall use the said premises only for <strong>{{ $purpose ?? 'residential' }}</strong> purposes.
    </p>

    <p>
        That the Lessor shall, before handing over the said premises, ensure the working of sanitary, electrical and water supply connections and other fittings pertaining to the said premises. It is agreed that it shall be the responsibility of the Lessor for their return in the working condition at the time of re-possession of the said premises, subject to normal wear and tear.
    </p>

    <p>
        That the Lessee is not authorized to make any alteration in the construction of the said premises.
    </p>

    <p>
        That the day-to-day repair jobs shall be affected by the Lessee at his own cost, and any major repairs, either structural or to the electrical or water connection, plumbing leaks, water seepage shall be attended to by the Lessor. In the event of the Lessor failing to carry out the repairs on receiving notice from the Lessee, the Lessee shall undertake the necessary repairs and the Lessor will be liable to immediately reimburse costs incurred by the Lessee.
    </p>

    <p>
        That the Lessor or its duly authorized agent shall have the right to enter or upon the said premises or any part thereof at a mutually arranged convenient time for the purpose of inspection.
    </p>

    <p>
        That in consideration of use of the said premises the Lessee agrees that he shall pay to the Lessor during the period of this agreement, a monthly rent at the rate of <strong>Rs. {{ number_format($rent_amount) }} ({{ $rent_amount_words }})</strong>. The amount will be paid in advance on or before the <strong>{{ $rent_due_date ?? '5th' }}</strong> of every English calendar month.
    </p>

    <p>
        It is hereby agreed that in the event of default in payment of the rent for a consecutive period of three months the lessor shall be entitled to terminate the lease.
    </p>

    <p>
        That the Lessee has paid to the Lessor a sum of <strong>Rs. {{ number_format($security_deposit) }} ({{ $security_deposit_words }})</strong> as deposit, free of interest. The said deposit shall be returned to the Lessee simultaneously with the Lessee vacating the said premises. In the event of failure on the part of the Lessor to refund the said deposit amount to the Lessee as aforesaid, the Lessee shall be entitled to continue to use and occupy the said premises without payment of any rent until the Lessor refunds the said amount.
    </p>

    <p>
        That the Lessor shall be responsible for the payment of all taxes and levies pertaining to the said premises including but not limited to House Tax, Property Tax, other cesses, if any, and any other statutory taxes, levied by the Government or Governmental Departments. During the term of this Agreement, the Lessor shall comply with all rules, regulations and requirements of any statutory authority, local, state, and central government, and governmental departments in relation to the said premises.
    </p>

    <p>
        <strong>IN WITNESS WHEREOF</strong>, the parties hereto have set their hands on the day and year first hereinabove mentioned.
    </p>

    <div class="signature-section">
        <table style="width: 100%; margin-top: 50px;">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <div class="signature-line">Agreed & Accepted by the Lessor</div>
                    <p style="margin-top: 40px;"><strong>{{ $landlord_name }}</strong></p>
                    <p>Date: _______________</p>
                </td>
                <td style="width: 50%; vertical-align: top;">
                    <div class="signature-line">Agreed & Accepted by the Lessee</div>
                    <p style="margin-top: 40px;"><strong>{{ $tenant_name }}</strong></p>
                    <p>Date: _______________</p>
                </td>
            </tr>
        </table>

        <div class="witness-section">
            <p style="font-weight: bold; margin-bottom: 15px;">WITNESS:</p>
            <table style="width: 100%;">
                <tr>
                    <td style="width: 50%; vertical-align: top;">
                        <p style="margin-bottom: 30px;">1. _______________________</p>
                        <p>Name: _______________________</p>
                        <p>Address: _______________________</p>
                    </td>
                    <td style="width: 50%; vertical-align: top;">
                        <p style="margin-bottom: 30px;">2. _______________________</p>
                        <p>Name: _______________________</p>
                        <p>Address: _______________________</p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    </div> <!-- End of content-start -->
</body>
</html>
