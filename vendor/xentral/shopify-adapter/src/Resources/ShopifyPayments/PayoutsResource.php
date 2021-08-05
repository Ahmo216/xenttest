<?php

namespace XentralAdapters\Shopify\Resources\ShopifyPayments;

use DateTime;
use XentralAdapters\Shopify\ConditionParser;
use XentralAdapters\Shopify\Data\Payout;
use XentralAdapters\Shopify\PaginatedRequestHandler;
use XentralAdapters\Shopify\RateLimitingAwareClient;
use XentralAdapters\Shopify\ResourceContract;

class PayoutsResource implements ResourceContract
{
  /** @var RateLimitingAwareClient */
  private $client;

  public function __construct(RateLimitingAwareClient $client)
  {
    $this->client = $client;
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/shopify_payments/payout#show-2020-10
   */
  public function get(int $id): Payout
  {
    $response = $this->client->request('GET', "/admin/api/2020-10/shopify_payments/payouts/{$id}.json");
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return $this->createPayoutFromResponseArray($decodedResponse['payout']);
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/shopify_payments/payout#index-2020-10
   * @return Payout[]
   */
  public function list(array $conditions = []): array
  {
    $requestHandler = new PaginatedRequestHandler(
      $this->client,
      '/admin/api/2020-10/shopify_payments/payouts.json',
      ConditionParser::toQueryString($conditions)
    );

    $result = $requestHandler->handle();

    return array_map(function (array $data) {
      return $this->createPayoutFromResponseArray($data);
    }, array_merge(...$result));
  }

  protected function createPayoutFromResponseArray(array $response): Payout
  {
    return new Payout(
      $response['id'],
      $response['status'],
      $response['currency'],
      $response['amount'],
      DateTime::createFromFormat('Y-m-d', $response['date'])?: null
    );
  }
}
