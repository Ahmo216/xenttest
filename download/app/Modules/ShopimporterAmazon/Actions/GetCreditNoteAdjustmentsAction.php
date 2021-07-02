<?php

declare(strict_types=1);

namespace App\Modules\ShopimporterAmazon\Actions;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Collection;

class GetCreditNoteAdjustmentsAction
{
    /** @var ConnectionInterface */
    private $connection;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(array $adjustmentIds): Collection
    {
        return $this->connection
            ->table('shopimporter_amazon_creditnotes_adjustmentid')
            ->whereIn('adjustmentid', $adjustmentIds)
            ->get();
    }
}
