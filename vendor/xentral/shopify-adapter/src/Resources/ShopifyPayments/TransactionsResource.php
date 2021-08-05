<?php

namespace XentralAdapters\Shopify\Resources\ShopifyPayments;

use DateTime;
use XentralAdapters\Shopify\ConditionParser;
use XentralAdapters\Shopify\Data\Transaction;
use XentralAdapters\Shopify\PaginatedRequestHandler;
use XentralAdapters\Shopify\RateLimitingAwareClient;
use XentralAdapters\Shopify\ResourceContract;

class TransactionsResource implements ResourceContract
{
  /** @var RateLimitingAwareClient */
  private $client;

  public function __construct(RateLimitingAwareClient $client)
  {
    $this->client = $client;
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/shopify_payments/transaction#index-2020-10
   * @return Transaction[]
   */
  public function forPayout(int $payoutId): array
  {
    $requestHandler = new PaginatedRequestHandler(
      $this->client,
      '/admin/api/2020-10/shopify_payments/balance/transactions.json',
      ConditionParser::toQueryString(['payout_id' => $payoutId])
    );

    $result = $requestHandler->handle();

    return array_map(function (array $data) {
      return $this->createTransactionFromResponseArray($data);
    }, array_merge(...$result));
  }

  protected function createTransactionFromResponseArray(array $response): Transaction
  {
    return new Transaction(
      $response['id'],
      $response['type'],
      $response['test'],
      $response['payout_id'],
      $response['payout_status'],
      $response['currency'],
      $response['amount'],
      $response['fee'],
      $response['net'],
      DateTime::createFromFormat(DATE_ATOM, $response['processed_at']) ?: null,
      $response['source_id'],
      $response['source_type'],
      $response['source_order_id'],
      $response['source_order_transaction_id']
    );
  }
}
