<?php

namespace XentralAdapters\Shopify\Resources\ShopifyPayments;

use DateTime;
use XentralAdapters\Shopify\ConditionParser;
use XentralAdapters\Shopify\Data\Dispute;
use XentralAdapters\Shopify\PaginatedRequestHandler;
use XentralAdapters\Shopify\RateLimitingAwareClient;
use XentralAdapters\Shopify\ResourceContract;

class DisputesResource implements ResourceContract
{
  /** @var RateLimitingAwareClient */
  private $client;

  public function __construct(RateLimitingAwareClient $client)
  {
    $this->client = $client;
  }
  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/shopify_payments/dispute#index-2020-10
   * @return Dispute[]
   */
  public function list(array $conditions = []): array
  {
    $requestHandler = new PaginatedRequestHandler(
      $this->client,
      '/admin/api/2020-10/shopify_payments/disputes.json',
      ConditionParser::toQueryString($conditions)
    );

    $result = $requestHandler->handle();

    return array_map(function (array $data) {
      return $this->createDisputeFromResponseArray($data);
    }, array_merge(...$result));
  }

  protected function createDisputeFromResponseArray(array $response): Dispute
  {
    return new Dispute(
      $response['id'],
      $response['type'],
      $response['currency'],
      $response['amount'],
      $response['reason'],
      $response['network_reason_code'],
      $response['status'],
      DateTime::createFromFormat(DATE_ATOM, $response['evidence_due_by']) ?: null,
      DateTime::createFromFormat(DATE_ATOM, $response['evidence_sent_on']) ?: null,
      $response['order_id'],
      DateTime::createFromFormat(DATE_ATOM, $response['finalized_on']) ?: null
    );
  }
}
