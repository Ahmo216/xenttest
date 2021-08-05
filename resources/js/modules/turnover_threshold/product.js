var TurnoverThresholdProduct = (function ($) {
    'use strict';

    var me = {
        selector: {
            popupTurnoverThresoldProduct: '#popupArticle',
            dataTable: '#lieferschwelle_artikel'
        },
        getNew: function () {
            $('#e_id').val('0');
            me.reset();
            $(me.selector.popupTurnoverThresoldProduct).dialog('open');
        },
        get: function (id) {
            $.ajax({
                url: 'index.php?module=lieferschwelle&action=artikeledit&cmd=get',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                beforeSend: function () {
                    App.loading.open();
                },
                success: function (data) {
                    $(me.selector.popupTurnoverThresoldProduct).find('#e_id').val(data.id);
                    $(me.selector.popupTurnoverThresoldProduct).find('#e_artikel').val(data.artikel);
                    $(me.selector.popupTurnoverThresoldProduct).find('#e_artempfaengerland').val(data.empfaengerland);
                    $(me.selector.popupTurnoverThresoldProduct).find('#e_artsteuersatz').val(data.steuersatz);
                    $(me.selector.popupTurnoverThresoldProduct).find('#e_bemerkung').val(data.bemerkung);
                    $(me.selector.popupTurnoverThresoldProduct).find('#e_revenue_account').val(data.revenue_account);
                    $(me.selector.popupTurnoverThresoldProduct).find('#e_aktiv').prop('checked', data.aktiv == 1 ? true : false);
                    App.loading.close();
                    $(me.selector.popupTurnoverThresoldProduct).dialog('open');
                }
            });
        },
        reset: function () {
            $(me.selector.popupTurnoverThresoldProduct).find('#e_id').val('');
            $(me.selector.popupTurnoverThresoldProduct).find('#e_artikel').val('');
            $(me.selector.popupTurnoverThresoldProduct).find('#e_artempfaengerland').val('');
            $(me.selector.popupTurnoverThresoldProduct).find('#e_artsteuersatz').val('');
            $(me.selector.popupTurnoverThresoldProduct).find('#e_bemerkung').val('');
            $(me.selector.popupTurnoverThresoldProduct).find('#e_revenue_account').val('');
            $(me.selector.popupTurnoverThresoldProduct).find('#e_aktiv').prop('checked', false);
        },
        reloadTable: function () {
            var oTableL = $('#lieferschwelle_artikel').dataTable();
            var tmp = $('.dataTables_filter input[type=search]').val();
            oTableL.fnFilter('%');
            oTableL.fnFilter(tmp);
        },
        delete: function (id) {
            var conf = confirm($(me.selector.popupTurnoverThresoldProduct).data('deletemessage'));
            if (conf) {
                $.ajax({
                    url: 'index.php?module=lieferschwelle&action=artikeldelete',
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
        save: function () {
            $.ajax({
                url: 'index.php?module=lieferschwelle&action=artikelsave',
                data: {
                    //Alle Felder die fürs editieren vorhanden sind
                    id: $('#e_id').val(),
                    artikel: $('#e_artikel').val(),
                    artempfaengerland: $('#e_artempfaengerland').val(),
                    artsteuersatz: $('#e_artsteuersatz').val(),
                    bemerkung: $('#e_bemerkung').val(),
                    revenue_account: $('#e_revenue_account').val(),
                    aktiv: $('#e_aktiv').prop('checked') ? 1 : 0
                },
                method: 'post',
                dataType: 'json',
                beforeSend: function () {
                    App.loading.open();
                },
                success: function (data) {
                    App.loading.close();
                    if (data.status == 1) {
                        me.reset();
                        me.reloadTable();
                        $(me.selector.popupTurnoverThresoldProduct).dialog('close');
                    } else {
                        alert(data.statusText);
                    }
                }
            });
        },
        registerEvents: function () {
            $(me.selector.dataTable).find('img.delete-product').on('click', function () {
                me.delete($(this).data('id'));
            });
            $(me.selector.dataTable).find('img.edit-product').on('click', function () {
                me.get($(this).data('id'));
            });
        },
        init: function () {
            $('#newproduct').on('click', function () {
                me.getNew();
            });
            $('#e_artikel').focus();

            $(me.selector.popupTurnoverThresoldProduct).dialog({
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

            $(me.selector.popupTurnoverThresoldProduct).dialog({
                close: function () { me.reset();}
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
    TurnoverThresholdProduct.init();
});
