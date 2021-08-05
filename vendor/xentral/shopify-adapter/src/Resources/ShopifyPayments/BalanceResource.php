<?php

namespace XentralAdapters\Shopify\Resources\ShopifyPayments;

use XentralAdapters\Shopify\Data\Balance;
use XentralAdapters\Shopify\RateLimitingAwareClient;
use XentralAdapters\Shopify\ResourceContract;

class BalanceResource implements ResourceContract
{
  /** @var RateLimitingAwareClient */
  private $client;

  public function __construct(RateLimitingAwareClient $client)
  {
    $this->client = $client;
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/shopify_payments/balance#show-2020-10
   */
  public function currentBalance(): Balance
  {
    $response = $this->client->request('GET', '/admin/api/2020-10/shopify_payments/balance.json');
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return new Balance($decodedResponse['balance'][0]['currency'], $decodedResponse['balance'][0]['amount']);
  }
}
