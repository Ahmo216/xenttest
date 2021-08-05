<?php

namespace XentralAdapters\Shopify\Data;

use DateTime;

class CsvTransaction
{
    /** @var DateTime */
    private $transactionDate;
    /** @var string */
    private $type;
    /** @var string */
    private $orderId;
    /** @var string */
    private $payoutStatus;
    /** @var DateTime */
    private $payoutDate;
    /** @var DateTime */
    private $availableOn;
    /** @var string */
    private $amount;
    /** @var string */
    private $fee;
    /** @var string */
    private $net;
    /** @var string */
    private $checkoutId;
    /** @var string */
    private $cardSource;
    /** @var string|null */
    private $cardBrand;
    /** @var string */
    private $currency;

    public function __construct(
        DateTime $transactionDate,
        string $type,
        string $orderId,
        string $payoutStatus,
        DateTime $payoutDate,
        DateTime $availableOn,
        string $amount,
        string $fee,
        string $net,
        string $checkoutId,
        string $cardSource,
        ?string $cardBrand = null,
        string $currency = 'EUR'
    )
    {
        $this->transactionDate = $transactionDate;
        $this->type = $type;
        $this->orderId = $orderId;
        $this->payoutStatus = $payoutStatus;
        $this->payoutDate = $payoutDate;
        $this->availableOn = $availableOn;
        $this->amount = $amount;
        $this->fee = $fee;
        $this->net = $net;
        $this->checkoutId = $checkoutId;
        $this->cardSource = $cardSource;
        $this->cardBrand = $cardBrand;
        $this->currency = $currency;
    }

    public function getTransactionDate(): DateTime
    {
        return $this->transactionDate;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function getPayoutStatus(): string
    {
        return $this->payoutStatus;
    }

    public function getPayoutDate(): DateTime
    {
        return $this->payoutDate;
    }

    public function getAvailableOn(): DateTime
    {
        return $this->availableOn;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getFee(): string
    {
        return $this->fee;
    }

    public function getNet(): string
    {
        return $this->net;
    }

    public function getCheckoutId(): string
    {
        return $this->checkoutId;
    }

    public function getCardSource(): string
    {
        return $this->cardSource;
    }

    public function getCardBrand(): ?string
    {
        return $this->cardBrand;
    }

    public function getCurrency(): string
    {
        return $this->currency ?: 'EUR';
    }
}
