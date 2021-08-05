<?php

namespace XentralAdapters\Shopify\Factories;

use DateTime;
use XentralAdapters\Shopify\Data\ProductImage;

class ProductImageFactory
{
  public static function createFromResponseArray(array $data): ProductImage
  {
    return new ProductImage(
      $data['id'],
      $data['position'],
      $data['product_id'],
      $data['variant_ids'],
      $data['src'],
      $data['width'],
      $data['height'],
      DateTime::createFromFormat(DATE_ATOM, $data['created_at']) ?: new DateTime(),
      DateTime::createFromFormat(DATE_ATOM, $data['updated_at']) ?: null
    );
  }
}
