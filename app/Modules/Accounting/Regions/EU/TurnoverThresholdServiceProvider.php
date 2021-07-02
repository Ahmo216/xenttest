<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Regions\EU;

use App\Modules\Accounting\Regions\EU\Repositories\TurnoverThresholdProductRepository;
use App\Modules\Accounting\Regions\EU\Repositories\TurnoverThresholdProductRepositoryInterface;
use App\Modules\Accounting\Regions\EU\Repositories\TurnoverThresholdRepository;
use App\Modules\Accounting\Regions\EU\Repositories\TurnoverThresholdRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class TurnoverThresholdServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TurnoverThresholdProductRepositoryInterface::class, TurnoverThresholdProductRepository::class);
        $this->app->bind(TurnoverThresholdRepositoryInterface::class, TurnoverThresholdRepository::class);
    }
}
