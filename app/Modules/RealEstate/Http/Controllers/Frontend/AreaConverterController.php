<?php

namespace App\Modules\RealEstate\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Botble\Theme\Facades\Theme;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AreaConverterController extends Controller
{
    /**
     * Area units with their conversion factors to square feet
     */
    private array $units = [
        'square_feet' => ['name' => 'Square Feet', 'factor' => 1],
        'square_meter' => ['name' => 'Square Meter', 'factor' => 10.763915],
        'square_yard' => ['name' => 'Square Yard', 'factor' => 9],
        'square_inch' => ['name' => 'Square Inch', 'factor' => 0.00694444],
        'square_centimeter' => ['name' => 'Square Centimeter', 'factor' => 0.00107639],
        'square_kilometer' => ['name' => 'Square Kilometer', 'factor' => 10763910.4],
        'square_mile' => ['name' => 'Square Mile', 'factor' => 27878400],
        'acre' => ['name' => 'Acre', 'factor' => 43560],
        'hectare' => ['name' => 'Hectare', 'factor' => 107639.104],
        'guntha' => ['name' => 'Guntha', 'factor' => 1089],
        'ground' => ['name' => 'Ground', 'factor' => 2400],
        'bigha' => ['name' => 'Bigha', 'factor' => 14400], // Standard Bigha
        'square_karam' => ['name' => 'Square Karam', 'factor' => 30.25],
        'murabba' => ['name' => 'Murabba', 'factor' => 2722500],
        'decimal' => ['name' => 'Decimal', 'factor' => 435.6],
        'lessa' => ['name' => 'Lessa', 'factor' => 144],
        'cent' => ['name' => 'Cent', 'factor' => 435.6],
        'biswa_kacha' => ['name' => 'Biswa Kacha', 'factor' => 1350],
        'marla' => ['name' => 'Marla', 'factor' => 272.25],
        'chatak' => ['name' => 'Chatak', 'factor' => 45],
        'dhur' => ['name' => 'Dhur', 'factor' => 68.0625],
        'biswa' => ['name' => 'Biswa', 'factor' => 1350],
        'kanal' => ['name' => 'Kanal', 'factor' => 5445],
        'gaj' => ['name' => 'Gaj', 'factor' => 9],
        'killa' => ['name' => 'Killa', 'factor' => 43560],
        'pura' => ['name' => 'Pura', 'factor' => 87120],
        'katha' => ['name' => 'Katha', 'factor' => 720],
    ];

    /**
     * State-wise Bigha conversion factors (in square feet)
     */
    private array $stateBighaFactors = [
        'andhra_pradesh' => ['bigha' => 14400],
        'assam' => ['bigha' => 14400],
        'bihar' => ['bigha' => 13680],
        'gujarat' => ['bigha' => 17424],
        'haryana' => ['bigha' => 10890],
        'himachal_pradesh' => ['bigha' => 8712],
        'jammu_and_kashmir' => ['bigha' => 10890],
        'jharkhand' => ['bigha' => 13680],
        'karnataka' => ['bigha' => 14400],
        'kerala' => ['bigha' => 14400],
        'madhya_pradesh' => ['bigha' => 12000],
        'maharashtra' => ['bigha' => 14400],
        'manipur' => ['bigha' => 14400],
        'odisha' => ['bigha' => 14400],
        'punjab' => ['bigha' => 10890],
        'rajasthan' => ['bigha' => 27225], // Pucca Bigha
        'tamil_nadu' => ['bigha' => 14400],
        'telangana' => ['bigha' => 14400],
        'tripura' => ['bigha' => 14400],
        'uttar_pradesh' => ['bigha' => 13680],
        'uttarakhand' => ['bigha' => 6804],
        'west_bengal' => ['bigha' => 14400],
    ];

    /**
     * List of Indian states
     */
    private array $states = [
        'andhra_pradesh' => 'Andhra Pradesh',
        'assam' => 'Assam',
        'bihar' => 'Bihar',
        'gujarat' => 'Gujarat',
        'haryana' => 'Haryana',
        'himachal_pradesh' => 'Himachal Pradesh',
        'jammu_and_kashmir' => 'Jammu and Kashmir',
        'jharkhand' => 'Jharkhand',
        'karnataka' => 'Karnataka',
        'kerala' => 'Kerala',
        'madhya_pradesh' => 'Madhya Pradesh',
        'maharashtra' => 'Maharashtra',
        'manipur' => 'Manipur',
        'odisha' => 'Odisha',
        'punjab' => 'Punjab',
        'rajasthan' => 'Rajasthan',
        'tamil_nadu' => 'Tamil Nadu',
        'telangana' => 'Telangana',
        'tripura' => 'Tripura',
        'uttar_pradesh' => 'Uttar Pradesh',
        'uttarakhand' => 'Uttarakhand',
        'west_bengal' => 'West Bengal',
    ];

    /**
     * Show area converter page (Full Page)
     */
    public function index()
    {
        Theme::set('title', 'Land Area Converter - Convert Area Units Online');
        Theme::set('description', 'Free online land area converter. Convert between Square Feet, Square Meter, Acre, Bigha, Hectare, Guntha and 25+ other land measurement units used in India.');

        return Theme::of('realestate::frontend.area-converter.index', [
            'units' => $this->units,
            'states' => $this->states,
            'isShortcode' => false,
        ])->render();
    }

    /**
     * Get content only for shortcode usage
     */
    public function getContent()
    {
        return view('realestate::frontend.area-converter.index', [
            'units' => $this->units,
            'states' => $this->states,
            'isShortcode' => true,
        ])->render();
    }

    /**
     * Convert area units
     */
    public function convert(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'value' => 'required|numeric|min:0',
            'from_unit' => 'required|string',
            'to_unit' => 'required|string',
            'state' => 'nullable|string',
        ]);

        $value = $validated['value'];
        $fromUnit = $validated['from_unit'];
        $toUnit = $validated['to_unit'];
        $state = $validated['state'] ?? null;

        $result = $this->performConversion($value, $fromUnit, $toUnit, $state);

        return response()->json([
            'success' => true,
            'result' => $result,
            'formatted_result' => number_format($result, 6),
        ]);
    }

    /**
     * Perform the conversion calculation
     */
    private function performConversion(float $value, string $fromUnit, string $toUnit, ?string $state = null): float
    {
        // Get conversion factor for from_unit
        $fromFactor = $this->getUnitFactor($fromUnit, $state);
        
        // Get conversion factor for to_unit
        $toFactor = $this->getUnitFactor($toUnit, $state);

        // Convert to square feet first, then to target unit
        $squareFeet = $value * $fromFactor;
        $result = $squareFeet / $toFactor;

        return $result;
    }

    /**
     * Get conversion factor for a unit
     */
    private function getUnitFactor(string $unit, ?string $state = null): float
    {
        // Check if state-specific factor exists for Bigha
        if ($unit === 'bigha' && $state && isset($this->stateBighaFactors[$state][$unit])) {
            return $this->stateBighaFactors[$state][$unit];
        }

        return $this->units[$unit]['factor'] ?? 1;
    }
}
