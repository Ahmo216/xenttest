<?php

declare(strict_types=1);

namespace App\Modules\ShopimporterAmazon\SettlementV2Actions;

use App\Modules\ShopimporterAmazon\Actions\GetInvoicesByAmazonOrderIdsAction;
use Illuminate\Support\Collection;

class GetInvoicesFromSettlementCollectionAction
{
    /** @var GetOrderIdsBySettlementCollectionAction $getOrderIdsBySettlementCollectionAction */
    private $getOrderIdsBySettlementCollectionAction;

    /** @var GetInvoicesByAmazonOrderIdsAction $getInvoicesByAmazonOrderIdsAction */
    private $getInvoicesByAmazonOrderIdsAction;

    public function __construct(
        GetOrderIdsBySettlementCollectionAction $getOrderIdsBySettlementCollectionAction,
        GetInvoicesByAmazonOrderIdsAction $getInvoicesByAmazonOrderIdsAction
    ) {
        $this->getOrderIdsBySettlementCollectionAction = $getOrderIdsBySettlementCollectionAction;
        $this->getInvoicesByAmazonOrderIdsAction = $getInvoicesByAmazonOrderIdsAction;
    }

    public function __invoke(Collection $collection): Collection
    {
        $orderIds = ($this->getOrderIdsBySettlementCollectionAction)($collection);

        return ($this->getInvoicesByAmazonOrderIdsAction)($orderIds);
    }
}
