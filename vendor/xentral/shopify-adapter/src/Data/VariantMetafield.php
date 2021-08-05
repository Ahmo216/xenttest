<?php

namespace XentralAdapters\Shopify\Data;

use DateTime;

class VariantMetafield extends Metafield
{
  /** @var int|null */
  protected $productId;
  /** @var string|null */
  protected $ownerResource = 'variant';

  public function __construct(
    ?int $id,
    string $key,
    string $value,
    string $valueType,
    string $namespace,
    string $description,
    int $ownerId,
    int $productId,
    ?DateTime $createdAt = null,
    ?DateTime $updatedAt = null
  )
  {
    $this->id = $id;
    $this->key = $key;
    $this->value = $value;
    $this->valueType = $valueType;
    $this->namespace = $namespace;
    $this->description = $description;
    $this->ownerId = $ownerId;
    $this->createdAt = $createdAt;
    $this->updatedAt = $updatedAt;
    $this->productId = $productId;
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
   * @return VariantMetafield
   */
  public function setProductId(?int $productId): VariantMetafield
  {
    $this->productId = $productId;
    return $this;
  }

}
