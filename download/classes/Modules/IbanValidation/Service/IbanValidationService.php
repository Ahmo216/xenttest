<?php

declare(strict_types=1);

namespace Xentral\Modules\IbanValidation\Service;

use Xentral\Modules\IbanValidation\Wrapper\IbanValidationWrapperInterface;

final class IbanValidationService
{
    private $ibanValidator;

    public function __construct(IbanValidationWrapperInterface $validator)
    {
        $this->ibanValidator = $validator;
    }

    public function isIbanValid(string $iban): bool
    {
        return $this->ibanValidator->assert($iban);
    }
}
