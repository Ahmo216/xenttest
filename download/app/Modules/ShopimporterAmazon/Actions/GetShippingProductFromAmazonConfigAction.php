<?php

declare(strict_types=1);

namespace App\Modules\ShopimporterAmazon\Actions;

use App\Modules\Product\Models\Product;
use Illuminate\Database\ConnectionInterface;

class GetShippingProductFromAmazonConfigAction
{
    /** @var ConnectionInterface $connection */
    private $connection;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(int $shopId): ?Product
    {
        $shop = $this->connection->table('shopexport')->find($shopId);
        $articleId = (int)$shop->artikelporto;
        if ($articleId <= 0) {
            return null;
        }

        return Product::query()->find($articleId);
    }
}
