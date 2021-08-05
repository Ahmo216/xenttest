<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\Exception;

use RuntimeException as SplRuntimeException;

class EmailArchiveCannotSaveException extends SplRuntimeException implements SystemMailExceptionInterface
{
}
