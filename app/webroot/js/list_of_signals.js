$(document).ready(function () {
    var dates = $("#AggregateListOfSignal0DateDetected, #AggregateListOfSignal0DateClosed").datepicker({
        minDate: "-100Y",
        maxDate: "-0D",
        dateFormat: 'dd-mm-yy',
        showButtonPanel: true,
        changeMonth: true,
        changeYear: true,
        showAnim: 'show',
        onSelect: function (selectedDate) {
            var option = this.id == "AggregateListOfSignal0DateDetected" ? "minDate" : "maxDate",
                instance = $(this).data("datepicker"),
                date = $.datepicker.parseDate(
                    instance.settings.dateFormat ||
                    $.datepicker._defaults.dateFormat,
                    selectedDate, instance.settings);
            dates.not(this).datepicker("option", option, date);
        }
    });

    reloadStuff();
    function reloadStuff() {

        var dates2 = $('.date-pick-from, .date-pick-to').datepicker({
           
            minDate: "-100Y",
            maxDate: "-0D",
            dateFormat: 'dd-mm-yy',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            showAnim: 'show',
        });
        $(".autoComblete").autocomplete({
            source: "/drug_dictionaries/autocomplete.json"
        });
        $(".autoComblete2").autocomplete({
            source: "/drug_dictionaries/autocomblete.json"
        });
        // console.log("original disco man");
    }

    $(".removeTr").click(function () {
        if (typeof $(this).attr('id') !== 'undefined' && $(this).attr('id') !== false && $(this).attr('id') !== "") {
            $.ajax({
                async: true, type: 'POST',
                // beforeSend:function(request) {$('#loading').show();}, 
                // complete:function(request, json) {$('#post7').html(request.responseText); 
                // $('#loading').hide()}, 
                url: '/sadr_list_of_drugs/delete.json',
                data: { 'id': $(this).attr('id') },
                success: function (data) {
                    // console.log(data);
                }
            });
        }
        updateTr($(this));
    });

    $("#addListOfSignals").click(function () {
        var intId = $("#buildyourform tr").length - 1;
        if (intId < 15) {
            $("#buildyourform").append(constructTr(intId));
            reloadStuff();
        } else {
            alert("Sorry, cant add more than " + intId + " rows at a time!");
        }
    });

    function constructTr(intId, field1) {
        var intId2 = intId + 1;
        var fieldWrapper = $("<tr class=\"fieldwrapper\" id=\"field" + intId + "\"/>");
        var fName = $("<td>" + intId2 + "</td>");
        var signal_term = $('<td data-title="Signal Term *"><div class="control-group"><input type="text" id="AggregateListOfSignal' + intId + 'signal_term"   class="span11  autosave-ignore" name="data[AggregateListOfSignal][' + intId + '][signal_term]"  data-items="4"  autocomplete="off", ></div></td>');
        var status = $('<td colspan=""/>').attr("data-title", "status *").append(
            $('<div class="control-group"/>') 
                .append($('<small class="help-block"></small>'))
                .append($('#AggregateListOfSignal0Status').clone()
                    .attr({
                        'id': 'AggregateListOfSignal' + intId + 'status',
                        'name': 'data[AggregateListOfSignal][' + intId + '][status]'
                    })
                    .val('')
                )
            // Add the new input field here
        );

        // CSS style for the small label
        $('<style>.small-label { font-size: 12px; }</style>').appendTo('head');
        var date_detected = $('<td data-title="date_detected (dd-mm-yyyy) *">	<div class="control-group"><input type="text" id="AggregateListOfSignal' + intId + 'date_detected" class="span12 date-pick-from autosave-ignore" name="data[AggregateListOfSignal][' + intId + '][date_detected]"></div> </td>');

        var date_closed = $('<td data-title="date_closed (dd-mm-yyyy) *">	<div class="control-group"><input type="text" id="AggregateListOfSignal' + intId + 'date_closed" class="span12 date-pick-to autosave-ignore" name="data[AggregateListOfSignal][' + intId + '][date_closed]"></div> </td>');


        var source_trigger = $('<td data-title="source_trigger"><div class="control-group"><input type="text" id="AggregateListOfSignal' + intId + 'source_trigger"  class="span12 autosave-ignore" name="data[AggregateListOfSignal][' + intId + '][source_trigger]"></div></td>');

        var reason_summary = $('<td data-title="reason_summary"><div class="control-group"><input type="text" id="AggregateListOfSignal' + intId + 'reason_summary"  class="span12 autosave-ignore" name="data[AggregateListOfSignal][' + intId + '][reason_summary]"></div></td>');
        var evaluation_method = $('<td data-title="evaluation_method"><div class="control-group"><input type="text" id="AggregateListOfSignal' + intId + 'evaluation_method"  class="span9 autosave-ignore" name="data[AggregateListOfSignal][' + intId + '][evaluation_method]"></div></td>');
        var outcome = $('<td data-title="outcome"><div class="control-group"><input type="text" id="AggregateListOfSignal' + intId + 'outcome"  class="span12 autosave-ignore" name="data[AggregateListOfSignal][' + intId + '][outcome]"></div></td>');

        var removeButton_ = $('<button  type="button" class="btn-mini" title="click here to delete row" ><i class="icon-minus"></i></button>"');
        removeButton_.tooltip();
        removeButton_.click(function () {
            if (typeof $(this).attr('id') !== 'undefined' && $(this).attr('id') !== false && $(this).attr('id') !== "") {
                $.ajax({
                    url: '/sadr_list_of_drugs/delete.json',
                    type: 'POST',
                    async: true,
                    data: { 'id': $(this).attr('id') },
                    success: function (data) {
                        // console.log(data);
                    }
                });
            }
            updateTr($(this));
        });
        var removeButton = $('<td/>').attr("data-title", "Remove Drug - ").append(removeButton_);
        fieldWrapper.append(fName);
        fieldWrapper.append(signal_term);
        fieldWrapper.append(date_detected);
        fieldWrapper.append(status);
        fieldWrapper.append(date_closed);
        fieldWrapper.append(source_trigger);
        fieldWrapper.append(reason_summary);
        fieldWrapper.append(evaluation_method);
        fieldWrapper.append(outcome);
        fieldWrapper.append(removeButton);

        return fieldWrapper;
    }

    function updateTr(myobj) {
        // updateTr($(this).parent().parent().nextAll());
        myobj.tooltip('hide');
        // $(this).parent().nextAll().remove();

        myobj
            .parent()
            .parent()
            .find('td')
            .wrapInner('<div style="display: block;" />')
            .parent()
            .find('td > div')
            .slideUp(300, function () {
                $(this).parent().parent().nextAll().each(function () {
                    no = parseInt($(this).text()) - 1;
                    intId = parseInt($(this).text()) - 2;
                    $($(this).children().get(0)).text(no);
                    $(this).prop('id', 'field' + intId);
                    $($(this).children().get(1)).find('input').prop('id', 'AggregateListOfSignal' + intId + 'signal_term');
                    $($(this).children().get(1)).find('input').prop('name', 'data[AggregateListOfSignal][' + intId + '][signal_term]');
                    $($(this).children().get(2)).find('input').prop('id', 'AggregateListOfSignal' + intId + 'date_detected');
                    $($(this).children().get(2)).find('input').prop('name', 'data[AggregateListOfSignal][' + intId + '][date_detected]');
                    $($(this).children().get(2)).find('input').prop('id', 'AggregateListOfSignal' + intId + 'status');
                    $($(this).children().get(2)).find('input').prop('name', 'data[AggregateListOfSignal][' + intId + '][status]');
                    $($(this).children().get(2)).find('input').prop('id', 'AggregateListOfSignal' + intId + 'date_closed');
                    $($(this).children().get(2)).find('input').prop('name', 'data[AggregateListOfSignal][' + intId + '][date_closed]');
                    $($(this).children().get(3)).find('input').prop('id', 'AggregateListOfSignal' + intId + 'source_trigger');
                    $($(this).children().get(3)).find('input').prop('name', 'data[AggregateListOfSignal][' + intId + '][source_trigger]');
                    $($(this).children().get(4)).find('select').prop('id', 'AggregateListOfSignal' + intId + 'reason_summary');
                    $($(this).children().get(4)).find('select').prop('name', 'data[AggregateListOfSignal][' + intId + '][reason_summary]');
                    $($(this).children().get(5)).find('select').prop('id', 'AggregateListOfSignal' + intId + 'evaluation_method');
                    $($(this).children().get(5)).find('select').prop('name', 'data[AggregateListOfSignal][' + intId + '][evaluation_method]');
                    $($(this).children().get(6)).find('select').prop('id', 'AggregateListOfSignal' + intId + 'outcome');
                    $($(this).children().get(6)).find('select').prop('name', 'data[AggregateListOfSignal][' + intId + '][outcome]');
                });
                $(this).parent().parent().remove();
                reloadStuff();
            });
    };
});

