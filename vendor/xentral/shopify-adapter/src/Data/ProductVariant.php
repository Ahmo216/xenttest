<?php

namespace XentralAdapters\Shopify\Data;

use DateTime;

class ProductVariant
{
  public const INVENTORY_MANAGEMENT_SHOPIFY = 'shopify';
  public const INVENTORY_MANAGEMENT_NOTRACKING = null;
  public const INVENTORY_MANAGEMENT_FULFILLMENT_SERVICE = 'fulfillment_service';

  /** @var int */
  private $id;
  /** @var int|null */
  private $productId;
  /** @var int|null */
  private $inventoryItemId;
  /** @var int */
  private $inventoryQuantity;
  /** @var string */
  private $sku = '';
  /** @var string|null */
  private $inventoryPolicy;
  /** @var string|null */
  private $inventoryManagement;
  /** @var string|null */
  private $barcode;
  /** @var string */
  private $title = '';
  /** @var float */
  private $price = 0;
  /** @var float|null */
  private $compareAtPrice;
  /** @var float|null */
  private $weight;
  /** @var bool */
  private $taxable = true;
  /** @var int|null */
  private $position;
  /** @var DateTime|null */
  private $createdAt;
  /** @var DateTime|null */
  private $updatedAt;
  /** @var string|null */
  private $option1;
  /** @var string|null */
  private $option2;
  /** @var string|null */
  private $option3;

  public function __construct(
    ?int $id = null,
    ?DateTime $createdAt = null,
    ?DateTime $updatedAt = null
  )
  {
    $this->id = $id;
    $this->createdAt = $createdAt;
    $this->updatedAt = $updatedAt;
  }

  /**
   * @return int|null
   */
  public function getProductId(): ?int
  {
    return $this->productId;
  }

  /**
   * @param int|null $productId
   * @return ProductVariant
   */
  public function setProductId(?int $productId): ProductVariant
  {
    $this->productId = $productId;
    return $this;
  }

  /**
   * @return int
   */
  public function getInventoryQuantity(): int
  {
    return $this->inventoryQuantity;
  }

  /**
   * @param int $inventoryQuantity
   * @return ProductVariant
   */
  public function setInventoryQuantity(int $inventoryQuantity): ProductVariant
  {
    $this->inventoryQuantity = $inventoryQuantity;
    return $this;
  }

  /**
   * @return int|null
   */
  public function getPosition(): ?int
  {
    return $this->position;
  }

  /**
   * @param int|null $position
   * @return ProductVariant
   */
  public function setPosition(?int $position): ProductVariant
  {
    $this->position = $position;
    return $this;
  }

  /**
   * @return DateTime|null
   */
  public function getCreatedAt(): ?DateTime
  {
    return $this->createdAt;
  }

  /**
   * @param DateTime|null $createdAt
   * @return ProductVariant
   */
  public function setCreatedAt(?DateTime $createdAt): ProductVariant
  {
    $this->createdAt = $createdAt;
    return $this;
  }

  /**
   * @return DateTime|null
   */
  public function getUpdatedAt(): ?DateTime
  {
    return $this->updatedAt;
  }

  /**
   * @param DateTime|null $updatedAt
   * @return ProductVariant
   */
  public function setUpdatedAt(?DateTime $updatedAt): ProductVariant
  {
    $this->updatedAt = $updatedAt;
    return $this;
  }

  public function toArray(): array
  {
    $variantDataArray = [
      'id' => $this->getId(),
      'inventory_item_id' => $this->getInventoryItemId(),
      'inventory_management' => $this->getInventoryManagement(),
      'inventory_policy' => $this->getInventoryPolicy(),
      'weight' => $this->getWeight(),
      'barcode' => $this->getBarcode(),
      'compare_at_price' => $this->getCompareAtPrice(),
      'price' => $this->getPrice(),
      'title' => $this->getTitle(),
      'sku' => $this->getSku(),
      'taxable' => $this->isTaxable(),
      'option1' => $this->getOption1(),
      'option2' => $this->getOption2(),
      'option3' => $this->getOption3(),
    ];

    return array_filter($variantDataArray);
  }

  /**
   * @return int
   */
  public function getId(): ?int
  {
    return $this->id;
  }

  /**
   * @param int $id
   * @return ProductVariant
   */
  public function setId(int $id): ProductVariant
  {
    $this->id = $id;
    return $this;
  }

  /**
   * @return int|null
   */
  public function getInventoryItemId(): ?int
  {
    return $this->inventoryItemId;
  }

  /**
   * @param int|null $inventoryItemId
   * @return ProductVariant
   */
  public function setInventoryItemId(?int $inventoryItemId): ProductVariant
  {
    $this->inventoryItemId = $inventoryItemId;
    return $this;
  }

  /**
   * @return string|null
   */
  public function getInventoryManagement(): ?string
  {
    return $this->inventoryManagement;
  }

  /**
   * @param string|null $inventoryManagement
   * @return ProductVariant
   */
  public function setInventoryManagement(?string $inventoryManagement): ProductVariant
  {
    $this->inventoryManagement = $inventoryManagement;
    return $this;
  }

  /**
   * @return string|null
   */
  public function getInventoryPolicy(): ?string
  {
    return $this->inventoryPolicy;
  }

  /**
   * @param string|null $inventoryPolicy
   * @return ProductVariant
   */
  public function setInventoryPolicy(?string $inventoryPolicy): ProductVariant
  {
    $this->inventoryPolicy = $inventoryPolicy;
    return $this;
  }

  /**
   * @return float|null
   */
  public function getWeight(): ?float
  {
    return $this->weight;
  }

  /**
   * @param float|null $weight
   * @return ProductVariant
   */
  public function setWeight(?float $weight): ProductVariant
  {
    $this->weight = $weight;
    return $this;
  }

  /**
   * @return string|null
   */
  public function getBarcode(): ?string
  {
    return $this->barcode;
  }

  /**
   * @param string|null $barcode
   * @return ProductVariant
   */
  public function setBarcode(?string $barcode): ProductVariant
  {
    $this->barcode = $barcode;
    return $this;
  }

  /**
   * @return float|null
   */
  public function getCompareAtPrice(): ?float
  {
    return $this->compareAtPrice;
  }

  /**
   * @param float|null $compareAtPrice
   * @return ProductVariant
   */
  public function setCompareAtPrice(?float $compareAtPrice): ProductVariant
  {
    $this->compareAtPrice = $compareAtPrice;
    return $this;
  }

  /**
   * @return float
   */
  public function getPrice(): float
  {
    return $this->price;
  }

  /**
   * @param float $price
   * @return ProductVariant
   */
  public function setPrice(float $price): ProductVariant
  {
    $this->price = $price;
    return $this;
  }

  /**
   * @return string
   */
  public function getTitle(): string
  {
    return $this->title;
  }

  /**
   * @param string $title
   * @return ProductVariant
   */
  public function setTitle(string $title): ProductVariant
  {
    $this->title = $title;
    return $this;
  }

  /**
   * @return string
   */
  public function getSku(): string
  {
    return $this->sku;
  }

  /**
   * @param string $sku
   * @return ProductVariant
   */
  public function setSku(string $sku): ProductVariant
  {
    $this->sku = $sku;
    return $this;
  }

  /**
   * @return bool
   */
  public function isTaxable(): bool
  {
    return $this->taxable;
  }

  /**
   * @param bool $taxable
   * @return ProductVariant
   */
  public function setTaxable(bool $taxable): ProductVariant
  {
    $this->taxable = $taxable;
    return $this;
  }

  /**
   * @return string|null
   */
  public function getOption1(): ?string
  {
    return $this->option1;
  }

  /**
   * @param string|null $option1
   * @return ProductVariant
   */
  public function setOption1(?string $option1): ProductVariant
  {
    $this->option1 = $option1;
    return $this;
  }

  /**
   * @return string|null
   */
  public function getOption2(): ?string
  {
    return $this->option2;
  }

  /**
   * @param string|null $option2
   * @return ProductVariant
   */
  public function setOption2(?string $option2): ProductVariant
  {
    $this->option2 = $option2;
    return $this;
  }

  /**
   * @return string|null
   */
  public function getOption3(): ?string
  {
    return $this->option3;
  }

  /**
   * @param string|null $option3
   * @return ProductVariant
   */
  public function setOption3(?string $option3): ProductVariant
  {
    $this->option3 = $option3;
    return $this;
  }
}
