<?php

namespace XentralAdapters\Shopify;

use DateTime;

class ConditionParser
{
  public static function parse(array $conditions): array
  {
    foreach ($conditions as $key => $value) {
      if ($value instanceof DateTime) {
        $conditions[$key] = $value->format(DATE_ATOM);
      }
    }

    return $conditions;
  }

  public static function toQueryString(array $conditions): string
  {
    if (empty($conditions)) {
      return '';
    }

    return '?' . http_build_query(self::parse($conditions));
  }
}
