<?php

namespace XentralAdapters\Shopify\Data;

class Fulfillment
{
  /** @var int */
  private $id;
  /** @var int */
  private $orderId;
  /** @var string */
  private $status;

  public function __construct(int $id, int $orderId, string $status)
  {
    $this->id = $id;
    $this->orderId = $orderId;
    $this->status = $status;
  }

  public function getId(): int
  {
    return $this->id;
  }

  public function getOrderId(): int
  {
    return $this->orderId;
  }

  public function getStatus(): string
  {
    return $this->status;
  }
}
