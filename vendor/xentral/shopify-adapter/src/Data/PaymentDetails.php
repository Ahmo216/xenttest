<?php

namespace XentralAdapters\Shopify\Data;

class PaymentDetails
{
  /** @var string|null */
  private $avsResultCode;
  /** @var string|null */
  private $creditCardBin;
  /** @var string|null */
  private $creditCardCompany;
  /** @var string|null */
  private $creditCardNumber;
  /** @var string|null */
  private $cvvResultCode;

  public function __construct(
    ?string $avsResultCode = null,
    ?string $creditCardBin = null,
    ?string $creditCardCompany = null,
    ?string $creditCardNumber = null,
    ?string $cvvResultCode = null
  ) {
    $this->avsResultCode = $avsResultCode;
    $this->creditCardBin = $creditCardBin;
    $this->creditCardCompany = $creditCardCompany;
    $this->creditCardNumber = $creditCardNumber;
    $this->cvvResultCode = $cvvResultCode;
  }

  public function getAvsResultCode(): ?string
  {
    return $this->avsResultCode;
  }

  public function getCreditCardBin(): ?string
  {
    return $this->creditCardBin;
  }

  public function getCreditCardCompany(): ?string
  {
    return $this->creditCardCompany;
  }

  public function getCreditCardNumber(): ?string
  {
    return $this->creditCardNumber;
  }

  public function getCvvResultCode(): ?string
  {
    return $this->cvvResultCode;
  }
}
