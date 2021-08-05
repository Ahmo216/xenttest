<?php

namespace XentralAdapters\Shopify\Factories;

use DateTime;
use XentralAdapters\Shopify\Data\ProductVariant;

class ProductVariantFactory
{
  public static function createFromResponseArray(array $data): ProductVariant
  {

    $variant = new ProductVariant(
      $data['id'],
      DateTime::createFromFormat(DATE_ATOM, $data['created_at']) ?: null,
      DateTime::createFromFormat(DATE_ATOM, $data['updated_at']) ?: null
    );

    return $variant
      ->setProductId($data['product_id'])
      ->setInventoryItemId($data['inventory_item_id'])
      ->setInventoryManagement($data['inventory_management'])
      ->setInventoryPolicy($data['inventory_policy'])
      ->setInventoryQuantity($data['inventory_quantity'])
      ->setWeight($data['weight'])
      ->setSku($data['sku'])
      ->setTitle($data['title'])
      ->setPrice($data['price'])
      ->setCompareAtPrice($data['compare_at_price'])
      ->setBarcode($data['barcode'])
      ->setTaxable($data['taxable'])
      ->setPosition($data['position'])
      ->setOption1($data['option1'])
      ->setOption2($data['option2'])
      ->setOption3($data['option3']);
  }
}
