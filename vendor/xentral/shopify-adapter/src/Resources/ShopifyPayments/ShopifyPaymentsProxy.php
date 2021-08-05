<?php

namespace XentralAdapters\Shopify\Resources\ShopifyPayments;

use XentralAdapters\Shopify\RateLimitingAwareClient;

class ShopifyPaymentsProxy
{
  /** @var RateLimitingAwareClient */
  private $client;

  public function __construct(RateLimitingAwareClient $client)
  {
    $this->client = $client;
  }

  public function balance(): BalanceResource
  {
    return new BalanceResource($this->client);
  }

  public function disputes(): DisputesResource
  {
    return new DisputesResource($this->client);
  }

  public function payouts(): PayoutsResource
  {
    return new PayoutsResource($this->client);
  }

  public function transactions(): TransactionsResource
  {
    return new TransactionsResource($this->client);
  }
}
