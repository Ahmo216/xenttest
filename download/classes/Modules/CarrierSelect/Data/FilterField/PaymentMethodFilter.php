<?php

declare(strict_types=1);

namespace Xentral\Modules\CarrierSelect\Data\FilterField;


use Xentral\Modules\CarrierSelect\Data\Order;

final class PaymentMethodFilter implements FilterFieldInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'payment_method';
    }

    /**
     * @param Order $order
     *
     * @return array
     */
    public function getValuesFromOrder(Order $order): array
    {
        return [$order->getPaymentMethod()];
    }
}
