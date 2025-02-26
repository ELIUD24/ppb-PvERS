$(document).ready(function () {

    var data = $('input[name="data[Aggregate][manager_initiated]"]').val();

    if (data != '1') {
        $('input[name="data[Aggregate][brand_name]"]').attr('disabled', true);
        $('input[name="data[Aggregate][inn_name]"]').attr('disabled', true);
        $('input[name="data[Aggregate][mah]"]').attr('disabled', true);
        $('input[name="data[Aggregate][local_technical]"]').attr('disabled', true);
        $('input[name="data[Aggregate][therapeutic_group]"]').attr('disabled', true);
        $('input[name="data[Aggregate][authorised_indications]"]').attr('disabled', true);
        $('input[name="data[Aggregate][form_strength]"]').attr('disabled', true);

    }
    $('.date-pick-field').datepicker({
        minDate: "-100Y", maxDate: "0",
        dateFormat: 'yy-mm-dd',
        showButtonPanel: true,
        changeMonth: true,
        changeYear: true,
        showAnim: 'show'
    });

});
