var CarrierSelect = function ($) {
    'use strict';

    var me = {
        storage: {
            actualRuleId: null,
            actualRule: null
        },
        selector: {
            'carrierPopup': '#carrier-select-popup',
            'newbutton': '#create-new-rule',
            'dataTableList': '#carrierselectlist'
        },
        filters: {
            'article': 'article',
            'fastlane': 'fastlane',
            'order_amount': 'order-amount',
            'payment_method': 'payment-method',
            'shipping_country': 'shipping-country',
            'shipping_method': 'shipping-method',
            'project': 'project',
            'shop': 'shop',
            'volume': 'volume',
            'weight': 'weight'
        },
        comparator: {
            '': {
                'title': '',
                'count_inputs': 0
            },
            'not_empty':
                {
                    'title': 'nicht leer',
                    'count_inputs': 0
                }
            ,
            'empty': {
                'title': 'leer',
                'count_inputs': 0
            },
            'in': {
                'title': 'in',
                'count_inputs': null
            },
            'not_in': {
                'title': 'nicht in',
                'count_inputs': null
            },
            '=': {
                'title': '=',
                'count_inputs': 1
            },
            '>': {
                'title': '>',
                'count_inputs': 1
            },
            '>=': {
                'title': '>=',
                'count_inputs': 1
            },
            '<': {
                'title': '<',
                'count_inputs': 1
            },
            '<=': {
                'title': '<=',
                'count_inputs': 1
            },
            'between': {
                'title': 'zwischen',
                'count_inputs': 2
            }
        },
        ComparatorChanged: function ($comparator) {
            var field = $comparator.id
                                   .replace('carrier-select-popup-rules-div-', '')
                                   .replace('-comparator', '')
                                   .replace('-', '_');
            me.drawEmptyValues($($comparator).val(), field);
        },
        drawAllComperators: function () {
            $.each(me.filters, function (key, value) {
                $('#carrier-select-popup-rules-div-' + value + '-comparator').html(
                    me.drawCompartorSelect()
                ).on('change', function () {
                    me.ComparatorChanged(this);
                });
            });
        },
        drawCompartorSelect: function () {
            var html = '';
            $.each(me.comparator, function (key, value) {
                html += '<option value="' + key + '">' + value.title + '</option>';
            });

            return html;
        },
        resetCompartors: function () {
            $('#carrier-select-popup-rules-div').find('td.values').html('');
            $.each(me.filters, function (key, value) {
                $('#carrier-select-popup-rules-div-' + value + '-comparator').val('').trigger('change');
            });
        },
        updateLiveListTable: function () {
            $(me.selector.dataTableList).DataTable().ajax.reload();
        },
        setPopupError: function (message) {
            if (message === '') {
                $('#carrier-select-popup-error').html('');
                return;
            }
            $('#carrier-select-popup-error').html('<div class="error">' + message + '</div>');
        },
        delete: function (ruleId) {
            if (!confirm('Regel wirklich löschen?')) {
                return;
            }

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'index.php?module=carrierselect&action=list&cmd=delete_rule',
                data: {
                    rule_id: ruleId
                },
                success: function () {
                    me.updateLiveListTable();
                },
                error: function (e) {

                }
            });

            me.updateLiveListTable();
        },
        drawEmptyValues: function (comparator, field) {
            if (typeof me.comparator[comparator] == 'undefined') {
                return;
            }
            var defaultInputs = null;
            if (typeof me.comparator[comparator].count_inputs != 'undefined') {
                defaultInputs = me.comparator[comparator].count_inputs;
            }

            var $colTd =
                $('#carrier-select-popup-rules-div-' + me.filters[field] + '-comparator')
                    .parents('tr').first().find('td.values').first();
            var $inputs = $($colTd).find('input');
            var $emptyInputs = $($inputs).filter(function () {
                return $(this).val() + '' === '';
            });

            if (defaultInputs === null) {
                if ($($colTd).find('img.addfield').length > 0) {
                    return;
                }
                if($inputs.length === 0) {
                    $newInput = $('<input type="text" class="value" />');
                    $($colTd).append($newInput);
                    me.addAutoComplete($newInput, field);
                }
                var $plusIcon = $('<img src="./themes/new/images/pluspaket_gruen_kl.png" data-tabletype="'
                    + field + '" alt="hinzufügen" class="addfield" />');
                $($colTd).append($plusIcon);
                me.addFilterPlusEvent($plusIcon);
                return;
            } else {
                $($colTd).find('img.addfield').remove();
            }
            var neededEmpty = defaultInputs - $inputs.length;
            if (neededEmpty === 0) {
                return;
            }
            if (neededEmpty < 0) {
                var i = 0;
                var toRemove = -neededEmpty;
                if ($emptyInputs.length >= toRemove) {
                    for (i = 0; i < toRemove; i++) {
                        ($emptyInputs[$emptyInputs.length - 1]).remove();
                    }
                    return;
                }
                if ($emptyInputs.length > 0) {
                    toRemove -= $emptyInputs.length;
                    var toRemoveEmpty = $emptyInputs.length;
                    for (i = 0; i < toRemoveEmpty; i++) {
                        ($emptyInputs[$emptyInputs.length - 1]).remove();
                    }
                }
                $inputs = $($colTd).find('input');
                for (i = 0; i < toRemove; i++) {
                    ($inputs[$inputs.length - 1]).remove();
                }

                return;
            }
            var $newInput = null;
            for (var j = 0; j < neededEmpty; j++) {
                $newInput = $('<input type="text" class="value" />');
                $($colTd).append($newInput);
                me.addAutoComplete($newInput, field);
            }
        },
        drawFieldValues: function (filter) {
            var drawnInputs = 0;
            var $colTd =
                $('#carrier-select-popup-rules-div-' + me.filters[filter.filter_field] + '-comparator')
                    .parents('tr').first().find('td.values').first();
            if (typeof filter.values != 'undefined') {
                $.each(filter.values, function (key, value) {
                    var $newInput = $('<input type="text" class="value" value="' + value + '" />');
                    drawnInputs++;
                    $($colTd).append($newInput);
                    me.addAutoComplete($newInput, filter.filter_field);
                });
            }
            me.drawEmptyValues(filter.filter_comparator, filter.filter_field);
        },
        drawFilter: function () {
            me.resetCompartors();
            if (typeof me.storage.actualRule.filters == 'undefined') {
                return;
            }
            $.each(me.storage.actualRule.filters, function (key, filter) {
                if (typeof me.filters[filter.filter_field] != 'undefined') {
                    $('#carrier-select-popup-rules-div-' + me.filters[filter.filter_field] + '-comparator')
                        .val(filter.filter_comparator);
                    me.drawFieldValues(filter);
                }
            });
        },
        open: function (ruleId) {
            me.storage.actualRule = null;
            me.setPopupError('');
            me.storage.actualRuleId = ruleId;
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'index.php?module=carrierselect&action=list&cmd=get_rule',
                data: {
                    rule_id: ruleId
                },
                success: function (data) {
                    me.storage.actualRule = data;
                    $('#carrier-select-popup-name').val(data.name);
                    $('#carrier-select-popup-carrier').val(data.carrier);
                    $('#carrier-select-popup-priority').val(data.priority);
                    $('#carrier-select-popup-active').prop('checked', data.active == '1');
                    me.drawFilter();
                    $(me.selector.carrierPopup).dialog('open');
                },
                error: function (e) {
                    me.storage.actualRule = null;
                }
            });
        },
        addFilterPlusEvent: function ($plusIcon) {
            $($plusIcon).on('click', function () {
                var $emptyInput = $('<input type="text" class="value" />');
                $(this).before($emptyInput);
                me.addAutoComplete($emptyInput, $(this).data('tabletype'));
            });
        },
        addAutoComplete: function ($element, tabletype) {
            $($element).on('change', function () {
                var $comparator = $(this).parents('tr').first().find('select.comparator-select');
                me.drawEmptyValues($($comparator).val(), $($comparator).data('field'));
            });
            $($element).on('blur', function () {
                var $comparator = $(this).parents('tr').first().find('select.comparator-select');
                me.drawEmptyValues($($comparator).val(), $($comparator).data('field'));
            });
            if (tabletype === 'project') {
                $($element).autocomplete({
                    source: 'index.php?module=ajax&action=filter&rmodule=batches&raction=edit&rid=&filtername=projektname',
                    select: function (event, ui) {
                        var i = ui.item.value;
                        var zahl = i.indexOf(' ');
                        var text = i.slice(0, zahl);
                        $(this).val(text);
                        return false;
                    }
                });
                return;
            }
            if (tabletype === 'article') {
                $($element).autocomplete({
                    source: 'index.php?module=ajax&action=filter&rmodule=batches&raction=edit&rid=&filtername=artikelnummer',
                    select: function (event, ui) {
                        var i = ui.item.value;
                        var zahl = i.indexOf(' ');
                        var text = i.slice(0, zahl);
                        $(this).val(text);
                        return false;
                    }
                });
                return;
            }
            if (tabletype === 'shop') {
                $($element).autocomplete({
                    source: 'index.php?module=ajax&action=filter&rmodule=batches&raction=edit&rid=&filtername=shopnameid'
                });
                return;
            }
            if (tabletype === 'shipping_method') {
                $($element).autocomplete({
                    source: 'index.php?module=ajax&action=filter&rmodule=batches&raction=edit&rid=&filtername=versandartentype'
                });
                return;
            }
            if (tabletype === 'payment_method') {
                $($element).autocomplete({
                    source: 'index.php?module=ajax&action=filter&rmodule=batches&raction=edit&rid=&filtername=zahlungsweisetype'
                });
                return;
            }
            if (tabletype === 'group') {
                $($element).autocomplete({
                    source: 'index.php?module=ajax&action=filter&rmodule=batches&raction=edit&rid=&filtername=gruppekennziffer',
                    select: function (event, ui) {
                        var i = ui.item.value;
                        var zahl = i.indexOf(' ');
                        var text = i.slice(0, zahl);
                        $(this).val(text);
                        return false;
                    }
                });
                return;
            }
            if (tabletype === 'storagelocation') {
                $($element).autocomplete({
                    source: 'index.php?module=ajax&action=filter&rmodule=batches&raction=edit&rid=&filtername=lagerplatz'
                });
                return;
            }
            if (tabletype === 'articlecategory') {

                $($element).autocomplete({
                    source: 'index.php?module=ajax&action=filter&rmodule=batches&raction=edit&rid=&filtername=artikelkategorien',
                    select: function (event, ui) {
                        var i = ui.item.value;
                        var zahl = i.indexOf(' ');
                        if (zahl > 0) {
                            var text = i.slice(0, zahl);
                            $(this).val(text);
                            return false;
                        }
                    }
                });
                return;
            }
        },
        createActualRuleFromFormToSave: function () {
            me.storage.actualRule.name = $('#carrier-select-popup-name').val();
            me.storage.actualRule.carrier = $('#carrier-select-popup-carrier').val();
            me.storage.actualRule.priority = $('#carrier-select-popup-priority').val();
            me.storage.actualRule.active = $('#carrier-select-popup-active').prop('checked') ? 1 : 0;
            if (typeof me.storage.actualRule.filters == 'undefined') {
                me.storage.actualRule.filters = [];
            }
            var foundFields = [];
            var newFilters = [];
            $.each(me.storage.actualRule.filters, function (key, filter) {
                if (typeof filter.filter_field == 'undefined') {
                    return;
                }
                if (typeof me.filters[filter.filter_field] != 'undefined') {
                    var $comparator = $(
                        '#carrier-select-popup-rules-div-' + me.filters[filter.filter_field] + '-comparator');
                    if ($comparator.val() + '' === '') {
                        return;
                    }
                    var notEmptyValues = me.getFieldValues($comparator);
                    me.storage.actualRule.filters[key].filter_comparator = $($comparator).val();
                    me.storage.actualRule.filters[key].values = notEmptyValues;
                    foundFields.push(filter.filter_field);
                    newFilters.push(me.storage.actualRule.filters[key]);
                }
            });
            me.storage.actualRule.filters = newFilters;
            $('select.comparator-select').each(function () {
                if ($(this).val() + '' === '') {
                    return;
                }
                if (foundFields.includes($(this).data('field'))) {
                    return;
                }
                var notEmptyValues = me.getFieldValues(this);
                me.storage.actualRule.filters.push(
                    {
                        id: null,
                        filter_field: $(this).data('field'),
                        filter_comparator: $(this).val(),
                        values: notEmptyValues
                    }
                );
            });
        },
        getFieldValues: function ($compartorElement) {
            var notEmptyValues = [];
            var $colTd =
                $('#carrier-select-popup-rules-div-'
                    + $($compartorElement).data('field').replace('_', '-')
                    + '-comparator')
                    .parents('tr').first().find('td.values').first();
            $colTd.find('input').each(function () {
                if ($(this).val() + '' === '') {
                    return;
                }
                if (notEmptyValues.includes($(this).val())) {
                    return;
                }
                notEmptyValues.push($(this).val());
            });
            return notEmptyValues;
        },
        saveRule: function () {
            me.createActualRuleFromFormToSave();
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'index.php?module=carrierselect&action=list&cmd=save_rule',
                data: me.storage.actualRule,
                error: function (e) {
                    me.setPopupError(e.responseJSON.error);
                },
                success: function () {
                    $(me.selector.carrierPopup).dialog('close');
                    me.updateLiveListTable();
                }
            });
        },
        init: function () {
            me.drawAllComperators();
            $(me.selector.newbutton).on('click', function () {
                me.open(0);
            });
            $(me.selector.dataTableList).on('afterreload', function () {
                $(me.selector.dataTableList).find('img.delete').on('click', function () {
                    me.delete($(this).data('id'));
                });
                $(me.selector.dataTableList).find('img.edit').on('click', function () {
                    me.open($(this).data('id'));
                });
            });
            $(me.selector.dataTableList).trigger('afterreload');
            $(me.selector.carrierPopup).dialog(
                {
                    modal: true,
                    autoOpen: false,
                    minWidth: 940,
                    title: '',
                    buttons: {
                        'ABBRECHEN': function () {
                            $(this).dialog('close');
                        },
                        'SPEICHERN': function () {
                            me.saveRule();
                        }
                    }
                });
        }
    };
    return {
        init: me.init
    };

}(jQuery, Push);

$(document).ready(function () {
    CarrierSelect.init();
});
