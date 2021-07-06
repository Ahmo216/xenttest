<?php

declare(strict_types=1);

namespace Xentral\Modules\DeutschePost\Exception;

use SoapFault;

class ProductWebServiceSoapException extends ProductWebServiceException
{
    static public function fromSoapFault(SoapFault $origin): self
    {
        return new self(
            $origin->getMessage(),
            $origin->getCode(),
            $origin
        );
    }
}
