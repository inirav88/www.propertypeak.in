<?php

namespace App\Modules\RealEstate\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Botble\Theme\Facades\Theme;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class RentAgreementController extends Controller
{
    /**
     * All Indian states
     */
    private array $indianStates = [
        'andhra_pradesh' => 'Andhra Pradesh',
        'assam' => 'Assam',
        'bihar' => 'Bihar',
        'chhattisgarh' => 'Chhattisgarh',
        'goa' => 'Goa',
        'gujarat' => 'Gujarat',
        'haryana' => 'Haryana',
        'himachal_pradesh' => 'Himachal Pradesh',
        'jharkhand' => 'Jharkhand',
        'karnataka' => 'Karnataka',
        'kerala' => 'Kerala',
        'madhya_pradesh' => 'Madhya Pradesh',
        'maharashtra' => 'Maharashtra',
        'manipur' => 'Manipur',
        'meghalaya' => 'Meghalaya',
        'mizoram' => 'Mizoram',
        'nagaland' => 'Nagaland',
        'odisha' => 'Odisha',
        'punjab' => 'Punjab',
        'rajasthan' => 'Rajasthan',
        'sikkim' => 'Sikkim',
        'tamil_nadu' => 'Tamil Nadu',
        'telangana' => 'Telangana',
        'tripura' => 'Tripura',
        'uttar_pradesh' => 'Uttar Pradesh',
        'uttarakhand' => 'Uttarakhand',
        'west_bengal' => 'West Bengal',
        'andaman_nicobar' => 'Andaman and Nicobar Islands',
        'chandigarh' => 'Chandigarh',
        'dadra_nagar_haveli' => 'Dadra and Nagar Haveli and Daman and Diu',
        'delhi' => 'Delhi',
        'jammu_kashmir' => 'Jammu and Kashmir',
        'ladakh' => 'Ladakh',
        'lakshadweep' => 'Lakshadweep',
        'puducherry' => 'Puducherry',
    ];

    /**
     * Property types
     */
    private array $propertyTypes = [
        'residential' => 'Residential',
        'commercial' => 'Commercial',
        'industrial' => 'Industrial',
    ];

    /**
     * Agreement duration types
     */
    private array $agreementTypes = [
        '11_months' => '11 Months',
        '1_year' => '1 Year',
        '2_years' => '2 Years',
        '3_years' => '3 Years',
        '5_years' => '5 Years',
    ];

    /**
     * Show rent agreement form
     */
    public function index()
    {
	Theme::layout('full-width');
    Theme::set('pageTitle', 'Rent Agreement - Create Legal Rent Agreement Online');

return Theme::of('realestate::frontend.rent-agreement.index', [
    'states' => $this->indianStates,
    'propertyTypes' => $this->propertyTypes,
    'agreementTypes' => $this->agreementTypes,
])->render();
        
    }

    /**
     * Save agreement data to session
     */
    public function save(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'landlord_name' => 'required|string|max:255',
            'landlord_address' => 'required|string|max:500',
            'landlord_city' => 'required|string|max:100',
            'landlord_state' => 'required|string',
            'landlord_pincode' => 'required|string|max:10',
            'landlord_phone' => 'required|string|max:20',
            'landlord_email' => 'nullable|email|max:255',
            'tenant_name' => 'required|string|max:255',
            'tenant_address' => 'required|string|max:500',
            'tenant_city' => 'required|string|max:100',
            'tenant_state' => 'required|string',
            'tenant_pincode' => 'required|string|max:10',
            'tenant_phone' => 'required|string|max:20',
            'tenant_email' => 'nullable|email|max:255',
            'property_address' => 'required|string|max:500',
            'property_city' => 'required|string|max:100',
            'property_state' => 'required|string',
            'property_pincode' => 'required|string|max:10',
            'floor_number' => 'required|string|max:50',
            'property_type' => 'required|in:residential,commercial',
            'parking' => 'nullable|in:with_parking,without_parking',
            'property_description' => 'nullable|string|max:1000',
            'rent_amount' => 'required|numeric|min:1',
            'rent_amount_words' => 'required|string|max:255',
            'security_deposit' => 'required|numeric|min:0',
            'security_deposit_words' => 'required|string|max:255',
            'agreement_duration' => 'required|in:11_months,1_year,2_years,3_years,5_years',
            'agreement_start_date' => 'required|date',
            'rent_due_date' => 'required|in:1st,5th,10th,15th',
            'notice_period' => 'required|string|max:50',
            'purpose' => 'required|in:residential,commercial',
        ]);

        $request->session()->put('rent_agreement', $validated);

        return redirect()->route('rent-agreement.preview');
    }

    /**
     * Show agreement preview
     */
    public function preview(Request $request): View|RedirectResponse
    {
        $data = $request->session()->get('rent_agreement');

        if (empty($data)) {
            return redirect()->route('rent-agreement.index')
                ->with('error', 'Please fill in the agreement form first.');
        }

        $data['states'] = $this->indianStates;
        $data['propertyTypes'] = $this->propertyTypes;
        $data['agreementTypes'] = $this->agreementTypes;
        $data['duration'] = $this->getDurationText($data['agreement_duration']);
        $data['endDate'] = $this->calculateEndDate($data['agreement_start_date'], $data['agreement_duration']);

        Theme::layout('full-width');
        Theme::set('pageTitle', 'Rent Agreement Preview');

        return view('realestate::frontend.rent-agreement.preview', $data);
    }

    /**
     * Download agreement as PDF
     */
    public function download(Request $request)
    {
        $data = $request->session()->get('rent_agreement');

        if (empty($data)) {
            return redirect()->route('rent-agreement.index')
                ->with('error', 'Please fill in the agreement form first.');
        }

        $data['states'] = $this->indianStates;
        $data['propertyTypes'] = $this->propertyTypes;
        $data['agreementTypes'] = $this->agreementTypes;
        $data['duration'] = $this->getDurationText($data['agreement_duration']);
        $data['endDate'] = $this->calculateEndDate($data['agreement_start_date'], $data['agreement_duration']);
        $data['generatedDate'] = now()->format('F d, Y');

        // For now, return HTML view that can be printed to PDF
        return response()->view('realestate::frontend.rent-agreement.pdf', $data)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="rent-agreement-' . time() . '.html"');
    }

    /**
     * Get duration text
     */
    private function getDurationText(string $duration): string
    {
        return match($duration) {
            '11_months' => '11 Months',
            '1_year' => '1 Year',
            '2_years' => '2 Years',
            '3_years' => '3 Years',
            '5_years' => '5 Years',
            default => $duration,
        };
    }

    /**
     * Calculate agreement end date
     */
    private function calculateEndDate(string $startDate, string $duration): string
    {
        $start = Carbon::parse($startDate);

        return match($duration) {
            '11_months' => $start->copy()->addMonths(11)->format('F d, Y'),
            '1_year' => $start->copy()->addYear()->format('F d, Y'),
            '2_years' => $start->copy()->addYears(2)->format('F d, Y'),
            '3_years' => $start->copy()->addYears(3)->format('F d, Y'),
            '5_years' => $start->copy()->addYears(5)->format('F d, Y'),
            default => $start->copy()->addYear()->format('F d, Y'),
        };
    }
}
