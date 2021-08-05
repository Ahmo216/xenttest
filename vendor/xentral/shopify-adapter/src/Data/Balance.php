<?php

namespace XentralAdapters\Shopify\Data;

class Balance
{
  /** @var string */
  private $currency;
  /** @var string */
  private $amount;

  public function __construct(string $currency, string $amount)
  {
    $this->currency = $currency;
    $this->amount = $amount;
  }

  public function getCurrency(): string
  {
    return $this->currency;
  }

  public function getAmount(): string
  {
    return $this->amount;
  }
}
