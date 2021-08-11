<?php

declare(strict_types=1);

namespace App\Modules\ShopimporterAmazon\SettlementV2Actions;

use App\Modules\ShopimporterAmazon\Models\V2SettlementItem;
use Illuminate\Support\Collection;

class RemoveShippingWithDiscountFromV2CollectionAction
{
    public function __invoke(Collection $collection): Collection
    {
        $filteredCollection = clone $collection;
        $promotions = clone $collection;
        $promotions->filter(
            function (V2SettlementItem $item) {
                return $item->getAmountType() === V2SettlementItem::AMOUNT_TYPE_PROMOTION;
            }
        );
        /** @var V2SettlementItem $promotion */
        foreach ($promotions as $promotion) {
            if ($this->isShippingPromotionExists($filteredCollection, $promotion)) {
                $filteredCollection = $filteredCollection->filter(
                    function (V2SettlementItem $item) use ($promotion) {
                        return !$this->isItemCurrentPromotionItem($item, $promotion)
                            && !$this->isShippingItemMatchToPromotionItem($item, $promotion);
                    }
                );
            }
        }

        return $filteredCollection;
    }

    private function isShippingPromotionExists(Collection $filteredCollection, V2SettlementItem $promotionItem): bool
    {
        return in_array($promotionItem->getAmountDescription(), ['Shipping', 'TaxDiscount'])
            && $filteredCollection->filter(
                function (V2SettlementItem $item) use ($promotionItem) {
                    return $this->isShippingItemMatchToPromotionItem($item, $promotionItem);
                }
            )->first() instanceof V2SettlementItem;
    }

    private function isSettlementItemInSamePosition(V2SettlementItem $item, V2SettlementItem $itemToCompare): bool
    {
        return $item->getAdjustmentId() === $itemToCompare->getAdjustmentId()
        && $item->getOrderItemCode() === $itemToCompare->getOrderItemCode();
    }

    private function isItemCurrentPromotionItem(V2SettlementItem $item, V2SettlementItem $itemToCompare): bool
    {
        if ($item->getAmountType() !== V2SettlementItem::AMOUNT_TYPE_PROMOTION
            || $itemToCompare->getAmountType() !== V2SettlementItem::AMOUNT_TYPE_PROMOTION) {
            return false;
        }
        if (!$this->isSettlementItemInSamePosition($item, $itemToCompare)) {
            return false;
        }

        return $item->getAmount() === $itemToCompare->getAmount()
            && $item->getAmountDescription() === $itemToCompare->getAmountDescription();
    }

    private function isShippingItemMatchToPromotionItem(V2SettlementItem $item, V2SettlementItem $promotionItem): bool
    {
        if ($item->getAmountType() === V2SettlementItem::AMOUNT_TYPE_PROMOTION
            || $promotionItem->getAmountType() !== V2SettlementItem::AMOUNT_TYPE_PROMOTION) {
            return false;
        }
        if (!$this->isSettlementItemInSamePosition($item, $promotionItem)) {
            return false;
        }

        return $item->getAmountDescription() === $promotionItem->getAmountDescription()
            || ($item->getAmountDescription() === 'ShippingTax'
                && $promotionItem->getAmountDescription() === 'TaxDiscount');
    }
}
