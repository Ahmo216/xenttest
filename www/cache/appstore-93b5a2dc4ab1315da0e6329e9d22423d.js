/********* appstore.js *********/ 
$(document).ready(function () {
    $('body').addClass('module-appstore');

    var appstore = {
        storage: {}
    };
    appstore.search = $('#appstore-search');
    appstore.categoryPage = $('.category-page');
    appstore.categoryBlock = $('.appstore-categories');
    appstore.popularBlock = $('.popular');
    appstore.categoryBack = $('.category-page-back');
    appstore.tabs = $('span.appstore-tab');
    appstore.activePhrase = '';
    appstore.activeCategory = '';
    if($('#modulesJson').length > 0) {
        appstore.storage.apps = JSON.parse($('#modulesJson').html());
    }

    appstore.tabs.on("click touch", function () {
        var self = $(this);

        appstore.tabs.removeClass('appstore-tab-active');
        self.addClass('appstore-tab-active');

        appTypeVisibility(self.data("filter"));
    });

    appstore.categoryBack.on("click touch", function () {
        window.history.back();
    });

    if(appstore.categoryPage.length > 0){
        $('#appstore .popular').hide();
    }

    /**
     *
     * @param {String} filter
     */
    function appTypeVisibility(filter){
        var overview = $('#appstore .overview'),
            popularApps = overview.find('.popular'),
            availableApps = overview.find('.available-apps')

        if(filter === 'installed'){
            popularApps.hide();
            availableApps.hide();
        }

        if(filter === 'all'){
            popularApps.show();

            if(availableApps.children().length > 0){
                availableApps.show();
            }
        }

        if(appstore.categoryPage.length > 0){
           popularApps.hide();
        }
    }

    // Testversion anfragen
    $('.testversion').on('click', function (e) {
        e.preventDefault();
        var hash = $(this).data('hash');

        if (typeof hash == 'undefined') {
            var $modulediv = $(this).parents('div.module').first();
            hash = typeof $modulediv[0].id != 'undefined' ? $modulediv[0].id : $modulediv.id;
        }
        getanfrage(hash);
    });

    // Suchen beim Tippen im Suchfeld
    appstore.search.on('keyup', function () {
        appstore.activePhrase = $(this).val()
        searchAppsByPhrase(appstore.activePhrase, appstore.activeCategory, appstore.categoryBlock, appstore.popularBlock);
    });

    /**
     * Dropdown-Button
     */
    $('.dropdown').each(function () {
        var $container = $(this);
        var $link = $container.children('a.dropdown-link');
        var $sublinks = $container.find('a.dropdown-sublink');
        var $caret = $link.children('.caret');

        // Dropdown öffen/schließen
        $caret.on('click', function (e) {
            e.preventDefault();
            $container.toggleClass('open');
        });

        // Dropdown schließen, wenn Fokus verloren geht
        $link.on('focusout', function () {
            setTimeout(function () {
                $container.removeClass('open');
            }, 300);
        });

        // Bei Button-Click: Dropdown öffnen, wenn Linkziel '#' ist; ansonsten Link folgen
        $link.on('click', function (e) {
            var linkTarget = $(this).attr('href');
            if (linkTarget === '#') {
                $container.addClass('open');
                e.preventDefault();
            }
        });

        // Dropdown-Links öffnen für iOs-Geräte
        $sublinks.on('touchend', function (e) {
            e.preventDefault();

            var linkTarget = $(this).attr('href');
            if (linkTarget !== '#') {
                window.open(linkTarget);
            }

            setTimeout(function () {
                $container.removeClass('open');
            }, 300);
        });
    });

    $('#anfragepopup').dialog(
        {
            modal: true,
            autoOpen: false,
            minWidth: 940,
            title: 'Testmodul anfragen',
            buttons: {
                'Testmodul anfragen': function () {
                    if (!confirmAppTest(false)) {
                        confirmAppTest(true);
                    }
                },
                'Abbrechen': function () {
                    $(this).dialog('close');
                }
            },
            close: function (event, ui) {

            }
        }
    );

    $('#anfrageokpopup').dialog(
        {
            modal: true,
            autoOpen: false,
            minWidth: 940,
            title: 'Testmodul anfragen',
            buttons: {
                OK: function () {
                    $(this).dialog('close');
                }
            },
            close: function (event, ui) {

            }
        }
    );

    $('a.activate').on('click', function (e) {
        e.preventDefault();
        $.ajax({
            url: 'index.php?module=appstore&action=list&cmd=' +
                ($(this).hasClass('deactivate') ? 'deactivate' : 'activate'),
            type: 'POST',
            dataType: 'json',
            data: {
                module: $(this).data('module')
            }
        }).done(function (data) {
            if (typeof data.status != 'undefined' && data.status == 1 && typeof data.module != 'undefined') {
                var $moda = $('*').find('[data-module=\'' + data.module + '\']');
                if ($moda.length) {
                    $($moda).toggleClass('activate');
                    $($moda).toggleClass('deactivate');
                    if ($($moda).hasClass('deactivate')) {
                        $($moda).html('Deaktivieren');
                        $($moda).parents('div.dropdown').first().find('a.dropdown-link').first().toggleClass(
                            'deactivated', false);
                        $($moda).parents('div.dropdown').first().find('a.dropdown-link').first().toggleClass(
                            'activated', true);
                    } else {
                        $($moda).html('Aktivieren');
                        $($moda).parents('div.dropdown').first().find('a.dropdown-link').first().toggleClass(
                            'deactivated', true);
                        $($moda).parents('div.dropdown').first().find('a.dropdown-link').first().toggleClass(
                            'activated', false);
                    }
                }
            } else if (typeof data.error != 'undefined') {
                alert($data.error);
            }
        });
    });
    $('a.deactivate').on('click', function (e) {
        e.preventDefault();
        $.ajax({
            url: 'index.php?module=appstore&action=list&cmd=' +
                ($(this).hasClass('deactivate') ? 'deactivate' : 'activate'),
            type: 'POST',
            dataType: 'json',
            data: {
                module: $(this).data('module')
            }
        }).done(function (data) {
            if (typeof data.status != 'undefined' && data.status == 1 && typeof data.module != 'undefined') {
                var $moda = $('*').find('[data-module=\'' + data.module + '\']');
                if ($moda.length) {
                    $($moda).toggleClass('activate');
                    $($moda).toggleClass('deactivate');
                    if ($($moda).hasClass('deactivate')) {
                        $($moda).html('Deaktivieren');
                        $($moda).parents('div.dropdown').first().find('a.dropdown-link').first().toggleClass(
                            'deactivated', false);
                        $($moda).parents('div.dropdown').first().find('a.dropdown-link').first().toggleClass(
                            'activated', true);
                    } else {
                        $($moda).html('Aktivieren');
                        $($moda).parents('div.dropdown').first().find('a.dropdown-link').first().toggleClass(
                            'deactivated', true);
                        $($moda).parents('div.dropdown').first().find('a.dropdown-link').first().toggleClass(
                            'activated', false);
                    }
                }
            } else if (typeof data.error != 'undefined') {
                alert($data.error);
            }
        });
    });
});

function confirmAppTest(showError) {
    var ok = false;
    $.ajax({
        url: 'index.php?module=appstore&action=list',
        type: 'POST',
        dataType: 'json',
        async: false,
        data: {modulbestaetigen: $('#anfragemd5').val(), testen: 1},
        success: function (data) {
            if (typeof data.html != 'undefined' && data.html != '') {
                $('#anfragepopup').dialog('close');
                $('#anfragemd5').val('');
                $('#anfrageokpopup').dialog('open');
                $('#anfrageoknachricht').html(data.html);
                ok = true;
            } else {
                if (showError) {
                    $('#anfragepopup').dialog('close');
                    $('#anfragemd5').val('');
                    $('#anfrageokpopup').dialog('open');
                    $('#anfrageoknachricht').html(
                        '<div class="error">Es ist ein Fehler bei der Anfrage aufgetreten.</div>'
                    );
                }
            }
        },
        beforeSend: function () {

        }
    });

    return ok;
}

function getanfrage(anfragemd5) {
    $('#anfragemd5').val(anfragemd5);
    $('#anfragepopup').dialog('open');
}

/**
 *
 * @param {String} phrase
 * @param {String} category
 * @param {jQuery} categoryBlock
 * @param {jQuery} popularBlock
 */
function searchAppsByPhrase(phrase, category, categoryBlock, popularBlock) {
    $.ajax({
        url: 'index.php?module=appstore&action=list&cmd=suche',
        type: 'POST',
        dataType: 'json',
        data: {
            val: phrase,
            category: category
        }
    })
     .done(function (data) {
         if (typeof data === 'undefined' || data === null) {
             return;
         }

         if (typeof data.ausblenden !== 'undefined' && data.ausblenden !== null) {
             $.each(data.ausblenden, function (k, v) {
                 if (k != '') {
                     $('#' + k).hide();
                 }
             });
         }
         if (typeof data.anzeigen !== 'undefined' && data.anzeigen !== null) {
             $.each(data.anzeigen, function (k, v) {
                 if (k != '') {
                     $('#' + k).show();
                 }
             });
         }
         if (typeof data.katausblenden !== 'undefined' && data.katausblenden !== null) {
             $.each(data.katausblenden, function (k, v) {
                 if (k != '') {
                     $('#' + k).hide();
                 }
             });
         }
         if (typeof data.kateinblenden !== 'undefined' && data.kateinblenden !== null) {
             $.each(data.kateinblenden, function (k, v) {
                 if (k != '') {
                     $('#' + k).show();
                 }
             });
         }
         // Meldung anzeigen wenn keine Module gefunden wurden
         if (typeof data.installiertgefunden !== 'undefined' && data.installiertgefunden !== null) {
             if (parseInt(data.installiertgefunden) === 0) {
                 $('.purchases').hide();
             } else {
                 $('.purchases').show();
             }
         }
         // "Käufe" ein-/ausblenden
         if (typeof data.kaufbargefunden !== 'undefined' && data.kaufbargefunden !== null) {
             if (parseInt(data.kaufbargefunden) === 0) {
                 $('#no-apps-found').show();
             } else {
                 $('#no-apps-found').hide();
             }
         }


         if(phrase.length > 0){
             categoryBlock.hide();
             categoryBlock.prev('h2').hide();

             popularBlock.hide();
         } else {
             categoryBlock.show();
             categoryBlock.prev('h2').show();

             popularBlock.show();
         }
     });
}


/********* appstore_buy.js *********/ 
var AppstoreBuy = function ($) {
    'use strict';

    var me = {

        storage: {
            actualType: null,
            oldValue: null,
            newValue: null,
            hubspot: null,
            sent: null,
            conversationValue: null,
            campaignLabel: null
        },

        elem: {
            $landingTotalPrice: null,
            $landingTotalPriceTimespan: null,
            $landingUserCounter: null,
            $landingUserCounterPopup: null
        },

        updateKey: function () {
            $.ajax({
                url: 'index.php?module=welcome&action=start&cmd=updatekey',
                type: 'POST',
                dataType: 'text',
                data: {},
                success: function () {
                    $.ajax({
                        url: 'index.php?module=appstore&action=buy&cmd=getbuyinfo',
                        type: 'POST',
                        dataType: 'text',
                        data: {},
                        success: function () {
                            window.location.href = 'index.php?module=appstore&action=buy';
                        },
                        beforeSend: function () {

                        },
                        error: function () {
                            $('#modalbeta').parent().loadingOverlay('remove');
                            $('#tabs-1').loadingOverlay('remove');
                        }
                    });
                }
            });
        },

        bindUnBuyButton: function ($element) {
            me.storage.oldValue = $($element).data('oldvalue');
            me.storage.newValue = $($element).data('newvalue');
            $('#unnewvalue').val(me.storage.newValue);
            me.storage.price = parseFloat($($element).data('price'));
            me.storage.actualType = $($element).data('type');
            if (me.storage.actualType === 'delete_module' || me.storage.actualType === 'delete_all') {
                $('#unnewvalue').hide();
            } else {
                $('#unnewvalue').show();
            }
            $('#modulunbuytext').html($($element).data('info'));
            $('#modalunbuy').dialog('open');
        },

        buyFromDemoSend: function () {
            $.ajax({
                url: 'index.php?module=appstore&action=buy&cmd=buyfromdemo',
                type: 'POST',
                dataType: 'json',
                data: {
                    company: $('#customercompany').val(),
                    email: $('#customeremail').val(),
                    name: $('#customername').val(),
                    street: $('#customerstreet').val(),
                    street2: $('#customeraddress2').val(),
                    zip: $('#customerzip').val(),
                    city: $('#customercity').val(),
                    country: $('#customercountry').val(),
                    bank: $('#customerbank').val(),
                    bankname: $('#customerbankname').val(),
                    iban: $('#customeriban').val(),
                    bic: $('#customerbic').val(),
                    user: $('#customeruser').val(),
                    lightuser: $('#customerlightuser').val(),
                    agreement: $('#customeragreement').prop('checked') ? 1 : 0,
                    change: ($('#buyfromdemo').length === 0) ? 1 : 0
                },
                success: function (data) {
                    me.storage.sent = true;
                    if (data.errorMessage !== undefined) {
                        me.setError(data);
                    }
                    if (data.status === 'OK') {
                        me.hubspotSend($('#buyversion').data('hubspoteventok'));
                        me.updateKey();
                    } else {
                        me.hubspotSend($('#buyversion').data('hubspoteventerror'));
                        //TODO remove loading
                    }
                },
                beforeSend: function () {

                },
                error: function () {
                    //TODO remove loading
                }
            });
            me.hubspotSend($('#buyversion').data('hubspotevent'));
        },

        setError: function (error) {
            var current, i, inputField,
                errorMessage = 'Bitte ergänze die markierten Pflichtfelder',
                $errorContainer = $('#buyversion').next('div'),
                $errorHint = $errorContainer.find('.error-hint');

            if (error.length === 0) {
                return;
            }

            if (typeof error.errorMessage === 'string' || error.errorMessage instanceof String) {
                errorMessage = error.errorMessage;
            }

            if ($errorHint.length > 0) {
                $errorHint.html(errorMessage);
            } else {
                $errorContainer.append('<p class="error-hint">' + errorMessage + '</p>');
            }

            if (error.invalidFields === undefined || error.invalidFields.length === 0) {
                return;
            }

            $('[id^="customer"]').removeClass('input-error');

            for (i = 0; i < error.invalidFields.length; i++) {
                current = error.invalidFields[i];
                inputField = $('#customer' + current);

                if (inputField.length > 0) {
                    inputField.addClass('input-error');
                }
            }
        },
        updateDataLayerScript: function() {
            if($('#datalayerscript').length > 0) {
                $('#datalayerscript').remove();
            }
            $('body').append(
                '<script id="datalayerscript">\n' +
                '  dataLayer = [{\n' +
                '    \'conversionValue\': \'' + (me.storage.conversationValue) + '\'\n' +
                '  },' +
                '  {' +
                '    \'campaign\': \'' + (me.storage.campaignLabel) + '\'\n' +
                '  }];\n' +
                '</script>'
            );
        },
        initBuyVersion: function () {
            var buyFromDemo = $('#buyfromdemo'),
                buyVersionContainer = $('#buyversion');

            if ($('.buy-licence-landingpage').length) {
                buyVersionContainer.dialog(
                    {
                        modal: true,
                        autoOpen: false,
                        width: '70%',
                        title: '',
                        buttons: {
                            'Jetzt mieten': function () {

                                me.buyFromDemoSend();
                            }
                        },
                        close: function () {
                            if (me.storage.sent === null) {
                                me.hubspotSend($('#buyversion').data('hubspoteventabort'));
                            }
                        }
                    }
                );
            }

            buyFromDemo.on('click', function () {
                buyVersionContainer.dialog('open');
                me.hubspotSend($(this).data('hubspotevent'), $('#customeruserdemo').val());
            });

            $('#changecustomerinfos').on('click', function () {
                if ($('#buy-licence-landingpage').length > 0) {
                    me.storage.sent = null;
                    $('#buyversion').dialog('open');
                } else {
                    me.storage.oldValue = $(this).data('oldvalue');
                    var diffuser = parseInt($('#customeruser').val());// - parseInt(me.storage.oldValue);
                    me.storage.newValue = diffuser;
                    $('#newvalue').val(me.storage.newValue);
                    me.storage.price = parseFloat($(this).data('price'));
                    me.storage.actualType = 'add_user';
                    $('#newvalue').hide();
                    $('#modulbuytext').html(
                        'Weitere ' + diffuser + ' mieten für ' + (me.storage.price * diffuser) + ' EUR pro Monat?');
                    $('#modalbuy').dialog('open');
                }
            });
            if ($('#customerinfocontent').length === 0) {
                $('#buyversion + .ui-dialog-buttonpane').append('<div class="buy-version-legal">' +
                    '                <input type="checkbox" id="customeragreement">' +
                    '                <label for="customeragreement"> Ich habe die' +
                    '                    <a href="index.php?module=dataprotection&action?list" title="Datenschutzbestimmungen" target="_blank">Datenschutzbestimmungen</a> gelesen und akzeptiere die' +
                    '                    <a href="https://xentral.com/agb" title="Allgemeine Geschäftsbedingungen" target="_blank">AGB</a>.' +
                    '                </label>' +
                    '            </div>');
            }
            me.hubspotSend($('#buyversion').data('hubspoteventinit'));
        },

        initLandingpage: function () {
            if (me.elem.$landingUserCounter.length === 0) {
                return;
            }
            me.storage.campaignLabel = me.elem.$landingTotalPrice.data('campaign') + '';
            var initialCostPerUser = parseInt(me.elem.$landingTotalPrice.data('userprice')),
                cloudPrice = parseInt(me.elem.$landingCloudPricePopup.data('cloudprice')),
                numberOfUser = parseInt($('#customeruserdemo').val());

            me.setUserLicencePrice(numberOfUser, initialCostPerUser, cloudPrice);

            me.elem.$landingUserCounter.on('change', function () {
                numberOfUser = parseInt(this.value);
                me.setUserLicencePrice(numberOfUser, initialCostPerUser, cloudPrice);
            });
            me.elem.$landingUserCounterPopup.on('change', function () {
                numberOfUser = parseInt(this.value);
                me.setUserLicencePrice(numberOfUser, initialCostPerUser, cloudPrice);
            });
        },

        /**
         *
         * @param {Number} numberOfUser
         * @param {Number} initialCostPerUser
         * @param {Number} cloudPrice
         */
        setUserLicencePrice: function (numberOfUser, initialCostPerUser, cloudPrice) {
            if (isNaN(numberOfUser)) {
                numberOfUser = 0;
            }
            if (isNaN(initialCostPerUser)) {
                initialCostPerUser = 0;
            }
            if (isNaN(cloudPrice)) {
                cloudPrice = 0;
            }
            me.storage.conversationValue = numberOfUser * initialCostPerUser + cloudPrice;
            me.elem.$landingTotalPrice.html(me.storage.conversationValue);
            if (me.elem.$landingTotalPricePopup !== null) {
                me.elem.$landingTotalPricePopup.html(me.storage.conversationValue);
            }
            me.elem.$landingTotalPriceTimespan.html(numberOfUser + ' User/Monat');
            me.elem.$landingTotalPriceTimespanPopup.html(numberOfUser + ' User/Monat');
            me.updateDataLayerScript();
        },

        hubspotSend: function (eventName, value) {
            if (me.storage.hubspot === null) {
                return;
            }
            if (value === undefined || value === null) {
                me.storage.hubspot.push(['trackEvent', {id: eventName}]);
                return;
            }

            me.storage.hubspot.push(['trackEvent', {id: eventName, value: value}]);
        },

        init: function () {
            $('#fieldsetmodules').hide();
            if ($('.buy-licence-landingpage').length > 0
                && $('.buy-licence-landingpage').data('hubspotactive') + '' === '1') {
                me.storage.hubspot = window._hsq = window._hsq || [];
            }
            me.elem.$landingTotalPrice = $('#landing-total-price');
            me.elem.$landingTotalPricePopup = $('#landing-total-price-popup');
            me.elem.$landingTotalPriceTimespan = $('#landing-total-price-timespan');
            me.elem.$landingTotalPriceTimespanPopup = $('#landing-total-price-timespan-popup');
            me.elem.$landingCloudPricePopup = $('#landing-cloud-price');
            me.elem.$landingUserCounter = $('.buy-licence-landingpage .counter-component input');
            me.elem.$landingUserCounterPopup = $('#buyversion .counter-component input');

            $('#customeruserdemo').on('change', function () {
                $('#customeruser').val($(this).val());
                $('#customeruser').trigger('change');
            });
            $('#customeruser').on('change', function () {
                if ($('#customeruserdemo').length > 0) {
                    $('#customeruserdemo').val($(this).val());
                }
            });


            me.initLandingpage();

            if ($('#buyversion').length > 0) {
                me.initBuyVersion();
            }

            if ($('#changecustomerinfos').length > 0) {
                $('#customerInfoTable').append(
                    '<input checked hidden style="display: none" type="checkbox" id="customeragreement">');
            }
            $('#unbuylightuser').hide();
            $('#unbuyuser').hide();
            $.ajax({
                url: 'index.php?module=appstore&action=buy&cmd=getbuyinfo',
                type: 'POST',
                dataType: 'json',
                data: {},
                success: function (data) {
                    $('#buyed').html(data.data);
                    $('#buyed').find('input.unbuybutton').on('click', function () {
                        me.bindUnBuyButton($(this));
                    });
                    if (typeof data.user != 'undefined') {
                        $('#unbuyuser').show();
                        $('#unbuyuser').data('oldvalue', data.user);
                        if (data.user !== data.maxuser) {
                            $('#usercount').attr('disabled', 'disabled');
                            $('#unbuyuser').attr('disabled', 'disabled');
                        }
                    } else {
                        $('#unbuyuser').hide();
                    }
                    if (typeof data.lightuser != 'undefined') {
                        $('#unbuylightuser').show();
                        $('#unbuylightuser').data('oldvalue', data.lightuser);
                        if (data.lightuser !== data.maxlightuser) {
                            $('#lightusercount').attr('disabled', 'disabled');
                            $('#unbuylightuser').attr('disabled', 'disabled');
                        }
                    } else {
                        $('#unbuylightuser').hide();
                    }
                    if (typeof data.customerinfo != 'undefined') {
                        $('#customercompany').val(data.name);
                        $('#customeremail').val(data.email);
                        $('#customerstreet').val(data.strasse);
                        $('#customername').val(data.ansprechpartner);
                        $('#customeraddress2').val(data.adresszusatz);
                        $('#customerzip').val(data.plz);
                        $('#customercity').val(data.ort);
                        $('#customercountry').val(data.land);
                        $('#customerbankname').val(data.inhaber);
                        $('#customerbank').val(data.bank);
                        $('#customerbic').val(data.swift);
                        $('#customeriban').val(data.iban);
                        //$('#customeruser').val(data.maxuser);
                        //$('#customerinfocontent').html(data.customerinfo);
                    }
                    if (data.data !== '') {
                        $('#buyedmodule').html(data.data);
                        $('#fieldsetmodules').show();
                    }
                }
            });
            $('#modalbeta').dialog(
                {
                    modal: true,
                    autoOpen: false,
                    width: '70%',
                    title: '',
                    buttons: {
                        'Ja ich möchte immer Zugriff auf die nächste Beta Version haben': function () {
                            $('#modalbeta').parent().loadingOverlay();
                            $.ajax({
                                url: 'index.php?module=appstore&action=buy&cmd=activatebeta',
                                type: 'POST',
                                dataType: 'json',
                                data: {},
                                success: function (data) {
                                    if (data.status === 'OK') {
                                        me.updateKey();
                                    } else {
                                        $('#modalbeta').parent().loadingOverlay('remove');
                                    }
                                },
                                beforeSend: function () {

                                },
                                error: function () {
                                    $('#modalbeta').parent().loadingOverlay('remove');
                                }
                            });
                        }
                    }
                }
            );

            $('#modalunbuy').dialog(
                {
                    modal: true,
                    autoOpen: false,
                    width: '70%',
                    title: '',
                    buttons: {
                        Abbrechen: function () {
                            $(this).dialog('close');
                        },
                        'Kündigen': function () {
                            if (me.storage.actualType !== 'delete_module') {
                                me.storage.newValue = parseInt($('#unnewvalue').val());
                            }
                            if (me.storage.actualType === 'delete_module' || me.storage.actualType === 'delete_all' ||
                                me.storage.newValue > 0) {
                                if (confirm('Wirklich kündigen?')) {
                                    $('#modalunbuy').parent().loadingOverlay();
                                    $.ajax({
                                        url: 'index.php?module=appstore&action=buy&cmd=sendbuy',
                                        type: 'POST',
                                        dataType: 'json',
                                        data: {
                                            old: me.storage.oldValue,
                                            new: me.storage.actualType !== 'delete_module'
                                                ? me.storage.oldValue - me.storage.newValue : me.storage.newValue,
                                            field: me.storage.actualType
                                        },
                                        success: function (data) {
                                            if (typeof data.error != 'undefined') {
                                                alert(data.error);
                                                $('#modalunbuy').parent().loadingOverlay('remove');
                                                return;
                                            }
                                            if (typeof data.url != 'undefined') {
                                                me.updateKey();
                                            }
                                        },
                                        beforeSend: function () {

                                        },
                                        error: function () {
                                            $('#modalunbuy').parent().loadingOverlay('remove');
                                        }
                                    });
                                }
                            }
                        }
                    },
                    close: function (event, ui) {

                    }
                });

            $('#modalbuy').dialog(
                {
                    modal: true,
                    autoOpen: false,
                    width: '50%',
                    title: '',
                    buttons: {
                        Abbrechen: function () {
                            $(this).dialog('close');
                        },
                        Mieten: function () {
                            if (me.storage.actualType !== 'add_module') {
                                me.storage.newValue = parseInt($('#newvalue').val());
                            }
                            if (me.storage.actualType === 'add_module' || me.storage.newValue > 0) {
                                if (confirm('Wirklich für ' + (
                                    Number.parseFloat(me.storage.price
                                        * (me.storage.actualType === 'add_module' ? 1 : me.storage.newValue)).toFixed(2)
                                ) + ' EUR mieten?')) {
                                    $('#modalbuy').parent().loadingOverlay();
                                    $.ajax({
                                        url: 'index.php?module=appstore&action=buy&cmd=sendbuy',
                                        type: 'POST',
                                        dataType: 'json',
                                        data: {
                                            old: me.storage.oldValue,
                                            new: me.storage.actualType !== 'add_module'
                                                ? me.storage.oldValue + me.storage.newValue : me.storage.newValue,
                                            field: me.storage.actualType
                                        },
                                        success: function (data) {
                                            if (typeof data.error != 'undefined') {
                                                alert(data.error);
                                                $('#modalbuy').parent().loadingOverlay('remove');
                                                return;
                                            }
                                            if (typeof data.url != 'undefined') {
                                                me.updateKey();
                                            }
                                        },
                                        beforeSend: function () {

                                        },
                                        error: function () {
                                            $('#modalbuy').parent().loadingOverlay('remove');
                                        }
                                    });
                                }
                            }
                        }
                    },
                    close: function (event, ui) {

                    }
                });

            $('input.buybutton').on('click', function () {
                me.bindBuyButton(this);
            });
            $('a.buybutton').on('click', function () {
                me.bindBuyButton(this);
            });
            $('input.unbuybutton').on('click', function () {
                me.bindUnBuyButton($(this));
            });

            $('input.buybutton.autoopen').trigger('click');
        },
        bindBuyButton: function(element)
        {
            me.storage.oldValue = $(element).data('oldvalue');
            me.storage.newValue = $(element).data('newvalue');
            $('#newvalue').val(me.storage.newValue);
            me.storage.price = parseFloat($(element).data('price'));
            me.storage.actualType = $(element).data('type');
            if (me.storage.actualType === 'add_module') {
                $('#newvalue').hide();
            } else {
                $('#newvalue').show();
            }
            $('#modulbuytext').html($(element).data('info'));
            $('#modalbuy').dialog('open');
        }
    };
    return {
        init: me.init
    };

}(jQuery);

$(document).ready(function () {
    AppstoreBuy.init();
});


/********* counter-component.js *********/ 
function CounterComponent(self) {
    'use strict';
    var me = {
        storage: {
            maximum: null
        },
        elem: {
            $self: null,
            $sub: null,
            $plus: null,
            $counter: null
        },
        init: function (self) {
            me.elem.$self = self;
            me.elem.$sub = self.find('.sub-button');
            me.elem.$plus = self.find('.plus-button');
            me.elem.$counter = self.find('input');
            me.storage.maximum = me.elem.$self.data('max');

            if (me.elem.$sub.length === 0 ||
                me.elem.$plus.length === 0 ||
                me.elem.$counter.length === 0) {
                return;
            }

            me.registerEvents();
        },

        registerEvents: function () {
            me.elem.$sub.on('click touch', function () {
                if(me.elem.$counter[0].value > 1){
                    me.elem.$counter[0].value--;

                    me.elem.$counter.trigger('change');
                }
            });

            me.elem.$plus.on('click touch', function () {
                if(me.storage.maximum !== undefined && me.storage.maximum !== null){
                    if(me.elem.$counter[0].value < me.storage.maximum){
                        me.elem.$counter[0].value++;
                    }
                } else {
                    me.elem.$counter[0].value++;
                }
                
                me.elem.$counter.trigger('change');
            });
        }

    }

    me.init(self)
}

$(document).ready(function () {
    $('.counter-component').each(function () {
        new CounterComponent($(this));
    })
});

