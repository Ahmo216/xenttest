<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Exceptions;

class InvalidArgumentException extends \InvalidArgumentException implements AccountingExceptionInterface
{
}
