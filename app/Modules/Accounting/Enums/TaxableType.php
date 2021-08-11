<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Enums;

use BenSampo\Enum\Enum;

final class TaxableType extends Enum
{
    /** is Taxable */
    public const DOMESTIC = 0;

    /** is Taxable with VAT-id */
    public const IS_EU = 1;

    /** is tax-exempt */
    public const EXPORT = 2;

    /** domestic but tax exempt */
    public const DOMESTIC_TAX_EXEMPT = 3;
}
