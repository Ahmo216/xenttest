<?php

declare(strict_types=1);

namespace App\Modules\ShopimporterAmazon\SettlementV2Actions;

use App\Modules\Accounting\Models\Invoice;
use App\Modules\Accounting\Models\Order;
use App\Modules\ShopimporterAmazon\Models\V2SettlementItem;
use Illuminate\Support\Collection;

class FilterV2CollectionWithInvoiceAction
{
    public function __invoke(Collection $collection, Collection $invoices): Collection
    {
        $orderIds = $invoices->map(
            function (Invoice $item) {
                return $item->order instanceof Order ? $item->order->internet : null;
            }
        )->unique()->filter()->toArray();

        return $collection->filter(
            function (V2SettlementItem $item) use ($orderIds) {
                return in_array($item->getOrderId(), $orderIds, true);
            }
        );
    }
}
