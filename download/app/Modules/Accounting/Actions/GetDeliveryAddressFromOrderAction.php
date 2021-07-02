<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Actions;

use App\Modules\Accounting\DTO\Address;
use App\Modules\Accounting\Models\DeliveryNote;
use App\Modules\Accounting\Models\Order;

class GetDeliveryAddressFromOrderAction
{
    public function __invoke(Order $order): Address
    {
        $deliveryNote = $this->getFirstNotCancelledDeliveryNote($order);

        if ($deliveryNote instanceof DeliveryNote) {
            return $deliveryNote->deliveryAddress;
        }

        if ($order->hasDifferentDeliveryAddress()) {
            return $order->deliveryAddress;
        }

        return $order->invoiceAddress;
    }

    private function getFirstNotCancelledDeliveryNote(Order $order): ?DeliveryNote
    {
        return $order->deliveryNotes()
            ->where('status', '!=', 'storniert')
            ->first();
    }
}
