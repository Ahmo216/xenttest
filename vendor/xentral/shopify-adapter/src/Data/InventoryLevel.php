<?php

namespace XentralAdapters\Shopify\Data;

use DateTime;

class InventoryLevel
{
  /** @var int */
  private $inventoryItemId;
  /** @var int */
  private $locationId;
  /** @var int|null */
  private $available;
  /** @var DateTime|null */
  private $updatedAt;

  public function __construct(int $inventoryItemId, int $locationId, ?int $available = null, ?DateTime $updatedAt = null)
  {
    $this->inventoryItemId = $inventoryItemId;
    $this->locationId = $locationId;
    $this->available = $available;
    $this->updatedAt = $updatedAt;
  }

  public function getInventoryItemId(): int
  {
    return $this->inventoryItemId;
  }

  public function getLocationId(): int
  {
    return $this->locationId;
  }

  public function getAvailable(): ?int
  {
    return $this->available;
  }

  public function getUpdatedAt(): ?DateTime
  {
    return $this->updatedAt;
  }
}
