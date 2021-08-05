<?php

return [
    [
        ['GET', 'POST', 'PUT', 'DELETE'],
        '/',
        ['Version1', null, 'Start', 'indexAction']
    ],
    [
        ['GET', 'POST', 'PUT', 'DELETE'],
        '/v1',
        ['Version1', null, 'Start', 'indexAction']
    ],
    [
        ['GET', 'POST', 'PUT', 'DELETE'],
        '/docs.html',
        ['Version1', null, 'Start', 'docsAction']
    ],
    [
        ['GET', 'POST', 'PUT', 'DELETE'],
        '/assets/{assetfile}',
        ['Version1', null, 'Start', 'docsAssetsAction', 'handle_assets']
    ],
    // ArticleSubscriptions
    [
        'POST',
        '/v1/aboartikel',
        ['Version1', 'ArticleSubscription', 'ArticleSubscription', 'createAction', 'create_subscription']
    ],
    [
        'GET',
        '/v1/aboartikel',
        ['Version1', 'ArticleSubscription', 'Generic', 'listAction', 'list_subscriptions']
    ],
    [
        'GET',
        '/v1/aboartikel/{id:\d+}',
        ['Version1', 'ArticleSubscription', 'Generic', 'readAction', 'view_subscription']
    ],
    [
        'PUT',
        '/v1/aboartikel/{id:\d+}',
        ['Version1', 'ArticleSubscription', 'ArticleSubscription', 'updateAction', 'edit_subscription']
    ],

    [
        'DELETE',
        '/v1/aboartikel/{id:\d+}',
        ['Version1', 'ArticleSubscription', 'Generic', 'deleteAction', 'delete_subscription']
    ],
    // Article subscription groups
    [
        'POST',
        '/v1/abogruppen',
        ['Version1', 'ArticleSubscriptionGroup', 'Generic', 'createAction', 'create_subscription_group']
    ],
    [
        'GET',
        '/v1/abogruppen',
        ['Version1', 'ArticleSubscriptionGroup', 'Generic', 'listAction', 'list_subscription_groups']
    ],
    [
        'GET',
        '/v1/abogruppen/{id:\d+}',
        ['Version1', 'ArticleSubscriptionGroup', 'Generic', 'readAction', 'view_subscription_group']
    ],
    [
        'PUT',
        '/v1/abogruppen/{id:\d+}',
        ['Version1', 'ArticleSubscriptionGroup', 'Generic', 'updateAction', 'edit_subscription_group']
    ],
    // Addresses (v1)
    [
        /** @see AddressController::createAction */
        'POST',
        '/v1/adressen',
        ['Version1', null, 'Address', 'createAction', 'create_address']
    ],
    [
        /** @see AddressController::listAction */
        'GET',
        '/v1/adressen',
        ['Version1', null, 'Address', 'listAction', 'list_addresses']
    ],
    [
        /** @see AddressController::readAction */
        'GET',
        '/v1/adressen/{id:\d+}',
        ['Version1', null, 'Address', 'readAction', 'view_address']
    ],
    [
        /** @see AddressController::updateAction */
        'PUT',
        '/v1/adressen/{id:\d+}',
        ['Version1', null, 'Address', 'updateAction', 'edit_address']
    ],
    // Addresses (v2)
    [
        'GET',
        '/v2/adressen',
        ['Version1', 'Address', 'Generic', 'listAction', 'list_addresses']
    ],
    [
        'GET',
        '/v2/adressen/{id:\d+}',
        ['Version1', 'Address', 'Generic', 'readAction', 'view_address']
    ],
    // AddressType (Mr, Mrs, Company)
    [
        'POST',
        '/v1/adresstyp',
        ['Version1', 'AddressType', 'Generic', 'createAction', 'create_address_type']
    ],
    [
        'GET',
        '/v1/adresstyp',
        ['Version1', 'AddressType', 'Generic', 'listAction', 'list_address_types']
    ],
    [
        'GET',
        '/v1/adresstyp/{id:\d+}',
        ['Version1', 'AddressType', 'Generic', 'readAction', 'view_address_type']
    ],
    [
        'PUT',
        '/v1/adresstyp/{id:\d+}',
        ['Version1', 'AddressType', 'Generic', 'updateAction', 'edit_address_type']
    ],
    // Articles
    [
        'GET',
        '/v1/artikel',
        ['Version1', 'Article', 'Generic', 'listAction', 'list_articles']
    ],
    [
        'GET',
        '/v1/artikel/{id:\d+}',
        ['Version1', 'Article', 'Generic', 'readAction', 'view_article']
    ],
    // Article properties (size, color, etc)
    [
        'GET',
        '/v1/eigenschaften',
        ['Version1', 'Property', 'Generic', 'listAction', 'list_property']
    ],
    [
        'GET',
        '/v1/eigenschaften/{id:\d+}',
        ['Version1', 'Property', 'Generic', 'readAction', 'view_property']
    ],
    [
        'DELETE',
        '/v1/eigenschaften/{id:\d+}',
        ['Version1', 'Property', 'Generic', 'deleteAction', 'delete_property']
    ],
    [
        'PUT',
        '/v1/eigenschaften/{id:\d+}',
        ['Version1', 'Property', 'Generic', 'updateAction', 'edit_property']
    ],
    [
        'POST',
        '/v1/eigenschaften',
        ['Version1', 'Property', 'Generic', 'createAction', 'create_property']
    ],
    // Article property values (For example Size: M, Color: red)
    [
        'GET',
        '/v1/eigenschaftenwerte',
        ['Version1', 'PropertyValue', 'Generic', 'listAction', 'list_property_value']
    ],
    [
        'GET',
        '/v1/eigenschaftenwerte/{id:\d+}',
        ['Version1', 'PropertyValue', 'Generic', 'readAction', 'view_property_value']
    ],
    [
        'DELETE',
        '/v1/eigenschaftenwerte/{id:\d+}',
        ['Version1', 'PropertyValue', 'Generic', 'deleteAction', 'delete_property_value']
    ],
    [
        'PUT',
        '/v1/eigenschaftenwerte/{id:\d+}',
        ['Version1', 'PropertyValue', 'Generic', 'updateAction', 'edit_property_value']
    ],
    [
        'POST',
        '/v1/eigenschaftenwerte',
        ['Version1', 'PropertyValue', 'Generic', 'createAction', 'create_property_value']
    ],
    [
        'GET', '/v1/belege', ['Version1', null, 'Start', 'indexAction']
    ],
    // Offers
    [
        'GET',
        '/v1/belege/angebote',
        ['Version1', 'DocumentOffer', 'Generic', 'listAction', 'list_quotes']
    ],
    [
        'GET',
        '/v1/belege/angebote/{id:\d+}',
        ['Version1', 'DocumentOffer', 'Generic', 'readAction', 'view_quote']
    ],
    // Sales orders
    [
        'GET',
        '/v1/belege/auftraege',
        ['Version1', 'DocumentSalesOrder', 'Generic', 'listAction', 'list_orders']
    ],
    [
        'GET',
        '/v1/belege/auftraege/{id:\d+}',
        ['Version1', 'DocumentSalesOrder', 'Generic', 'readAction', 'view_order']
    ],
    // Delivery notes
    [
        'GET',
        '/v1/belege/lieferscheine',
        ['Version1', 'DocumentDeliveryNote', 'Generic', 'listAction', 'list_delivery_notes']
    ],
    [
        'GET',
        '/v1/belege/lieferscheine/{id:\d+}',
        ['Version1', 'DocumentDeliveryNote', 'Generic', 'readAction', 'view_delivery_note']
    ],
    // Invoices
    [
        'GET',
        '/v1/belege/rechnungen',
        ['Version1', 'DocumentInvoice', 'Generic', 'listAction', 'list_invoices']
    ],
    [
        'GET',
        '/v1/belege/rechnungen/{id:\d+}',
        ['Version1', 'DocumentInvoice', 'Generic', 'readAction', 'view_invoice']
    ],
    [
        'DELETE',
        '/v1/belege/rechnungen/{id:\d+}',
        ['Version1', 'DocumentInvoice', 'Generic', 'deleteAction', 'delete_invoice']
    ],
    // Credit notes / Cancellation invoices
    [
        'GET',
        '/v1/belege/gutschriften',
        ['Version1', 'DocumentCreditNote', 'Generic', 'listAction', 'list_credit_memos']
    ],
    [
        'GET',
        '/v1/belege/gutschriften/{id:\d+}',
        ['Version1', 'DocumentCreditNote', 'Generic', 'readAction', 'view_credit_memo']
    ],
    [
        'GET',
        '/v1/reports/{id:\d+}/download',
        ['Version1', null, 'Reports', 'downloadAction', 'view_report']
    ],
    // Files
    [
        'POST',
        '/v1/dateien',
        ['Version1', 'File', 'File', 'createAction', 'create_file']
    ],
    [
        'GET',
        '/v1/dateien',
        ['Version1', 'File', 'File', 'listAction', 'list_files']
    ],
    [
        'GET',
        '/v1/dateien/{id:\d+}',
        ['Version1', 'File', 'File', 'readAction', 'view_file']
    ],
    [
        'GET',
        '/v1/dateien/{id:\d+}/download',
        ['Version1', 'File', 'File', 'downloadAction', 'view_file']
    ],
    [
        'GET',
        '/v1/dateien/{id:\d+}/base64',
        ['Version1', 'File', 'File', 'base64Action', 'view_file']
    ],
    // DocumentScanner (DocScan)
    [
        'POST',
        '/v1/docscan',
        ['Version1', 'DocumentScanner', 'DocumentScanner', 'createAction', 'create_scanned_document']
    ],
    [
        'GET',
        '/v1/docscan',
        ['Version1', 'DocumentScanner', 'DocumentScanner', 'listAction', 'list_scanned_documents']
    ],
    [
        'GET',
        '/v1/docscan/{id:\d+}',
        ['Version1', 'DocumentScanner', 'DocumentScanner', 'readAction', 'view_scanned_document']
    ],
    // Article categories
    [
        'POST',
        '/v1/artikelkategorien',
        ['Version1', 'ArticleCategory', 'Generic', 'createAction', 'create_article_category']
    ],
    [
        'GET',
        '/v1/artikelkategorien',
        ['Version1', 'ArticleCategory', 'Generic', 'listAction', 'list_article_categories']
    ],
    [
        'GET',
        '/v1/artikelkategorien/{id:\d+}',
        ['Version1', 'ArticleCategory', 'Generic', 'readAction', 'view_article_category']
    ],
    [
        'PUT',
        '/v1/artikelkategorien/{id:\d+}',
        ['Version1', 'ArticleCategory', 'Generic', 'updateAction', 'edit_article_category']
    ],
    // Groups
    [
        'POST',
        '/v1/gruppen',
        ['Version1', 'Group', 'Generic', 'createAction', 'create_group']
    ],
    [
        'GET',
        '/v1/gruppen',
        ['Version1', 'Group', 'Generic', 'listAction', 'list_groups']
    ],
    [
        'GET',
        '/v1/gruppen/{id:\d+}',
        ['Version1', 'Group', 'Generic', 'readAction', 'view_group']
    ],
    [
        'PUT',
        '/v1/gruppen/{id:\d+}',
        ['Version1', 'Group', 'Generic', 'updateAction', 'edit_group']
    ],
    // CRM documents
    [
        'POST',
        '/v1/crmdokumente',
        ['Version1', 'CrmDocument', 'Generic', 'createAction', 'create_crm_document']
    ],
    [
        'GET',
        '/v1/crmdokumente',
        ['Version1', 'CrmDocument', 'Generic', 'listAction', 'list_crm_documents']
    ],
    [
        'GET',
        '/v1/crmdokumente/{id:\d+}',
        ['Version1', 'CrmDocument', 'Generic', 'readAction', 'view_crm_document']
    ],
    [
        'PUT',
        '/v1/crmdokumente/{id:\d+}',
        ['Version1', 'CrmDocument', 'Generic', 'updateAction', 'edit_crm_document']
    ],
    [
        'DELETE',
        '/v1/crmdokumente/{id:\d+}',
        ['Version1', 'CrmDocument', 'Generic', 'deleteAction', 'delete_crm_document']
    ],
    // Countries
    [
        'POST',
        '/v1/laender',
        ['Version1', 'Country', 'Generic', 'createAction', 'create_country']
    ],
    [
        'GET',
        '/v1/laender',
        ['Version1', 'Country', 'Generic', 'listAction', 'list_countries']
    ],
    [
        'GET',
        '/v1/laender/{id:\d+}',
        ['Version1', 'Country', 'Generic', 'readAction', 'view_country']
    ],
    [
        'PUT',
        '/v1/laender/{id:\d+}',
        ['Version1', 'Country', 'Generic', 'updateAction', 'edit_country']
    ],
    // Storages (warehouses etc)
    [
        'POST',
        '/v1/storage',
        ['Version1', 'Storage', 'Storage', 'createAction', 'create_storage']
    ],
    [
        'GET',
        '/v1/storage',
        ['Version1', 'Storage', 'Storage', 'listAction', 'view_storage']
    ],
    [
        'GET',
        '/v1/storage/{id:\d+}',
        ['Version1', 'Storage', 'Storage', 'readAction', 'view_storage']
    ],
    [
        'PUT',
        '/v1/storage/{id:\d+}',
        ['Version1', 'Storage', 'Storage', 'updateAction', 'edit_storage']
    ],
    [
        'DELETE',
        '/v1/storage/{id:\d+}',
        ['Version1', 'Storage', 'Storage', 'deleteAction', 'delete_storage']
    ],
    // Storage bins
    [
        'POST',
        '/v1/storage/bin',
        ['Version1', 'Storage', 'StorageBin', 'createAction', 'create_storage_bin']
    ],
    [
        'GET',
        '/v1/storage/bin',
        ['Version1', 'Storage', 'StorageBin', 'listAction', 'view_storage_bin']
    ],
    [
        'GET',
        '/v1/storage/bin/{id:\d+}',
        ['Version1', 'Storage', 'StorageBin', 'readAction', 'view_storage_bin']
    ],
    [
        'PUT',
        '/v1/storage/bin/{id:\d+}',
        ['Version1', 'Storage', 'StorageBin', 'updateAction', 'edit_storage_bin']
    ],
    [
        'DELETE',
        '/v1/storage/bin/{id:\d+}',
        ['Version1', 'Storage', 'StorageBin', 'deleteAction', 'delete_storage_bin']
    ],
    // Storage batches
    [
        'GET',
        '/v1/lagercharge',
        ['Version1', 'StorageBatch', 'Generic', 'listAction', 'view_storage_batch']
    ],
    // StorageBestBeforeDate (Lager-Mindesthaltbarkeitsdatum, MHD)
    [
        'GET',
        '/v1/lagermhd',
        ['Version1', 'StorageBestBeforeDate', 'Generic', 'listAction', 'view_storage_best_before']
    ],
    // Delivery addresses
    [
        'POST',
        '/v1/lieferadressen',
        ['Version1', 'DeliveryAddress', 'Generic', 'createAction', 'create_delivery_address']
    ],
    [
        'GET',
        '/v1/lieferadressen',
        ['Version1', 'DeliveryAddress', 'Generic', 'listAction', 'list_delivery_addresses']
    ],
    [
        'GET',
        '/v1/lieferadressen/{id:\d+}',
        ['Version1', 'DeliveryAddress', 'Generic', 'readAction', 'view_delivery_address']
    ],
    [
        'PUT',
        '/v1/lieferadressen/{id:\d+}',
        ['Version1', 'DeliveryAddress', 'Generic', 'updateAction', 'edit_delivery_address']
    ],
    [
        'DELETE',
        '/v1/lieferadressen/{id:\d+}',
        ['Version1', 'DeliveryAddress', 'Generic', 'deleteAction', 'delete_delivery_address']
    ],
    // Tax rate
    [
        'POST',
        '/v1/steuersaetze',
        ['Version1', 'TaxRate', 'Generic', 'createAction', 'create_tax_rate']
    ],
    [
        'GET',
        '/v1/steuersaetze',
        ['Version1', 'TaxRate', 'Generic', 'listAction', 'list_tax_rates']
    ],
    [
        'GET',
        '/v1/steuersaetze/{id:\d+}',
        ['Version1', 'TaxRate', 'Generic', 'readAction', 'view_tax_rate']
    ],
    [
        'PUT',
        '/v1/steuersaetze/{id:\d+}',
        ['Version1', 'TaxRate', 'Generic', 'updateAction', 'edit_tax_rate']
    ],
    // Shipping methods
    [
        'POST',
        '/v1/versandarten',
        ['Version1', 'ShippingMethod', 'Generic', 'createAction', 'create_shipping_method']
    ],
    [
        'GET',
        '/v1/versandarten',
        ['Version1', 'ShippingMethod', 'Generic', 'listAction', 'list_shipping_methods']
    ],
    [
        'GET',
        '/v1/versandarten/{id:\d+}',
        ['Version1', 'ShippingMethod', 'Generic', 'readAction', 'view_shipping_method']
    ],
    [
        'PUT',
        '/v1/versandarten/{id:\d+}',
        ['Version1', 'ShippingMethod', 'Generic', 'updateAction', 'edit_shipping_method']
    ],
    // Resubmission
    [
        'POST',
        '/v1/wiedervorlagen',
        ['Version1', 'Resubmission', 'Generic', 'createAction', 'create_resubmission']
    ],
    [
        'GET',
        '/v1/wiedervorlagen',
        ['Version1', 'Resubmission', 'Generic', 'listAction', 'list_resubmissions']
    ],
    [
        'GET',
        '/v1/wiedervorlagen/{id:\d+}',
        ['Version1', 'Resubmission', 'Generic', 'readAction', 'view_resubmission']
    ],
    [
        'PUT',
        '/v1/wiedervorlagen/{id:\d+}',
        ['Version1', 'Resubmission', 'Generic', 'updateAction', 'edit_resubmission']
    ],
    // Payment methods
    [
        'POST',
        '/v1/zahlungsweisen',
        ['Version1', 'PaymentMethod', 'Generic', 'createAction', 'create_payment_method']
    ],
    [
        'GET',
        '/v1/zahlungsweisen',
        ['Version1', 'PaymentMethod', 'Generic', 'listAction', 'list_payment_methods']
    ],
    [
        'GET',
        '/v1/zahlungsweisen/{id:\d+}',
        ['Version1', 'PaymentMethod', 'Generic', 'readAction', 'view_payment_method']
    ],
    [
        'PUT',
        '/v1/zahlungsweisen/{id:\d+}',
        ['Version1', 'PaymentMethod', 'Generic', 'updateAction', 'edit_payment_method']
    ],
    // Tracking numbers
    [
        'POST',
        '/v1/trackingnummern',
        ['Version1', 'TrackingNumber', 'TrackingNumber', 'createAction', 'create_tracking_number']
    ],
    [
        'GET',
        '/v1/trackingnummern',
        ['Version1', 'TrackingNumber', 'Generic', 'listAction', 'list_tracking_numbers']
    ],
    [
        'GET',
        '/v1/trackingnummern/{id:\d+}',
        ['Version1', 'TrackingNumber', 'Generic', 'readAction', 'view_tracking_number']
    ],
    [
        'PUT',
        '/v1/trackingnummern/{id:\d+}',
        ['Version1', 'TrackingNumber', 'TrackingNumber', 'updateAction', 'edit_tracking_number']
    ],
];
