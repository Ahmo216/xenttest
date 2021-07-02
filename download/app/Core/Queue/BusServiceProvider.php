<?php

declare(strict_types=1);

namespace App\Core\Queue;

use App\Core\Queue\Dispatcher\QueueDispatcher;
use App\Core\Queue\Dispatcher\QueueDispatcherInterface;
use Illuminate\Bus\BusServiceProvider as BaseServiceProvider;
use Illuminate\Contracts\Bus\Dispatcher as DispatcherContract;
use Illuminate\Contracts\Queue\Factory as QueueFactoryContract;

class BusServiceProvider extends BaseServiceProvider
{
    /**
     * Replaces the original Dispatcher binding with our own implementation.
     */
    public function register()
    {
        parent::register();
        $this->app->singleton(DispatcherContract::class, function ($app) {
            return new QueueDispatcher($app, function ($connection = null) use ($app) {
                return $app[QueueFactoryContract::class]->connection($connection);
            });
        });
        $this->app->alias(DispatcherContract::class, QueueDispatcherInterface::class);
    }
}
