<?php

declare(strict_types=1);

namespace App\Modules\Stock\Actions;

use Illuminate\Database\ConnectionInterface;

class GetStockOfProductInSpecificStorageForShippingProcessAction
{
    /** @var ConnectionInterface */
    private $connection;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(int $productId, ?int $storageId = null): float
    {
        $query = $this->connection->table('lager_platz_inhalt')
            ->join('lager_platz', 'lager_platz_inhalt.lager_platz', '=', 'lager_platz.id')
            ->join('lager', 'lager_platz.lager', '=', 'lager.id')
            ->where('lager_platz_inhalt.artikel', $productId);
        if ($storageId !== null) {
            $query = $query->where('lager.id', '=', $storageId);
        }

        return (float)$query
            ->where('lager_platz.geloescht', '!=', 1)
            ->where('lager_platz.autolagersperre', '!=', 1)
            ->where('lager_platz.sperrlager', '!=', 1)
            ->sum('lager_platz_inhalt.menge');
    }
}
