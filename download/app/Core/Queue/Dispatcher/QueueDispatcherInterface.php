<?php

declare(strict_types=1);

namespace App\Core\Queue\Dispatcher;

use Illuminate\Contracts\Bus\Dispatcher;

/**
 * This interface should be used as type hint for `QueueDispatcher` dependencies.
 */
interface QueueDispatcherInterface extends Dispatcher
{
}
