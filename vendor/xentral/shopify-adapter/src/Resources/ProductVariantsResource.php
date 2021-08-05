<?php

namespace XentralAdapters\Shopify\Resources;

use GuzzleHttp\ClientInterface;
use Psr\Http\Client\ClientExceptionInterface;
use XentralAdapters\Shopify\Data\ProductVariant;
use XentralAdapters\Shopify\Factories\ProductVariantFactory;
use XentralAdapters\Shopify\PaginatedRequestHandler;
use XentralAdapters\Shopify\RateLimitingAwareClient;
use XentralAdapters\Shopify\ResourceContract;

class ProductVariantsResource implements ResourceContract
{
  /** @var RateLimitingAwareClient */
  private $client;

  public function __construct(RateLimitingAwareClient $client)
  {
    $this->client = $client;
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/products/product-variant#show-2020-10
   */
  public function get(int $variantId): ProductVariant
  {
    $response = $this->client->request('GET', "/admin/api/2020-10/variants/{$variantId}.json");
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return ProductVariantFactory::createFromResponseArray($decodedResponse['variant']);
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/products/product-variant#count-2020-10
   */
  public function count(int $productId): int
  {
    $response = $this->client->request('GET', "/admin/api/2020-10/products/{$productId}/variants/count.json");
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return $decodedResponse['count'];
  }

  /**
   * @https://shopify.dev/docs/admin-api/rest/reference/products/product-variant#index-2020-10
   * @return ProductVariant[]
   */
  public function list(int $productId): array
  {
    $requestHandler = new PaginatedRequestHandler($this->client, "/admin/api/2020-10/products/{$productId}/variants.json");

    $result = $requestHandler->handle();

    return array_map(function (array $data) {
      return ProductVariantFactory::createFromResponseArray($data);
    }, array_merge(...$result));
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/products/product-variant#create-2020-10
   */
  public function create(int $productId, array $variantData): ProductVariant
  {
    $response = $this->client->request(
      'POST',
      "/admin/api/2020-10/products/{$productId}/variants.json",
      [
        'json' => [
          'variant' => $variantData
        ]
      ]
    );
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return ProductVariantFactory::createFromResponseArray($decodedResponse['variant']);
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/products/product-variant#update-2020-10
   */
  public function update(int $variantId, array $variantData): ProductVariant
  {
    $response = $this->client->request(
      'PUT',
      "/admin/api/2020-10/variants/{$variantId}.json",
      [
        'json' => [
          'variant' => $variantData
        ]
      ]
    );
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return ProductVariantFactory::createFromResponseArray($decodedResponse['variant']);
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/products/product-variant#destroy-2020-10
   */
  public function delete(int $productId, int $variantId): bool
  {
    try {
      $this->client->request('DELETE', "/admin/api/2020-10/products/{$productId}/variants/{$variantId}.json");

      return true;
    } catch (ClientExceptionInterface $exception) {
      return false;
    }
  }
}
