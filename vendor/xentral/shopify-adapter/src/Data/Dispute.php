<?php

namespace XentralAdapters\Shopify\Data;

use DateTime;

class Dispute
{
  /** @var int */
  private $id;
  /** @var string */
  private $type;
  /** @var string */
  private $currency;
  /** @var string */
  private $amount;
  /** @var string */
  private $reason;
  /** @var int */
  private $networkReasonCode;
  /** @var string */
  private $status;
  /** @var DateTime|null */
  private $evidenceDueBy;
  /** @var DateTime|null */
  private $evidenceSentOn;
  /** @var int|null */
  private $orderId;
  /** @var DateTime|null */
  private $finalizedOn;

  public function __construct(
    int $id,
    string $type,
    string $currency,
    string $amount,
    string $reason,
    int $networkReasonCode,
    string $status,
    ?DateTime $evidenceDueBy = null,
    ?DateTime $evidenceSentOn = null,
    ?int $orderId = null,
    ?DateTime $finalizedOn = null
  ) {
    $this->id = $id;
    $this->type = $type;
    $this->currency = $currency;
    $this->amount = $amount;
    $this->reason = $reason;
    $this->networkReasonCode = $networkReasonCode;
    $this->status = $status;
    $this->evidenceDueBy = $evidenceDueBy;
    $this->evidenceSentOn = $evidenceSentOn;
    $this->orderId = $orderId;
    $this->finalizedOn = $finalizedOn;
  }

  public function getId(): int
  {
    return $this->id;
  }

  public function getType(): string
  {
    return $this->type;
  }

  public function getCurrency(): string
  {
    return $this->currency;
  }

  public function getAmount(): string
  {
    return $this->amount;
  }

  public function getReason(): string
  {
    return $this->reason;
  }

  public function getNetworkReasonCode(): int
  {
    return $this->networkReasonCode;
  }

  public function getStatus(): string
  {
    return $this->status;
  }

  public function getEvidenceDueBy(): ?DateTime
  {
    return $this->evidenceDueBy;
  }

  public function getEvidenceSentOn(): ?DateTime
  {
    return $this->evidenceSentOn;
  }

  public function getOrderId(): ?int
  {
    return $this->orderId;
  }

  public function getFinalizedOn(): ?DateTime
  {
    return $this->finalizedOn;
  }
}
