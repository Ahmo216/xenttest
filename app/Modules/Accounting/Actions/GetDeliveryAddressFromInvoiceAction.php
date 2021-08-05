<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Actions;

use App\Modules\Accounting\DTO\Address;
use App\Modules\Accounting\Models\Invoice;
use App\Modules\Accounting\Models\Order;

class GetDeliveryAddressFromInvoiceAction
{
    /** @var GetDeliveryAddressFromOrderAction $getDeliveryAddressFromOrderAction */
    private $getDeliveryAddressFromOrderAction;

    public function __construct(GetDeliveryAddressFromOrderAction $getDeliveryAddressFromOrderAction)
    {
        $this->getDeliveryAddressFromOrderAction = $getDeliveryAddressFromOrderAction;
    }

    public function __invoke(Invoice $invoice): Address
    {
        $order = $invoice->order;
        if ($order instanceof Order) {
            return ($this->getDeliveryAddressFromOrderAction)($order);
        }

        return $invoice->invoiceAddress;
    }
}
