<?php

namespace Xentral\Modules\Api\Controller\Version1;

use Exception;
use Xentral\Components\Http\Response;
use Xentral\Modules\Api\Http\Exception\HttpException;
use Xentral\Modules\Api\Resource\Result\ItemResult;
use Xentral\Modules\Api\Resource\Result\ListResult;
use Xentral\Modules\Storage\Data\StorageBin;
use Xentral\Modules\Storage\Exception\StorageBinNotFoundException;
use Xentral\Modules\Storage\Exception\StorageExceptionInterface;
use Xentral\Modules\Storage\Service\StorageBinRepository;

class StorageBinController extends AbstractController
{
    /** @var StorageBinRepository */
    private $storageBinRepository;

    /**
     * StorageBinController constructor.
     *
     * @param $legacyApi
     * @param $database
     * @param $converter
     * @param $request
     * @param $resource
     */
    public function __construct($legacyApi, $database, $converter, $request, $resource)
    {
        $this->storageBinRepository = $legacyApi->app->Container->get(StorageBinRepository::class);

        parent::__construct($legacyApi, $database, $converter, $request, $resource);
    }

    /**
     * Create a new storage bin.
     *
     * @return Response
     */
    public function createAction(): Response
    {
        $data = $this->getRequestData();

        try {
            $storageBin = StorageBin::fromArray($data);
            $storageBin = $this->storageBinRepository->create($storageBin);
        } catch (StorageExceptionInterface $e) {
            throw new HttpException(400, $e->getMessage());
        }

        $result = new ItemResult($storageBin->toArray());
        $result->setSuccess(true);

        return $this->sendResult($result, Response::HTTP_CREATED);
    }

    /**
     * Update an existing storage bin.
     *
     * @return Response
     * @throws Exception
     *
     */
    public function updateAction(): Response
    {
        try {
            $storageId = (int)$this->request->attributes->get('id');

            $storageBin = $this->storageBinRepository->getById($storageId);

            $data = $this->getRequestData();

            $storageBin
                ->setName($data['name'])
                ->setStorageId($data['storage_id'])
                ->setComment($data['comment'])
                ->setProjectId($data['project_id'])
                ->setAddressId($data['address_id'])
                ->setCategory($data['category'])
                ->setShelfType($data['shelf_type'])
                ->setIsAutomaticShippingDisabled($data['is_automatic_shipping_disabled'])
                ->setIsConsumablesStorage($data['is_consumables_storage'])
                ->setIsBlockedStock($data['is_blocked_stock'])
                ->setLength($data['length'])
                ->setWidth($data['width'])
                ->setHeight($data['height'])
                ->setIsPosStorage($data['is_pos_storage'])
                ->setRowNumber($data['row_number'])
                ->setIsAvailableForProduction($data['is_available_for_production']);

            $storageBin = $this->storageBinRepository->update($storageBin);
        } catch (StorageExceptionInterface $e) {
            throw new HttpException(404, $e->getMessage());
        }

        $result = new ItemResult($storageBin->toArray());
        $result->setSuccess(true);

        return $this->sendResult($result);
    }

    /**
     * Get a single storage bin based on its id.
     *
     * @return Response
     */
    public function readAction(): Response
    {
        $id = $this->getResourceId();

        try {
            $storageData = $this->storageBinRepository->getById($id);
        } catch (StorageBinNotFoundException $e) {
            throw new HttpException(404, $e->getMessage());
        }

        $result = new ItemResult($storageData->toArray());
        $result->setSuccess(true);

        return $this->sendResult($result);
    }

    /**
     * Returns an API response containing a list of active storage bins.
     *
     * @return Response
     * @example GET /v1/storage/bin?page=2&items=10
     *
     */
    public function listAction(): Response
    {
        $page = $this->getPaginationPage();
        $limit = $this->getPaginationCount();
        $offset = ($page - 1) * $limit;

        $items = $this->storageBinRepository->getList($offset, $limit);

        $data = [];
        /* @var StorageBin $item */
        foreach ($items as $item) {
            $data[] = $item->toArray();
        }

        $items_total = $this->storageBinRepository->getTotalCount();

        $pagination = [
            'items_per_page' => $limit,
            'items_current' => count($data),
            'items_total' => (int)$items_total,
            'page_current' => (int)floor($offset / $limit) + 1,
        ];
        $pagination['page_last'] = (int)ceil($pagination['items_total'] / $limit);

        $result = new ListResult($data, $pagination);
        $result->setSuccess(true);

        return $this->sendResult($result);
    }

    /**
     * Mark storage bin as deleted.
     *
     * @return Response
     * @throws Exception
     *
     */
    public function deleteAction(): Response
    {
        $id = $this->getResourceId();

        try {
            $storageBin = $this->storageBinRepository->getById($id);
        } catch (StorageBinNotFoundException $e) {
            throw new HttpException(404, $e->getMessage());
        }

        $storageBin->setIsDeleted(true);
        $this->storageBinRepository->save($storageBin);

        $result = new ItemResult(['id' => $id]);
        $result->setSuccess(true);

        return $this->sendResult($result);
    }
}
