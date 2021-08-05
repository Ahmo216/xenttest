<?php

namespace XentralAdapters\Shopify;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class RateLimitingAwareClient
{
  const HTTP_TOO_MANY_REQUESTS = 429;
  /** @var ClientInterface */
  private $client;

  public function __construct(ClientInterface $client)
  {
    $this->client = $client;
  }

  public function send(RequestInterface $request, array $options = []): ResponseInterface
  {
    try {
      return $this->client->send($request, $options);
    } catch (RequestException $exception) {
      if ($exception->getResponse() !== null && $exception->getResponse()->getStatusCode() === self::HTTP_TOO_MANY_REQUESTS) {
        sleep(max((int)$exception->getResponse()->getHeaderLine('Retry-After'), 1));

        return $this->send($this->resetRequest($request), $options);
      } else {
        throw $exception;
      }
    }
  }

  public function request(string $method, string $uri = '', array $options = []): ResponseInterface
  {
    $request = new Request($method, $uri);

    return $this->send($request, $options);
  }

  protected function resetRequest(RequestInterface $request): RequestInterface
  {
    if (in_array(strtolower($request->getMethod()), ['post', 'put'], true)) {
      $request->getBody()->rewind();
    }

    return $request;
  }
}
