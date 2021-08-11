<?php

declare(strict_types=1);

namespace App\Modules\ShopimporterAmazon\Actions;

use App\Modules\Accounting\Models\Order;
use App\Modules\Accounting\Models\OrderItem;

class GetOrderItemBySkuAction
{
    public function __invoke(?string $sku, ?Order $order): ?OrderItem
    {
        if (!$order instanceof Order) {
            return null;
        }

        return $order
            ->items()
            ->leftJoin(
                'artikelnummer_fremdnummern',
                function ($join) use ($order, $sku) {
                    $join->on('auftrag_position.artikel', '=', 'artikelnummer_fremdnummern.artikel')
                        ->where('artikelnummer_fremdnummern.shopid', '=', $order->getAttribute('shop'))
                        ->where('artikelnummer_fremdnummern.aktiv', 1)
                        ->where('artikelnummer_fremdnummern.nummer', $sku);
                }
            )
            ->where('auftrag_position.nummer', $sku)
            ->orWhere('artikelnummer_fremdnummern.nummer', $sku)
            ->get()->first();
    }
}
