<?php

declare(strict_types=1);

namespace App\Modules\Stock\Actions;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Carbon;

class GetOrderReservationQuantityOfPreferredStorageAction
{
    /** @var ConnectionInterface */
    private $connection;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(int $productId, int $storageId): float
    {
        return $this->connection
            ->table('lager_reserviert')
            ->join('auftrag', 'lager_reserviert.parameter', '=', 'auftrag.id')
            ->where('lager_reserviert.artikel', '=', $productId)
            ->where('lager_reserviert.objekt', '=', 'auftrag')
            ->where('auftrag.standardlager', '=', $storageId)
            ->where('auftrag.status', '=', 'freigegenen')
            ->where(function ($query) {
                return $query
                    ->orWhere('lager_reserviert.datum', '>', Carbon::now()->toDateString())
                    ->orWhere('lager_reserviert.datum', '=', '0000-00-00')
                    ->orWhereNull('lager_reserviert.datum');
            })
            ->sum('lager_reserviert.menge');
    }
}
