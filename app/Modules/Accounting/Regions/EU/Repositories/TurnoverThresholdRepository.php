<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Regions\EU\Repositories;

use App\Modules\Accounting\Exceptions\DefinitionNotFoundException;
use App\Modules\Accounting\Regions\EU\Models\TurnoverThreshold;
use Illuminate\Database\Eloquent\Collection;

class TurnoverThresholdRepository implements TurnoverThresholdRepositoryInterface
{
    public function getById(int $id): TurnoverThreshold
    {
        $turnoverThreshold = TurnoverThreshold::query()->find($id);
        if (!$turnoverThreshold instanceof TurnoverThreshold) {
            throw new DefinitionNotFoundException("Definition for id {$id} not found");
        }

        return $turnoverThreshold;
    }

    public function getByCountries(array $countries): Collection
    {
        return TurnoverThreshold::query()->whereIn('empfaengerland', $countries)->get();
    }

    public function list(): Collection
    {
        return TurnoverThreshold::query()->get();
    }

    public function delete(TurnoverThreshold $threshold): void
    {
        $threshold->delete();
    }
}
