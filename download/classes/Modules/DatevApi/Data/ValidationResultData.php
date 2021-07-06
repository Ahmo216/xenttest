<?php

declare(strict_types=1);

namespace Xentral\Modules\DatevApi\Data;

final class ValidationResultData
{
    /** @var bool $isValid */
    private $isValid;

    /** @var array|ValidationResultErrorData[] $errors */
    private $errors = [];

    public function __construct(bool $isValid, array $errors = [])
    {
        $this->isValid = $isValid;
        $this->errors = $errors;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }

    /**
     * @return array|ValidationResultErrorData[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
