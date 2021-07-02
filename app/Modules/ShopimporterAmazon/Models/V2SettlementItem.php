<?php

declare(strict_types=1);

namespace App\Modules\ShopimporterAmazon\Models;

use DateTimeInterface;

class V2SettlementItem
{
    public const REFUND_TYPE = 'Refund';

    public const RETURN_TYPE = 'Return';

    public const SHIPMENT_TYPE = 'Shipment';

    public const AMOUNT_TYPE_FEE = 'ItemFees';

    public const AMOUNT_TYPE_ITEM_PRICE = 'ItemPrice';

    public const AMOUNT_TYPE_PROMOTION = 'Promotion';

    /** @var string */
    protected $settlementId;

    /** @var string */
    protected $transactionType;

    /** @var string */
    protected $orderId;

    /** @var string */
    protected $adjustmentId;

    /** @var string */
    protected $marketPlaceName;

    /** @var string */
    protected $amountType;

    /** @var string */
    protected $amountDescription;

    /** @var float */
    protected $amount;

    /** @var string */
    protected $fulfillmentId;

    /** @var DateTimeInterface */
    protected $postedDate;

    /** @var string */
    protected $orderItemCode;

    /** @var string */
    protected $sku;

    /** @var int|null */
    protected $quantityPurchased;

    /** @var string */
    protected $currency;

    /**
     * @return string
     */
    public function getSettlementId(): string
    {
        return $this->settlementId;
    }

    /**
     * @param string $settlementId
     *
     * @return V2SettlementItem
     */
    public function setSettlementId(string $settlementId): V2SettlementItem
    {
        $this->settlementId = $settlementId;

        return $this;
    }

    /**
     * @return string
     */
    public function getTransactionType(): string
    {
        return $this->transactionType;
    }

    /**
     * @param string $transactionType
     *
     * @return V2SettlementItem
     */
    public function setTransactionType(string $transactionType): V2SettlementItem
    {
        $this->transactionType = $transactionType;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * @param string $orderId
     *
     * @return V2SettlementItem
     */
    public function setOrderId(string $orderId): V2SettlementItem
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * @return string
     */
    public function getAdjustmentId(): string
    {
        return $this->adjustmentId;
    }

    /**
     * @param string $adjustmentId
     *
     * @return V2SettlementItem
     */
    public function setAdjustmentId(string $adjustmentId): V2SettlementItem
    {
        $this->adjustmentId = $adjustmentId;

        return $this;
    }

    /**
     * @return string
     */
    public function getMarketPlaceName(): string
    {
        return $this->marketPlaceName;
    }

    /**
     * @param string $marketPlaceName
     *
     * @return V2SettlementItem
     */
    public function setMarketPlaceName(string $marketPlaceName): V2SettlementItem
    {
        $this->marketPlaceName = $marketPlaceName;

        return $this;
    }

    /**
     * @return string
     */
    public function getAmountType(): string
    {
        return $this->amountType;
    }

    /**
     * @param string $amountType
     *
     * @return V2SettlementItem
     */
    public function setAmountType(string $amountType): V2SettlementItem
    {
        $this->amountType = $amountType;

        return $this;
    }

    /**
     * @return string
     */
    public function getAmountDescription(): string
    {
        return $this->amountDescription;
    }

    /**
     * @param string $amountDescription
     *
     * @return V2SettlementItem
     */
    public function setAmountDescription(string $amountDescription): V2SettlementItem
    {
        $this->amountDescription = $amountDescription;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     *
     * @return V2SettlementItem
     */
    public function setAmount(float $amount): V2SettlementItem
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return string
     */
    public function getFulfillmentId(): string
    {
        return $this->fulfillmentId;
    }

    /**
     * @param string $fulfillmentId
     *
     * @return V2SettlementItem
     */
    public function setFulfillmentId(string $fulfillmentId): V2SettlementItem
    {
        $this->fulfillmentId = $fulfillmentId;

        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getPostedDate(): DateTimeInterface
    {
        return $this->postedDate;
    }

    /**
     * @param DateTimeInterface $postedDate
     *
     * @return V2SettlementItem
     */
    public function setPostedDate(DateTimeInterface $postedDate): V2SettlementItem
    {
        $this->postedDate = $postedDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrderItemCode(): string
    {
        return $this->orderItemCode;
    }

    /**
     * @param string $orderItemCode
     *
     * @return V2SettlementItem
     */
    public function setOrderItemCode(string $orderItemCode): V2SettlementItem
    {
        $this->orderItemCode = $orderItemCode;

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
     *
     * @return V2SettlementItem
     */
    public function setSku(string $sku): V2SettlementItem
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getQuantityPurchased(): ?int
    {
        return $this->quantityPurchased;
    }

    /**
     * @param int|null $quantityPurchased
     *
     * @return V2SettlementItem
     */
    public function setQuantityPurchased(?int $quantityPurchased): V2SettlementItem
    {
        $this->quantityPurchased = $quantityPurchased;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     *
     * @return V2SettlementItem
     */
    public function setCurrency(string $currency): V2SettlementItem
    {
        $this->currency = $currency;

        return $this;
    }
}
