<?php

declare(strict_types=1);

namespace App\Modules\Stock\Actions;

use Illuminate\Database\ConnectionInterface;

class GetStockOfProductForShippingProcessAction
{
    /** @var ConnectionInterface */
    private $connection;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(int $productId): float
    {
        return (float)$this->connection->table('lager_platz_inhalt')
            ->join('lager_platz', 'lager_platz_inhalt.lager_platz', '=', 'lager_platz.id')
            ->where('lager_platz_inhalt.artikel', $productId)
            ->where('lager_platz.geloescht', '!=', 1)
            ->where('lager_platz.autolagersperre', '!=', 1)
            ->where('lager_platz.sperrlager', '!=', 1)
            ->sum('lager_platz_inhalt.menge');
    }
}
