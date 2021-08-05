<?php

namespace XentralAdapters\Shopify\Data;

class Location
{
  /** @var int */
  private $id;
  /** @var string */
  private $name;
  /** @var bool */
  private $active;

  public function __construct(int $id, string $name, bool $active)
  {
    $this->id = $id;
    $this->name = $name;
    $this->active = $active;
  }

  public function getId(): int
  {
    return $this->id;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function isActive(): bool
  {
    return $this->active;
  }
}
