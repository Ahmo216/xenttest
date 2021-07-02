<?php

namespace Xentral\Modules\Api\Exception;

use Throwable;
use Xentral\Modules\Api\Error\ApiError;
use Xentral\Modules\Api\Http\Exception\HttpException;

class InvalidArgumentException extends HttpException
{
    public function __construct(
        $message = 'Invalid argument',
        $code = ApiError::CODE_INVALID_ARGUMENT,
        ?Throwable $previous = null
    ) {
        parent::__construct(400, $message, $code, $previous);
    }
}
