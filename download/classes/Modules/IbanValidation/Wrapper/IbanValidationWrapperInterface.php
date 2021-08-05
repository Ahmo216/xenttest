<?php

declare(strict_types=1);

namespace Xentral\Modules\IbanValidation\Wrapper;


interface IbanValidationWrapperInterface
{
    public function assert(string $iban): bool;
}
