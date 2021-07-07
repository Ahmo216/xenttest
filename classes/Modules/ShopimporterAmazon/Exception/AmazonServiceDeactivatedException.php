<?php

declare(strict_types=1);

namespace Xentral\Modules\ShopimporterAmazon\Exception;

use RuntimeException as SplRuntimeException;

class AmazonServiceDeactivatedException extends SplRuntimeException implements ShopimporterAmazonExceptionInterface
{

}
