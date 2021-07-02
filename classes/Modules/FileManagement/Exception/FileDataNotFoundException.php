<?php

declare(strict_types=1);

namespace Xentral\Modules\FileManagement\Exception;

use \RuntimeException as SplRuntimeException;

class FileDataNotFoundException extends SplRuntimeException implements FileManagementExceptionInterface
{

}
