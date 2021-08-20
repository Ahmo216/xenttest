<?php

declare(strict_types=1);

namespace App\Modules\Stock\Actions;

use App\Modules\Accounting\Models\Order;
use App\Modules\Product\Models\Product;

class GetNonDeliveredQuantityOfProductInSpecificOrderAction
{
    public function __invoke(Product $product, Order $order): float
    {
        return (float)$order
            ->items()
            ->get()
            ->where('artikel', $product->id)
            ->sum(function ($position) {return max(0, $position->menge - $position->menge_geliefert); });
    }
}
