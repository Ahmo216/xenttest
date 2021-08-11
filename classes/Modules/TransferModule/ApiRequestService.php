<?php

declare(strict_types=1);

namespace Xentral\Modules\TransferModule;


use Xentral\Components\Database\Database;
use Xentral\Modules\Label\LabelGateway;
use Xentral\Modules\Label\LabelService;
use Xentral\Modules\TransferModule\Data\ApiRequest;
use Xentral\Modules\TransferModule\Data\ApiRequestCollection;

final class ApiRequestService
{
    /** @var Database $db */
    private $db;

    /** @var LabelGateway $labelGateWay */
    private $labelGateWay;

    /** @var LabelService $labelService */
    private $labelService;

    /** @var string[] */
    private const ALLOWED_TYPES_FOR_LABELS = [
        'auftrag',
        'lieferschein',
        'rechnung',
        'gutschrift',
        'angebot',
        'retoure',
        'verbindlichkeit',
        'bestellung',
        'artikel',
    ];

    /**
     * ApiRequestService constructor.
     *
     * @param Database     $db
     * @param LabelGateway $labelGateway
     * @param LabelService $labelService
     */
    public function __construct(Database $db, LabelGateway $labelGateway, LabelService $labelService)
    {
        $this->db = $db;
        $this->labelGateWay = $labelGateway;
        $this->labelService = $labelService;
    }

    /**
     * @param ApiRequest $apiRequest
     *
     * @return int
     */
    public function create(ApiRequest $apiRequest): int
    {
        $this->db->perform(
            'INSERT INTO `api_request`
            (`api`, `uebertragung_account`, `status`, `prio`, `zeitstempel`, `typ`, `parameter1`, 
             `parameter1int`, `parameter2`, `anzeige`, `projekt`, `uebertragen`, `geloescht`, 
             `datei`, `uebertragen_am`, `anzahl_uebertragen`)
             VALUE (:api, :uebertragung_account, :status, :prio, NOW(), :typ, :parameter1,
                   :parameter1int, :parameter2, :anzeige, :projekt, :uebertragen, :geloescht, 
             :datei, :uebertragen_am, :anzahl_uebertragen)',
            $apiRequest->toArray()
        );

        return $this->db->lastInsertId();
    }

    /**
     * @param int $apiRequestId
     *
     * @return ApiRequest|null
     */
    public function getById(int $apiRequestId): ?ApiRequest
    {
        $dbState = $this->db->fetchRow('SELECT * FROM `api_request` WHERE `id` = :id', ['id' => $apiRequestId]);
        if (empty($dbState)) {
            return null;
        }

        return ApiRequest::fromDbState($dbState);
    }

    /**
     * @param ApiRequest $apiRequest
     *
     * @return bool
     */
    public function update(ApiRequest $apiRequest): bool
    {
        return $this->db->fetchAffected(
                "UPDATE `api_request` 
                SET `api` = :api, 
                    `uebertragung_account` = :uebertragung_account, 
                    `status` = :status, 
                    `prio` = :prio,
                    `zeitstempel` = :zeitstempel,
                    `typ` = :typ,
                    `parameter1` = :parameter1, 
                    `parameter1int` =:parameter1int,
                    `parameter2` = :parameter2,
                    `anzeige` = :anzeige,
                    `projekt` = :projekt,
                    `uebertragen` = :uebertragen, 
                    `geloescht` = :geloescht, 
                    `datei` = :datei,
                    `uebertragen_am` = :uebertragen_am,
                    `anzahl_uebertragen` = :anzahl_uebertragen
                WHERE `id` = :id",
                $apiRequest->toArray()
            ) > 0;
    }

    /**
     * @param ApiRequestCollection $apiRequestCollection
     *
     * @return int
     */
    public function updateCollection(ApiRequestCollection $apiRequestCollection): int
    {
        $affected = 0;
        foreach ($apiRequestCollection as $apiRequest) {
            if ($this->update($apiRequest)) {
                $affected++;
            }
        }

        return $affected;
    }

    /**
     * @param int $apiRequestId
     */
    public function delete(int $apiRequestId): void
    {
        $this->db->perform('DELETE FROM `api_request` WHERE `id` = :id', ['id' => $apiRequestId]);
    }

    /**
     * @param ApiRequestCollection $apiRequests
     *
     * @return int
     */
    public function deleteCollection(ApiRequestCollection $apiRequests): int
    {
        if (count($apiRequests) === 0) {
            return 0;
        }
        $idsToDelete = [];
        /** @var ApiRequest $apiRequest */
        foreach ($apiRequests as $apiRequest) {
            $idsToDelete[] = $apiRequest->getId();
        }

        return $this->db->fetchAffected('DELETE FROM `api_request` WHERE `id` IN (:ids)', ['ids' => $idsToDelete]);
    }

    /**
     * @param int    $transferAccountId
     * @param string $status
     *
     * @return ApiRequestCollection
     */
    public function getByStatusAndTransferModule(int $transferAccountId, string $status): ApiRequestCollection
    {
        $dbState = $this->db->fetchAll(
            'SELECT * FROM `api_request` WHERE `uebertragung_account` = :transfer_account_id AND `status` = :status',
            [
                'transfer_account_id' => $transferAccountId,
                'status'              => $status,
            ]
        );

        return ApiRequestCollection::fromDbState($dbState);
    }

    /**
     * @param array $apiRequestIds
     *
     * @return ApiRequestCollection
     */
    public function getByIds(array $apiRequestIds): ApiRequestCollection
    {
        $dbState = $this->db->fetchAll('SELECT * FROM `api_request` WHERE `id` IN (:ids)', ['ids' => $apiRequestIds]);

        return ApiRequestCollection::fromDbState($dbState);
    }

    /**
     * @param ApiRequestCollection $apiRequests
     * @param array                $labels
     */
    public function removeLabels(ApiRequestCollection $apiRequests, array $labels): void
    {
        if (count($apiRequests) === 0 || count($labels) === 0) {
            return;
        }
        $labesByType = $this->getLabelsByType($apiRequests);
        foreach ($labesByType as $type => $requests) {
            foreach ($requests as $documentId => $labelsInDb) {
                $lablesToRemove = array_intersect($labels, $labelsInDb);
                foreach ($lablesToRemove as $label) {
                    $this->labelService->unassignLabel($type, $documentId, $label);
                }
            }
        }
    }

    /**
     * @param ApiRequestCollection $apiRequests
     * @param array                $labels
     */
    public function assignLabels(ApiRequestCollection $apiRequests, array $labels): void
    {
        if (count($apiRequests) === 0 || count($labels) === 0) {
            return;
        }
        $labesByType = $this->getLabelsByType($apiRequests);
        foreach ($labesByType as $type => $requests) {
            foreach ($requests as $documentId => $labelsInDb) {
                $lablesToAdd = array_diff($labels, $labelsInDb);
                foreach ($lablesToAdd as $label) {
                    $this->labelService->assignLabel($type, $documentId, $label);
                }
            }
        }
    }

    /**
     * @param ApiRequestCollection $apiRequests
     *
     * @return array
     */
    private function getLabelsByType(ApiRequestCollection $apiRequests): array
    {
        if (count($apiRequests) === 0) {
            return [];
        }
        $types = array_intersect($apiRequests->getApiRequestTypes(), self::ALLOWED_TYPES_FOR_LABELS);
        if (empty($types)) {
            return [];
        }
        $labelsByType = [];
        foreach ($types as $type) {
            $requestsByType = $apiRequests->filterByType($type);
            /** @var ApiRequest $apiRequest */
            foreach ($requestsByType as $apiRequest) {
                $documentId = $apiRequest->getParameter1Int();
                $labelsByType[$type][$documentId] = array_map(
                    static function ($val) {
                        return $val['type'];
                    },
                    $this->labelGateWay->findLabelsByReferences($type, [$documentId])
                );
            }
        }

        return $labelsByType;
    }
}
