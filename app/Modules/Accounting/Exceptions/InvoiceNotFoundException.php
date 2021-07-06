<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Exceptions;

class InvoiceNotFoundException extends \RuntimeException implements AccountingExceptionInterface
{
}
