<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\Exception;

use RuntimeException as SplRuntimeException;

class InvalidArgumentException extends SplRuntimeException implements SystemMailExceptionInterface
{
}
