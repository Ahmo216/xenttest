<?php

declare(strict_types=1);

namespace App\Modules\Stock\Actions;

use App\Modules\Accounting\Models\Order;
use App\Modules\Product\Models\Product;

class HasPreferredStorageEnoughStockForOrderAction
{
    /** @var GetStockOfProductInSpecificStorageForShippingProcessAction $getStockOfProductInSpecificStorageForShippingProcessAction */
    private $getStockOfProductInSpecificStorageForShippingProcessAction;

    /** @var GetReservationQuantityOfProductAction $getReservationQuantityOfProductAction */
    private $getReservationQuantityOfProductAction;

    /** @var GetProductReservationQuantityOfOrderAction $getProductReservationQuantityOfOrderAction */
    private $getProductReservationQuantityOfOrderAction;

    /** @var GetNonDeliveredQuantityOfProductInSpecificOrderAction $getNonDeliveredQuantityOfProductInSpecificOrderAction */
    private $getNonDeliveredQuantityOfProductInSpecificOrderAction;

    /** @var GetOrderReservationQuantityOfPreferredStorageAction $getOrderReservationQuantityOfPreferredStorageAction */
    private $getOrderReservationQuantityOfPreferredStorageAction;

    public function __construct(
        GetStockOfProductInSpecificStorageForShippingProcessAction $getStockOfProductInSpecificStorageForShippingProcessAction,
        GetReservationQuantityOfProductAction $getReservationQuantityOfProductAction,
        GetProductReservationQuantityOfOrderAction $getProductReservationQuantityOfOrderAction,
        GetNonDeliveredQuantityOfProductInSpecificOrderAction $getNonDeliveredQuantityOfProductInSpecificOrderAction,
        GetOrderReservationQuantityOfPreferredStorageAction $getOrderReservationQuantityOfPreferredStorageAction
    ) {
        $this->getStockOfProductInSpecificStorageForShippingProcessAction = $getStockOfProductInSpecificStorageForShippingProcessAction;
        $this->getReservationQuantityOfProductAction = $getReservationQuantityOfProductAction;
        $this->getProductReservationQuantityOfOrderAction = $getProductReservationQuantityOfOrderAction;
        $this->getNonDeliveredQuantityOfProductInSpecificOrderAction = $getNonDeliveredQuantityOfProductInSpecificOrderAction;
        $this->getOrderReservationQuantityOfPreferredStorageAction = $getOrderReservationQuantityOfPreferredStorageAction;
    }

    public function __invoke(Order $order, Product $product, int $storageId, float $quantity, int $decimalAccuracy = 4): bool
    {
        $quantityInStorage = round(($this->getStockOfProductInSpecificStorageForShippingProcessAction)($product->id, $storageId), $decimalAccuracy);
        if ($quantityInStorage < $quantity) {
            return false;
        }

        $reserved = round(($this->getReservationQuantityOfProductAction)($product->id), $decimalAccuracy);
        $reservedForOrder = round(($this->getProductReservationQuantityOfOrderAction)($product->id, $order->id, $order->adresse));
        $reservedQuantityForOtherDocuments = $reserved - $reservedForOrder;
        if (round($quantityInStorage - $reservedQuantityForOtherDocuments, $decimalAccuracy) >= round($quantity, $decimalAccuracy)) {
            return true;
        }
        $quantityInOrder = round(($this->getNonDeliveredQuantityOfProductInSpecificOrderAction)($product, $order), $decimalAccuracy);
        if ($quantityInStorage < $quantityInOrder) {
            return false;
        }
        if (round($quantityInStorage - $reservedQuantityForOtherDocuments, $decimalAccuracy) >= round($quantityInOrder, $decimalAccuracy)) {
            return true;
        }
        $reservedForStorage = ($this->getOrderReservationQuantityOfPreferredStorageAction)($product->id, $storageId);
        $otherOrderReservationsForPreferredStorage = $reservedForStorage - $reservedForOrder;
        $neededQuantityInStorage = round($quantityInOrder + $otherOrderReservationsForPreferredStorage, $decimalAccuracy);
        if ($neededQuantityInStorage > $quantityInStorage) {
            return false;
        }
        $quantityInAllStorages = round(($this->getStockOfProductInSpecificStorageForShippingProcessAction)($product->id), $decimalAccuracy);
        $allNeeded = round($reservedQuantityForOtherDocuments + $quantityInOrder, $decimalAccuracy);

        return $allNeeded >= $quantityInAllStorages;
    }
}
