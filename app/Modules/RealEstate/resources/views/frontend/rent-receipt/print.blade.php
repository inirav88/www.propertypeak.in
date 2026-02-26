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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .receipt-header {
            text-align: center;
            border-bottom: 3px solid #db1d23;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .receipt-header h1 {
            color: #db1d23;
            font-size: 28px;
            margin-bottom: 5px;
        }
        
        .receipt-header p {
            color: #666;
            font-size: 14px;
        }
        
        .receipt-number {
            text-align: right;
            margin-bottom: 20px;
            color: #666;
            font-size: 14px;
        }
        
        .receipt-body {
            margin-bottom: 30px;
        }
        
        .receipt-row {
            display: flex;
            margin-bottom: 15px;
            border-bottom: 1px dashed #eee;
            padding-bottom: 10px;
        }
        
        .receipt-label {
            width: 200px;
            font-weight: 600;
            color: #333;
        }
        
        .receipt-value {
            flex: 1;
            color: #555;
        }
        
        .amount-box {
            background: #f8f9fa;
            border: 2px solid #db1d23;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }
        
        .amount-box .amount {
            font-size: 32px;
            font-weight: bold;
            color: #db1d23;
        }
        
        .amount-box .amount-words {
            font-size: 16px;
            color: #666;
            margin-top: 10px;
        }
        
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
            padding-top: 30px;
        }
        
        .signature-box {
            text-align: center;
            width: 250px;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-bottom: 10px;
            padding-top: 10px;
        }
        
        .revenue-stamp {
            position: absolute;
            top: 150px;
            right: 50px;
            width: 80px;
            height: 100px;
            border: 2px solid #c41e3a;
            border-radius: 5px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: white;
            transform: rotate(-15deg);
        }
        
        .revenue-stamp .india {
            font-size: 8px;
            color: #c41e3a;
            font-weight: bold;
        }
        
        .revenue-stamp .revenue {
            font-size: 9px;
            color: #c41e3a;
            font-weight: bold;
        }
        
        .revenue-stamp .amount {
            font-size: 12px;
            color: #c41e3a;
            font-weight: bold;
        }
        
        .revenue-stamp .one-rupee {
            font-size: 20px;
            color: #c41e3a;
            font-weight: bold;
        }
        
        .disclaimer {
            margin-top: 30px;
            padding: 15px;
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            font-size: 12px;
            color: #856404;
        }
        
        .print-actions {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            background: white;
        }
        
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 0 10px;
        }
        
        .btn-print {
            background: #db1d23;
            color: white;
        }
        
        .btn-download {
            background: #28a745;
            color: white;
        }
        
        .btn-close {
            background: #6c757d;
            color: white;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .receipt-container {
                box-shadow: none;
                padding: 20px;
            }
            
            .print-actions {
                display: none;
            }
            
            .revenue-stamp {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
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
            <div class="amount">ONE RUPEE</div>
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
                <div class="receipt-label">Received From:</div>
                <div class="receipt-value">{{ $tenant_name }}</div>
            </div>
            
            <div class="receipt-row">
                <div class="receipt-label">Received By:</div>
                <div class="receipt-value">{{ $landlord_name }}</div>
            </div>
            
            @if($landlord_pan)
            <div class="receipt-row">
                <div class="receipt-label">Landlord PAN:</div>
                <div class="receipt-value">{{ $landlord_pan }}</div>
            </div>
            @endif
            
            <div class="receipt-row">
                <div class="receipt-label">Property Address:</div>
                <div class="receipt-value">{{ $property_address }}</div>
            </div>
            
            <div class="receipt-row">
                <div class="receipt-label">Rent Period:</div>
                <div class="receipt-value">{{ $rent_period }}</div>
            </div>
            
            <div class="receipt-row">
                <div class="receipt-label">Payment Method:</div>
                <div class="receipt-value">{{ $payment_method }}</div>
            </div>
            
            <div class="receipt-row">
                <div class="receipt-label">Payment Date:</div>
                <div class="receipt-value">{{ $payment_date }}</div>
            </div>
        </div>
        
        <div class="amount-box">
            <div class="amount">₹{{ $rent_amount }}</div>
            <div class="amount-words">{{ $rent_amount_words }}</div>
        </div>
        
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line">
                    <p style="margin-top: 60px;">Tenant Signature</p>
                </div>
                <p style="font-size: 12px; color: #666;">{{ $tenant_name }}</p>
            </div>
            
            <div class="signature-box">
                <div class="signature-line">
                    <p style="margin-top: 60px;">Landlord Signature</p>
                </div>
                <p style="font-size: 12px; color: #666;">{{ $landlord_name }}</p>
            </div>
        </div>
        
        <div class="disclaimer">
            <strong>Important:</strong> This is a computer-generated receipt. Both parties should sign the receipt for it to be valid. 
            Revenue stamp (₹1) is mandatory for cash payments exceeding ₹5,000. 
            Landlord's PAN is required if annual rent exceeds ₹1,00,000.
        </div>
    </div>
    
    <div class="print-actions">
        <button class="btn btn-print" onclick="window.print()">
            <i class="fas fa-print"></i> Print Receipt
        </button>
        <a href="{{ route('rent-receipt.index') }}" class="btn btn-close">
            <i class="fas fa-arrow-left"></i> Generate New Receipt
        </a>
    </div>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</body>
</html>
