<?php

namespace XentralAdapters\Shopify;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use XentralAdapters\Shopify\Resources\CountriesResource;
use XentralAdapters\Shopify\Resources\FulfillmentsResource;
use XentralAdapters\Shopify\Resources\InventoryLevelsResource;
use XentralAdapters\Shopify\Resources\LocationsResource;
use XentralAdapters\Shopify\Resources\MetafieldsResource;
use XentralAdapters\Shopify\Resources\OrdersResource;
use XentralAdapters\Shopify\Resources\ProductImagesResource;
use XentralAdapters\Shopify\Resources\ProductsResource;
use XentralAdapters\Shopify\Resources\ProductVariantsResource;
use XentralAdapters\Shopify\Resources\ShopifyPayments\ShopifyPaymentsProxy;

class ShopifyClient
{
  /** @var RateLimitingAwareClient */
  private $client;

  public static function create(string $baseUri, string $apiKey, string $apiSecret): self
  {
    $client = new Client([
      'base_uri' => $baseUri,
      'auth' => [$apiKey, $apiSecret]
    ]);

    return new self($client);
  }

  public function __construct(ClientInterface $client)
  {
    $rateLimitingAwareClient = new RateLimitingAwareClient($client);
    $this->client = $rateLimitingAwareClient;
  }

  public function shopifyPayments(): ShopifyPaymentsProxy
  {
    return new ShopifyPaymentsProxy($this->client);
  }

  public function orders(): OrdersResource
  {
    return new OrdersResource($this->client);
  }

  public function products(): ProductsResource
  {
    return new ProductsResource($this->client);
  }

  public function variants(): ProductVariantsResource
  {
    return new ProductVariantsResource($this->client);
  }

  public function countries(): CountriesResource
  {
    return new CountriesResource($this->client);
  }

  public function inventoryLevels(): InventoryLevelsResource
  {
    return new InventoryLevelsResource($this->client);
  }

  public function locations(): LocationsResource
  {
    return new LocationsResource($this->client);
  }

  public function metafields(): MetafieldsResource
  {
    return new MetafieldsResource($this->client);
  }

  public function fulfillments(): FulfillmentsResource
  {
    return new FulfillmentsResource($this->client);
  }

  public function images(): ProductImagesResource
  {
    return new ProductImagesResource($this->client);
  }
}
