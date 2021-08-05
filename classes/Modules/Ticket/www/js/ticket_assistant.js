var TicketAssistant = (function ($) {
    'use strict';

    var me = {
        storage: {
            saveTimer: null,
            savedComment: '',
            savedtags: ''
        },
        selector: {
            ticketAssistantForm: '#ticketassistent'
        },
        init: function () {
            $('#changeticket').on('change', function () {
                me.onChangeTicket($(this).val());
            });
            $('label.label-send-form').on('click', function () {
                me.sendForm($(this).data('send-form'));
            });
            clearTimeout(me.storage.saveTimer);
            me.storage.saveTimer = setTimeout(function () {
                me.saveComment();
            }, 300);
            $('#savecommentcrm').on('click', function () {
                if ($(this).val() + '' === '') {
                    return;
                }
                $.ajax({
                    url: 'index.php?module=ticket&action=savecommentcrm',
                    type: 'POST',
                    data: {
                        ticketId: $(me.selector.ticketAssistantForm).data('ticket-id'),
                        ticketKommentar: $('.jsComment').val(),
                        ticketTags: $('.jstags').val()
                    },
                    beforeSend: function () {
                        App.loading.open();
                        var saveSpan = $('<span />');
                        saveSpan.css({
                            position: 'absolute',

                            display: 'block',
                            padding: '2px',
                            color: '#555'
                        }).text('Kommentar wird gespeichert.');

                    },
                    success: function (data) {

                        App.loading.close();
                    }, fail: function () {

                    }
                });
            });
        },
        saveComment: function () {
            var jsComment = $('.jskommentar').val();
            var jstags = $('.jstags').val();
            if (me.storage.savedComment !== jsComment || me.storage.savedtags !== jstags) {
                $.ajax({
                    url: 'index.php',
                    data: {
                        module: 'ticket',
                        action: 'savekommentar',
                        ticketId: $(me.selector.ticketAssistantForm).data('ticket-id'),
                        ticketKommentar: jsComment,
                        ticketTags: jstags
                    },
                    beforeSend: function () {
                        App.loading.open();
                        var saveSpan = $('<span />');
                        saveSpan.css({
                            position: 'absolute',

                            display: 'block',
                            padding: '2px',
                            color: '#555'
                        })
                                .text('Kommentar wird gespeichert.');
                    },
                    success: function (data) {
                        me.storage.savedComment = jsComment;
                        me.storage.savedtags = jstags;
                        clearTimeout(me.storage.saveTimer);
                        me.storage.saveTimer = setTimeout(function () {
                            me.saveComment();
                        }, 1300);
                        console.log(data);
                        App.loading.close();
                    }, fail: function () {
                        clearTimeout(me.storage.saveTimer);
                        me.storage.saveTimer = setTimeout(function () {
                            me.saveComment();
                        }, 1300);
                    }
                });
            } else {
                clearTimeout(me.storage.saveTimer);
                me.storage.saveTimer = setTimeout(function () {
                    me.saveComment();
                }, 1300);
            }
        },
        onChangeTicket: function (cmd) {
            switch (cmd) {
                case 'adresseedit':
                    window.open(
                        'index.php?module=adresse&action=edit&id='
                        + $(me.selector.ticketAssistantForm).data('addressid')
                    );
                    break;
                case 'wiedervorlage':
                    if (
                        !confirm(
                            'Soll eine Wiedervorlage angelegt werden?')
                    ) {
                        return document.getElementById('changeticket').selectedIndex = 0;
                    }
                    window.open(
                        'index.php?module=ticket&action=assistent&cmd=wiedervorlage&id='
                        + $(me.selector.ticketAssistantForm).data('ticket-message-id')
                    );
                    break;
                case 'aufgabe':
                    if (!confirm('Soll eine Aufgabe angelegt werden?')) {
                        return document.getElementById('changeticket').selectedIndex = 0;
                    }
                    window.open(
                        'index.php?module=ticket&action=assistent&cmd=aufgabe&id='
                        + $(me.selector.ticketAssistantForm).data('ticket-message-id')
                    );
                    break;
                case 'adressecreate':
                    if (!confirm('Soll wirklich eine neue Adresse angelegt werden?')) {
                        return document.getElementById('changeticket').selectedIndex = 0;
                    }

                    window.open(
                        'index.php?module=ticket&action=assistent&cmd=adressecreate&id='
                        + $(me.selector.ticketAssistantForm).data('ticket-message-id')
                    );
                    break;
            }
        },
        sendForm: function (button) {

            if (button === 'spam') {
                $('input[name=\'antwort\'][value=\'spam\']').attr('checked', 'checked');
            }

            if (button === 'zuordnen') {
                $('input[name=\'antwort\'][value=\'zuordnen\']').attr('checked', 'checked');
            }

            if (button === 'beantwortet') {
                $('input[name=\'antwort\'][value=\'beantwortet\']').attr('checked', 'checked');
            }

            if (button === 'sofort') {
                $('input[name=\'antwort\'][value=\'sofort\']').attr('checked', 'checked');
            }
            if (button === 'forward') {
                $('input[name=\'antwort\'][value=\'forward\']').attr('checked', 'checked');
            }

            document.getElementById('ticketassistent').submit();
        }
    };

    return {
        init: me.init,
        sendForm: me.sendForm
    };
})(jQuery);

$(document).ready(function () {
    if ($('#ticketassistent').length) {
        TicketAssistant.init();
    }
});
