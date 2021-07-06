<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Regions\EU\Repositories;

use App\Modules\Accounting\Regions\EU\Models\TurnoverThresholdProduct;
use Illuminate\Database\Eloquent\Collection;

interface TurnoverThresholdProductRepositoryInterface
{
    public function getById(int $id): TurnoverThresholdProduct;

    public function getActive(): Collection;
}
