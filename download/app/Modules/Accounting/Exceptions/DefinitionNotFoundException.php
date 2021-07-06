<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Exceptions;

class DefinitionNotFoundException extends \RuntimeException implements AccountingExceptionInterface
{
}
