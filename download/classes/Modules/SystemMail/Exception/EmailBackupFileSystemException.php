<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\Exception;

use RuntimeException as SplRuntimeException;

class EmailBackupFileSystemException extends SplRuntimeException implements SystemMailExceptionInterface
{
}
