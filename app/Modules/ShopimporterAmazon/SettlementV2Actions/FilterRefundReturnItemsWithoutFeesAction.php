<?php

declare(strict_types=1);

namespace App\Modules\ShopimporterAmazon\SettlementV2Actions;

use App\Modules\ShopimporterAmazon\Models\V2SettlementItem;
use Illuminate\Support\Collection;

class FilterRefundReturnItemsWithoutFeesAction
{
    public function __invoke(Collection $settlementItems): Collection
    {
        return $settlementItems->filter(function (V2SettlementItem $item) {
            return $item->getAmountType() !== V2SettlementItem::AMOUNT_TYPE_FEE && in_array(
                $item->getTransactionType(),
                [V2SettlementItem::REFUND_TYPE, V2SettlementItem::RETURN_TYPE],
                true
            );
        });
    }
}
