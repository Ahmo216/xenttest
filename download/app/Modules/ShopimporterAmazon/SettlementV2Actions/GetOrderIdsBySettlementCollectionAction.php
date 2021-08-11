<?php

declare(strict_types=1);

namespace App\Modules\ShopimporterAmazon\SettlementV2Actions;

use App\Modules\ShopimporterAmazon\Models\V2SettlementItem;
use Illuminate\Support\Collection;

class GetOrderIdsBySettlementCollectionAction
{
    public function __invoke(Collection $collection): array
    {
        return array_values($collection->map(
            function (V2SettlementItem $item) {
                return $item->getOrderId();
            }
        )->unique()->toArray());
    }
}
