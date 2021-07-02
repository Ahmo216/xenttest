<?php

namespace Xentral\Modules\Storage\Exception;

use RuntimeException as SplRuntimeException;

class StorageNotFoundException extends SplRuntimeException implements StorageExceptionInterface
{

}
