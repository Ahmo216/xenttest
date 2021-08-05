<?php

namespace XentralAdapters\Shopify\Data;

use DateTime;

class Transaction
{
  /** @var int */
  private $id;
  /** @var string */
  private $type;
  /** @var bool */
  private $test;
  /** @var int */
  private $payoutId;
  /** @var string */
  private $payoutStatus;
  /** @var string */
  private $currency;
  /** @var string */
  private $amount;
  /** @var string */
  private $fee;
  /** @var string */
  private $net;
  /** @var int|null */
  private $sourceId;
  /** @var string|null */
  private $sourceType;
  /** @var DateTime|null */
  private $processedAt;
  /** @var int|null */
  private $sourceOrderId;
  /** @var int|null */
  private $sourceOrderTransactionId;

  public function __construct(
    int $id,
    string $type,
    bool $test,
    int $payoutId,
    string $payoutStatus,
    string $currency,
    string $amount,
    string $fee,
    string $net,
    ?DateTime $processedAt = null,
    ?int $sourceId = null,
    ?string $sourceType = null,
    ?int $sourceOrderId = null,
    ?int $sourceOrderTransactionId = null
  ) {
    $this->id = $id;
    $this->type = $type;
    $this->test = $test;
    $this->payoutId = $payoutId;
    $this->payoutStatus = $payoutStatus;
    $this->currency = $currency;
    $this->amount = $amount;
    $this->fee = $fee;
    $this->net = $net;
    $this->processedAt = $processedAt;
    $this->sourceId = $sourceId;
    $this->sourceType = $sourceType;
    $this->sourceOrderId = $sourceOrderId;
    $this->sourceOrderTransactionId = $sourceOrderTransactionId;
  }

  public function getId(): int
  {
    return $this->id;
  }

  public function getType(): string
  {
    return $this->type;
  }

  public function isTest(): bool
  {
    return $this->test;
  }

  public function getPayoutId(): int
  {
    return $this->payoutId;
  }

  public function getPayoutStatus(): string
  {
    return $this->payoutStatus;
  }

  public function getCurrency(): string
  {
    return $this->currency;
  }

  public function getAmount(): string
  {
    return $this->amount;
  }

  public function getFee(): string
  {
    return $this->fee;
  }

  public function getNet(): string
  {
    return $this->net;
  }

  public function getProcessedAt(): ?DateTime
  {
    return $this->processedAt;
  }

  public function getSourceId(): ?int
  {
    return $this->sourceId;
  }

  public function getSourceType(): ?string
  {
    return $this->sourceType;
  }

  public function getSourceOrderId(): ?int
  {
    return $this->sourceOrderId;
  }

  public function getSourceOrderTransactionId(): ?int
  {
    return $this->sourceOrderTransactionId;
  }
}
