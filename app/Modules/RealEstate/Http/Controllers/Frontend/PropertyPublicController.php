<?php

namespace App\Modules\RealEstate\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Modules\RealEstate\Models\Property;
use App\Modules\RealEstate\Repositories\PropertyRepository;
use Botble\Theme\Facades\Theme;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PropertyPublicController extends Controller
{
    public function __construct(
        private PropertyRepository $propertyRepository,
    ) {}

    /**
     * Display property detail page.
     */
    public function show(string $slug): Response
    {
        $property = $this->propertyRepository->findBySlug($slug);

        if (!$property) {
            throw new NotFoundHttpException('Property not found.');
        }

        // Load similar properties
        $similarProperties = $this->propertyRepository->findSimilar($property, 4);

        // SEO Meta
        $metaTitle = $property->seo_title ?? $property->name;
        $metaDescription = $property->seo_description ?? '';

        // Schema.org structured data
        $schemaData = $this->generateSchemaData($property);

        // Set SEO meta for the theme
        Theme::set('title', $metaTitle);
        Theme::set('description', $metaDescription);

        // Add mortgage calculator JavaScript
        Theme::asset()->container('footer')->writeScript('mortgage-calculator', '
            window.superCalc = function (btn) {
                var container = btn.closest(".mc-widget-final");
                if (!container) return;

                var total = parseFloat(container.querySelector(".mc-total").value) || 0;
                var down = parseFloat(container.querySelector(".mc-down").value) || 0;
                var rate = parseFloat(container.querySelector(".mc-rate").value) || 0;
                var term = parseFloat(container.querySelector(".mc-term").value) || 0;
                var rent = parseFloat(container.querySelector(".mc-rent").value) || 0;

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

                if (container.querySelector(".mc-emi")) container.querySelector(".mc-emi").innerText = Math.round(emi).toLocaleString("en-US");
                if (container.querySelector(".mc-interest")) container.querySelector(".mc-interest").innerText = Math.round(totalInterest).toLocaleString("en-US");
                if (container.querySelector(".mc-payback")) container.querySelector(".mc-payback").innerText = Math.round(totalPayback).toLocaleString("en-US");
                if (container.querySelector(".mc-yield")) container.querySelector(".mc-yield").innerText = yieldVal.toFixed(2) + "%";

                var cfEl = container.querySelector(".mc-cashflow");
                if (cfEl) {
                    cfEl.innerText = Math.round(cashFlow).toLocaleString("en-US");
                    cfEl.style.color = cashFlow >= 0 ? "#25d366" : "#ff5a5f";
                }
            };

            window.toggleAmortization = function (btn) {
                var container = btn.closest(".mc-widget-final");
                var scheduleDiv = container.querySelector(".amortization-schedule");
                
                if (!scheduleDiv) return;
                
                if (scheduleDiv.style.display === "none" || scheduleDiv.style.display === "") {
                    scheduleDiv.style.display = "block";
                    btn.innerHTML = "<i class=\"fa fa-table\"></i> Hide Amortization Schedule";
                    window.generateAmortizationSchedule(container);
                } else {
                    scheduleDiv.style.display = "none";
                    btn.innerHTML = "<i class=\"fa fa-table\"></i> View Amortization Schedule";
                }
            };

            window.generateAmortizationSchedule = function (container) {
                var total = parseFloat(container.querySelector(".mc-total").value) || 0;
                var down = parseFloat(container.querySelector(".mc-down").value) || 0;
                var rate = parseFloat(container.querySelector(".mc-rate").value) || 0;
                var term = parseFloat(container.querySelector(".mc-term").value) || 0;
                
                var principal = total - down;
                var monthlyRate = rate / 100 / 12;
                var numberOfPayments = term * 12;
                var emi = 0;
                
                if (principal > 0 && monthlyRate > 0 && numberOfPayments > 0) {
                    emi = principal * monthlyRate * Math.pow(1 + monthlyRate, numberOfPayments) / (Math.pow(1 + monthlyRate, numberOfPayments) - 1);
                }
                
                var tbody = container.querySelector(".amortization-body");
                if (!tbody) return;
                
                tbody.innerHTML = "";
                
                if (emi <= 0 || principal <= 0) {
                    tbody.innerHTML = "<tr><td colspan=\"4\" style=\"text-align: center; padding: 20px;\">Please calculate mortgage first</td></tr>";
                    return;
                }
                
                var balance = principal;
                var currentYear = 1;
                var yearlyInterest = 0;
                var yearlyPrincipal = 0;
                
                for (var month = 1; month <= numberOfPayments; month++) {
                    var interestPayment = balance * monthlyRate;
                    var principalPayment = emi - interestPayment;
                    balance = balance - principalPayment;
                    
                    yearlyInterest += interestPayment;
                    yearlyPrincipal += principalPayment;
                    
                    if (month % 12 === 0 || month === numberOfPayments) {
                        var row = document.createElement("tr");
                        row.innerHTML = 
                            "<td style=\"padding: 8px; border-bottom: 1px solid #eee;\">" + currentYear + "</td>" +
                            "<td style=\"padding: 8px; border-bottom: 1px solid #eee;\">" + Math.round(yearlyInterest).toLocaleString("en-US") + "</td>" +
                            "<td style=\"padding: 8px; border-bottom: 1px solid #eee;\">" + Math.round(yearlyPrincipal).toLocaleString("en-US") + "</td>" +
                            "<td style=\"padding: 8px; border-bottom: 1px solid #eee;\">" + Math.round(Math.max(0, balance)).toLocaleString("en-US") + "</td>";
                        tbody.appendChild(row);
                        
                        currentYear++;
                        yearlyInterest = 0;
                        yearlyPrincipal = 0;
                    }
                }
            };

            setTimeout(function () {
                var btns = document.querySelectorAll(".mc-widget-final button");
                for (var i = 0; i < btns.length; i++) {
                    var btn = btns[i];
                    if (btn.innerText.includes("Calculate") || btn.textContent.includes("Calculate")) {
                        window.superCalc(btn);
                    }
                }
            }, 1500);
        ');
        
        return Theme::scope('real-estate.property-detail', compact(
            'property',
            'similarProperties',
            'metaTitle',
            'metaDescription',
            'schemaData'
        ), 'realestate::frontend.properties.show')->render();
    }

    /**
     * Generate Schema.org structured data for property.
     */
    private function generateSchemaData(Property $property): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'RealEstateListing',
            'name' => $property->name,
            'description' => $property->description,
            'url' => route('properties.show', $property->slug),
            'offers' => [
                '@type' => 'Offer',
                'price' => $property->price,
                'priceCurrency' => 'INR',
            ],
        ];

        // Add address
        if ($property->address || $property->city) {
            $schema['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => $property->address,
                'addressLocality' => $property->city,
                'addressRegion' => $property->state,
                'addressCountry' => $property->country ?? 'IN',
            ];
        }

        // Add images
        if ($property->images && count($property->images) > 0) {
            $schema['image'] = array_map(function ($image) {
                return asset('storage/' . $image);
            }, $property->images);
        }

        // Add geo coordinates
        if ($property->latitude && $property->longitude) {
            $schema['geo'] = [
                '@type' => 'GeoCoordinates',
                'latitude' => $property->latitude,
                'longitude' => $property->longitude,
            ];
        }

        return $schema;
    }
}
