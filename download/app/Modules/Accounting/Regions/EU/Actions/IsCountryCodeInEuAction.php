<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Regions\EU\Actions;

use App\Modules\Accounting\Exceptions\DefinitionNotFoundException;
use Illuminate\Database\ConnectionInterface;

class IsCountryCodeInEuAction
{
    /** @var ConnectionInterface $connection */
    private $connection;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(string $countryCode): ?bool
    {
        $country = $this->connection->table('laender')->where('iso', '=', $countryCode)->get(['eu'])->first();
        if ($country === null) {
            throw new DefinitionNotFoundException("Country {$countryCode} not found");
        }

        return (bool)$country->eu;
    }
}
