<?php

namespace XentralAdapters\Shopify\Data;

use DateTime;

class Product
{
  public const STATUS_ACTIVE = 'active';
  public const STATUS_ARCHIVED = 'archived';
  public const STATUS_DRAFT = 'draft';

  /** @var int|null */
  private $id;
  /** @var string|null */
  private $handle;
  /** @var string|null */
  private $title;
  /** @var string|null */
  private $bodyHtml;
  /** @var string|null */
  private $vendor;
  /** @var string|null */
  private $productType;
  /** @var bool|null */
  private $published;
  /** @var string|null */
  private $status;
  /** @var array */
  private $images;
  /** @var array */
  private $options = [];
  /** @var array */
  private $variants = [];
  /** @var DateTime|null */
  private $createdAt;
  /** @var DateTime|null */
  private $updatedAt;
  /** @var DateTime|null */
  private $publishedAt;

  public function __construct(
    ?int $id = null,
    ?DateTime $createdAt = null,
    ?DateTime $updatedAt = null,
    ?DateTime $publishedAt = null
  )
  {
    $this->id = $id;
    $this->createdAt = $createdAt;
    $this->updatedAt = $updatedAt;
    $this->publishedAt = $publishedAt;
  }

  public function toArray(): array
  {
    $productDataArray = [
      'id' => $this->getId(),
      'title' => $this->getTitle(),
      'handle' => $this->getHandle(),
      'body_html' => $this->getBodyHtml(),
      'status' => $this->getStatus(),
      'options' => $this->getOptions()
    ];

    $productDataArray['variants'] = array_map(function (ProductVariant $variant) {
      return $variant->toArray();
    }, $this->getVariants());

    return array_filter($productDataArray);
  }

  /**
   * @return int|null
   */
  public function getId(): ?int
  {
    return $this->id;
  }

  /**
   * @param int|null $id
   * @return Product
   */
  public function setId(?int $id): Product
  {
    $this->id = $id;
    return $this;
  }

  /**
   * @return string|null
   */
  public function getTitle(): ?string
  {
    return $this->title;
  }

  /**
   * @param string|null $title
   * @return Product
   */
  public function setTitle(?string $title): Product
  {
    $this->title = $title;
    return $this;
  }

  /**
   * @return string|null
   */
  public function getHandle(): ?string
  {
    return $this->handle;
  }

  /**
   * @param string|null $handle
   * @return Product
   */
  public function setHandle(?string $handle): Product
  {
    $this->handle = $handle;
    return $this;
  }

  /**
   * @return string|null
   */
  public function getBodyHtml(): ?string
  {
    return $this->bodyHtml;
  }

  /**
   * @param string|null $bodyHtml
   * @return Product
   */
  public function setBodyHtml(?string $bodyHtml): Product
  {
    $this->bodyHtml = $bodyHtml;
    return $this;
  }

  /**
   * @return string|null
   */
  public function getStatus(): ?string
  {
    return $this->status;
  }

  /**
   * @param string|null $status
   * @return Product
   */
  public function setStatus(?string $status): Product
  {
    if (!in_array($status, [
      null, self::STATUS_ACTIVE, self::STATUS_ARCHIVED, self::STATUS_DRAFT
    ], true)) {
      //todo throw exception
    }
    $this->status = $status;
    return $this;
  }

  /**
   * @return array
   */
  public function getOptions(): array
  {
    return $this->options;
  }

  /**
   * @param array $options
   * @return Product
   */
  public function setOptions(array $options): Product
  {
    $this->options = $options;
    return $this;
  }

  /**
   * @return array
   */
  public function getVariants(): array
  {
    return $this->variants;
  }

  /**
   * @param array $variants
   * @return Product
   */
  public function setVariants(array $variants): Product
  {
    $this->variants = $variants;
    return $this;
  }

  /**
   * @return string|null
   */
  public function getVendor(): ?string
  {
    return $this->vendor;
  }

  /**
   * @param string|null $vendor
   * @return Product
   */
  public function setVendor(?string $vendor): Product
  {
    $this->vendor = $vendor;
    return $this;
  }

  /**
   * @return string|null
   */
  public function getProductType(): ?string
  {
    return $this->productType;
  }

  /**
   * @param string|null $productType
   * @return Product
   */
  public function setProductType(?string $productType): Product
  {
    $this->productType = $productType;
    return $this;
  }

  /**
   * @return bool|null
   */
  public function getPublished(): ?bool
  {
    return $this->published;
  }

  /**
   * @param bool|null $published
   * @return Product
   */
  public function setPublished(?bool $published): Product
  {
    $this->published = $published;
    return $this;
  }

  /**
   * @return array
   */
  public function getImages(): array
  {
    return $this->images;
  }

  /**
   * @param array $images
   * @return Product
   */
  public function setImages(array $images): Product
  {
    $this->images = $images;
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
   * @return DateTime|null
   */
  public function getUpdatedAt(): ?DateTime
  {
    return $this->updatedAt;
  }

  /**
   * @return DateTime|null
   */
  public function getPublishedAt(): ?DateTime
  {
    return $this->publishedAt;
  }

}
