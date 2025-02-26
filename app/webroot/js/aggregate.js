$(document).ready(function () {


    $('#parentDetails').hide();
    $('#otherDetails').hide();
    $('#basicInformation').hide();

    var cache3 = {}, lastXhr;
    $("#AggregateCompanyName").autocomplete({
        source: function (request, response) {
            var term = request.term;
            if (term in cache3) {
                response(cache3[term]);
                return;
            }

            lastXhr = $.getJSON("/facility_codes/autocomplete.json", request, function (data, status, xhr) {
                cache3[term] = data;
                if (xhr === lastXhr) {
                    response(data);
                }
            });
        },
        select: function (event, ui) {
            $("#AggregateCompanyName").val(ui.item.label);
            $("#AggregateCompanyCode").val(ui.item.value);
            return false;
        }
    })


    $("#AggregateCountyId").combobox();

    if ($('#AggregateReportType').val() == 'Followup') {
        $('#AggregateReporterEditForm :input').attr('readonly', 'readonly');
        $('.editable :input').prop('disabled', false).attr('readonly', false);
        if ($('#AggregateSeriousYes').is(':checked')) {
            console.log('Yes was selected initially');
            $('#AggregateSeriousNo').prop('disabled', true); // disable the "No" option
            $('#clearButton').prop('disabled', true);
        }

        // Handle the scale for severity
        if ($('#AggregateSeverityMild').is(':checked')) {
            $('#AggregateSeverityMild').prop('disabled', false);
            $('#AggregateSeverityModerate').prop('disabled', false);
            $('#AggregateSeveritySevere').prop('disabled', false);
            $('#AggregateSeverityFatal').prop('disabled', false);
            $('#AggregateSeverityUnknown').prop('disabled', true);
        }
        if ($('#AggregateSeverityModerate').is(':checked')) {
            $('#AggregateSeverityMild').prop('disabled', true);
            $('#AggregateSeverityModerate').prop('disabled', false);
            $('#AggregateSeveritySevere').prop('disabled', false);
            $('#AggregateSeverityFatal').prop('disabled', false);
            $('#AggregateSeverityUnknown').prop('disabled', true);
        }
        if ($('#AggregateSeveritySevere').is(':checked')) {
            $('#AggregateSeverityMild').prop('disabled', true);
            $('#AggregateSeverityModerate').prop('disabled', true);
            $('#AggregateSeveritySevere').prop('disabled', false);
            $('#AggregateSeverityFatal').prop('disabled', false);
            $('#AggregateSeverityUnknown').prop('disabled', true);
        }
        if ($('#AggregateSeverityFatal').is(':checked')) {
            $('#AggregateSeverityMild').prop('disabled', true);
            $('#AggregateSeverityModerate').prop('disabled', true);
            $('#AggregateSeveritySevere').prop('disabled', true);
            $('#AggregateSeverityFatal').prop('disabled', false);
            $('#AggregateSeverityUnknown').prop('disabled', true);
        }
        if ($('#AggregateSeverityUnknown').is(':checked')) {
            $('#AggregateSeverityMild').prop('disabled', false);
            $('#AggregateSeverityModerate').prop('disabled', false);
            $('#AggregateSeveritySevere').prop('disabled', false);
            $('#AggregateSeverityFatal').prop('disabled', false);
            $('#AggregateSeverityUnknown').prop('disabled', false);
        }



    }

    //Person submitting
    $('.person-submit').on('change', function () {
        var pilih = $(this).val();
        if (pilih == 'Yes') {
            $('.diff:input').prop('disabled', false);
        } else {
            $('.diff:input').val('');
            $('.diff:input').prop('disabled', true);
        }
    });

    $('.summary-available').on('change', function () {
        var pilih = $(this).val();
        if (pilih == 'Yes') {
            $('#parentDetails').show();
            $('#otherDetails').show();
            $('#basicInformation').show();
            $('.available:input').prop('disabled', false);
        } else {
            $('#parentDetails').hide();
            $('#otherDetails').hide();
            $('#basicInformation').hide();
            $('.available:input').prop('disabled', true);
        }
    });

    if ($("#AggregateSummaryAvailableNo").is(':checked')) { $('.available:input').prop('disabled', true); }
    if ($("#AggregateSummaryAvailableYes").is(':checked')) { 
        $('#parentDetails').show();
        $('#otherDetails').show();
        $('#basicInformation').show();
        $('.available:input').prop('disabled', false);

    }
    if ($("#AggregatePersonSubmittingNo").is(':checked')) { $('.diff:input').prop('disabled', true); }

    //If Male disable
    $('input[name="data[Aggregate][gender]"]').click(function () {
        if ($(this).val() == 'Male') {
            $('input[name="data[Aggregate][pregnancy_status]"]').attr('disabled', this.checked).attr('checked', !this.checked);
        } else {
            $('input[name="data[Aggregate][pregnancy_status]"]').attr('disabled', false);
        }
    });
    if ($('input[name="data[Aggregate][gender]"][value="Male"]').is(':checked')) { $('input[name="data[Aggregate][pregnancy_status]"]').attr('disabled', true).attr('checked', false); }

    //If not serious disable criteria
    $('input[name="data[Aggregate][serious]"]').click(function () {
        if ($(this).val() == 'No') {
            $('input[name="data[Aggregate][serious_reason]"]').attr('disabled', this.checked).attr('checked', !this.checked);
            $('#serious_reason_clear').hide();
        } else {
            $('input[name="data[Aggregate][serious_reason]"]').attr('disabled', false);
            $('#serious_reason_clear').show();
        }
    });
    if ($('input[name="data[Aggregate][serious]"][value="No"]').is(':checked')) { $('input[name="data[Aggregate][serious_reason]"]').attr('disabled', true).attr('checked', false); }

    $("#AggregateReaction").autocomplete({
        source: "/meddras/autocomplete.json"
    });

    var cache2 = {}, lastXhr;
    $("#AggregateInstitutionCode").autocomplete({
        source: function (request, response) {
            var term = request.term;
            if (term in cache2) {
                response(cache2[term]);
                return;
            }

            lastXhr = $.getJSON("/facility_codes/autocomplete.json", request, function (data, status, xhr) {
                cache2[term] = data;
                if (xhr === lastXhr) {
                    response(data);
                }
            });
        },
        select: function (event, ui) {
            $("#AggregateNameOfInstitution").val(ui.item.label);
            $("#AggregateInstitutionCode").val(ui.item.value);
            $("#AggregateAddress").val(ui.item.addr);
            $("#AggregateInstitutionContact").val(ui.item.phone);
            return false;
        }
    });

    var cache3 = {}, lastXhr;
    $("#AggregateNameOfInstitution").autocomplete({
        source: function (request, response) {
            var term = request.term;
            if (term in cache3) {
                response(cache3[term]);
                return;
            }

            lastXhr = $.getJSON("/facility_codes/autocomplete.json", request, function (data, status, xhr) {
                cache3[term] = data;
                if (xhr === lastXhr) {
                    response(data);
                }
            });
        },
        select: function (event, ui) {
            $("#AggregateNameOfInstitution").val(ui.item.label);
            $("#AggregateInstitutionCode").val(ui.item.value);
            $("#AggregateAddress").val(ui.item.addr);
            $("#AggregateInstitutionContact").val(ui.item.phone);
            return false;
        }
    })
    // get the id of Aggregate_medicinal_product_ 
    $('#AggregateMedicinalProduct').change(function () {
        $('#AggregateHerbalProduct').attr("checked", false);
        $('#AggregateCosmeceuticals').attr("checked", false);
        $('#AggregateProductOther').attr("checked", false);


    }
    );
    // get the Aggregate_herbal_product_ 
    $('#AggregateHerbalProduct').change(function () {
        $('#AggregateMedicinalProduct').attr("checked", false);
        $('#AggregateCosmeceuticals').attr("checked", false);
        $('#AggregateProductOther').attr("checked", false);
    }
    );
    // Aggregate_cosmeceuticals_
    $('#AggregateCosmeceuticals').change(function () {
        $('#AggregateHerbalProduct').attr("checked", false);
        $('#AggregateMedicinalProduct').attr("checked", false);
        $('#AggregateProductOther').attr("checked", false);
    }
    );
    // Aggregate_product_other_
    $('#AggregateProductOther').change(function () {
        $('#AggregateHerbalProduct').attr("checked", false);
        $('#AggregateCosmeceuticals').attr("checked", false);
        $('#AggregateMedicinalProduct').attr("checked", false);
    }

    );
    $('.date-pick-field').datepicker({
        minDate: "-100Y", maxDate: "0",
        dateFormat: 'yy-mm-dd',
        showButtonPanel: true,
        changeMonth: true,
        changeYear: true,
        showAnim: 'show'
    });

});
