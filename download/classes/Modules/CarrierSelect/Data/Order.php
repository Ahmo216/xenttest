<?php

declare(strict_types=1);

namespace Xentral\Modules\CarrierSelect\Data;

class Order
{
    /** @var array $positions */
    private $positions = [];

    /** @var float $shopId */
    private $shopId = 0;

    /** @var string $shippingMethod */
    private $shippingMethod = '';

    /** @var string $paymentMethod */
    private $paymentMethod = '';

    /** @var float $orderAmount */
    private $orderAmount = 0;

    /** @var int $projectId */
    private $projectId = 0;

    /** @var bool $fastlane */
    private $fastlane = false;

    /** @var string $countryCode */
    private $countryCode = 'DE';

    /** @var string $shippingCountryCode */
    private $shippingCountryCode = 'DE';

    /** @var bool $differentShippingAdress */
    private $differentShippingAdress = false;

    /**
     * Order constructor.
     *
     * @param array           $orderData
     * @param OrderPosition[] $positions
     */
    public function __construct(array $orderData, array $positions)
    {
        if (isset($orderData['gesamtsumme'])) {
            $this->setOrderAmount((float)$orderData['gesamtsumme']);
        }
        $this->setPaymentMethod((string)$orderData['zahlungsweise']);
        $this->setProjectId((int)$orderData['projekt']);
        $this->setShopId((int)$orderData['shop']);
        $this->setShippingMethod((string)$orderData['versandart']);
        $this->setCountryCode((string)$orderData['land']);
        $this->setShippingCountryCode((string)$orderData['lieferland']);
        $this->setDifferentShippingAdress((bool)$orderData['abweichendelieferadresse']);
        $this->setFastlane((bool)$orderData['fastlane']);

        foreach ($positions as $position) {
            $this->addPosition($position);
        }
    }

    /**
     * @param OrderPosition $position
     */
    public function addPosition(OrderPosition $position): void
    {
        $this->positions[] = $position;
    }

    /**
     * @return array
     */
    public function getOrderdata(): array
    {
        return [
            'projekt'                  => $this->getProjectId(),
            'shop'                     => $this->getShopId(),
            'gesamtsumme'              => $this->getOrderAmount(),
            'versandart'               => $this->getShippingMethod(),
            'zahlungsweise'            => $this->getPaymentMethod(),
            'fastlane'                 => (int)$this->isFastlane(),
            'land'                     => $this->getCountryCode(),
            'abweichendelieferadresse' => $this->isDifferentShippingAdress(),
            'lieferland'               => $this->getShippingCountryCode(),
            'positions'                => $this->getPositions(),
        ];
    }

    /**
     * @return OrderPosition[]
     */
    public function getPositions(): array
    {
        $positions = [];

        /** @var OrderPosition $position */
        foreach ($this->positions as $position) {
            $positions[] = new OrderPosition($position->toArray());
        }

        return $positions;
    }

    /**
     * @return int
     */
    public function getShopId(): int
    {
        return $this->shopId;
    }

    /**
     * @param int $shopId
     */
    public function setShopId(int $shopId): void
    {
        $this->shopId = $shopId;
    }

    /**
     * @return string
     */
    public function getShippingMethod(): string
    {
        return $this->shippingMethod;
    }

    /**
     * @param string $shippingMethod
     */
    public function setShippingMethod(string $shippingMethod): void
    {
        $this->shippingMethod = $shippingMethod;
    }

    /**
     * @return string
     */
    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    /**
     * @param string $paymentMethod
     */
    public function setPaymentMethod(string $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * @return float
     */
    public function getOrderAmount(): float
    {
        return $this->orderAmount;
    }

    /**
     * @param float $orderAmount
     */
    public function setOrderAmount(float $orderAmount): void
    {
        $this->orderAmount = $orderAmount;
    }

    /**
     * @return int
     */
    public function getProjectId(): int
    {
        return $this->projectId;
    }

    /**
     * @param int $projectId
     */
    public function setProjectId(int $projectId): void
    {
        $this->projectId = $projectId;
    }

    /**
     * @return bool
     */
    public function isFastlane(): bool
    {
        return $this->fastlane;
    }

    /**
     * @param bool $fastlane
     */
    public function setFastlane(bool $fastlane): void
    {
        $this->fastlane = $fastlane;
    }

    /**
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     */
    public function setCountryCode(string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    /**
     * @return string
     */
    public function getShippingCountryCode(): string
    {
        return $this->shippingCountryCode;
    }

    /**
     * @param string $shippingCountryCode
     */
    public function setShippingCountryCode(string $shippingCountryCode): void
    {
        $this->shippingCountryCode = $shippingCountryCode;
    }

    /**
     * @return bool
     */
    public function isDifferentShippingAdress(): bool
    {
        return $this->differentShippingAdress;
    }

    /**
     * @param bool $differentShippingAdress
     */
    public function setDifferentShippingAdress(bool $differentShippingAdress): void
    {
        $this->differentShippingAdress = $differentShippingAdress;
    }
}
