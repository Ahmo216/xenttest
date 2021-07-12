<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Api\Response\Type\Api;

use Shopware\Core\Framework\Api\Context\AdminApiSource;
use Shopware\Core\Framework\Api\Context\ContextSource;
use Shopware\Core\Framework\Api\Response\JsonApiResponse;
use Shopware\Core\Framework\Api\Response\Type\JsonFactoryBase;
use Shopware\Core\Framework\Api\Serializer\JsonApiEncoder;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\System\SalesChannel\Api\ResponseFields;
use Shopware\Core\System\SalesChannel\Api\StructEncoder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class JsonApiType extends JsonFactoryBase
{
    /**
     * @var JsonApiEncoder
     */
    private $serializer;

    /**
     * @var StructEncoder
     */
    private $structEncoder;

    public function __construct(JsonApiEncoder $serializer, StructEncoder $structEncoder)
    {
        $this->serializer = $serializer;
        $this->structEncoder = $structEncoder;
    }

    public function supports(string $contentType, ContextSource $origin): bool
    {
        return $contentType === 'application/vnd.api+json' && $origin instanceof AdminApiSource;
    }

    public function createDetailResponse(Criteria $criteria, Entity $entity, EntityDefinition $definition, Request $request, Context $context, bool $setLocationHeader = false): Response
    {
        $headers = [];

        $entityBaseUrl = $this->getEntityBaseUrl($request, $definition);
        if ($setLocationHeader) {
            $headers['Location'] = $entityBaseUrl . '/' . $entity->getUniqueIdentifier();
        }

        $rootNode = [
            'links' => [
                'self' => $request->getUri(),
            ],
        ];

        $response = $this->serializer->encode(
            $criteria,
            $definition,
            $entity,
            $this->getApiBaseUrl($request),
            $request->attributes->getInt('version'),
            $rootNode
        );

        return new JsonApiResponse($response, JsonApiResponse::HTTP_OK, $headers, true);
    }

    public function createListingResponse(Criteria $criteria, EntitySearchResult $searchResult, EntityDefinition $definition, Request $request, Context $context): Response
    {
        $baseUrl = $this->getBaseUrl($request);
        $uri = $baseUrl . $request->getPathInfo();

        $rootNode = [
            'links' => $this->createPaginationLinks($searchResult, $uri, $request->query->all()),
        ];

        $rootNode['links']['self'] = $request->getUri();

        $rootNode['meta'] = [
            'totalCountMode' => $searchResult->getCriteria()->getTotalCountMode(),
            'total' => $searchResult->getTotal(),
        ];

        $fields = new ResponseFields(
            $request->get('includes', [])
        );

        $aggregations = [];
        foreach ($searchResult->getAggregations() as $aggregation) {
            $aggregations[$aggregation->getName()] = $this->structEncoder->encode(
                $aggregation,
                $request->attributes->getInt('version'),
                $fields
            );
        }

        $rootNode['aggregations'] = $aggregations;

        $response = $this->serializer->encode(
            $criteria,
            $definition,
            $searchResult,
            $this->getApiBaseUrl($request),
            $request->attributes->getInt('version'),
            $rootNode
        );

        return new JsonApiResponse($response, JsonApiResponse::HTTP_OK, [], true);
    }

    protected function getApiBaseUrl(Request $request): string
    {
        $versionPart = $this->getVersion($request) ? ('/v' . $this->getVersion($request)) : '';

        return $this->getBaseUrl($request) . '/api' . $versionPart;
    }
}