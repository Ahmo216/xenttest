<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Regions\EU\Actions;

use App\Modules\Accounting\Regions\EU\Repositories\TurnoverThresholdProductRepositoryInterface;
use App\Modules\Accounting\Regions\EU\Repositories\TurnoverThresholdRepositoryInterface;

class GetProductDefinitionsWithoutActiveThresholdAction
{
    /** @var TurnoverThresholdRepositoryInterface $turnoverThresholdRepository */
    private $turnoverThresholdRepository;

    /** @var TurnoverThresholdProductRepositoryInterface $turnoverThresholdProductRepository */
    private $turnoverThresholdProductRepository;

    public function __construct(
        TurnoverThresholdRepositoryInterface $turnoverThresholdRepository,
        TurnoverThresholdProductRepositoryInterface $turnoverThresholdProductRepository
    ) {
        $this->turnoverThresholdRepository = $turnoverThresholdRepository;
        $this->turnoverThresholdProductRepository = $turnoverThresholdProductRepository;
    }

    public function __invoke(): array
    {
        $countries = $this->turnoverThresholdProductRepository
            ->getActive()
            ->map(function ($item) {return $item->getAttribute('empfaengerland');})
            ->unique()
            ->filter()
            ->toArray();
        if (empty($countries)) {
            return [];
        }

        $turnovers = $this->turnoverThresholdRepository->getByCountries($countries)
            ->map(function ($item) {return $item->getAttribute('empfaengerland');})
            ->unique()
            ->filter()
            ->toArray();

        return array_diff($countries, $turnovers);
    }
}
