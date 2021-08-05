<?php

namespace Xentral\Modules\Api\Exception;

use Throwable;
use Xentral\Modules\Api\Error\ApiError;
use Xentral\Modules\Api\Http\Exception\HttpException;

class BadRequestException extends HttpException
{
    public function __construct(
        $message = 'Bad request',
        $code = ApiError::CODE_BAD_REQUEST,
        ?Throwable $previous = null,
        array $errors = []
    ) {
        parent::__construct(400, $message, $code, $previous, $errors);
    }
}
