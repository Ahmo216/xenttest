/********* onlineshop_appnew.js *********/ 
var ShopimportAppNew = function ($) {
    'use strict';

    var me = {
        selector: {
            vueAppNewError: '#onlineshop-appnew-error',
            vueAppNew: '#onlineshop-appnew',
            vueAppNewJson: '#onlineshop-appnewjson'
        },
        initAppNewErrorVue: function () {
            new Vue({
                el: me.selector.vueAppNewError,
                data: {
                    showAssistant: true,
                    pagination: true,
                    allowClose: true,
                    pages: [
                        {
                            type: 'defaultPage',
                            icon: 'password-icon',
                            headline: 'Request ungültig',
                            subHeadline: $(me.selector.vueAppNewError).data('errormsg'),

                            ctaButtons: [
                                {
                                    title: 'OK',
                                    action: 'close'
                                }]
                        }
                    ]
                }
            });
        },
        initAppNewVue: function () {
            new Vue({
                el: me.selector.vueAppNew,
                data: {
                    showAssistant: true,
                    pagination: true,
                    allowClose: true,
                    pages: [
                        {
                            type: 'form',
                            dataRequiredForSubmit: {
                                data: JSON.stringify($(me.selector.vueAppNew).data('appnewdata'))
                            },
                            submitType: 'submit',
                            submitUrl: 'index.php?module=onlineshops&action=appnew&cmd=createdata',
                            headline: $(me.selector.vueAppNew).data('heading'),
                            subHeadline: $(me.selector.vueAppNew).data('info'),
                            form:
                                [
                                    {
                                        id: 0,
                                        name: 'create-shop',
                                        inputs: [
                                            {
                                                type: 'select',
                                                name: 'shopId',
                                                label: 'Auswahl',
                                                validation: true,
                                                options: JSON.parse($(me.selector.vueAppNewJson).html())
                                            }]
                                    }]
                            ,
                            ctaButtons: [
                                {
                                    title: 'Weiter',
                                    type: 'submit',
                                    action: 'submit'
                                }]
                        }
                    ]
                }
            });
        },
        init: function () {
            if ($(me.selector.vueAppNewError).length) {
                me.initAppNewErrorVue();
            }
            if ($(me.selector.vueAppNew).length) {
                me.initAppNewVue();
            }
            if ($('#frmappnew').length === 0) {
                return;
            }

            $('#data').on('paste', function (e) {
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: 'index.php?module=onlineshops&action=appnew&cmd=checkdata',
                    data: {
                        data: e.originalEvent.clipboardData.getData('text')
                    },
                    success: function (data) {
                        $('#msgwrapper').html(data.html);
                    },
                    error: function (data) {
                        if (typeof data.responseJSON !== 'undefined') {
                            $('#msgwrapper').html('<div class="error">' + data.responseJSON.error + '</div>');
                        }
                    }
                });
            });
            $('#data').on('change', function () {
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: 'index.php?module=onlineshops&action=appnew&cmd=checkdata',
                    data: {
                        data: $(this).val()
                    },
                    success: function (data) {
                        $('#msgwrapper').html(data.html);
                    },
                    error: function (data) {
                        if (typeof data.responseJSON !== 'undefined') {
                            $('#msgwrapper').html('<div class="error">' + data.responseJSON.error + '</div>');
                        }
                    }
                });
            });
        }

    };
    return {
        init: me.init
    };

}(jQuery);

$(document).ready(function () {
    ShopimportAppNew.init();
});


/********* shopsettings.js *********/ 
var ShopSettings = function ($) {
    'use strict';

    var me = {
        storage: {
            exampleTemplate: null,
            treeApi: null,
            lastTreeSearch: ''
        },
        openTransformationPopup: function () {
            //$('#smartyinput').dialog('open');
            $('#textareasmartyincomming').val($('#transform_cart').val());
            $('#textareasmartyincomminginput').val($('#textareasmartyincomminginput').val());
        },
        loadCart: function (shopId) {
            if ($('#cart').val() + '' === '') {
                return;
            }
            $.ajax({
                type: 'POST',
                url: 'index.php?module=onlineshops&action=edit&cmd=loadCart',
                data: {
                    shopid: shopId,
                    extid: $('#cart').val(),
                    format: $('#smartyinputtype').val(),
                    replacecart: $('#replacecart').prop('checked') ? 1 : 0,
                    content: $('#textareasmartyincomming').val()
                },
                success: function (data) {
                    $('#textareasmartyincomminginput').val(data.input);
                    $('#dataincommingobject').html(data.object);
                    $('#dataincommingpreview').val(data.preview);
                }
            });
        },
        initCategoryTree: function () {
            $('#mlmTree').aciTree({
                autoInit: false,
                checkboxChain: false,
                ajax: {
                    url: 'index.php?module=onlineshops&action=edit&cmd=loadTree&id=' + $('#mlmTree').data('id')
                },
                checkbox: true,
                multiSelectable: true,
                itemHook: function (parent, item, itemData, level) {
                    //console.log(itemData);
                },
                filterHook: function (item, search, regexp) {

                    if (search.length) {
                        var parent = this.parent(item);

                        if (parent.length) {
                            var label = this.getLabel(parent);
                            if (regexp.test(String(label))) {
                                this.setVisible(item);
                                return true;
                            }
                            this.setVisible(item);
                        }

                        if (regexp.test(String(this.getLabel(item)))) {
                            item.addClass('searched');
                            return true;
                        } else {
                            return false;
                        }

                        //return regexp.test(String(this.getLabel(item)));
                    } else {
                        return true;
                    }
                }
            });

            me.storage.treeApi = $('#mlmTree').aciTree('api');


            $('#search').val('');
            me.storage.lastTreeSearch = '';

            $('#search').on('keyup', function () {
                if ($(this).val() === me.storage.lastTreeSearch) {
                    return;
                }

                $('.aciTreeLi').removeClass('searched');

                me.storage.lastTreeSearch = $(this).val();
                api.filter(null, {
                    search: $(this).val(),
                    callback: function () {

                    },
                    success: function (item, options) {

                        if (!options.first) {
                            //alert('No results found!');
                        }
                    }
                });
            });


            $('#mlmTree').on('acitree', function (event, api, item, eventName, options) {
                switch (eventName) {
                    case 'checked':
                        var ajaxData = {
                            id: api.getId(item),
                            shopId: $('#mlmTree').data('id'),
                            name: api.getLabel(item),
                            checked: true,
                            todo: 'check'
                        };
                        var dataid = api.getId(item);
                        var allChildren = api.children(null, true, true);
                        $(allChildren).each(function () {
                            if (api.getId($(this)) != dataid) {
                                api.uncheck($(this));
                            }
                        });
                        $('#category_root_id').val(dataid);

                        $.ajax({
                            url: 'index.php?module=onlineshops&action=edit&cmd=checkTreeNode',
                            data: ajaxData,
                            success: function (data) {

                            }
                        });
                        break;
                    case 'unchecked':
                        var ajaxData = {
                            id: api.getId(item),
                            shopId: $('#mlmTree').data('id'),
                            name: api.getLabel(item),
                            checked: false,
                            todo: 'uncheck'
                        };
                        $('#category_root_id').val(0);

                        $.ajax({
                            url: 'index.php?module=onlineshops&action=edit&cmd=uncheckTreeNode',
                            data: ajaxData,
                            success: function (data) {

                            }
                        });
                        break;
                }
            });

            $('#mlmTree').aciTree('init');
            $(window).on('scroll', function () {
                checkContainerPos();
            });
        },
        initSmarty: function () {
            $('#editcarttransformation').on('click', function () {
                me.openTransformationPopup();
            });
            $('#runincomming').on('click', function () {
                $('#transform_cart').val($('#textareasmartyincomming').val());
                $('#transform_cart_data').val($('#textareasmartyincomminginput').val());
                $.ajax({
                    url: 'index.php?module=onlineshops&action=edit&cmd=runincomming',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        content: $('#textareasmartyincomming').val(),
                        input: $('#textareasmartyincomminginput').val(),
                        shopid: $('#loadCart').data('shopid'),
                        format: $('#smartyinputtype').val(),
                        replacecart: $('#replacecart').prop('checked') ? 1 : 0
                    },
                    success: function (data) {
                        $('#dataincommingobject').html(data.object);
                        $('#dataincommingpreview').val(data.preview);
                    }
                });
            });
            $('#saveincomming').on('click', function () {
                $.ajax({
                    url: 'index.php?module=onlineshops&action=edit&cmd=savesmartyincomming',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        content: $('#textareasmartyincomming').val(),
                        input: $('#textareasmartyincomminginput').val(),
                        shopid: $('#loadCart').data('shopid'),
                        format: $('#smartyinputtype').val(),
                        replacecart: $('#replacecart').prop('checked') ? 1 : 0,
                        active: $('#transferactive').prop('checked') ? '1' : '0'
                    },
                    success: function () {
                        $('#transform_cart_format').val($('#smartyinputtype').val());
                        $('#transform_cart_replace').val($('#replacecart').prop('checked') ? 1 : 0);
                        $('#transform_cart').val($('#textareasmartyincomming').val());
                        $('#transform_cart_data').val($('#textareasmartyincomminginput').val());
                        $('#transform_cart_active').val($('#transferactive').prop('checked') ? '1' : '0');
                    }
                });
            });
            $('#transferactive').on('change', function () {
                $('#transform_cart_active').val($('#transferactive').prop('checked') ? '1' : '0');
            });
            $('#smartyinputtype').on('change', function () {
                me.loadDefaultTemplate();
            });

            me.loadDefaultTemplate();
            $('#loadDefaultTemplate').on('click', function () {
                if (me.storage.loadDefaultTemplate + '' === '') {
                    return;
                }
                if (($('#textareasmartyincomming').val() + '').trim() !== '' && !confirm('Das bisherige Template wird ersetzt. Fortfahren?')) {
                    return;
                }
                $('#textareasmartyincomming').val(me.storage.loadDefaultTemplate);
            });
            $('#loadCart').on('click', function () {
                var cart = $('#cart').val() + '';
                if (cart === '') {
                    return;
                }
                me.loadCart($(this).data('shopid'));
            });
        },
        loadDefaultTemplate: function () {
            me.storage.loadDefaultTemplate = '';
            $.ajax({
                url: 'index.php?module=onlineshops&action=edit&cmd=loadDefaultTemplate',
                type: 'POST',
                dataType: 'json',
                data: {
                    content: $('#textareasmartyincomming').val(),
                    input: $('#textareasmartyincomminginput').val(),
                    shopid: $('#loadCart').data('shopid'),
                    format: $('#smartyinputtype').val(),
                    replacecart: $('#replacecart').prop('checked') ? 1 : 0,
                    active: $('#transferactive').prop('checked') ? '1' : '0'
                },
                success: function (data) {
                    if (typeof data.template != 'undefined' && data.template !== '') {
                        me.storage.loadDefaultTemplate = data.template;
                        $('#loadDefaultTemplate').toggleClass('hidden', false);
                        $('#loadDefaultTemplate').show();
                    } else {
                        me.storage.loadDefaultTemplate = '';
                        $('#loadDefaultTemplate').hide();
                    }
                }
            });
        },
        init: function () {
            if ($('#smartyinput').length) {
                me.initSmarty();
            }
            if ($('#mlmTree').length) {
                me.initCategoryTree();
            }
        }
    };


    return {
        init: me.init
    };
}(jQuery);

$(document).ready(function () {
    ShopSettings.init();
});


/********* onlineshop_create.js *********/ 
var ShopCreate = function ($) {
    'use strict';

    var me = {
        storage: {
          vueElement: null
        },
        search:function (el)
        {
            var wert = $(el).val();
            $.ajax({
                url: 'index.php?module=onlineshops&action=create&cmd=suche',
                type: 'POST',
                dataType: 'json',
                data: { val: wert}})
             .done( function(data) {
                 if(typeof data != 'undefined' && data != null)
                 {
                     if(typeof data.ausblenden != 'undefined' &&  data.ausblenden != null)
                     {
                         var ausblenden = data.ausblenden.split(';');
                         $.each(ausblenden, function(k,v){
                             if(v != '')$('#'+v).hide();
                         });

                     }
                     if(typeof data.anzeigen != 'undefined' &&  data.anzeigen != null)
                     {
                         var anzeigen = data.anzeigen.split(';');
                         $.each(anzeigen, function(k,v){
                             if(v != '')$('#'+v).show();
                         });
                     }
                 }
             });
        },

        init:function () {
            $('#suche').on('keyup', function(){
               me.search(this);
            });
            me.storage.vueElement = $('#onlineshop-create').clone();
            $('.createbutton').on('click', function(){
                $.ajax({
                    url: 'index.php?module=onlineshops&action=create&cmd=getassistant',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        shopmodule: $(this).data('module'),
                    }
                }).done( function(data) {
                    if(typeof data.pages == 'undefined' && typeof data.location != 'undefined') {
                        window.location = data.location;
                        return;
                    }
                     if($('#onlineshop-create').length === 0) {
                         $('body').append(me.storage.vueElement);
                     }
                     new Vue({
                         el: '#onlineshop-create',
                         data: {
                             showAssistant: true,
                             pagination: true,
                             allowClose: true,
                             pages: data.pages
                         }
                     });
                 });
            });
            $('.autoOpenModule').first().each(function(){
                $(".createbutton[data-module='"+ $(this).data('module') +"']").first().trigger('click');
                $(this).remove();
            });
            $('.booster').first().each(function(){
                $.ajax({
                    url: 'index.php?module=onlineshops&action=create&cmd=getbooster',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        shopmodule: $(this).data('module'),
                    }
                }).done( function(data) {
                    if(typeof data.pages == 'undefined' && typeof data.location != 'undefined') {
                        window.location = data.location;
                        return;
                    }
                    if($('#onlineshop-create').length === 0) {
                        $('body').append(me.storage.vueElement);
                    }
                    new Vue({
                        el: '#onlineshop-booster',
                        data: {
                            showAssistant: true,
                            pagination: true,
                            allowClose: true,
                            pages: data.pages
                        }
                    });
                });
                //$(".createbutton[data-module='"+ $(this).data('module') +"']").first().trigger('click');
                $(this).remove();
            });
        }

    };
    return {
        init: me.init
    };
}(jQuery);

$(document).ready(function () {
    ShopCreate.init();
});


