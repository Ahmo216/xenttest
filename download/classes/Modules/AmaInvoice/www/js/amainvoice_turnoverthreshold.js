var AmaInvoiceTurnoverThreshold = function ($) {
    'use strict';

    var me = {
        storage: {
            json: null,
            actElement: null
        },
        selector: {
            jsonData: '#amainvoice-turnoverthreshold-data',
            storageSelect: '#e_storage',
            countryCodeInput: '#e_empfaengerland',
            vatNumberInput: '#e_ustid',
            activeSinceInput: '#e_ueberschreitung',
            inputInfos: 'span.amainvoice-input-info'
        },
        getStorageFromCountryCode: function (countryCode) {
            me.storage.actElement = null;
            if (me.storage.json === null) {
                return null;
            }
            $(me.storage.json).each(function (key, actElement) {
                if (actElement.country_code === countryCode) {
                    me.storage.actElement = actElement;
                }
            });
            $(me.selector.inputInfos).remove();
            if (me.storage.actElement === null) {
                return null;
            }
            $(me.selector.countryCodeInput).after(
                '<span class="amainvoice-input-info"><i>(gefunden in AmaInvoice)</i></span>'
            );
            $(me.selector.vatNumberInput).after(
                '<span class="amainvoice-input-info"><i>(AmaInvoice: ' + me.storage.actElement.vat_number +
                ')</i></span>'
            );
            if (me.storage.actElement.active_since !== null) {
                $(me.selector.activeSinceInput).after(
                    '<span class="amainvoice-input-info"><i>(AmaInvoice: ' + me.storage.actElement.active_since +
                    ')</i></span>'
                );
            }

            return me.storage.actElement;
        },
        init: function () {
            if ($(me.selector.jsonData).length) {
                me.storage.json = JSON.parse($(me.selector.jsonData).html());
            }
            $(me.storage.storageSelect).on('change', function () {
                $(me.selector.countryCodeInput).trigger('change');
            });
            $(me.selector.countryCodeInput).on('change', function () {
                me.getStorageFromCountryCode($(this).val());
            });

        }
    };

    return {
        init: me.init
    };

}(jQuery);

$(document).ready(function () {
    AmaInvoiceTurnoverThreshold.init();
});
