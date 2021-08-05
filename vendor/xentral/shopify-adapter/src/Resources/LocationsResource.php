<?php

namespace XentralAdapters\Shopify\Resources;

use XentralAdapters\Shopify\ConditionParser;
use XentralAdapters\Shopify\Data\Location;
use XentralAdapters\Shopify\PaginatedRequestHandler;
use XentralAdapters\Shopify\RateLimitingAwareClient;
use XentralAdapters\Shopify\ResourceContract;

class LocationsResource implements ResourceContract
{
  /** @var RateLimitingAwareClient */
  private $client;

  public function __construct(RateLimitingAwareClient $client)
  {
    $this->client = $client;
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/inventory/location#count-2020-10
   */
  public function count(): int
  {
    $response = $this->client->request('GET', "/admin/api/2020-10/locations/count.json");
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return $decodedResponse['count'];
  }

  /**
   * @https://shopify.dev/docs/admin-api/rest/reference/inventory/location#show-2020-10
   */
  public function get(int $id): Location
  {
    $response = $this->client->request('GET', "/admin/api/2020-10/locations/{$id}.json");
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return $this->createLocationFromResponseArray($decodedResponse['country']);
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/inventory/location#index-2020-10
   * @return Location[]
   */
  public function list(array $conditions = []): array
  {
    $requestHandler = new PaginatedRequestHandler(
      $this->client,
      '/admin/api/2020-10/locations.json',
      ConditionParser::toQueryString($conditions)
    );

    $result = $requestHandler->handle();

    return array_map(function (array $data) {
      return $this->createLocationFromResponseArray($data);
    }, array_merge(...$result));
  }

  protected function createLocationFromResponseArray(array $data): Location
  {
    return new Location(
      $data['id'],
      $data['name'],
      $data['active']
    );
  }
}
