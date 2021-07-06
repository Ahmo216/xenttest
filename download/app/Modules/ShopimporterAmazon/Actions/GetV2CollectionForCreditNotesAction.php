<?php

declare(strict_types=1);

namespace App\Modules\ShopimporterAmazon\Actions;

use App\Modules\ShopimporterAmazon\Models\V2SettlementItem;
use Illuminate\Support\Collection;

class GetV2CollectionForCreditNotesAction
{
    /**
     * @param Collection $collection
     *
     * @return Collection
     */
    public function __invoke(Collection $collection): Collection
    {
        return $collection->filter(
            function (V2SettlementItem $item) {
                return $item->getAmountType() !== V2SettlementItem::AMOUNT_TYPE_FEE
                    && in_array(
                        $item->getTransactionType(),
                        [V2SettlementItem::REFUND_TYPE, V2SettlementItem::RETURN_TYPE],
                        true
                    );
            }
        )->groupBy(
            function (V2SettlementItem $item) {
                return $item->getAdjustmentId();
            }
        )->map(
            function ($item) {
                return $item->groupBy(
                    function (V2SettlementItem $item) {
                        return $item->getOrderItemCode();
                    }
                );
            }
        );
    }
}
