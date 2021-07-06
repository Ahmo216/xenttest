<?php

namespace Xentral\Modules\SepaHbciFints\Exception;

use InvalidArgumentException as SplInvalidArgumentException;

class InvalidArgumentException extends SplInvalidArgumentException implements SepaHbciFintsExceptionInterface
{
}
