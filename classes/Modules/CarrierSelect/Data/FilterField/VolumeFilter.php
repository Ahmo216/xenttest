<?php

declare(strict_types=1);

namespace Xentral\Modules\CarrierSelect\Data\FilterField;


use Xentral\Modules\CarrierSelect\Data\Order;

final class VolumeFilter implements FilterFieldInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'volume';
    }

    /**
     * @param Order $order
     *
     * @return array
     */
    public function getValuesFromOrder(Order $order): array
    {
        $positions = $order->getPositions();
        $volumes = [];
        foreach($positions as $position) {
            $volumes[] = $position->getQuantity() * $position->getVolume();
        }

        return $volumes;
    }
}
