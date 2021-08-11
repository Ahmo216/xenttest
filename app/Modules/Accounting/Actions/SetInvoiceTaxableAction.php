<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Actions;

use App\Modules\Accounting\Enums\TaxableType;
use App\Modules\Accounting\Models\Invoice;

class SetInvoiceTaxableAction
{
    /** @var IsInvoiceTaxExemptAction */
    private $isInvoiceTaxExemptAction;

    public function __construct(IsInvoiceTaxExemptAction $isInvoiceTaxExemptAction)
    {
        $this->isInvoiceTaxExemptAction = $isInvoiceTaxExemptAction;
    }

    public function __invoke(Invoice $invoice): void
    {
        if (!($this->isInvoiceTaxExemptAction)($invoice)) {
            return;
        }
        if (empty($invoice->getAttribute('ustid'))) {
            $invoice->setAttribute('ust_befreit', TaxableType::IS_EU)->save();
        } else {
            $invoice->setAttribute('ust_befreit', TaxableType::DOMESTIC)->save();
        }
    }
}
