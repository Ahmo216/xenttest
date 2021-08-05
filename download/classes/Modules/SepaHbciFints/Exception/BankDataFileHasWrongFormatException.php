<?php

namespace Xentral\Modules\SepaHbciFints\Exception;

use RuntimeException;

class BankDataFileHasWrongFormatException extends RuntimeException implements SepaHbciFintsExceptionInterface
{
}
