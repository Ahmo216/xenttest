<?php

declare(strict_types=1);

namespace Xentral\Modules\CarrierSelect\Data\FilterField;


use Xentral\Modules\CarrierSelect\Data\Order;

final class ShippingCountryFilter implements FilterFieldInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'shipping_country';
    }

    /**
     * @param Order $order
     *
     * @return array
     */
    public function getValuesFromOrder(Order $order): array
    {
        if($order->isDifferentShippingAdress()) {
            return [$order->getShippingCountryCode()];
        }

        return [$order->getCountryCode()];
    }
}
