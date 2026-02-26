@php
    Theme::layout('full-width');
    Theme::set('breadcrumbEnabled', 'no');

    Theme::asset()->usePath()->add('fancybox', 'plugins/fancybox/jquery.fancybox.min.css');
    Theme::asset()->container('footer')->usePath()->add('fancybox', 'plugins/fancybox/jquery.fancybox.min.js');
    Theme::asset()->usePath()->add('leaflet', 'plugins/leaflet/leaflet.css');
    Theme::asset()->container('footer')->usePath()->add('leaflet', 'plugins/leaflet/leaflet.js');
    
    // UI/UX Improvements for detail pages
    Theme::asset()->usePath()->add('property-detail-fixes', 'css/property-detail-fixes.css');

    $style = theme_option('real_estate_property_detail_layout', 1);
    $style = in_array($style, range(1, 4)) ? $style : 1;
    Theme::set('pageTitle', $property->name);

    // Mortgage Calculator JavaScript
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
@endphp

@include(Theme::getThemeNamespace("views.real-estate.single-layouts.style-$style"), ['model' => $property])

<style>
/* Force override margin and UI issues */
.flat-property-detail {
    padding-top: 30px !important;
}

.flat-property-detail .header-property-detail {
    margin: 0 0 30px 0 !important;
    padding: 30px !important;
    border-radius: 16px !important;
    background-color: #fff !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
}

/* Fix Overview Section */
.single-property-overview {
    background: #fff !important;
    padding: 30px !important;
    border-radius: 16px !important;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06) !important;
    margin-bottom: 40px !important;
}

.single-property-overview .title {
    font-size: 22px !important;
    font-weight: 700 !important;
    margin-bottom: 24px !important;
    color: #1a1a1a !important;
}

.single-property-overview .info-box {
    gap: 16px !important;
}

.single-property-overview .info-box .item {
    display: flex !important;
    align-items: center !important;
    gap: 16px !important;
    padding: 20px !important;
    background: #f8f9fa !important;
    border-radius: 12px !important;
    border: 1px solid #e5e7eb !important;
    transition: all 0.3s ease !important;
}

.single-property-overview .info-box .item:hover {
    background: #f3f4f6 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08) !important;
}

.single-property-overview .info-box .box-icon {
    width: 48px !important;
    height: 48px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    background: #fff !important;
    border-radius: 10px !important;
    color: #3b82f6 !important;
    font-size: 22px !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.06) !important;
    flex-shrink: 0 !important;
}

.single-property-overview .info-box .content {
    display: flex !important;
    flex-direction: column !important;
    gap: 4px !important;
}

.single-property-overview .info-box .label {
    font-size: 12px !important;
    color: #6b7280 !important;
    font-weight: 500 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
}

.single-property-overview .info-box .content span:last-child {
    font-size: 15px !important;
    font-weight: 600 !important;
    color: #111827 !important;
}

/* Description Section */
.single-property-desc {
    background: #fff !important;
    padding: 30px !important;
    border-radius: 16px !important;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06) !important;
    margin-bottom: 40px !important;
}

.single-property-desc .title {
    font-size: 22px !important;
    font-weight: 700 !important;
    margin-bottom: 20px !important;
    color: #1a1a1a !important;
}

/* Element Spacing */
.single-property-element {
    margin-bottom: 40px !important;
    padding-bottom: 40px !important;
    border-bottom: 1px solid #e5e7eb !important;
}

.single-property-element:last-child {
    border-bottom: none !important;
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
}

/* Responsive */
@media (max-width: 991px) {
    .flat-property-detail .header-property-detail {
        padding: 20px !important;
        margin: 0 0 20px 0 !important;
    }
    
    .single-property-overview,
    .single-property-desc {
        padding: 20px !important;
    }
    
    .single-property-overview .info-box .item {
        padding: 16px !important;
    }
}
</style>

<template id="map-popup-content">
    <div class="map-listing-item">
        <div class="inner-box">
            <div class="image-box">
                <a href="{{ $property->url }}">
                    {{ RvMedia::image($property->image_thumb, $property->name) }}
                </a>
                {!! BaseHelper::clean($property->status_html) !!}
            </div>
            <div class="content">
                @if($property->short_address)
                    <p class="location">
                        <x-core::icon name="ti ti-map-pin" />
                        {{ $property->short_address }}
                    </p>
                @endif
                <div class="title">
                    <a href="{{ $property->url }}" title="{{ $property->name }}">
                        {{ $property->name }}
                    </a>
                </div>
                @if (!setting('real_estate_hide_price', false))
                    <div class="price">{{ $property->price_html }}</div>
                @endif
                <ul class="list-info">
                    @if ($property->number_bedroom)
                        <li>
                            <x-core::icon name="ti ti-bed" />
                            {{ number_format($property->number_bedroom) }}
                        </li>
                    @endif

                    @if ($property->number_bathroom)
                        <li>
                            <x-core::icon name="ti ti-bath" />
                            {{ number_format($property->number_bathroom) }}
                        </li>
                    @endif

                    @if ($property->square)
                        <li>
                            <x-core::icon name="ti ti-ruler" />
                            {{ $property->square_text }}
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</template>
