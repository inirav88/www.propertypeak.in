<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent Receipt - {{ $receipt_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background: white;
            padding: 30px;
        }
        
        .receipt-container {
            width: 100%;
            background: white;
            padding: 30px;
            border: 2px solid #db1d23;
        }
        
        .receipt-header {
            text-align: center;
            border-bottom: 3px solid #db1d23;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        
        .receipt-header h1 {
            color: #db1d23;
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .receipt-header p {
            color: #666;
            font-size: 12px;
        }
        
        .receipt-number {
            text-align: right;
            margin-bottom: 20px;
            color: #666;
            font-size: 11px;
        }
        
        .receipt-row {
            margin-bottom: 12px;
            border-bottom: 1px dashed #eee;
            padding-bottom: 8px;
        }
        
        .receipt-label {
            display: inline-block;
            width: 150px;
            font-weight: bold;
            color: #333;
        }
        
        .receipt-value {
            color: #555;
        }
        
        .amount-box {
            background: #f8f9fa;
            border: 2px solid #db1d23;
            padding: 15px;
            text-align: center;
            margin: 25px 0;
        }
        
        .amount-box .amount {
            font-size: 28px;
            font-weight: bold;
            color: #db1d23;
        }
        
        .amount-box .amount-words {
            font-size: 14px;
            color: #666;
            margin-top: 8px;
        }
        
        .signature-section {
            margin-top: 50px;
            width: 100%;
        }
        
        .signature-box {
            display: inline-block;
            width: 45%;
            text-align: center;
        }
        
        .signature-box.right {
            float: right;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 50px;
            padding-top: 8px;
        }
        
        .revenue-stamp {
            position: absolute;
            top: 100px;
            right: 40px;
            width: 70px;
            height: 85px;
            border: 2px solid #c41e3a;
            border-radius: 3px;
            text-align: center;
            background: white;
            transform: rotate(-15deg);
        }
        
        .revenue-stamp .india {
            font-size: 7px;
            color: #c41e3a;
            font-weight: bold;
        }
        
        .revenue-stamp .revenue {
            font-size: 8px;
            color: #c41e3a;
            font-weight: bold;
        }
        
        .revenue-stamp .one-rupee {
            font-size: 18px;
            color: #c41e3a;
            font-weight: bold;
        }
        
        .disclaimer {
            margin-top: 30px;
            padding: 10px;
            background: #fff3cd;
            border-left: 3px solid #ffc107;
            font-size: 10px;
            color: #856404;
            clear: both;
        }
    </style>
</head>
<body>
    <div class="receipt-container" style="position: relative;">
        @if($needs_revenue_stamp)
        <div class="revenue-stamp">
            <div class="india">INDIA</div>
            <div class="revenue">REVENUE</div>
            <div class="one-rupee">₹1</div>
        </div>
        @endif
        
        <div class="receipt-header">
            <h1>HOUSE RENT RECEIPT</h1>
            <p>Official Receipt for Income Tax HRA Claim</p>
        </div>
        
        <div class="receipt-number">
            <strong>Receipt No:</strong> {{ $receipt_number }} | 
            <strong>Date:</strong> {{ $generated_date }}
        </div>
        
        <div class="receipt-body">
            <div class="receipt-row">
                <span class="receipt-label">Received From:</span>
                <span class="receipt-value">{{ $tenant_name }}</span>
            </div>
            
            <div class="receipt-row">
                <span class="receipt-label">Received By:</span>
                <span class="receipt-value">{{ $landlord_name }}</span>
            </div>
            
            @if($landlord_pan)
            <div class="receipt-row">
                <span class="receipt-label">Landlord PAN:</span>
                <span class="receipt-value">{{ $landlord_pan }}</span>
            </div>
            @endif
            
            <div class="receipt-row">
                <span class="receipt-label">Property Address:</span>
                <span class="receipt-value">{{ $property_address }}</span>
            </div>
            
            <div class="receipt-row">
                <span class="receipt-label">Rent Period:</span>
                <span class="receipt-value">{{ $rent_period }}</span>
            </div>
            
            <div class="receipt-row">
                <span class="receipt-label">Payment Method:</span>
                <span class="receipt-value">{{ $payment_method }}</span>
            </div>
            
            <div class="receipt-row">
                <span class="receipt-label">Payment Date:</span>
                <span class="receipt-value">{{ $payment_date }}</span>
            </div>
        </div>
        
        <div class="amount-box">
            <div class="amount">₹{{ $rent_amount }}</div>
            <div class="amount-words">{{ $rent_amount_words }}</div>
        </div>
        
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line">
                    <p style="margin-top: 5px; font-size: 11px;">Tenant Signature</p>
                    <p style="font-size: 10px; color: #666;">{{ $tenant_name }}</p>
                </div>
            </div>
            
            <div class="signature-box right">
                <div class="signature-line">
                    <p style="margin-top: 5px; font-size: 11px;">Landlord Signature</p>
                    <p style="font-size: 10px; color: #666;">{{ $landlord_name }}</p>
                </div>
            </div>
        </div>
        
        <div class="disclaimer">
            <strong>Important:</strong> This is a computer-generated receipt. Both parties should sign the receipt for it to be valid. 
            Revenue stamp (₹1) is mandatory for cash payments exceeding ₹5,000. 
            Landlord's PAN is required if annual rent exceeds ₹1,00,000.
        </div>
    </div>
</body>
</html>
