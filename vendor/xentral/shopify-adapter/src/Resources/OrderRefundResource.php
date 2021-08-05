<?php

namespace XentralAdapters\Shopify\Resources;

use XentralAdapters\Shopify\Data\Order;
use XentralAdapters\Shopify\Data\OrderLineItem;
use XentralAdapters\Shopify\Data\Refund;
use XentralAdapters\Shopify\RateLimitingAwareClient;
use XentralAdapters\Shopify\ResourceContract;

class OrderRefundResource implements ResourceContract
{
  /** @var RateLimitingAwareClient */
  private $client;

  public function __construct(RateLimitingAwareClient $client)
  {
    $this->client = $client;
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/orders/refund?api%5Bversion%5D=2020-10#calculate-2020-10
   * @link https://shopify.dev/docs/admin-api/rest/reference/orders/refund?api%5Bversion%5D=2020-10#create-2020-10
   */
  public function refund(Order $order): Refund
  {
    $lineItemsToRefund = array_map(function (OrderLineItem $lineItem) {
      return [
        'line_item_id' => $lineItem->getId(),
        'quantity' => $lineItem->getQuantity()
      ];
    }, $order->getLineItems());

    $refundCalculationResponse = $this->client->request(
      'POST',
      "/admin/api/2020-10/orders/{$order->getId()}/refunds/calculate.json",
      [
        'json' => [
          'refund' => [
            'refund_line_items' => $lineItemsToRefund
          ]
        ]
      ]
    );

    $refundResponse = $this->client->request(
      'POST',
      "/admin/api/2020-10/orders/{$order->getId()}/refunds.json",
      [
        'json' => json_decode($refundCalculationResponse->getBody()->getContents(), true)
      ]
    );

    $decodedResponse = json_decode($refundResponse->getBody()->getContents(), true);

    return new Refund($decodedResponse['refund']);
  }
}
