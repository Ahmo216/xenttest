<?php

namespace XentralAdapters\Shopify\Data;

use DateTime;

class Metafield
{
  public const VALUE_TYPE_STRING = 'string';
  public const VALUE_TYPE_INTEGER = 'integer';
  public const VALUE_TYPE_JSON_STRING = 'json_string';

  public const NAMESPACE_GLOBAL = 'global';

  /** @var int|null */
  protected $id;
  /** @var string */
  protected $key;
  /** @var string */
  protected $value;
  /** @var string */
  protected $valueType;
  /** @var string */
  protected $namespace;
  /** @var string */
  protected $description;
  /** @var int|null */
  protected $ownerId;
  /** @var string|null */
  protected $ownerResource;
  /** @var DateTime|null */
  protected $createdAt;
  /** @var DateTime|null */
  protected $updatedAt;

  public function __construct(
    ?int $id = null,
    ?string $key = null,
    ?string $value = null,
    ?string $valueType = null,
    ?string $namespace = null,
    ?string $description = null,
    ?int $ownerId = null,
    ?string $ownerResource = null,
    ?DateTime $createdAt = null,
    ?DateTime $updatedAt = null
  ) {
    $this->id = $id;
    $this->key = $key;
    $this->value = $value;
    $this->valueType = $valueType;
    $this->namespace = $namespace;
    $this->description = $description;
    $this->ownerId = $ownerId;
    $this->ownerResource = $ownerResource;
    $this->createdAt = $createdAt;
    $this->updatedAt = $updatedAt;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getKey(): string
  {
    return $this->key;
  }

  public function getValue(): string
  {
    return $this->value;
  }

  /**
   * @param string $value
   * @return Metafield
   */
  public function setValue(string $value): Metafield
  {
    $this->value = $value;
    return $this;
  }

  public function getValueType(): string
  {
    return $this->valueType;
  }

  public function getNamespace(): string
  {
    return $this->namespace;
  }

  public function getDescription(): string
  {
    return $this->description;
  }

  public function getOwnerId(): ?int
  {
    return $this->ownerId;
  }

  public function getOwnerResource(): ?string
  {
    return $this->ownerResource;
  }

  public function getCreatedAt(): ?DateTime
  {
    return $this->createdAt;
  }

  public function getUpdatedAt(): ?DateTime
  {
    return $this->updatedAt;
  }

  public function toArray(): array{
    return [
      'id' => $this->getId(),
      'key' => $this->getKey(),
      'value' => $this->getValue(),
      'value_type' => $this->getValueType(),
      'namespace' => $this->getNamespace(),
    ];
  }
}
