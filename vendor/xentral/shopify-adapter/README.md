# Xentral Shopify Adapter

This repository contains the xentral Shopify adapter.  
It is a Guzzle based client for Shopify's API. It has a fluent syntax and custom
DTO classes for every resource.

## Usage

The client has two options to be instantiated:  
The first one is via the default constructor which needs a preconfigured Guzzle
instance.

```php
use GuzzleHttp\Client;
use XentralAdapters\Shopify\ShopifyClient;

$client = new Client([
  'base_uri' => $_SERVER['SHOPIFY_SHOP_URL'],
  'auth' => [$_SERVER['SHOPIFY_API_KEY'], $_SERVER['SHOPIFY_API_PASSWORD']]
]);

$shopify = new ShopifyClient($client);

$products = $shopify->products()->list();
```

The second one is the "convenience" option is via the static `create()` method:

```php

$shopify = \XentralAdapters\Shopify\ShopifyClient::create(
  $_SERVER['SHOPIFY_SHOP_URL'],
  $_SERVER['SHOPIFY_API_KEY'],
  $_SERVER['SHOPIFY_API_PASSWORD']
);

$products = $shopify->products()->list();
```

### Available methods

```php
$shopify->countries()->count();
$shopify->countries()->list();
$shopify->countries()->get(...);
$shopify->inventoryLevels()->list();
$shopify->inventoryLevels()->adjust(...);
$shopify->locations()->count();
$shopify->locations()->list();
$shopify->locations()->get(...);
$shopify->metafields()->createFor(...);
$shopify->metafields()->delete(...);
$shopify->metafields()->listFor(...);
$shopify->orders()->list();
$shopify->orders()->get(...);
$shopify->orders()->cancel(...);
$shopify->orders()->listMetafields(...);
$shopify->orders()->refund(...);
$shopify->orders()->transactions()->list(...);
$shopify->orders()->transactions()->get(...);
$shopify->orders()->transactions()->count(...);
$shopify->products()->count();
$shopify->products()->list();
$shopify->products()->get(...);
$shopify->products()->create(...);
$shopify->products()->update(...);
$shopify->products()->images()->count(...);
$shopify->products()->images()->list(...);
$shopify->products()->images()->get(...);
$shopify->products()->images()->create(...);
$shopify->products()->images()->update(...);
$shopify->products()->images()->delete(...);
$shopify->products()->variants()->count(...);
$shopify->products()->variants()->list(...);
$shopify->products()->variants()->get(...);
$shopify->products()->variants()->update(...);
$shopify->products()->variants()->delete(...);
```


## Testing

This package has two test suites.  
One with unit tests which can be executed
via `./vendor/bin/phpunit --testsuite unit`.  
The feature test suite requires you to set Shopify credentials in
the `phpunit.xml`. If this is done it can be executed
via `./vendor/bin/phpunit --testsuite feature`.
