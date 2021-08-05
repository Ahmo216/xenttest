<?php

namespace XentralAdapters\Shopify\Data;

class OrderLineItem
{
  /** @var int */
  private $id;
  /** @var int */
  private $productId;
  /** @var int */
  private $variantId;
  /** @var string */
  private $title;
  /** @var array */
  private $discountAllocations;
  /** @var bool */
  private $taxable;
  /** @var array */
  private $taxLines;
  /** @var array */
  private $priceSet;
  /** @var int */
  private $quantity;

  public function __construct(
    int $id,
    int $productId,
    int $variantId,
    string $title,
    array $discountAllocations,
    bool $taxable,
    array $taxLines,
    array $priceSet,
    int $quantity
  ) {
    $this->id = $id;
    $this->productId = $productId;
    $this->variantId = $variantId;
    $this->title = $title;
    $this->discountAllocations = $discountAllocations;
    $this->taxable = $taxable;
    $this->taxLines = $taxLines;
    $this->priceSet = $priceSet;
    $this->quantity = $quantity;
  }

  public function getId(): int
  {
    return $this->id;
  }

  public function getProductId(): int
  {
    return $this->productId;
  }

  public function getVariantId(): int
  {
    return $this->variantId;
  }

  public function getTitle(): string
  {
    return $this->title;
  }

  public function getDiscountAllocations(): array
  {
    return $this->discountAllocations;
  }

  public function isTaxable(): bool
  {
    return $this->taxable;
  }

  public function getTaxLines(): array
  {
    return $this->taxLines;
  }

  public function getPriceSet(): array
  {
    return $this->priceSet;
  }

  public function getQuantity(): int
  {
    return $this->quantity;
  }
}
