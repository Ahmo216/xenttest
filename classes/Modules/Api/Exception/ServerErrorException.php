<?php

namespace Xentral\Modules\Api\Exception;

use Throwable;
use Xentral\Modules\Api\Error\ApiError;
use Xentral\Modules\Api\Http\Exception\HttpException;

class ServerErrorException extends HttpException
{
    public function __construct(
        $message = 'Unknown server error',
        $code = ApiError::CODE_UNEXPECTED_ERROR,
        ?Throwable $previous = null
    ) {
        parent::__construct(500, $message, $code, $previous);
    }
}
