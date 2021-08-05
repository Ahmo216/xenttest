<?php

namespace XentralAdapters\Shopify\Resources;

use Psr\Http\Client\ClientExceptionInterface;
use XentralAdapters\Shopify\Data\ProductImage;
use XentralAdapters\Shopify\Factories\ProductImageFactory;
use XentralAdapters\Shopify\RateLimitingAwareClient;
use XentralAdapters\Shopify\ResourceContract;

class ProductImagesResource implements ResourceContract
{
  /** @var RateLimitingAwareClient */
  private $client;

  public function __construct(RateLimitingAwareClient $client)
  {
    $this->client = $client;
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/products/product-image#count-2020-10
   */
  public function count(int $productId): int
  {
    $response = $this->client->request('GET', "/admin/api/2020-10/products/{$productId}/images/count.json");
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return $decodedResponse['count'];
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/products/product-image#index-2020-10
   */
  public function list(int $productId): array
  {
    $response = $this->client->request('GET', "/admin/api/2020-10/products/{$productId}/images.json");
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return array_map(function (array $data) {
      return ProductImageFactory::createFromResponseArray($data);
    }, $decodedResponse['images']);
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/products/product-image#show-2020-10
   */
  public function get(int $productId, int $imageId): ProductImage
  {
    $response = $this->client->request('GET', "/admin/api/2020-10/products/{$productId}/images/{$imageId}.json");
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return ProductImageFactory::createFromResponseArray($decodedResponse['image']);
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/products/product-image#create-2020-10
   */
  public function create(int $productId, array $imageData): ProductImage
  {
    $response = $this->client->request(
      'POST',
      "/admin/api/2020-10/products/{$productId}/images.json",
      [
        'json' => [
          'image' => $imageData
        ]
      ]
    );
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return ProductImageFactory::createFromResponseArray($decodedResponse['image']);
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/products/product-image#update-2020-10
   */
  public function update(int $productId, int $imageId, array $imageData): ProductImage
  {
    $response = $this->client->request(
      'PUT',
      "/admin/api/2020-10/products/{$productId}/images/{$imageId}.json",
      [
        'json' => [
          'image' => $imageData
        ]
      ]
    );
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return ProductImageFactory::createFromResponseArray($decodedResponse['image']);
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/products/product-image#destroy-2020-10
   */
  public function delete(int $productId, int $imageId): bool
  {
    try {
      $this->client->request('DELETE', "/admin/api/2020-10/products/{$productId}/images/{$imageId}.json");

      return true;
    } catch (ClientExceptionInterface $exception) {
      return false;
    }
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/products/product-image#destroy-2020-10
   */
  public function deleteForVariant(int $variantId, int $imageId): bool
  {
    try {
      $this->client->request('DELETE', "/admin/api/2020-10/variants/{$variantId}/images/{$imageId}.json");

      return true;
    } catch (ClientExceptionInterface $exception) {
      return false;
    }
  }
}
