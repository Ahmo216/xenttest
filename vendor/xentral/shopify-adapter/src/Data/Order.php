<?php

namespace XentralAdapters\Shopify\Data;

class Order
{
  /** @var int */
  private $id;
  /** @var array */
  private $lineItems;
  /** @var float */
  private $totalPrice;
  /** @var float */
  private $totalTax;
  /** @var float */
  private $totalDiscounts;
  /** @var string */
  private $currency;
  /** @var string */
  private $name;
  /** @var string */
  private $sourceName;
  /** @var string|null */
  private $email;
  /** @var string|null */
  private $note;
  /** @var Customer|null */
  private $customer;

  public function __construct(
    int $id,
    array $lineItems,
    float $totalPrice,
    float $totalTax,
    float $totalDiscounts,
    string $currency,
    string $name,
    string $sourceName,
    ?string $email = null,
    ?string $note = null,
    ?Customer $customer = null
  ) {
    $this->id = $id;
    $this->lineItems = $lineItems;
    $this->totalPrice = $totalPrice;
    $this->totalTax = $totalTax;
    $this->totalDiscounts = $totalDiscounts;
    $this->currency = $currency;
    $this->name = $name;
    $this->sourceName = $sourceName;
    $this->email = $email;
    $this->note = $note;
    $this->customer = $customer;
  }

  public function getId(): int
  {
    return $this->id;
  }

  /**
   * @return OrderLineItem[]
   */
  public function getLineItems(): array
  {
    return $this->lineItems;
  }

  public function getTotalPrice(): float
  {
    return $this->totalPrice;
  }

  public function getTotalTax(): float
  {
    return $this->totalTax;
  }

  public function getTotalDiscounts(): float
  {
    return $this->totalDiscounts;
  }

  public function getCurrency(): string
  {
    return $this->currency;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function getSourceName(): string
  {
    return $this->sourceName;
  }

  public function getEmail(): ?string
  {
    return $this->email;
  }

  public function getNote(): ?string
  {
    return $this->note;
  }

  public function getCustomer(): ?Customer
  {
    return $this->customer;
  }
}
