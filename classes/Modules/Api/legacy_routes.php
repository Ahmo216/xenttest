<?php

/**
 * Legacy-API routes
 *
 * @example POST /www/api/legacy/AdresseGet
 */
return [
    [
        'POST',
        '/v1/gobnavconnect',
        ['Legacy', null, 'GobNavConnect', 'exampleAction', 'handle_navision']
    ],
    [
        'POST',
        '/v1/gobnavconnect/',
        ['Legacy', null, 'GobNavConnect', 'exampleAction', 'handle_navision']
    ],
    [
        'POST',
        '/{action}',
        ['Legacy', null, 'Default', 'postAction']
    ],
    [
        'GET',
        '/{action}',
        ['Legacy', null, 'Default', 'postAction']
    ],
    [
        'GET',
        '/v1/mobileapi/dashboard',
        ['Legacy', null, 'MobileApi', 'dashboardAction', 'mobile_app_communication']
    ],
    [
        'GET',
        '/opentrans/dispatchnotification/{id:\d+}',
        ['Legacy', null, 'OpenTransConnect', 'readDispatchnotification', 'handle_opentrans']
    ],
    [
        'GET',
        '/opentrans/dispatchnotification/orderid/{orderid:\d+}',
        ['Legacy', null, 'OpenTransConnect', 'readDispatchnotification', 'handle_opentrans']
    ],
    [
        'GET',
        '/opentrans/dispatchnotification/ordernumber/{ordernumber:\w+}',
        ['Legacy', null, 'OpenTransConnect', 'readDispatchnotification', 'handle_opentrans']
    ],
    [
        'GET',
        '/opentrans/dispatchnotification/extorder/{extorder:\w+}',
        ['Legacy', null, 'OpenTransConnect', 'readDispatchnotification', 'handle_opentrans']
    ],

    [
        'PUT',
        '/opentrans/dispatchnotification/{id:\d+}',
        ['Legacy', null, 'OpenTransConnect', 'updateDispatchnotification', 'handle_opentrans']
    ],
    [
        'PUT',
        '/opentrans/dispatchnotification/orderid/{orderid:\d+}',
        ['Legacy', null, 'OpenTransConnect', 'updateDispatchnotification', 'handle_opentrans']
    ],
    [
        'PUT',
        '/opentrans/dispatchnotification/ordernumber/{ordernumber:\w+}',
        ['Legacy', null, 'OpenTransConnect', 'updateDispatchnotification', 'handle_opentrans']
    ],
    [
        'PUT',
        '/opentrans/dispatchnotification/extorder/{extorder:\w+}',
        ['Legacy', null, 'OpenTransConnect', 'updateDispatchnotification', 'handle_opentrans']
    ],
    [
        'GET',
        '/opentrans/order/{id:\d+}',
        ['Legacy', null, 'OpenTransConnect', 'readOrder', 'handle_opentrans']
    ],
    [
        'GET',
        '/opentrans/order/ordernumber/{ordernumber:\w+}',
        ['Legacy', null, 'OpenTransConnect', 'readOrder', 'handle_opentrans']
    ],
    [
        'GET',
        '/opentrans/order/extorder/{extorder:\w+}',
        ['Legacy', null, 'OpenTransConnect', 'readOrder', 'handle_opentrans']
    ],
    [
        'POST',
        '/opentrans/order',
        ['Legacy', null, 'OpenTransConnect', 'createOrder', 'handle_opentrans']
    ],
    [
        'DELETE',
        '/opentrans/order/{id:\d+}',
        ['Legacy', null, 'OpenTransConnect', 'deleteOrder', 'handle_opentrans']
    ],
    [
        'DELETE',
        '/opentrans/order/ordernumber/{ordernumber:\w+}',
        ['Legacy', null, 'OpenTransConnect', 'deleteOrder', 'handle_opentrans']
    ],
    [
        'DELETE',
        '/opentrans/order/extorder/{extorder:\w+}',
        ['Legacy', null, 'OpenTransConnect', 'deleteOrder', 'handle_opentrans']
    ],
    [
        'GET',
        '/opentrans/invoice/{id:\d+}',
        ['Legacy', null, 'OpenTransConnect', 'readInvoice', 'handle_opentrans']
    ],
    [
        'GET',
        '/opentrans/invoice/orderid/{orderid:\d+}',
        ['Legacy', null, 'OpenTransConnect', 'readInvoice', 'handle_opentrans']
    ],
    [
        'GET',
        '/opentrans/invoice/ordernumber/{ordernumber:\w+}',
        ['Legacy', null, 'OpenTransConnect', 'readInvoice', 'handle_opentrans']
    ],
    [
        'GET',
        '/opentrans/invoice/extorder/{extorder:\w+}',
        ['Legacy', null, 'OpenTransConnect', 'readInvoice', 'handle_opentrans']
    ],
    [
        'POST',
        '/shopimport/auth',
        ['Legacy', null, 'Shopimport', 'auth', 'communicate_with_shop']
    ],
    [
        'POST',
        '/shopimport/syncstorage/{articlenumber:.+}',
        ['Legacy', null, 'Shopimport', 'syncStorage', 'communicate_with_shop']
    ],
    [
        'POST',
        '/shopimport/articletoxentral/{articlenumber:.+}',
        ['Legacy', null, 'Shopimport', 'putArticleToXentral', 'communicate_with_shop']
    ],
    [
        'POST',
        '/shopimport/articletoshop/{articlenumber:.+}',
        ['Legacy', null, 'Shopimport', 'putArticleToShop', 'communicate_with_shop']
    ],
    [
        'POST',
        '/shopimport/ordertoxentral/{ordernumber:.+}',
        ['Legacy', null, 'Shopimport', 'putOrderToXentral', 'communicate_with_shop']
    ],
    [
        'GET',
        '/shopimport/articlesyncstate',
        ['Legacy', null, 'Shopimport', 'getArticleSyncState', 'communicate_with_shop']
    ],
    [
        'GET',
        '/shopimport/statistics',
        ['Legacy', null, 'Shopimport', 'getStatistics', 'communicate_with_shop']
    ],
    [
        'GET',
        '/shopimport/modulelinks',
        ['Legacy', null, 'Shopimport', 'getModulelinks', 'communicate_with_shop']
    ],
    [
        'POST',
        '/shopimport/disconnect',
        ['Legacy', null, 'Shopimport', 'postDisconnect', 'communicate_with_shop']
    ],
    [
        'POST',
        '/shopimport/reconnect',
        ['Legacy', null, 'Shopimport', 'postReconnect', 'communicate_with_shop']
    ],
    [
        'GET',
        '/shopimport/status',
        ['Legacy', null, 'Shopimport', 'getStatus', 'communicate_with_shop']
    ],
    [
        'POST',
        '/shopimport/refund',
        ['Legacy', null, 'Shopimport', 'postRefund', 'communicate_with_shop']
    ],
];
