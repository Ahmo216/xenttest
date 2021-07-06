<?php

declare(strict_types=1);

namespace App\Modules\Product\Enums;

use BenSampo\Enum\Enum;

final class TaxType extends Enum
{
    public const NORMAL = 'normal';

    public const REDUCED = 'ermaessigt';

    public const TAXFREE = 'befreit';
}
