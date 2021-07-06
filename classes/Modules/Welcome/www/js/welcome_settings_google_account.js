/**
 * Run SQL Query and receive feedback about the success.
 */
var GoogleAuth = (function ($) {
    'use strict';

    var me = {
        isInitialized: false,
        formId: null,
        buttonId: null,
        postFieldName: null,
        url: {
            ajaxAccountExists: 'index.php?module=welcome&action=settings&cmd=google-account-exists'
        },

        mailForm: {
            formId: '#form-google-mail',
            buttonId: '#btn-google-mail-auth',
            fieldName: 'submit_authorize_gmail',
            scope: 'mail'
        },

        calendarForm: {
            formId: '#form-google-calendar',
            buttonId: '#btn-google-calendar-auth',
            fieldName: 'authorize_google_calendar',
            scope: 'calendar'
        },

        init: function () {
            if (me.isInitialized === true) {
                return;
            }
            me.registerEvents();
            me.isInitialized = true;
        },

        registerEvents: function () {
            $(me.mailForm.buttonId).on('click', function (event) {
                event.preventDefault();
                me.ajaxAccountExists(me.mailForm);
            });
            $(me.calendarForm.buttonId).on('click', function (event) {
                event.preventDefault();
                me.ajaxAccountExists(me.calendarForm);
            });
        },

        submitForm: function (form) {
            var $form = $(form.formId);
            $form.append('<input type="hidden" name="' + form.fieldName + '" value="1" /> ');
            $form.submit();
        },

        ajaxAccountExists: function(form) {
            $.ajax({
                url: me.url.ajaxAccountExists,
                dataType: 'json',
                method: 'post',
                data: {
                    scope: form.scope
                },
                beforeSend: function () {
                    App.loading.open();
                },
                success: function (data) {
                    App.loading.close();
                    if (data.account_exists === true && data.is_scope_granted === true) {
                        var confirmValue = confirm('Soll die Verbindung zu Google geschlossen und neu aufgebaut werden?');
                        if (confirmValue === false) {
                            return;
                        }
                    }
                    me.submitForm(form);
                },
                error: function (xhr, status, httpStatus) {
                    App.loading.close();
                    throw('request error: ' + xhr.responseText);
                },
            });
        },
    };

    return {
        init: me.init,
    };

})(jQuery);

$(document).ready(function () {
    if($('#btn-google-mail-auth').length === 0) {
        return
    }
    GoogleAuth.init();
});
