<?php

namespace XentralAdapters\Shopify\Factories;

use XentralAdapters\Shopify\Data\Customer;
use XentralAdapters\Shopify\Data\Order;
use XentralAdapters\Shopify\Data\OrderLineItem;
use XentralAdapters\Shopify\Data\PaymentDetails;

class OrderFactory
{
  public static function createFromResponseArray(array $data): Order
  {
    $lineItems = array_map(function (array $lineData) {
      return new OrderLineItem(
        $lineData['id'],
        $lineData['product_id'],
        $lineData['variant_id'],
        $lineData['title'],
        $lineData['discount_allocations'],
        $lineData['taxable'],
        $lineData['tax_lines'],
        $lineData['price_set'],
        $lineData['quantity']
      );
    }, $data['line_items']);

    if ($data['customer']) {
      $customer = new Customer(
        $data['customer']['first_name'],
        $data['customer']['last_name'],
        $data['customer']['email']
      );
    } else {
      $customer = null;
    }

    return new Order(
      $data['id'],
      $lineItems,
      $data['total_price'],
      $data['total_tax'],
      $data['total_discounts'],
      $data['currency'],
      $data['name'],
      $data['source_name'],
      $data['email'],
      $data['note'],
      $customer
    );
  }
}
