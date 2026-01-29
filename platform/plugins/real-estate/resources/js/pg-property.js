/**
 * PG (Paying Guest) Property Type Handler
 * Shows/hides PG-specific fields based on property type selection
 */

$(document).ready(function () {
    const $typeSelect = $('#type');
    const $periodFormGroup = $('.period-form-group');

    // PG-specific field containers (will be added to form)
    const pgFieldsSelector = '.pg-fields-container';

    function handlePropertyTypeChange() {
        const selectedType = $typeSelect.val();

        // Handle period field visibility (for Rent)
        if (selectedType === 'rent') {
            $periodFormGroup.removeClass('hidden');
        } else {
            $periodFormGroup.addClass('hidden');
        }

        // Handle PG fields visibility
        if (selectedType === 'pg') {
            $(pgFieldsSelector).removeClass('hidden').show();
            // Make PG-specific fields required
            $(pgFieldsSelector).find('[data-pg-required]').attr('required', true);
        } else {
            $(pgFieldsSelector).addClass('hidden').hide();
            // Remove required attribute from PG fields
            $(pgFieldsSelector).find('[data-pg-required]').removeAttr('required');
        }
    }

    // Handle type change
    $typeSelect.on('change', handlePropertyTypeChange);

    // Handle pricing model change
    $(document).on('change', '#pricing_model', function () {
        const pricingModel = $(this).val();
        const $pricePerBed = $('.price-per-bed-group');
        const $pricePerRoom = $('.price-per-room-group');

        if (pricingModel === 'per_bed') {
            $pricePerBed.show().find('input').attr('required', true);
            $pricePerRoom.hide().find('input').removeAttr('required');
        } else if (pricingModel === 'per_room') {
            $pricePerRoom.show().find('input').attr('required', true);
            $pricePerBed.hide().find('input').removeAttr('required');
        } else if (pricingModel === 'both') {
            $pricePerBed.show().find('input').attr('required', true);
            $pricePerRoom.show().find('input').attr('required', true);
        }
    });

    // Handle food included checkbox
    $(document).on('change', '#food_included', function () {
        const $foodDetails = $('.food-details-group');
        if ($(this).is(':checked')) {
            $foodDetails.show();
        } else {
            $foodDetails.hide();
        }
    });

    // Initialize on page load
    handlePropertyTypeChange();
    $('#pricing_model').trigger('change');
    $('#food_included').trigger('change');
});
