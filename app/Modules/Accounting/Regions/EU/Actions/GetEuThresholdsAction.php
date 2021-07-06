<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Regions\EU\Actions;

use App\Modules\Accounting\Regions\EU\Repositories\TurnoverThresholdRepositoryInterface;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Eloquent\Collection;

class GetEuThresholdsAction
{
    /** @var TurnoverThresholdRepositoryInterface $turnoverThresholdRepository */
    private $turnoverThresholdRepository;

    /** @var ConnectionInterface $database */
    private $database;

    public function __construct(
        TurnoverThresholdRepositoryInterface $turnoverThresholdRepository,
        ConnectionInterface $database
    ) {
        $this->turnoverThresholdRepository = $turnoverThresholdRepository;
        $this->database = $database;
    }

    public function __invoke(): Collection
    {
        $euCountries = $this->database->table('laender')->where('eu', '=', 1)->pluck('iso')->toArray();

        return $this->turnoverThresholdRepository->getByCountries(
            array_diff(
                $euCountries,
                [
                    $this->database->table('firmendaten_werte')
                        ->where('name', 'land')
                        ->value('wert'),
                ]
            )
        );
    }
}
