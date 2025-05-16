$(document).ready(function () {

    var data = $('input[name="data[Aggregate][manager_initiated]"]').val();

    $('.person-submit').on('change', function () {
        var pilih = $(this).val();
        if (pilih == 'Yes') {
            $('.diff:input').prop('disabled', false);
        } else {
            $('.diff:input').val('');
            $('.diff:input').prop('disabled', true);
        }
    });

    
    if ($("#AggregatePersonSubmittingNo").is(':checked')) { $('.diff:input').prop('disabled', true); }

    if (data != '1') {
        $('input[name="data[Aggregate][brand_name]"]').attr('disabled', true);
        $('input[name="data[Aggregate][inn_name]"]').attr('disabled', true);
        $('input[name="data[Aggregate][mah]"]').attr('disabled', true);
        $('input[name="data[Aggregate][local_technical]"]').attr('disabled', true);
        $('input[name="data[Aggregate][therapeutic_group]"]').attr('disabled', true);
        $('input[name="data[Aggregate][authorised_indications]"]').attr('disabled', true);
        $('textarea[name="data[Aggregate][authorised_indications]"]')
        .prop('disabled', true);
        $('input[name="data[Aggregate][form_strength]"]').attr('disabled', true);
        $('input[name="data[Aggregate][data_lock]"]').attr('disabled', true);
        $('input[name="data[Aggregate][next_data_lock]"]').attr('disabled', true);
        $('input[name="data[Aggregate][interval_code]"]').attr('disabled', true);
        $('input[name="data[Aggregate][date_of_birth]"]').attr('disabled', true);
        $('input[name="data[Aggregate][submission_frequency]"]').attr('disabled', true);
        $('input[name="data[Aggregate][data_interval]"]').attr('disabled', true);
        $('select[name="data[Aggregate][submission_frequency]"]').prop('disabled', true);

    }
    $('.date-pick-field').datepicker({
        minDate: "-100Y", maxDate: "0",
        dateFormat: 'yy-mm-dd',
        showButtonPanel: true,
        changeMonth: true,
        changeYear: true,
        showAnim: 'show'
    });
    $('.dateInput').datepicker({
        minDate: "-100Y", maxDate: "0",
        dateFormat: 'yy-mm-dd',
        showButtonPanel: true,
        changeMonth: true,
        changeYear: true,
        showAnim: 'show'
    });
    $('.dateInputFuture').datepicker({
        minDate: "0", maxDate: "10Y",
        dateFormat: 'yy-mm-dd',
        showButtonPanel: true,
        changeMonth: true,
        changeYear: true,
        showAnim: 'show'
    });
    
    

});
