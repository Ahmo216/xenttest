<?php

declare(strict_types=1);

namespace App\Modules\ShopimporterAmazon\SettlementV2Actions;

use Illuminate\Support\Collection;

class GetCreditNoteSettlementItemsFromReportAction
{
    /** @var GetV2CollectionFromV2ReportAction $getV2CollectionFromV2ReportAction */
    private $getV2CollectionFromV2ReportAction;

    /** @var FilterRefundReturnItemsWithoutFeesAction $filterRefundReturnItemsWithoutFeesAction */
    private $filterRefundReturnItemsWithoutFeesAction;

    public function __construct(
        GetV2CollectionFromV2ReportAction $getV2CollectionFromV2ReportAction,
        FilterRefundReturnItemsWithoutFeesAction $filterRefundReturnItemsWithoutFeesAction
    ) {
        $this->getV2CollectionFromV2ReportAction = $getV2CollectionFromV2ReportAction;
        $this->filterRefundReturnItemsWithoutFeesAction = $filterRefundReturnItemsWithoutFeesAction;
    }

    public function __invoke(string $content): Collection
    {
        return ($this->filterRefundReturnItemsWithoutFeesAction)(($this->getV2CollectionFromV2ReportAction)($content));
    }
}
