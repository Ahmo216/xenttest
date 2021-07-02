<?php

declare(strict_types=1);

namespace Xentral\Modules\FileManagement\Exception;

use \RuntimeException as SplRuntimeException;

class FileManagementWriteException extends SplRuntimeException implements FileManagementExceptionInterface
{

}
