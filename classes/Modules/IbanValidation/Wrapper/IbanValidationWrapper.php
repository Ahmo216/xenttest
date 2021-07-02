<?php

declare(strict_types=1);

namespace Xentral\Modules\IbanValidation\Wrapper;

use Intervention\Validation\Exception\ValidationException;
use Intervention\Validation\Rules\Iban;
use Intervention\Validation\Validator;

final class IbanValidationWrapper implements IbanValidationWrapperInterface
{
    private $validator;

    public function __construct()
    {
        $this->validator = new Validator(new Iban);
    }

    public function assert(string $iban): bool
    {
        try {
            $this->validator->assert($iban);
        } catch (ValidationException $e) {
            return false;
        }

        return true;
    }

}
