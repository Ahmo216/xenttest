<?php

namespace Xentral\Modules\CarrierSelect\Exception;

use InvalidArgumentException as SplInvalidArgumentException;

/**
 * Class InvalidArgumentException
 *
 * @package Xentral\Modules\CarrierSelect\Exception
 */
class InvalidArgumentException extends SplInvalidArgumentException implements CarrierSelectExceptionInterface
{
}
