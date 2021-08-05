<?php

namespace XentralAdapters\Shopify\Resources;

use XentralAdapters\Shopify\Data\Fulfillment;
use XentralAdapters\Shopify\RateLimitingAwareClient;
use XentralAdapters\Shopify\ResourceContract;

class FulfillmentsResource implements ResourceContract
{
  /** @var RateLimitingAwareClient */
  private $client;

  public function __construct(RateLimitingAwareClient $client)
  {
    $this->client = $client;
  }

  public function list(int $orderId): array
  {
    $response = $this->client->request('GET', "/admin/api/2020-10/orders/{$orderId}/fulfillments.json");
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return array_map(function (array $data) {
      return $this->createFulfillmentFromResponseArray($data);
    }, $decodedResponse['fulfillments']);
  }

  public function create(
    int $orderId,
    int $locationId,
    string $trackingNumber,
    array $trackingUrls,
    bool $notifyCustomer
  ): Fulfillment
  {
    $response = $this->client->request(
      'POST',
      "/admin/api/2020-10/orders/{$orderId}/fulfillments.json",
      [
        'json' => [
          'fulfillment' => [
            'location_id' => $locationId,
            'tracking_number' => $trackingNumber,
            'tracking_urls' => $trackingUrls,
            'notify_customer' => $notifyCustomer
          ]
        ]
      ]
    );
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return $this->createFulfillmentFromResponseArray($decodedResponse['fulfillment']);
  }

  private function createFulfillmentFromResponseArray(array $data): Fulfillment
  {
    return new Fulfillment($data['id'], $data['order_id'], $data['status']);
  }
}
