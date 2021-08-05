/* global App, $, jQuery */
var IbanValidation = (function ($) {
    'use strict';

    var me = {

        isInitialized: false,

        storage: {
            $iban: $("input[name='iban']")
        },

        /**
         * @return void
         */
        init: function () {
            if (me.isInitialized === true) {
                return;
            }

            me.registerEvents();

            me.isInitialized = true;
        },

        /**
         * @return {void}
         */
        registerEvents: function () {
            $("input[name='iban']").on('blur', function () {
                var iban = $(this).val();

                if(iban !== undefined && iban !== '') {
                    me.checkIban(iban);
                }
            });
        },

        /**
         *
         * @param {string} elementValue
         *
         * @return {void}
         */
        checkIban: function (elementValue) {
            $.ajax({
                url: 'index.php?module=ajax&action=ibanvalidation',
                data: {
                    iban: elementValue
                },
                method: 'post',
                dataType: 'json',
                beforeSend: function () {
                    App.loading.open();
                },
                success: function (data) {
                    App.loading.close();
                    if(data.status){
                        me.storage.$iban.removeClass("validation-error");
                        $(".validation-error-message").remove();
                    }else{
                        me.storage.$iban.addClass("validation-error");
                        if(!$(".validation-error-message")[0]) {
                            me.storage.$iban.after(
                                '<br /><span class="validation-error-message">' + data.message +
                                '</span>');
                        }
                    }
                }
            });
        },
    };

    return {
        init: me.init,
    };

})(jQuery);

$(document).ready(function () {
    IbanValidation.init();
});
