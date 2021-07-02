<?php

declare(strict_types=1);

namespace App\Modules\ShopimporterAmazon\Actions;

use App\Modules\Accounting\Models\OrderItem;
use Illuminate\Database\Eloquent\Collection;

class GetOrderItemsByWebIdsAction
{
    public function __invoke(array $webIds): Collection
    {
        return OrderItem::whereIn('webid', $webIds)->get();
    }
}
