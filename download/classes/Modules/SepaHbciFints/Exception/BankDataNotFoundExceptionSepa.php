<?php

namespace Xentral\Modules\SepaHbciFints\Exception;

use RuntimeException;

class BankDataNotFoundExceptionSepa extends RuntimeException implements SepaHbciFintsExceptionInterface
{
}
