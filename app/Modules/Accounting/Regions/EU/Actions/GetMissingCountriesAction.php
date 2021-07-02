<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Regions\EU\Actions;

use Illuminate\Database\ConnectionInterface;

class GetMissingCountriesAction
{
    /** @var ConnectionInterface $database */
    private $database;

    public function __construct(ConnectionInterface $database)
    {
        $this->database = $database;
    }

    public function __invoke(string $excludeCountryCode = ''): array
    {
        return $this->database->table('delivery_threshold_missing_oss_definition AS d')
            ->join('rechnung as r', 'd.invoice_id', '=', 'r.id')
            ->leftJoin('lieferschwelle as l', function ($join) {
                $join->on('d.country_code', '=', 'l.empfaengerland')
                    ->where('l.use_storage', '0');
            })
            ->whereNull('l.id')
            ->whereIn('r.status', ['freigegeben', 'versendet'])
            ->where('d.country_code', '!=', $excludeCountryCode)
            ->where('d.country_code', '!=', '')
            ->get(['d.country_code', 'd.net_sum'])
            ->mapToGroups(function ($item, $key) {
                return [$item->country_code => $item->net_sum];
            })->map(function ($item, $key) {
                return $item->sum();
            })->toArray();
    }
}
