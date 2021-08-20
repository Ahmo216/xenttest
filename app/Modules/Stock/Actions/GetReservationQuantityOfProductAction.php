<?php

declare(strict_types=1);

namespace App\Modules\Stock\Actions;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Carbon;

class GetReservationQuantityOfProductAction
{
    /** @var ConnectionInterface */
    private $connection;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(int $productId): float
    {
        return (float)$this->connection->table('lager_reserviert')
            ->where('artikel', '=', $productId)
            ->where(function ($query) {
                return $query->orWhere('datum', '>', Carbon::now()->toDateString())->orWhere('datum', '=', '0000-00-00')->orWhereNull('datum');
            })
            ->sum('menge');
    }
}
