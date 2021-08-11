<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Actions;

use App\Modules\Accounting\Enums\TaxableType;
use App\Modules\Accounting\Models\CreditNote;

class SetCreditNoteTaxableAction
{
    private $isCreditNoteTaxExemptAction;

    public function __construct(IsCreditNoteTaxExemptAction $isCreditNoteTaxExemptAction)
    {
        $this->isCreditNoteTaxExemptAction = $isCreditNoteTaxExemptAction;
    }

    public function __invoke(CreditNote $creditNote): void
    {
        if (!($this->isCreditNoteTaxExemptAction)($creditNote)) {
            return;
        }
        if (empty($creditNote->getAttribute('ustid'))) {
            $creditNote->setAttribute('ust_befreit', TaxableType::IS_EU)->save();
        } else {
            $creditNote->setAttribute('ust_befreit', TaxableType::DOMESTIC)->save();
        }
    }
}
