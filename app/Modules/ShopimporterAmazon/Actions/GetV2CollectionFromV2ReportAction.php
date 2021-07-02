<?php

declare(strict_types=1);

namespace App\Modules\ShopimporterAmazon\Actions;

use App\Modules\ShopimporterAmazon\Models\V2SettlementItem;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use League\Csv\Reader;

class GetV2CollectionFromV2ReportAction
{
    /**
     * @param string|resource $stream
     *
     * @throws Exception
     */
    public function __invoke($stream): Collection
    {
        if (is_string($stream)) {
            $csv = Reader::createFromString($stream);
        } else {
            $csv = Reader::createFromStream($stream);
        }
        $csv->setDelimiter("\t");
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();
        $collection = new Collection();
        $currency = '';
        foreach ($records as $key => $record) {
            if ($key === 1) {
                $currency = $record['currency'];
                continue;
            }
            $collection->add(
                (new V2SettlementItem())
                    ->setSettlementId($record['settlement-id'])
                    ->setTransactionType($record['transaction-type'])
                    ->setOrderId($record['order-id'])
                    ->setAdjustmentId($record['adjustment-id'])
                    ->setMarketPlaceName($record['marketplace-name'])
                    ->setAmountType($record['amount-type'])
                    ->setAmountDescription($record['amount-description'])
                    ->setAmount((float)str_replace(',', '.', $record['amount']))
                    ->setFulfillmentId($record['fulfillment-id'])
                    ->setPostedDate(new DateTime($record['posted-date']))
                    ->setOrderItemCode($record['order-item-code'])
                    ->setSku($record['sku'])
                    ->setQuantityPurchased(
                        $record['quantity-purchased'] === '' ? null : (int)$record['quantity-purchased']
                    )
                    ->setCurrency($currency)
            );
        }

        return $collection;
    }
}
