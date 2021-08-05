<?php

namespace XentralAdapters\Shopify\Data;

class Refund
{
  /** @var array */
  private $data;

  public function __construct(array $data)
  {
    $this->data = $data;
  }

  public function getRawData(): array
  {
    return $this->data;
  }
}
