<?php

declare(strict_types=1);

namespace Xentral\Modules\CarrierSelect\Data\FilterField;


use Xentral\Modules\CarrierSelect\Data\Order;

final class WeightFilter implements FilterFieldInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'weight';
    }

    /**
     * @param Order $order
     *
     * @return array
     */
    public function getValuesFromOrder(Order $order): array
    {
        $positions = $order->getPositions();
        $weights = [];
        foreach ($positions as $position) {
            $weights[] = $position->getQuantity() * $position->getWeight();
        }

        return $weights;
    }
}
