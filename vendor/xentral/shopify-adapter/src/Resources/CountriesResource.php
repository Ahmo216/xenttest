<?php

namespace XentralAdapters\Shopify\Resources;

use XentralAdapters\Shopify\ConditionParser;
use XentralAdapters\Shopify\Data\Country;
use XentralAdapters\Shopify\PaginatedRequestHandler;
use XentralAdapters\Shopify\RateLimitingAwareClient;
use XentralAdapters\Shopify\ResourceContract;

class CountriesResource implements ResourceContract
{
  /** @var RateLimitingAwareClient */
  private $client;

  public function __construct(RateLimitingAwareClient $client)
  {
    $this->client = $client;
  }
  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/store-properties/country#count-2020-10
   */
  public function count(): int
  {
    $response = $this->client->request('GET', "/admin/api/2020-10/countries/count.json");
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return $decodedResponse['count'];
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/store-properties/country#show-2020-10
   */
  public function get(int $id): Country
  {
    $response = $this->client->request('GET', "/admin/api/2020-10/countries/{$id}.json");
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return $this->createCountryFromResponseArray($decodedResponse['country']);
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/store-properties/country#index-2020-10
   * @return Country[]
   */
  public function list(array $conditions = []): array
  {
    $requestHandler = new PaginatedRequestHandler(
      $this->client,
      '/admin/api/2020-10/countries.json',
      ConditionParser::toQueryString($conditions)
    );

    $result = $requestHandler->handle();

    return array_map(function (array $data) {
      return $this->createCountryFromResponseArray($data);
    }, array_merge(...$result));
  }

  protected function createCountryFromResponseArray(array $data): Country
  {
    return new Country(
      $data['id'],
      $data['name'],
      $data['code'],
      $data['tax'],
      $data['provinces']
    );
  }
}
