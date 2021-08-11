<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Actions;

use App\Modules\Accounting\Enums\TaxableType;
use App\Modules\Accounting\Models\Invoice;

class IsInvoiceTaxExemptAction
{
    public function __invoke(Invoice $invoice): bool
    {
        $taxCode = (int)$invoice->getAttribute('ust_befreit');
        if ($taxCode === TaxableType::DOMESTIC) {
            return false;
        }
        if (in_array($taxCode, [TaxableType::EXPORT, TaxableType::DOMESTIC_TAX_EXEMPT])) {
            return true;
        }

        //EU with VAT-ID are tax-exempt, EU without VAT-ID are not
        return !empty($invoice->getAttribute('ustid'));
    }
}
