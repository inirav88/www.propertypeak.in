<?php

namespace App\Modules\RealEstate\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Botble\Theme\Facades\Theme;
use Illuminate\Http\Request;
use Illuminate\View\View;
// use Barryvdh\DomPDF\Facade\Pdf; // PDF library not installed yet

class RentReceiptController extends Controller
{
    /**
     * Show rent receipt generator form
     */
    public function index(): View
    {
        Theme::set('title', 'Rent Receipt Generator - Free House Rent Receipt for Income Tax');
        Theme::set('description', 'Generate free house rent receipts online for income tax HRA claims. Create professional rent receipts with revenue stamp for tax deductions.');
        
        return view('realestate::frontend.rent-receipt.index');
    }

    /**
     * Generate rent receipt PDF
     */
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'tenant_name' => 'required|string|max:255',
            'tenant_email' => 'nullable|email|max:255',
            'landlord_name' => 'required|string|max:255',
            'landlord_pan' => 'nullable|string|max:10',
            'property_address' => 'required|string|max:500',
            'rent_amount' => 'required|numeric|min:1',
            'rent_amount_words' => 'required|string|max:255',
            'rent_from' => 'required|date',
            'rent_to' => 'required|date|after_or_equal:rent_from',
            'payment_method' => 'required|in:cash,cheque,online,bank_transfer',
            'payment_date' => 'required|date',
            'receipt_number' => 'nullable|string|max:50',
            'include_revenue_stamp' => 'nullable|boolean',
        ]);

        // Format dates
        $fromDate = \Carbon\Carbon::parse($validated['rent_from'])->format('F d, Y');
        $toDate = \Carbon\Carbon::parse($validated['rent_to'])->format('F d, Y');
        $paymentDate = \Carbon\Carbon::parse($validated['payment_date'])->format('F d, Y');
        
        // Determine if revenue stamp is needed (> 5000 and cash payment)
        $needsRevenueStamp = ($validated['rent_amount'] > 5000 && $validated['payment_method'] === 'cash') || 
                             ($validated['include_revenue_stamp'] ?? false);

        $data = [
            'tenant_name' => $validated['tenant_name'],
            'tenant_email' => $validated['tenant_email'] ?? null,
            'landlord_name' => $validated['landlord_name'],
            'landlord_pan' => $validated['landlord_pan'] ?? null,
            'property_address' => $validated['property_address'],
            'rent_amount' => number_format($validated['rent_amount'], 2),
            'rent_amount_words' => $validated['rent_amount_words'],
            'rent_period' => $fromDate . ' to ' . $toDate,
            'payment_method' => $this->getPaymentMethodLabel($validated['payment_method']),
            'payment_date' => $paymentDate,
            'receipt_number' => $validated['receipt_number'] ?? 'RR-' . time(),
            'needs_revenue_stamp' => $needsRevenueStamp,
            'generated_date' => now()->format('F d, Y'),
        ];

        // PDF download option (requires barryvdh/laravel-dompdf package)
        // For now, we'll show the print view which users can save as PDF
        if ($request->has('download_pdf')) {
            // Return printable view with download headers
            return response()->view('realestate::frontend.rent-receipt.print', $data)
                ->header('Content-Type', 'text/html')
                ->header('Content-Disposition', 'attachment; filename="rent-receipt-' . $validated['receipt_number'] . '.html"');
        }

        // Return printable view
        return view('realestate::frontend.rent-receipt.print', $data);
    }

    /**
     * Get payment method label
     */
    private function getPaymentMethodLabel(string $method): string
    {
        return match($method) {
            'cash' => 'Cash',
            'cheque' => 'Cheque',
            'online' => 'Online Payment',
            'bank_transfer' => 'Bank Transfer',
            default => $method,
        };
    }
}
