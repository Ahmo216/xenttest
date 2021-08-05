<?php

namespace XentralAdapters\Shopify\Data;

class Country
{
  /** @var int */
  private $id;
  /** @var string */
  private $code;
  /** @var string */
  private $name;
  /** @var float */
  private $taxRate;
  /** @var array */
  private $provinces;

  public function __construct(int $id, string $code, string $name, float $taxRate, array $provinces)
  {
    $this->id = $id;
    $this->code = $code;
    $this->name = $name;
    $this->taxRate = $taxRate;
    $this->provinces = $provinces;
  }

  public function getId(): int
  {
    return $this->id;
  }

  public function getCode(): string
  {
    return $this->code;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function getTaxRate(): float
  {
    return $this->taxRate;
  }

  public function getProvinces(): array
  {
    return $this->provinces;
  }
}
