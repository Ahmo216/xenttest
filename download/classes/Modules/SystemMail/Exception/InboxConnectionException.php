<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\Exception;

use RuntimeException as SplRuntimeException;

class InboxConnectionException extends SplRuntimeException implements SystemMailExceptionInterface
{
}
