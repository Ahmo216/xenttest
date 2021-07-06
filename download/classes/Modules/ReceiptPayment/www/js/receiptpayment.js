var ReceiptPayment = function ($) {
    'use strict';

    var me = {
        selector: {
            paymentDataTable: '#zahlungseingang_list',
            challengePopup: '#challengepopup'
        },
        init: function () {
            if ($(me.selector.paymentDataTable).length > 0) {
                $(me.selector.paymentDataTable).on('afterreload', function () {
                    var collection = [];
                    $(me.selector.paymentDataTable).find('.label-container').each(function () {
                        var $self = $(this);
                        if (parseInt($self.data('value')) === 0) {
                            return;
                        }
                        collection[$self.data('id')] = [
                            {
                                title: $self.data('value'),
                                type: $self.data('id'),
                                target: $self.data('id'),
                                bgcolor: '#aa2222'
                            }
                        ];

                    });
                    LabelRenderer.render(collection);
                });
                $(me.selector.paymentDataTable).trigger('afterreload');
            }
            if ($(me.selector.challengePopup).length) {
                $(me.selector.challengePopup).dialog(
                    {
                        modal: true,
                        autoOpen: true,
                        minWidth: 940,
                        title: '',
                        buttons: {
                            OK: function () {
                                $('#challengepopupform').trigger('submit');
                            },
                            ABBRECHEN: function () {
                                $(this).dialog('close');
                            }
                        },
                        close: function (event, ui) {

                        }
                    });
            }
        }
    };

    return {
        init: me.init
    };

}(jQuery);

$(document).on('ready', function () {
    ReceiptPayment.init();
});
