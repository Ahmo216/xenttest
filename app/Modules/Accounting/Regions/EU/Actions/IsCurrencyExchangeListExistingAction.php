<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Regions\EU\Actions;

use Illuminate\Database\ConnectionInterface;

class IsCurrencyExchangeListExistingAction
{
    /** @var ConnectionInterface */
    private $connection;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(): bool
    {
        return $this->connection->table('waehrung_umrechnung')->count() > 0;
    }
}
