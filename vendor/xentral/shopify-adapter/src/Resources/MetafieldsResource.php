<?php

namespace XentralAdapters\Shopify\Resources;

use DateTime;
use Psr\Http\Client\ClientExceptionInterface;
use XentralAdapters\Shopify\Data\Metafield;
use XentralAdapters\Shopify\Data\VariantMetafield;
use XentralAdapters\Shopify\Exceptions\NotAvailableResourceException;
use XentralAdapters\Shopify\PaginatedRequestHandler;
use XentralAdapters\Shopify\RateLimitingAwareClient;
use XentralAdapters\Shopify\ResourceContract;

class MetafieldsResource implements ResourceContract
{
  /** @var string[] */
  protected $availableResources = [
    'product',
    'order',
    'product',
    'customer',
    'collection'
  ];

  /** @var RateLimitingAwareClient */
  private $client;

  public function __construct(RateLimitingAwareClient $client)
  {
    $this->client = $client;
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/metafield#index-2020-10
   */
  public function listFor(string $resource, int $resourceId): array
  {
    $this->validateResource($resource);

    $requestHandler = new PaginatedRequestHandler(
      $this->client,
      "/admin/api/2020-10/{$resource}s/{$resourceId}/metafields.json"
    );

    $result = $requestHandler->handle();

    return array_map(function (array $data) {
      return $this->createMetafieldFromResponseArray($data);
    }, array_merge(...$result));
  }

  protected function validateResource(string $resource): void
  {
    if (!in_array($resource, $this->availableResources, true)) {
      throw NotAvailableResourceException::for($resource, $this->availableResources);
    }
  }

  protected function createMetafieldFromResponseArray(array $data): Metafield
  {
    return new Metafield(
      $data['id'],
      $data['key'],
      $data['value'],
      $data['value_type'],
      $data['namespace'],
      $data['description'] ?: '',
      $data['owner_id'],
      $data['owner_resource'],
      DateTime::createFromFormat(DATE_ATOM, $data['created_at']) ?: new DateTime(),
      DateTime::createFromFormat(DATE_ATOM, $data['updated_at']) ?: null
    );
  }

  public function listForVariant(int $productId, int $variantId): array
  {
    $requestHandler = new PaginatedRequestHandler(
      $this->client,
      "/admin/api/2020-10/products/{$productId}/variants/{$variantId}/metafields.json"
    );

    $result = $requestHandler->handle();

    return array_map(function (array $data) use ($productId) {
      return $this->createVariantMetafieldFromResponseArray($productId, $data);
    }, array_merge(...$result));
  }

  protected function createVariantMetafieldFromResponseArray(int $productId, array $data): VariantMetafield
  {
    return new VariantMetafield(
      $data['id'],
      $data['key'],
      $data['value'],
      $data['value_type'],
      $data['namespace'],
      $data['description'] ?: '',
      $data['owner_id'],
      $productId,
      DateTime::createFromFormat(DATE_ATOM, $data['created_at']) ?: new DateTime(),
      DateTime::createFromFormat(DATE_ATOM, $data['updated_at']) ?: null
    );
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/metafield#create-2020-10
   * @param mixed $value
   */
  public function createFor(
    string $resource,
    string $namespace,
    string $key,
    string $valueType,
    $value,
    ?int $resourceId = null
  ): Metafield
  {
    $this->validateResource($resource);

    $url = $resourceId === null
      ? "/admin/api/2020-10/{$resource}s/metafields.json"
      : "/admin/api/2020-10/{$resource}s/{$resourceId}/metafields.json";

    $response = $this->client->request(
      'POST',
      $url,
      [
        'json' => [
          'metafield' => [
            'namespace' => $namespace,
            'key' => $key,
            'value_type' => $valueType,
            'value' => $value
          ]
        ]
      ]
    );
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return $this->createMetafieldFromResponseArray($decodedResponse['metafield']);
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/metafield#create-2020-10
   * @param MetaField $metafield
   */
  public function create(Metafield $metafield): Metafield
  {
    $this->validateMetaField($metafield);

    $url = $this->getUrl($metafield);

    $response = $this->client->request(
      'POST',
      $url,
      [
        'json' => [
          'metafield' => [
            'namespace' => $metafield->getNamespace(),
            'key' => $metafield->getKey(),
            'value_type' => $metafield->getValueType(),
            'value' => $metafield->getValue()
          ]
        ]
      ]
    );
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return $this->createMetafieldFromResponseArray($decodedResponse['metafield']);
  }

  protected function validateMetaField(MetaField $metafield)
  {
    if (!is_a($metafield, VariantMetafield::class) &&
      !in_array($metafield->getOwnerResource(), $this->availableResources, true)) {
      throw NotAvailableResourceException::for($metafield->getOwnerResource(), $this->availableResources);
    }

  }

  protected function getUrl(MetaField $metafield): string
  {
    if ($metafield->getId() === null) {
      if (is_a($metafield, VariantMetafield::class)) {
        return "/admin/api/2020-10/products/{$metafield->getProductId()}/{$metafield->getOwnerResource()}s/{$metafield->getOwnerId()}/metafields.json";
      }
      return "/admin/api/2020-10/{$metafield->getOwnerResource()}s/{$metafield->getOwnerId()}/metafields.json";
    }

    return "/admin/api/2020-10/metafields/{$metafield->getId()}.json";
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/metafield#create-2020-10
   * @param mixed $value
   */
  public function update(Metafield $metafield): Metafield
  {
    $this->validateMetaField($metafield);

    $url = $this->getUrl($metafield);

    $response = $this->client->request(
      'PUT',
      $url,
      [
        'json' => [
          'metafield' => [
            'namespace' => $metafield->getNamespace(),
            'key' => $metafield->getKey(),
            'value_type' => $metafield->getValueType(),
            'value' => $metafield->getValue()
          ]
        ]
      ]
    );
    $decodedResponse = json_decode($response->getBody()->getContents(), true);

    return $this->createMetafieldFromResponseArray($decodedResponse['metafield']);
  }

  /**
   * @link https://shopify.dev/docs/admin-api/rest/reference/metafield#destroy-2020-10
   */
  public function delete(int $metafieldId): bool
  {
    try {
      $this->client->request('DELETE', "/admin/api/2020-10/metafields/{$metafieldId}.json");

      return true;
    } catch (ClientExceptionInterface $exception) {
      return false;
    }
  }
}
