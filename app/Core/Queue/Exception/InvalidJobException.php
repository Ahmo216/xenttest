<?php

declare(strict_types=1);

namespace App\Core\Queue\Exception;

use RuntimeException as SplRuntimeException;
use Xentral\Core\Exception\CoreExceptionInterface;

class InvalidJobException extends SplRuntimeException implements CoreExceptionInterface
{
}
