<?php

namespace XentralAdapters\Shopify\Data;

class Customer
{
  /** @var string */
  private $firstName;
  /** @var string */
  private $lastName;
  /** @var string */
  private $email;

  public function __construct(string $firstName, string $lastName, string $email)
  {
    $this->firstName = $firstName;
    $this->lastName = $lastName;
    $this->email = $email;
  }

  public function getFirstName(): string
  {
    return $this->firstName;
  }

  public function getLastName(): string
  {
    return $this->lastName;
  }

  public function getFullName(): string
  {
    return trim($this->firstName) . ' ' . trim($this->lastName);
  }

  public function getEmail(): string
  {
    return $this->email;
  }
}