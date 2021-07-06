var TurnoverThresholdList = (function ($) {
    'use strict';

    var me = {
        selector: {
            popupTurnoverThresold: '#editLieferschwelle',
            dataTable: '#lieferschwelle_list'
        },
        reloadTable: function () {
            var oTableL = $(me.selector.dataTable).dataTable();
            var tmp = $('.dataTables_filter input[type=search]').val();
            oTableL.fnFilter('%');
            oTableL.fnFilter(tmp);
        },
        registerEvents: function () {
            $(me.selector.dataTable).find('img.delete-turnover-threshold').on('click', function () {
                me.delete($(this).data('id'));
            });
            $(me.selector.dataTable).find('img.edit-turnover-threshold').on('click', function () {
                me.get($(this).data('id'));
            });
        },
        delete: function (id) {
            var conf = confirm($(me.selector.popupTurnoverThresold).data('deletemessage'));
            if (conf) {
                $.ajax({
                    url: 'index.php?module=lieferschwelle&action=delete',
                    data: {
                        id: id
                    },
                    method: 'post',
                    dataType: 'json',
                    beforeSend: function () {
                        App.loading.open();
                    },
                    success: function (data) {
                        if (data.status == 1) {
                            me.reloadTable();
                        } else {
                            alert(data.statusText);
                        }
                        App.loading.close();
                    }
                });
            }

            return false;
        },
        getNew: function () {
            $('#e_id').val('0');
            $(me.selector.popupTurnoverThresold).find('#e_empfaengerland').val('');
            $(me.selector.popupTurnoverThresold).dialog('open');
            $('#e_storage').trigger('change');
        },
        get: function (id) {
            $.ajax({
                url: 'index.php?module=lieferschwelle&action=edit&cmd=get',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                beforeSend: function () {
                    App.loading.open();
                },
                success: function (data) {
                    $(me.selector.popupTurnoverThresold).find('#e_id').val(data.id);
                    $(me.selector.popupTurnoverThresold).find('#e_empfaengerland').val(data.empfaengerland);
                    $(me.selector.popupTurnoverThresold).find('#e_lieferschwelleeur').val(data.lieferschwelleeur);
                    $(me.selector.popupTurnoverThresold).find('#e_ustid').val(data.ustid);
                    $(me.selector.popupTurnoverThresold).find('#e_steuersatznormal').val(data.steuersatznormal);
                    $(me.selector.popupTurnoverThresold).find('#e_steuersatzermaessigt').val(data.steuersatzermaessigt);
                    $(me.selector.popupTurnoverThresold).find('#e_steuersatzspezial').val(data.steuersatzspezial);
                    $(me.selector.popupTurnoverThresold).find('#e_erloeskontonormal').val(data.erloeskontonormal);
                    $(me.selector.popupTurnoverThresold).find('#e_erloeskontoermaessigt').val(data.erloeskontoermaessigt);
                    $(me.selector.popupTurnoverThresold).find('#e_erloeskontobefreit').val(data.erloeskontobefreit);
                    $(me.selector.popupTurnoverThresold).find('#e_spezialursprungsland').val(data.steuersatzspezialursprungsland);
                    $(me.selector.popupTurnoverThresold).find('#e_ueberschreitung').val(data.ueberschreitung);
                    $(me.selector.popupTurnoverThresold).find('#e_umsatz').val(data.aktuellerumsatz);
                    $(me.selector.popupTurnoverThresold).find('#e_current_revenue_in_eur').val(data.current_revenue_in_eur);
                    $(me.selector.popupTurnoverThresold).find('#e_currency').val(data.currency  === null ? 'EUR' : data.currency);
                    $(me.selector.popupTurnoverThresold).find('#e_storage').val(data.use_storage);
                    $(me.selector.popupTurnoverThresold).find('#e_preiseanpassen').prop('checked', data.preiseanpassen == 1 ? true : false);
                    $(me.selector.popupTurnoverThresold).find('#e_verwenden').prop('checked', data.verwenden == 1 ? true : false);
                    App.loading.close();
                    $(me.selector.popupTurnoverThresold).dialog('open');
                    $('#e_storage').trigger('change');
                }
            });
        },
        reset: function () {
            $(me.selector.popupTurnoverThresold).find('#e_id').val('');
            $(me.selector.popupTurnoverThresold).find('#e_empfaengerland').val('');
            $(me.selector.popupTurnoverThresold).find('#e_lieferschwelleeur').val('');
            $(me.selector.popupTurnoverThresold).find('#e_ustid').val('');
            $(me.selector.popupTurnoverThresold).find('#e_steuersatznormal').val('');
            $(me.selector.popupTurnoverThresold).find('#e_steuersatzermaessigt').val('');
            $(me.selector.popupTurnoverThresold).find('#e_steuersatzspezial').val('');
            $(me.selector.popupTurnoverThresold).find('#e_erloeskontonormal').val('');
            $(me.selector.popupTurnoverThresold).find('#e_erloeskontoermaessigt').val('');
            $(me.selector.popupTurnoverThresold).find('#e_erloeskontobefreit').val('');
            $(me.selector.popupTurnoverThresold).find('#e_spezialursprungsland').val('');
            $(me.selector.popupTurnoverThresold).find('#e_ueberschreitung').val('');
            $(me.selector.popupTurnoverThresold).find('#e_currency').val('EUR');
            $(me.selector.popupTurnoverThresold).find('#e_current_revenue_in_eur').val('');
            $(me.selector.popupTurnoverThresold).find('#e_umsatz').val('');
            $(me.selector.popupTurnoverThresold).find('#e_storage').val('0');
            $(me.selector.popupTurnoverThresold).find('#e_preiseanpassen').prop('checked', false);
            $(me.selector.popupTurnoverThresold).find('#e_verwenden').prop('checked', false);
        },
        save: function () {
            if ($('#e_verwenden').prop('checked')
                && ($('#e_ueberschreitung').val() + '' === ''
                    || $('#e_ueberschreitung').val() + '' === '00.00.0000')
            ) {
                alert($('#e_ueberschreitung').data('errormessage'));
                return;
            }
            $.ajax({
                url: 'index.php?module=lieferschwelle&action=save',
                data: {
                    id: $('#e_id').val(),
                    empfaengerland: $('#e_empfaengerland').val(),
                    lieferschwelleeur: $('#e_lieferschwelleeur').val(),
                    ustid: $('#e_ustid').val(),
                    steuersatznormal: $('#e_steuersatznormal').val(),
                    steuersatzermaessigt: $('#e_steuersatzermaessigt').val(),
                    steuersatzspezial: $('#e_steuersatzspezial').val(),
                    erloeskontonormal: $('#e_erloeskontonormal').val(),
                    erloeskontoermaessigt: $('#e_erloeskontoermaessigt').val(),
                    erloeskontobefreit: $('#e_erloeskontobefreit').val(),
                    spezialursprungsland: $('#e_spezialursprungsland').val(),
                    ueberschreitung: $('#e_ueberschreitung').val(),
                    current_revenue_in_eur: $('#e_current_revenue_in_eur').val(),
                    currency: $('#e_currency').val(),
                    umsatz: $('#e_umsatz').val(),
                    storage: $('#e_storage').val(),
                    preiseanpassen: $('#e_preiseanpassen').prop('checked') ? 1 : 0,
                    verwenden: $('#e_verwenden').prop('checked') ? 1 : 0
                },
                method: 'post',
                dataType: 'json',
                beforeSend: function () {
                    App.loading.open();
                },
                success: function (data) {
                    App.loading.close();
                    if (data.status == 1) {
                        me.reloadTable();
                        $(me.selector.popupTurnoverThresold).dialog('close');
                        me.reset();
                    } else {
                        alert(data.statusText);
                    }
                }
            });
        },
        init: function () {
            $('#e_ursprungsland').focus();
            $('#newturnoverthreshold').on('click', function () {
                me.getNew();
            });
            $(me.selector.popupTurnoverThresold).dialog({
                modal: true,
                bgiframe: true,
                closeOnEscape: false,
                minWidth: 650,
                maxHeight: 700,
                autoOpen: false,
                buttons: {
                    ABBRECHEN: function () {
                        me.reset();
                        $(this).dialog('close');
                    },
                    SPEICHERN: function () {
                        me.save();
                    }
                }
            });

            $(me.selector.popupTurnoverThresold).dialog({
                close: function () {
                    me.reset();
                }
            });
            me.registerEvents();
            $(me.selector.dataTable).on('afterreload', function () { me.registerEvents();});
        }
    };

    return {
        init: me.init
    };

})(jQuery);
$(document).ready(function () {
    TurnoverThresholdList.init();
});
