<?php

declare(strict_types=1);

namespace Xentral\Modules\DatevApi\Exception;

use InvalidArgumentException;

final class NoDocumentsFoundException extends InvalidArgumentException implements DatevExceptionInterface
{
}
