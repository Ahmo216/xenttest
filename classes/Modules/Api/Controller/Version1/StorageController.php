<?php

namespace Xentral\Modules\Api\Controller\Version1;

use Exception;
use Xentral\Components\Http\Response;
use Xentral\Modules\Api\Http\Exception\HttpException;
use Xentral\Modules\Api\Resource\Result\ItemResult;
use Xentral\Modules\Api\Resource\Result\ListResult;
use Xentral\Modules\Storage\Data\Storage;
use Xentral\Modules\Storage\Exception\StorageNotFoundException;
use Xentral\Modules\Storage\Service\StorageRepository;

class StorageController extends AbstractController
{
    /** @var StorageRepository */
    private $storageRepository;

    /**
     * StorageController constructor.
     *
     * @param $legacyApi
     * @param $database
     * @param $converter
     * @param $request
     * @param $resource
     */
    public function __construct($legacyApi, $database, $converter, $request, $resource)
    {
        $this->storageRepository = $legacyApi->app->Container->get(StorageRepository::class);

        parent::__construct($legacyApi, $database, $converter, $request, $resource);
    }

    /**
     * Create a new storage.
     *
     * @return Response
     */
    public function createAction(): Response
    {
        $data = $this->getRequestData();

        try {
            $storage = Storage::fromArray($data);
            $storage = $this->storageRepository->create($storage);
        } catch (Exception $e) {
            throw new HttpException(400, $e->getMessage());
        }

        $result = new ItemResult($storage->toArray());
        $result->setSuccess(true);

        return $this->sendResult($result, Response::HTTP_CREATED);
    }

    /**
     * Update an existing storage.
     *
     * @throws Exception
     *
     * @return Response
     */
    public function updateAction(): Response
    {
        try {
            $storageId = (int)$this->request->attributes->get('id');
            $storage = $this->storageRepository->getById($storageId);

            $data = $this->getRequestData();

            $storage->setName($data['name'])
                ->setDescription($data['description'])
                ->setProjectId($data['project_id'])
                ->setAddressId($data['address_id']);

            $storage = $this->storageRepository->update($storage);
        } catch (StorageNotFoundException $e) {
            throw new HttpException(404, $e->getMessage());
        }

        $result = new ItemResult($storage->toArray());
        $result->setSuccess(true);

        return $this->sendResult($result);
    }

    /**
     * Get a single storage based on its id.
     *
     * @return Response
     */
    public function readAction(): Response
    {
        $id = $this->getResourceId();

        try {
            $storageData = $this->storageRepository->getById($id);
        } catch (StorageNotFoundException $e) {
            throw new HttpException(404, $e->getMessage());
        }

        $result = new ItemResult($storageData->toArray());
        $result->setSuccess(true);

        return $this->sendResult($result);
    }

    /**
     * Returns an API response containing a list of active storages.
     *
     * @example GET /v1/storage
     *
     * @return Response
     */
    public function listAction(): Response
    {
        $page = $this->getPaginationPage();
        $limit = $this->getPaginationCount();
        $offset = ($page - 1) * $limit;

        $items = $this->storageRepository->getList($offset, $limit);

        $data = [];
        /* @var Storage $item */
        foreach ($items as $item) {
            $data[] = $item->toArray();
        }

        $items_total = $this->storageRepository->getTotalCount();

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
     * Mark storage as deleted.
     *
     * In order to delete a storage completely, a lot of business logic needs
     * to checked beforehand to make sure that the deletion is safe. Doing these
     * checks would be difficult through the REST API, so it's much safer to just
     * mark the storage as deleted but leave the database entry otherwise intact.
     * This way any other tables are still able to access the storage data later.
     *
     * @throws Exception
     *
     * @return Response
     */
    public function deleteAction(): Response
    {
        $id = $this->getResourceId();

        try {
            $storage = $this->storageRepository->getById($id);
        } catch (StorageNotFoundException $e) {
            throw new HttpException(404, $e->getMessage());
        }

        $storage->setIsDeleted(true);
        $this->storageRepository->save($storage);

        $result = new ItemResult(['id' => $id]);
        $result->setSuccess(true);

        return $this->sendResult($result);
    }
}
