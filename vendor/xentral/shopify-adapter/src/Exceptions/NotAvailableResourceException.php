<?php

namespace XentralAdapters\Shopify\Exceptions;

class NotAvailableResourceException extends \Exception
{
  public static function for(string $notAvailableResource, array $availableResources): self
  {
    return new self(
      "Resource '{$notAvailableResource}' not available. Pick one out of " . implode(',', $availableResources)
    );
  }
}
