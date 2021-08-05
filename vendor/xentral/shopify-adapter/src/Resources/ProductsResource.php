<?php

namespace XentralAdapters\Shopify\Resources;

use XentralAdapters\Shopify\ConditionParser;
use XentralAdapters\Shopify\Data\Product;
use XentralAdapters\Shopify\Factories\ProductFactory;
use XentralAdapters\Shopify\PaginatedRequestHandler;
use XentralAdapters\Shopify\RateLimitingAwareClient;
use XentralAdapters\Shopify\ResourceContract;

class ProductsResource implements ResourceContract
{
  /** @var RateLimitingAwareClient */
  private $client;

  public function __construct(RateLimitingAwareClient $client)
  {
    $this->client = $client;
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/products/product#show-2020-10
   */
  public function get(int $id): Product
  {
    $response = $this->client->request('GET', "/admin/api/2020-10/products/{$id}.json");
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return ProductFactory::createFromResponseArray($decodedResponse['product']);
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/products/product#count-2020-10
   */
  public function count(array $conditions = []): int
  {
    $response = $this->client->request(
      'GET',
      "/admin/api/2020-10/products/count.json",
      [
        'query' => ConditionParser::parse($conditions)
      ]
    );
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return $decodedResponse['count'];
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/products/product#index-2020-10
   * @return Product[]
   */
  public function list(array $conditions = []): array
  {
    $requestHandler = new PaginatedRequestHandler(
      $this->client,
      '/admin/api/2020-10/products.json',
      ConditionParser::toQueryString($conditions)
    );

    $result = $requestHandler->handle();

    return array_map(function (array $data) {
      return ProductFactory::createFromResponseArray($data);
    }, array_merge(...$result));
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/products/product#create-2020-10
   */
  public function create(Product $product): Product
  {
    $response = $this->client->request(
      'POST',
      '/admin/api/2020-10/products.json',
      [
        'json' => [
          'product' => $product->toArray()
        ]
      ]
    );
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return ProductFactory::createFromResponseArray($decodedResponse['product']);
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/products/product#update-2020-10
   */
  public function update(Product $product): Product
  {
    $response = $this->client->request(
      'PUT',
      "/admin/api/2020-10/products/{$product->getId()}.json",
      [
        'json' => [
          'product' => $product->toArray()
        ]
      ]
    );
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return ProductFactory::createFromResponseArray($decodedResponse['product']);
  }


  public function images(): ProductImagesResource
  {
    return new ProductImagesResource($this->client);
  }

  public function variants(): ProductVariantsResource
  {
    return new ProductVariantsResource($this->client);
  }
}
