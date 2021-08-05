<?php

namespace XentralAdapters\Shopify\Data;

use DateTime;

class OrderTransaction
{
  /** @var int */
  private $id;
  /** @var int */
  private $orderId;
  /** @var string */
  private $currency;
  /** @var float */
  private $amount;
  /** @var string */
  private $status;
  /** @var string */
  private $sourceName;
  /** @var string */
  private $kind;
  /** @var DateTime */
  private $createdAt;
  /** @var DateTime|null */
  private $processedAt;
  /** @var PaymentDetails|null */
  private $paymentDetails;

  public function __construct(
    int $id,
    int $orderId,
    string $currency,
    float $amount,
    string $status,
    string $sourceName,
    string $kind,
    DateTime $createdAt,
    ?DateTime $processedAt = null,
    ?PaymentDetails $paymentDetails = null
  ) {
    $this->id = $id;
    $this->orderId = $orderId;
    $this->currency = $currency;
    $this->amount = $amount;
    $this->status = $status;
    $this->sourceName = $sourceName;
    $this->kind = $kind;
    $this->createdAt = $createdAt;
    $this->processedAt = $processedAt;
    $this->paymentDetails = $paymentDetails;
  }

  public function getId(): int
  {
    return $this->id;
  }

  public function getOrderId(): int
  {
    return $this->orderId;
  }

  public function getCurrency(): string
  {
    return $this->currency;
  }

  public function getAmount(): float
  {
    return $this->amount;
  }

  public function getStatus(): string
  {
    return $this->status;
  }

  public function getSourceName(): string
  {
    return $this->sourceName;
  }

  public function getKind(): string
  {
    return $this->kind;
  }

  public function getCreatedAt(): DateTime
  {
    return $this->createdAt;
  }

  public function getProcessedAt(): ?DateTime
  {
    return $this->processedAt;
  }

  public function getPaymentDetails(): ?PaymentDetails
  {
    return $this->paymentDetails;
  }
}
