<?php

namespace Xentral\Modules\Storage\Exception;

use RuntimeException as SplRuntimeException;

class StorageBinNameNotUniqueException extends SplRuntimeException implements StorageExceptionInterface
{

}
