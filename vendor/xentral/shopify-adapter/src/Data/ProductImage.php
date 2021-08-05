<?php

namespace XentralAdapters\Shopify\Data;

use DateTime;

class ProductImage
{
  /** @var int */
  private $id;
  /** @var int */
  private $position;
  /** @var int */
  private $productId;
  /** @var array */
  private $variantIds;
  /** @var string */
  private $src;
  /** @var int */
  private $width;
  /** @var int */
  private $height;
  /** @var DateTime */
  private $createdAt;
  /** @var DateTime|null */
  private $updatedAt;

  public function __construct(
    int $id,
    int $position,
    int $productId,
    array $variantIds,
    string $src,
    int $width,
    int $height,
    DateTime $createdAt,
    ?DateTime $updatedAt = null
  ) {
    $this->id = $id;
    $this->position = $position;
    $this->productId = $productId;
    $this->variantIds = $variantIds;
    $this->src = $src;
    $this->width = $width;
    $this->height = $height;
    $this->createdAt = $createdAt;
    $this->updatedAt = $updatedAt;
  }

  public function getId(): int
  {
    return $this->id;
  }

  public function getPosition(): int
  {
    return $this->position;
  }

  public function getProductId(): int
  {
    return $this->productId;
  }

  public function getVariantIds(): array
  {
    return $this->variantIds;
  }

  public function getSrc(): string
  {
    return $this->src;
  }

  public function getWidth(): int
  {
    return $this->width;
  }

  public function getHeight(): int
  {
    return $this->height;
  }

  public function getCreatedAt(): DateTime
  {
    return $this->createdAt;
  }

  public function getUpdatedAt(): ?DateTime
  {
    return $this->updatedAt;
  }
}
