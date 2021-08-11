<?php

namespace Xentral\Modules\Storage\Exception;

use RuntimeException as SplRuntimeException;

class StockEntryNotFoundException extends SplRuntimeException implements StorageExceptionInterface
{

}
