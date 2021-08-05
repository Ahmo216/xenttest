<?php

namespace XentralAdapters\Shopify\Resources;

use DateTime;
use XentralAdapters\Shopify\Data\OrderTransaction;
use XentralAdapters\Shopify\Data\PaymentDetails;
use XentralAdapters\Shopify\RateLimitingAwareClient;
use XentralAdapters\Shopify\ResourceContract;

class OrderTransactionsResource implements ResourceContract
{
  /** @var RateLimitingAwareClient */
  private $client;

  public function __construct(RateLimitingAwareClient $client)
  {
    $this->client = $client;
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/orders/transaction#index-2020-10
   * @return OrderTransaction[]
   */
  public function list(int $orderId): array
  {
    $response = $this->client->request('GET', "/admin/api/2020-10/orders/{$orderId}/transactions.json");
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return array_map(function (array $data) {
      return $this->createOrderTransactionsFromResponseArray($data);
    }, $decodedResponse['transactions']);
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/orders/transaction#show-2020-10
   */
  public function get(int $orderId, int $transactionId): OrderTransaction
  {
    $response = $this->client->request('GET', "/admin/api/2020-10/orders/{$orderId}/transactions/{$transactionId}.json");
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return $this->createOrderTransactionsFromResponseArray($decodedResponse['transaction']);
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/orders/transaction#count-2020-10
   */
  public function count(int $orderId): int
  {
    $response = $this->client->request('GET', "/admin/api/2020-10/orders/{$orderId}/transactions/count.json");
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return $decodedResponse['count'];
  }

  protected function createOrderTransactionsFromResponseArray(array $data): OrderTransaction
  {
    if (isset($data['payment_details'])) {
      $paymentDetails = new PaymentDetails(
        $data['payment_details']['avs_result_code'],
        $data['payment_details']['credit_card_bin'],
        $data['payment_details']['credit_card_company'],
        $data['payment_details']['credit_card_number'],
        $data['payment_details']['cvv_result_code']
      );
    } else {
      $paymentDetails = null;
    }

    return new OrderTransaction(
      $data['id'],
      $data['order_id'],
      $data['currency'],
      $data['amount'],
      $data['status'],
      $data['source_name'],
      $data['kind'],
      DateTime::createFromFormat(DATE_ATOM, $data['created_at'])?: new DateTime(),
      DateTime::createFromFormat(DATE_ATOM, $data['processed_at'])?: null
    );
  }
}
