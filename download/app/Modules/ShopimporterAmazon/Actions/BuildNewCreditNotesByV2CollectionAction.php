<?php

declare(strict_types=1);

namespace App\Modules\ShopimporterAmazon\Actions;

use App\Modules\Accounting\Models\CreditNote;
use App\Modules\Accounting\Models\Invoice;
use App\Modules\Product\Models\Product;
use App\Modules\ShopimporterAmazon\Models\V2SettlementItem;
use App\Modules\ShopimporterAmazon\SettlementV2Actions\RemoveShippingWithDiscountFromV2CollectionAction;
use Illuminate\Support\Collection;

class BuildNewCreditNotesByV2CollectionAction
{
    /** @var GetV2CollectionForCreditNotesAction */
    private $getV2CollectionForCreditNotesAction;

    /** @var BuildNewCreditNoteFromV2ItemAction */
    private $getNewCreditNoteFromV2ItemAction;

    /** @var RemoveShippingWithDiscountFromV2CollectionAction */
    private $removeShippingWithDiscountFromV2CollectionAction;

    /** @var GetCreditNoteAdjustmentsAction */
    private $getCreditNoteAdjustmentsAction;

    public function __construct(
        GetV2CollectionForCreditNotesAction $getV2CollectionForCreditNotesAction,
        BuildNewCreditNoteFromV2ItemAction $getNewCreditNoteFromV2ItemAction,
        RemoveShippingWithDiscountFromV2CollectionAction $removeShippingWithDiscountFromV2CollectionAction,
        GetCreditNoteAdjustmentsAction $getCreditNoteAdjustmentsAction
    ) {
        $this->getV2CollectionForCreditNotesAction = $getV2CollectionForCreditNotesAction;
        $this->getNewCreditNoteFromV2ItemAction = $getNewCreditNoteFromV2ItemAction;
        $this->removeShippingWithDiscountFromV2CollectionAction = $removeShippingWithDiscountFromV2CollectionAction;
        $this->getCreditNoteAdjustmentsAction = $getCreditNoteAdjustmentsAction;
    }

    public function __invoke(Collection $collection, Collection $invoices, ?Product $shippingProduct = null): array
    {
        $creditNotes = [];

        $creditNoteCollection = $this->filterNotCreatedV2Entries($collection);

        $creditNoteCollection = ($this->getV2CollectionForCreditNotesAction)($creditNoteCollection);
        /** @var Collection $items */
        foreach ($creditNoteCollection as $creditNoteItems) {
            /** @var V2SettlementItem $item */
            $item = $creditNoteItems->first()->first();
            /** @var Invoice $invoice */
            $invoice = $invoices->filter(
                function (Invoice $invoice) use ($item) {
                    return $item->getOrderId() === $invoice->order->internet;
                }
            )->first();
            $creditNote = ($this->getNewCreditNoteFromV2ItemAction)($creditNoteItems, $invoice, $shippingProduct);
            if (!$creditNote instanceof CreditNote) {
                continue;
            }
            $invoiceSum = $invoice->soll;
            $creditNoteSum = (float)$invoice->creditNotes()->get()->pluck('soll')->sum();
            if ($creditNoteSum < $invoiceSum) {
                $creditNotes[$item->getAdjustmentId()] = $creditNote;
            }
        }

        return $creditNotes;
    }

    private function filterNotCreatedV2Entries(Collection $collection): Collection
    {
        $creditNoteCollection = ($this->removeShippingWithDiscountFromV2CollectionAction)($collection);
        $adjustmentIds = $creditNoteCollection->map(
            function (V2SettlementItem $item) {
                return $item->getAdjustmentId();
            }
        )->unique()->toArray();
        $foundAdjustmentIds = ($this->getCreditNoteAdjustmentsAction)($adjustmentIds)->map(
            function ($item) {
                return $item->adjustmentid;
            }
        )->unique()->toArray();

        return $creditNoteCollection->filter(function (V2SettlementItem $item) use ($foundAdjustmentIds) {
            return !in_array($item->getAdjustmentId(), $foundAdjustmentIds);
        });
    }
}
