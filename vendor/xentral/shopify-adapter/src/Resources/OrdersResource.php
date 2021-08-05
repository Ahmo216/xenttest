<?php

namespace XentralAdapters\Shopify\Resources;

use XentralAdapters\Shopify\ConditionParser;
use XentralAdapters\Shopify\Data\Metafield;
use XentralAdapters\Shopify\Data\Order;
use XentralAdapters\Shopify\Data\Refund;
use XentralAdapters\Shopify\Factories\OrderFactory;
use XentralAdapters\Shopify\PaginatedRequestHandler;
use XentralAdapters\Shopify\RateLimitingAwareClient;
use XentralAdapters\Shopify\ResourceContract;

class OrdersResource implements ResourceContract
{
  /** @var RateLimitingAwareClient */
  private $client;

  public function __construct(RateLimitingAwareClient $client)
  {
    $this->client = $client;
  }

  /**
   * @list https://shopify.dev/docs/admin-api/rest/reference/orders/order#show-2020-10
   */
  public function get(int $id): Order
  {
    $response = $this->client->request('GET', "/admin/api/2020-10/orders/{$id}.json");
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return OrderFactory::createFromResponseArray($decodedResponse['order']);
  }

  /**
   * @list https://shopify.dev/docs/admin-api/rest/reference/orders/order#index-2020-10
   */
  public function list(array $conditions = []): array
  {
    $requestHandler = new PaginatedRequestHandler(
      $this->client,
      '/admin/api/2020-10/orders.json',
      ConditionParser::toQueryString($conditions)
    );

    $result = $requestHandler->handle();

    return array_map(function (array $data) {
      return OrderFactory::createFromResponseArray($data);
    }, array_merge(...$result));
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/orders/order#cancel-2020-10
   */
  public function cancel(int $orderId): Order
  {
    $response = $this->client->request('POST', "/admin/api/2020-10/orders/{$orderId}/cancel.json");
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return OrderFactory::createFromResponseArray($decodedResponse['order']);
  }

  public function transactions(): OrderTransactionsResource
  {
    return new OrderTransactionsResource($this->client);
  }

  /**
   * @return Metafield[]
   */
  public function listMetafields(int $orderId): array
  {
    return (new MetafieldsResource($this->client))->listFor('order', $orderId);
  }

  public function refund(Order $order): Refund
  {
    return (new OrderRefundResource($this->client))->refund($order);
  }

  public function fulfillments(): FulfillmentsResource
  {
    return new FulfillmentsResource($this->client);
  }
}
