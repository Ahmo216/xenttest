<?php

declare(strict_types=1);

namespace App\Modules\ShopimporterAmazon\Actions;

use App\Modules\Accounting\Actions\IsInvoiceTaxExemptAction;
use App\Modules\Accounting\Models\CreditNote;
use App\Modules\Accounting\Models\CreditNoteItem;
use App\Modules\Accounting\Models\Invoice;
use App\Modules\Accounting\Models\InvoiceItem;
use App\Modules\Accounting\Models\OrderItem;
use App\Modules\Product\Models\Product;
use App\Modules\ShopimporterAmazon\Models\V2SettlementItem;
use Illuminate\Support\Collection;

class BuildNewCreditNoteFromV2ItemAction
{
    /** @var GetOrderItemsByWebIdsAction */
    private $getOrderItemsByWebIdsAction;

    /** @var GetOrderItemBySkuAction */
    private $getOrderItemBySkuAndOrderIdAction;

    /** @var IsInvoiceTaxExemptAction */
    private $isInvoiceTaxExemptAction;

    public function __construct(
        GetOrderItemsByWebIdsAction $getOrderItemsByWebIdsAction,
        GetOrderItemBySkuAction $getOrderItemBySkuAndOrderIdAction,
        IsInvoiceTaxExemptAction $isInvoiceTaxExemptAction
    ) {
        $this->getOrderItemsByWebIdsAction = $getOrderItemsByWebIdsAction;
        $this->getOrderItemBySkuAndOrderIdAction = $getOrderItemBySkuAndOrderIdAction;
        $this->isInvoiceTaxExemptAction = $isInvoiceTaxExemptAction;
    }

    public function __invoke(Collection $creditNoteItems, Invoice $invoice, ?Product $shippingProduct = null): ?CreditNote
    {
        $creditNote = new CreditNote();
        $creditNote->rechnungid = $invoice->id;
        $creditNote->steuersatz_ermaessigt = $invoice->steuersatz_ermaessigt;
        $creditNote->steuersatz_normal = $invoice->steuersatz_normal;
        $creditNote->ustid = $invoice->ustid;
        $creditNote->ust_befreit = $invoice->ust_befreit;
        $creditNote->items = new Collection();
        $hasItems = false;
        /** @var Collection $creditNoteItemV2Collection */
        foreach ($creditNoteItems as $creditNoteItemV2Collection) {
            if ($shippingProduct instanceof Product) {
                $creditNoteItem = $this->getCreditNoteItem($this->getSettlementItemsByShippingDescription($creditNoteItemV2Collection), $invoice);
                if ($creditNoteItem instanceof CreditNoteItem) {
                    $creditNote->items->add($creditNoteItem);
                    $hasItems = true;
                }
                $creditNoteItem = $this->getCreditNoteShippingItem($this->getSettlementItemsByShippingDescription($creditNoteItemV2Collection, true), $invoice, $shippingProduct);
                if ($creditNoteItem instanceof CreditNoteItem) {
                    $creditNote->items->add($creditNoteItem);
                    $hasItems = true;
                }

                continue;
            }
            $creditNoteItem = $this->getCreditNoteItem($creditNoteItemV2Collection, $invoice);
            if ($creditNoteItem instanceof CreditNoteItem) {
                $creditNote->items->add($creditNoteItem);
                $hasItems = true;
            }
        }
        if (!$hasItems) {
            return null;
        }
        if ($creditNote->ust_befreit > 0 && $this->hasCollectionTaxation($creditNoteItems) && ($this->isInvoiceTaxExemptAction)($invoice)) {
            if (!empty($creditNote->ustid)) {
                $creditNote->ust_befreit = 0;
            } else {
                $creditNote->ust_befreit = 1;
            }
        }
        if ($creditNote->ust_befreit === 1 && !empty($creditNote->ustid)) {
            $creditNote->ust_befreit = 0;
        }
        /** @var V2SettlementItem $firstItem */
        $firstItem = $creditNoteItems->first()->first();
        $creditNote->datum = $firstItem->getPostedDate()->format('Y-m-d');
        $creditNote->waehrung = $firstItem->getCurrency();

        return $creditNote;
    }

    private function hasCollectionTaxation(Collection $creditNoteItems): bool
    {
        /** @var Collection $creditNoteItemV2Collection */
        foreach ($creditNoteItems as $creditNoteItemV2Collection) {
            if ($creditNoteItemV2Collection->filter(
                function (V2SettlementItem $item) {
                    return strpos($item->getAmountDescription(), 'Tax') !== false;
                }
            )->count() > 0) {
                return true;
            }
        }

        return false;
    }

    private function getSettlementItemsByShippingDescription(Collection $creditNoteItemV2Collection, bool $isShipping = false): Collection
    {
        return $creditNoteItemV2Collection->filter(
            function (V2SettlementItem $item) use ($isShipping) {
                $isShippingItem = strpos($item->getAmountDescription(), 'Shipping') !== false;

                return $isShipping === $isShippingItem;
            }
        );
    }

    private function getCreditNoteShippingItem(Collection $creditNoteItemV2Collection, Invoice $invoice, Product $shippingProduct): ?CreditNoteItem
    {
        if (count($creditNoteItemV2Collection) === 0) {
            return null;
        }
        /** @var V2SettlementItem $firstItem */
        $firstItem = $creditNoteItemV2Collection->first();
        /** @var OrderItem $orderItem */
        $orderItem = $this->getOrderItem($creditNoteItemV2Collection, $invoice, $firstItem);
        if (!$orderItem instanceof OrderItem) {
            return null;
        }
        $creditNoteItem = new CreditNoteItem();
        $creditNoteItem->artikel = $shippingProduct->id;
        $creditNoteItem->menge = 1;
        $creditNoteItem->nummer = $shippingProduct->nummer;
        $creditNoteItem->bezeichnung = $this->getShippingDescription($invoice, $shippingProduct);
        $creditNoteItem->waehrung = $firstItem->getCurrency();
        $sumGross = $creditNoteItemV2Collection->sum(function (V2SettlementItem $item) {return -$item->getAmount();});
        $taxSum = $this->getTaxAmountOfCreditNoteItemV2Collection($creditNoteItemV2Collection);
        $sumNet = $sumGross - $taxSum;

        $taxRate = $this->getPositionTaxRate($orderItem, $invoice, $sumGross, $sumNet);
        $creditNoteItem->steuersatz = $taxRate;
        $creditNoteItem->preis = $sumGross / (1 + $taxRate / 100);

        return $creditNoteItem;
    }

    private function getShippingDescription(Invoice $invoice, Product $shippingProduct): string
    {
        $shippingItem = $invoice->items()->get()->filter(
            function (InvoiceItem $item) use ($shippingProduct) {return (int)$item->artikel === (int)$shippingProduct->id;}
        )->first();
        if ($shippingItem instanceof InvoiceItem) {
            return $shippingItem->bezeichnung;
        }

        return (string)$shippingProduct->name_de;
    }

    private function getCreditNoteItem(Collection $creditNoteItemV2Collection, Invoice $invoice): ?CreditNoteItem
    {
        if (count($creditNoteItemV2Collection) === 0) {
            return null;
        }
        $creditNoteItem = new CreditNoteItem();
        /** @var V2SettlementItem $firstItem */
        $firstItem = $creditNoteItemV2Collection->first();
        /** @var OrderItem $orderItem */
        $orderItem = $this->getOrderItem($creditNoteItemV2Collection, $invoice, $firstItem);
        if (!$orderItem instanceof OrderItem) {
            return null;
        }
        $creditNoteItem->artikel = $orderItem->artikel;
        $creditNoteItem->nummer = $orderItem->nummer;
        $creditNoteItem->bezeichnung = $orderItem->bezeichnung;
        $creditNoteItem->umsatzsteuer = $orderItem->umsatzsteuer;
        $creditNoteItem->menge = $firstItem->getQuantityPurchased() ?: 1;
        $creditNoteItem->waehrung = $firstItem->getCurrency();
        $sumGross = $creditNoteItemV2Collection->sum(function (V2SettlementItem $item) {return -$item->getAmount();});
        $taxSum = $this->getTaxAmountOfCreditNoteItemV2Collection($creditNoteItemV2Collection);
        $sumNet = $sumGross - $taxSum;
        $taxRate = $this->getPositionTaxRate($orderItem, $invoice, $sumGross, $sumNet);
        $creditNoteItem->steuersatz = $taxRate;
        $orderItemQuantity = (int)$orderItem->menge;
        $creditNoteItem->preis = $sumGross / $creditNoteItem->menge / (1 + $taxRate / 100);
        if ($creditNoteItem->menge === 1 && $orderItemQuantity > 1 && $orderItem->preis * $orderItemQuantity >= $creditNoteItem->preis) {
            $creditNoteItem->menge = $orderItemQuantity;
            $creditNoteItem->preis /= $orderItemQuantity;
        }

        return $creditNoteItem;
    }

    private function getOrderItem(Collection $creditNoteItemV2Collection, Invoice $invoice, V2SettlementItem $firstItem): ?OrderItem
    {
        $webIds = $creditNoteItemV2Collection->map(function (V2SettlementItem $item) {return $item->getOrderItemCode();})->unique()->toArray();
        /** @var OrderItem $orderItem */
        $orderItem = ($this->getOrderItemsByWebIdsAction)($webIds)->first();
        if (!$orderItem instanceof OrderItem) {
            return ($this->getOrderItemBySkuAndOrderIdAction)($firstItem->getSku(), $invoice->order()->get()->first());
        }

        return $orderItem;
    }

    /**
     * Positions are splittet in Items with different amount-descriptions likePrincipal,Shipping, Giftwrap, Discount
     * for each of them items with Tax are existing
     * some of them starts with Tax like TaxDiscount, on some ot the items will end with Tax like ShippingTax
     *
     * @param Collection $creditNoteItemV2Collection
     *
     * @return float
     */
    private function getTaxAmountOfCreditNoteItemV2Collection(Collection $creditNoteItemV2Collection): float
    {
        return (float)$creditNoteItemV2Collection->sum(function (V2SettlementItem $item) {
            $isItemTaxAmount = strpos($item->getAmountDescription(), 'Tax') === 0 || substr($item->getAmountDescription(), -3) === 'Tax';
            if ($isItemTaxAmount) {
                return -$item->getAmount();
            }

            return 0;
        });
    }

    private function getPositionTaxRate(OrderItem $orderItem, Invoice $invoice, float $sumGross, float $sumNet): float
    {
        if ((float)$orderItem->steuersatz > 0 && $this->isGrossPriceMatchingToNetTaxRate($sumGross, $sumNet, (float)$orderItem->steuersatz)) {
            return (float)$orderItem->steuersatz;
        }
        if ($this->isGrossPriceMatchingToNetTaxRate($sumGross, $sumNet, (float)$orderItem->steuersatz_normal)) {
            return (float)$invoice->steuersatz_normal;
        }
        if ($this->isGrossPriceMatchingToNetTaxRate($sumGross, $sumNet, (float)$orderItem->steuersatz_ermaessigt)) {
            return (float)$invoice->steuersatz_ermaessigt;
        }
        $invoiceItem = $invoice->items()->where('auftrag_position_id', $orderItem->id)->first();
        if ($invoiceItem instanceof InvoiceItem && $this->isGrossPriceMatchingToNetTaxRate($sumGross, $sumNet, (float)$invoiceItem->steuersatz)) {
            return (float)$invoiceItem->steuersatz;
        }

        if ($orderItem->steuersatz > 0) {
            return (float)$orderItem->steuersatz;
        }
        if ($orderItem->umsatzsteuer === 'ermaessigt') {
            return (float)$invoice->steuersatz_ermaessigt;
        }

        return (float)$invoice->steuersatz_normal;
    }

    private function isGrossPriceMatchingToNetTaxRate(float $grossPrice, float $netPrice, float $taxRate): bool
    {
        return round($netPrice * (1 + $taxRate / 100), 2) === round($grossPrice, 2)
            || round($netPrice, 2) === round($grossPrice / (1 + $taxRate / 100), 2);
    }
}
