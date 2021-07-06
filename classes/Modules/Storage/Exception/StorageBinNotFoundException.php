<?php

namespace Xentral\Modules\Storage\Exception;

use RuntimeException as SplRuntimeException;

class StorageBinNotFoundException extends SplRuntimeException implements StorageExceptionInterface
{

}
