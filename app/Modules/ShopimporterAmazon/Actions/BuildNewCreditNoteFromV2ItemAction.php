<?php

declare(strict_types=1);

namespace App\Modules\ShopimporterAmazon\Actions;

use App\Modules\Accounting\Models\CreditNote;
use App\Modules\Accounting\Models\CreditNoteItem;
use App\Modules\Accounting\Models\Invoice;
use App\Modules\Accounting\Models\OrderItem;
use App\Modules\ShopimporterAmazon\Models\V2SettlementItem;
use Illuminate\Support\Collection;

class BuildNewCreditNoteFromV2ItemAction
{
    /** @var GetOrderItemsByWebIdsAction */
    private $getOrderItemsByWebIdsAction;

    public function __construct(
        GetOrderItemsByWebIdsAction $getOrderItemsByWebIdsAction
    ) {
        $this->getOrderItemsByWebIdsAction = $getOrderItemsByWebIdsAction;
    }

    public function __invoke(Collection $creditNoteItems, Invoice $invoice): CreditNote
    {
        $creditNote = new CreditNote();
        $creditNote->rechnungid = $invoice->id;
        $creditNote->items = new Collection();
        /** @var Collection $creditNoteItemV2Collection */
        foreach ($creditNoteItems as $creditNoteItemV2Collection) {
            $creditNote->items->add($this->getCreditNoteItem($creditNoteItemV2Collection, $invoice));
        }
        if ($creditNote->ust_befreit == 1 && !empty($creditNote->ustid)) {
            $creditNote->ust_befreit = 0;
        }
        /** @var V2SettlementItem $firstItem */
        $firstItem = $creditNoteItems->first()->first();
        $creditNote->datum = $firstItem->getPostedDate()->format('Y-m-d');
        $creditNote->waehrung = $firstItem->getCurrency();

        return $creditNote;
    }

    private function getCreditNoteItem(Collection $creditNoteItemV2Collection, Invoice $invoice): CreditNoteItem
    {
        $creditNoteItem = new CreditNoteItem();
        $webIds = $creditNoteItemV2Collection->map(function (V2SettlementItem $item) {return $item->getOrderItemCode();})->unique()->toArray();
        /** @var OrderItem $orderItem */
        $orderItem = ($this->getOrderItemsByWebIdsAction)($webIds)->first();
        /** @var V2SettlementItem $firstItem */
        $firstItem = $creditNoteItemV2Collection->first();
        $creditNoteItem->artikel = $orderItem->artikel;
        $creditNoteItem->nummer = $orderItem->nummer;
        $creditNoteItem->bezeichnung = $orderItem->bezeichnung;
        $creditNoteItem->umsatzsteuer = $orderItem->umsatzsteuer;
        $creditNoteItem->menge = $firstItem->getQuantityPurchased() ?: 1;
        $creditNoteItem->waehrung = $firstItem->getCurrency();
        $taxRate = $orderItem->umsatzsteuer === 'ermaessigt' ? (float)$invoice->steuersatz_ermaessigt : (float)$invoice->steuersatz_normal;
        if ($orderItem->steuersatz > 0) {
            $taxRate = (float)$orderItem->steuersatz;
        }
        $sumGross = $creditNoteItemV2Collection->sum(function (V2SettlementItem $item) {return -$item->getAmount();});
        $creditNoteItem->preis = $sumGross / $creditNoteItem->menge / (1 + $taxRate / 100);

        return $creditNoteItem;
    }
}
