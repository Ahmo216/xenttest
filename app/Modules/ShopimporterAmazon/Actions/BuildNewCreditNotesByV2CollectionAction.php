<?php

declare(strict_types=1);

namespace App\Modules\ShopimporterAmazon\Actions;

use App\Modules\Accounting\Models\CreditNote;
use App\Modules\Accounting\Models\Invoice;
use App\Modules\ShopimporterAmazon\Models\V2SettlementItem;
use Illuminate\Support\Collection;

class BuildNewCreditNotesByV2CollectionAction
{
    /** @var GetInvoicesByAmazonOrderIdsAction */
    private $getInvoicesByAmazonOrderIdsAction;

    /** @var GetV2CollectionForCreditNotesAction */
    private $getV2CollectionForCreditNotesAction;

    /** @var BuildNewCreditNoteFromV2ItemAction */
    private $getNewCreditNoteFromV2ItemAction;

    /** @var RemoveShippingWithDiscountFromV2CollectionAction */
    private $removeShippingWithDiscountFromV2CollectionAction;

    /** @var GetCreditNoteAdjustmentsAction */
    private $getCreditNoteAdjustmentsAction;

    public function __construct(
        GetInvoicesByAmazonOrderIdsAction $getInvoicesByAmazonOrderIdsAction,
        GetV2CollectionForCreditNotesAction $getV2CollectionForCreditNotesAction,
        BuildNewCreditNoteFromV2ItemAction $getNewCreditNoteFromV2ItemAction,
        RemoveShippingWithDiscountFromV2CollectionAction $removeShippingWithDiscountFromV2CollectionAction,
        GetCreditNoteAdjustmentsAction $getCreditNoteAdjustmentsAction
    ) {
        $this->getInvoicesByAmazonOrderIdsAction = $getInvoicesByAmazonOrderIdsAction;
        $this->getV2CollectionForCreditNotesAction = $getV2CollectionForCreditNotesAction;
        $this->getNewCreditNoteFromV2ItemAction = $getNewCreditNoteFromV2ItemAction;
        $this->removeShippingWithDiscountFromV2CollectionAction = $removeShippingWithDiscountFromV2CollectionAction;
        $this->getCreditNoteAdjustmentsAction = $getCreditNoteAdjustmentsAction;
    }

    public function __invoke(Collection $collection): array
    {
        $creditNotes = [];

        $creditNoteCollection = $this->filterNotCreatedV2Entries($collection);

        $orderIds = $creditNoteCollection->map(
            function (V2SettlementItem $item) {
                return $item->getOrderId();
            }
        )->unique()->toArray();

        $invoices = ($this->getInvoicesByAmazonOrderIdsAction)($orderIds);
        $orderIds = array_intersect(
            $orderIds,
            $invoices->map(
                function (Invoice $item) {
                    return $item->order->internet;
                }
            )->unique()->toArray()
        );
        $creditNoteCollection = $creditNoteCollection->filter(
            function (V2SettlementItem $item) use ($orderIds) {
                return in_array($item->getOrderId(), $orderIds, true);
            }
        );

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
            $creditNote = ($this->getNewCreditNoteFromV2ItemAction)($creditNoteItems, $invoice);
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
        $creditNoteCollection = ($this->removeShippingWithDiscountFromV2CollectionAction)(
            $collection->filter(
                function (V2SettlementItem $item) {
                    return $item->getAmountType() !== V2SettlementItem::AMOUNT_TYPE_FEE
                        && in_array(
                            $item->getTransactionType(),
                            [V2SettlementItem::REFUND_TYPE, V2SettlementItem::RETURN_TYPE],
                            true
                        );
                }
            )
        );
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
