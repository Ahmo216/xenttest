<?php

namespace Xentral\Modules\Api\Validator\Rule;

use Rakit\Validation\Rule;

class LengthRule extends Rule
{
    /** @var string $message */
    protected $message = "The attribute ':attribute' must have the length :length.";

    /** @var array $fillable_params */
    protected $fillable_params = ['length'];

    /**
     * @param mixed $value
     *
     * @throws \Rakit\Validation\MissingRequiredParameterException
     *
     * @return bool
     */
    public function check($value)
    {
        $this->requireParameters($this->fillable_params);

        $length = (int)$this->parameter('length');

        return mb_strlen((string)$value, 'UTF-8') === $length;
    }
}
