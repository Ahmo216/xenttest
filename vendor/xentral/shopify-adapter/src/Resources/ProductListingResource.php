<?php

namespace XentralAdapters\Shopify\Resources;

use Psr\Http\Client\ClientExceptionInterface;
use XentralAdapters\Shopify\ConditionParser;
use XentralAdapters\Shopify\Data\ProductListing;
use XentralAdapters\Shopify\PaginatedRequestHandler;
use XentralAdapters\Shopify\RateLimitingAwareClient;
use XentralAdapters\Shopify\ResourceContract;

class ProductListingResource implements ResourceContract
{
  /** @var RateLimitingAwareClient */
  private $client;

  public function __construct(RateLimitingAwareClient $client)
  {
    $this->client = $client;
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/sales-channels/productlisting#show-2020-10
   */
  public function get(int $id): ProductListing
  {
    $response = $this->client->request('GET', "/admin/api/2020-10/product_listings/{$id}.json");
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return $this->createProductListingFromResponseArray($decodedResponse['product_listing']);
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/sales-channels/productlisting#count-2020-10
   */
  public function count(): int
  {
    $response = $this->client->request('GET', '/admin/api/2020-10/product_listings/count.json');
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return $decodedResponse['count'];
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/sales-channels/productlisting#index-2020-10
   * @return ProductListing[]
   */
  public function list(array $conditions = []): array
  {
    $requestHandler = new PaginatedRequestHandler(
      $this->client,
      '/admin/api/2020-10/product_listings.json',
      ConditionParser::toQueryString($conditions)
    );

    $result = $requestHandler->handle();

    return array_map(function (array $data) {
      return $this->createProductListingFromResponseArray($data);
    }, array_merge(...$result));
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/sales-channels/productlisting#destroy-2020-10
   */
  public function delete(int $productListingId): bool
  {
    try {
      $this->client->request('DELETE', "/admin/api/2020-10/product_listings/{$productListingId}.json");

      return true;
    } catch (ClientExceptionInterface $exception) {
      return false;
    }
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/sales-channels/productlisting#destroy-2020-10
   */
  public function create(int $productId): ProductListing
  {
    // TODO is this really PUT for create? how is this concept?
    $response = $this->client->request(
      'PUT',
      "/admin/api/2020-10/product_listings/{$productId}.json",
      [
        'json' => [
          'product_listing' => [
            'product_id' => $productId
          ]
        ]
      ]
    );
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return $this->createProductListingFromResponseArray($decodedResponse['product_listing']);
  }

  protected function createProductListingFromResponseArray(array $data): ProductListing
  {
    //TODO fill relevant fields
    return new ProductListing();
  }
}
