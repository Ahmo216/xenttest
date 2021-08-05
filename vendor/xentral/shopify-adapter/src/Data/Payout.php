<?php

namespace XentralAdapters\Shopify\Data;

use DateTime;

class Payout
{
  /** @var int */
  private $id;
  /** @var string */
  private $status;
  /** @var string */
  private $currency;
  /** @var string */
  private $amount;
  /** @var DateTime|null */
  private $date;

  public function __construct(int $id, string $status, string $currency, string $amount, ?DateTime $date = null)
  {
    $this->id = $id;
    $this->status = $status;
    $this->currency = $currency;
    $this->amount = $amount;
    $this->date = $date;
  }

  public function getId(): int
  {
    return $this->id;
  }

  public function getStatus(): string
  {
    return $this->status;
  }

  public function getCurrency(): string
  {
    return $this->currency;
  }

  public function getAmount(): string
  {
    return $this->amount;
  }

  public function getDate(): ?DateTime
  {
    return $this->date;
  }
}
