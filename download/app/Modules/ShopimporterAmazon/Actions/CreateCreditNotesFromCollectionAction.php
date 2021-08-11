<?php

declare(strict_types=1);

namespace App\Modules\ShopimporterAmazon\Actions;

use App\Modules\Accounting\Models\CreditNote;
use erpooSystem;

class CreateCreditNotesFromCollectionAction
{
    private const CANCEL_REASON = 'Rechnung storniert';

    /** @var erpooSystem */
    private $legacyApp;

    /** @var CreateCreditNoteAdjustmentAction */
    private $createCreditNoteAdjustmentAction;

    public function __construct(
        erpooSystem $legacyApp,
        CreateCreditNoteAdjustmentAction $createCreditNoteAdjustmentAction
    ) {
        $this->legacyApp = $legacyApp;
        $this->createCreditNoteAdjustmentAction = $createCreditNoteAdjustmentAction;
    }

    public function __invoke(int $shopId, array $creditNotes, bool $useCurrentDate = false): array
    {
        $createdCreditNotes = [];
        foreach ($creditNotes as $adjustmentId => $creditNote) {
            $invoiceId = (int)$creditNote->rechnungid;
            $creditNoteId = (int)$this->legacyApp->erp->WeiterfuehrenRechnungZuGutschrift($invoiceId, self::CANCEL_REASON, false);
            $newCreditNote = CreditNote::query()->with('items')->find($creditNoteId);
            $newCreditNote->items()->delete();
            $newCreditNote->ust_befreit = $creditNote->ust_befreit;
            $newCreditNote->ustid = $creditNote->ustid;
            if (!$useCurrentDate) {
                $newCreditNote->datum = $creditNote->datum;
            }
            $newCreditNote->save();
            $sort = 0;
            foreach ($creditNote->items as $item) {
                $sort++;
                $newItem = clone $item;
                $newItem->gutschrift = $creditNoteId;
                $newItem->sort = $sort;
                $newItem->steuersatz = $item->steuersatz <= 0 ? -1 : $item->steuersatz;
                $newItem->save();
            }
            if (empty($newCreditNote->belegnr)) {
                $this->legacyApp->erp->BelegFreigabe('gutschrift', $creditNoteId, true);
            }
            $this->legacyApp->erp->GutschriftNeuberechnen($creditNoteId);
            $createdCreditNotes[$adjustmentId] = CreditNote::query()->find($creditNoteId);
            ($this->createCreditNoteAdjustmentAction)($shopId, $creditNoteId, (int)$item->artikel, $invoiceId, $adjustmentId);
        }

        return $createdCreditNotes;
    }
}
