document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    const factors = {
        square_feet: 1,
        square_meter: 10.763915,
        square_yard: 9,
        square_inch: 0.00694444,
        square_centimeter: 0.00107639,
        square_kilometer: 10763910.4,
        square_mile: 27878400,
        acre: 43560,
        hectare: 107639.104,
        guntha: 1089,
        ground: 2400,
        bigha: 14400,
        square_karam: 30.25,
        murabba: 2722500,
        decimal: 435.6,
        lessa: 144,
        cent: 435.6,
        biswa_kacha: 1350,
        marla: 272.25,
        chatak: 45,
        dhur: 68.0625,
        biswa: 1350,
        kanal: 5445,
        gaj: 9,
        killa: 43560,
        pura: 87120,
        katha: 720
    };

    const stateBighaFactors = {
        andhra_pradesh: 14400,
        assam: 14400,
        bihar: 13680,
        gujarat: 17424,
        haryana: 10890,
        himachal_pradesh: 8712,
        jammu_and_kashmir: 10890,
        jharkhand: 13680,
        karnataka: 14400,
        kerala: 14400,
        madhya_pradesh: 12000,
        maharashtra: 14400,
        manipur: 14400,
        odisha: 14400,
        punjab: 10890,
        rajasthan: 27225,
        tamil_nadu: 14400,
        telangana: 14400,
        tripura: 14400,
        uttar_pradesh: 13680,
        uttarakhand: 6804,
        west_bengal: 14400
    };

    const fromValue = document.getElementById('from_value');
    const fromUnit = document.getElementById('from_unit');
    const toValue = document.getElementById('to_value');
    const toUnit = document.getElementById('to_unit');
    const stateSelect = document.getElementById('state_select');
    const swapBtn = document.getElementById('swap_units');
    
    const resultFromValue = document.getElementById('result_from_value');
    const resultFromUnit = document.getElementById('result_from_unit');
    const resultToValue = document.getElementById('result_to_value');
    const resultToUnit = document.getElementById('result_to_unit');

    if (!fromValue || !fromUnit || !toValue || !toUnit) {
        console.error('Area Converter: Elements not found');
        return;
    }

    function getUnitName(unitKey) {
        const fromSelect = document.getElementById('from_unit');
        if (!fromSelect) return unitKey;
        const option = fromSelect.querySelector('option[value="' + unitKey + '"]');
        return option ? option.textContent : unitKey;
    }

    function getUnitFactor(unit, state) {
        if (unit === 'bigha' && state && stateBighaFactors[state]) {
            return stateBighaFactors[state];
        }
        return factors[unit] || 1;
    }

    function performConversion(value, fromUnitVal, toUnitVal, state) {
        const fromFactor = getUnitFactor(fromUnitVal, state);
        const toFactor = getUnitFactor(toUnitVal, state);
        return (value * fromFactor) / toFactor;
    }

    function formatNumber(num) {
        if (num === 0) return '0';
        if (num < 0.000001) return num.toExponential(4);
        if (num < 0.01) return num.toFixed(8).replace(/\.?0+$/, '');
        return num.toLocaleString('en-IN', { maximumFractionDigits: 6, minimumFractionDigits: 0 });
    }

    function convertArea() {
        const value = parseFloat(fromValue.value) || 0;
        const from = fromUnit.value;
        const to = toUnit.value;
        const state = stateSelect ? stateSelect.value : '';

        const result = performConversion(value, from, to, state);
        const formattedValue = formatNumber(result);
        
        toValue.textContent = formattedValue;
        
        if (resultFromValue) resultFromValue.textContent = value;
        if (resultFromUnit) resultFromUnit.textContent = getUnitName(from);
        if (resultToValue) resultToValue.textContent = formattedValue;
        if (resultToUnit) resultToUnit.textContent = getUnitName(to);
    }

    function swapUnits() {
        const temp = fromUnit.value;
        fromUnit.value = toUnit.value;
        toUnit.value = temp;
        convertArea();
    }

    fromValue.addEventListener('input', convertArea);
    fromUnit.addEventListener('change', convertArea);
    toUnit.addEventListener('change', convertArea);
    if (stateSelect) stateSelect.addEventListener('change', convertArea);
    if (swapBtn) swapBtn.addEventListener('click', swapUnits);

    convertArea();
    console.log('Area Converter: Ready');
});
