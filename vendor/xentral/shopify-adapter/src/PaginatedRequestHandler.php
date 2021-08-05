<?php

namespace XentralAdapters\Shopify;

use GuzzleHttp\Psr7\Request;

class PaginatedRequestHandler
{
  /** @var RateLimitingAwareClient */
  protected $client;
  /** @var array */
  protected $currentResponseHeaders = [];
  /** @var string */
  private $url;
  /** @var array */
  private $links = [];

  public function __construct(RateLimitingAwareClient $client, string $url, string $queryString = '')
  {
    $this->client = $client;
    $this->url = $url . $queryString;
  }

  public function handle(): array
  {
    $uriParts = explode('/', $this->url);
    $lastUriPart = $uriParts[count($uriParts) - 1];
    $resourceName = explode('.', $lastUriPart)[0];

    $result = [];
    do {
      $pageResult = $this->request($this->url);
      $result[] = $pageResult[$resourceName] ?? $pageResult;
    } while ($this->hasNextPage());

    return $result;
  }

  private function request(string $uri): array
  {
    $request = new Request('GET', $this->hasNextPage() ? $this->getNextPage() : $uri);
    $response = $this->client->send($request);
    $this->links = $response->getHeader('link');

    return json_decode($response->getBody()->getContents(), true);
  }

  private function hasNextPage(): bool
  {
    if (empty($this->links)) {
      return false;
    }

    foreach ($this->links as $link) {
      if (str_contains($link, '; rel="next"')) {
        return true;
      }
    }

    return false;
  }

  private function getNextPage(): string
  {
    foreach ($this->links as $link) {
      if (!str_contains($link, '; rel="next"')) {
        continue;
      }
      preg_match('#<(.*?)>; rel="next"#m', $link, $foundLinks);
      if ($foundLinks) {
        return $foundLinks[1];
      }
    }

    return '';
  }
}
