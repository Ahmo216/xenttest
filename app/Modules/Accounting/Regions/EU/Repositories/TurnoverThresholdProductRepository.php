<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Regions\EU\Repositories;

use App\Modules\Accounting\Exceptions\DefinitionNotFoundException;
use App\Modules\Accounting\Regions\EU\Models\TurnoverThresholdProduct;
use Illuminate\Database\Eloquent\Collection;

class TurnoverThresholdProductRepository implements TurnoverThresholdProductRepositoryInterface
{
    public function getById(int $id): TurnoverThresholdProduct
    {
        $turnoverThresholdProduct = TurnoverThresholdProduct::query()->find($id);
        if (!$turnoverThresholdProduct instanceof TurnoverThresholdProduct) {
            throw new DefinitionNotFoundException("ProductDefinition for id {$id} not found");
        }

        return $turnoverThresholdProduct;
    }

    public function getActive(): Collection
    {
        return TurnoverThresholdProduct::query()->where('aktiv', 1)->get();
    }
}
