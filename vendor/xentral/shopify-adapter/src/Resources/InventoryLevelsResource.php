<?php

namespace XentralAdapters\Shopify\Resources;

use DateTime;
use InvalidArgumentException;
use XentralAdapters\Shopify\ConditionParser;
use XentralAdapters\Shopify\Data\InventoryLevel;
use XentralAdapters\Shopify\PaginatedRequestHandler;
use XentralAdapters\Shopify\RateLimitingAwareClient;
use XentralAdapters\Shopify\ResourceContract;

class InventoryLevelsResource implements ResourceContract
{
  /** @var RateLimitingAwareClient */
  private $client;

  public function __construct(RateLimitingAwareClient $client)
  {
    $this->client = $client;
  }
  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/inventory/inventorylevel#index-2020-10
   * @return InventoryLevel[]
   */
  public function list(array $conditions = []): array
  {
    if (!isset($conditions['inventory_item_ids'])
      && !isset($conditions['location_ids'])) {
      throw new InvalidArgumentException(
        'You have to add at lest one of inventory_item_ids and location_ids to the conditions'
      );
    }

    $requestHandler = new PaginatedRequestHandler(
      $this->client,
      '/admin/api/2020-10/inventory_levels.json',
      ConditionParser::toQueryString($conditions)
    );

    $result = $requestHandler->handle();

    return array_map(function (array $data) {
      return $this->createInventoryLevelFromResponseArray($data);
    }, array_merge(...$result));
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/inventory/inventorylevel#adjust-2020-10
   */
  public function adjust(int $inventoryItemId, int $locationId, int $adjustment): InventoryLevel
  {
    $response = $this->client->request(
      'POST',
      '/admin/api/2020-10/inventory_levels/adjust.json',
      [
        'json' => [
          'inventory_item_id' => $inventoryItemId,
          'location_id' => $locationId,
          'available_adjustment' => $adjustment,
        ]
      ]
    );
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return $this->createInventoryLevelFromResponseArray($decodedResponse['inventory_level']);
  }

  protected function createInventoryLevelFromResponseArray(array $data): InventoryLevel
  {
    return new InventoryLevel(
      $data['inventory_item_id'],
      $data['location_id'],
      $data['available'],
      DateTime::createFromFormat(DATE_ATOM, $data['updated_at']) ?: null
    );
  }
}
