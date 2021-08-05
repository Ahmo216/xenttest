<?php

namespace XentralAdapters\Shopify;

interface ResourceContract
{
  public function __construct(RateLimitingAwareClient $client);
}
