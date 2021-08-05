<?php

namespace App\Core\License;

use RuntimeException;

class LicenseException extends RuntimeException
{
    public static function missingProperty(string $key): self
    {
        return new static("License property '{$key}' is missing. Please contact Support");
    }
}
