<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Regions\EU\Repositories;

use App\Modules\Accounting\Regions\EU\Models\TurnoverThreshold;
use Illuminate\Database\Eloquent\Collection;

interface TurnoverThresholdRepositoryInterface
{
    public function getById(int $id): TurnoverThreshold;

    public function getByCountries(array $countries): Collection;

    public function list(): Collection;

    public function delete(TurnoverThreshold $threshold): void;
}
