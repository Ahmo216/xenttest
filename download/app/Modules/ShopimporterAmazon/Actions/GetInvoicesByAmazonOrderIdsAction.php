<?php

declare(strict_types=1);

namespace App\Modules\ShopimporterAmazon\Actions;

use App\Modules\Accounting\Models\Invoice;
use App\Modules\Accounting\Models\Order;
use Illuminate\Support\Collection;

class GetInvoicesByAmazonOrderIdsAction
{
    public function __invoke(array $amazonOrderIds): Collection
    {
        $orderIds = Order::query()->whereIn('internet', $amazonOrderIds)->pluck('id');

        return Invoice::query()
            ->whereIn('auftragid', $orderIds)
            ->with('creditnotes')
            ->with('order')
            ->get();
    }
}
