<?php

declare(strict_types=1);

namespace Xentral\Modules\SubscriptionCycle\Exception;

use RuntimeException as SplRuntimeException;

final class RuntimeException extends SplRuntimeException implements SubscriptionCycleExceptionInterface
{
}
