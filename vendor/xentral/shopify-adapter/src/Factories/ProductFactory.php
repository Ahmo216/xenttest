<?php

namespace XentralAdapters\Shopify\Factories;

use DateTime;
use XentralAdapters\Shopify\Data\Product;

class ProductFactory
{
  public static function createFromResponseArray(array $data): Product
  {
    $productImages = array_map(function (array $imageData) {
      return ProductImageFactory::createFromResponseArray($imageData);
    }, $data['images']);

    $productVariants = array_map(function (array $variantData) {
      return ProductVariantFactory::createFromResponseArray($variantData);
    }, $data['variants']);

    $product = new Product(
      $data['id'],
      DateTime::createFromFormat(DATE_ATOM, $data['created_at']) ?: null,
      DateTime::createFromFormat(DATE_ATOM, $data['updated_at']) ?: null,
      DateTime::createFromFormat(DATE_ATOM, $data['published_at']) ?: null
    );

    return $product
      ->setHandle($data['handle'])
      ->setTitle($data['title'])
      ->setBodyHtml($data['body_html'])
      ->setVendor($data['vendor'])
      ->setProductType($data['product_type'])
      ->setImages($productImages)
      ->setVariants($productVariants)
      ->setOptions($data['options'])
      ->setStatus($data['status'] ?: null)
      ->setPublished(!empty($data['published_at']));

  }
}
