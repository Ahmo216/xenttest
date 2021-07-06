<?php

declare(strict_types=1);

namespace App\Modules\ShopimporterAmazon\Actions;

use App\Modules\Accounting\Models\Invoice;
use App\Modules\Accounting\Models\Order;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Collection;

class GetInvoicesByAmazonOrderIdsAction
{
    /** @var ConnectionInterface $connection */
    private $connection;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(array $amazonOrderIds): Collection
    {
        $orderIds = Order::query()->whereIn('internet', $amazonOrderIds)->pluck('id');

        return Invoice::query()
            ->whereIn('auftragid', $orderIds)
            ->with('creditnotes')
            ->get();
    }
}
