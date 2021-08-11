<?php

declare(strict_types=1);

namespace Xentral\Modules\Storage\Service;


use DateTime;
use Xentral\Components\Database\Database;
use Xentral\Modules\Storage\Data\ObjectStorageLink;
use Xentral\Modules\Storage\Data\ObjectStorageLinkCollection;
use Xentral\Modules\Storage\Exception\InvalidArgumentException;
use Xentral\Modules\Storage\Exception\RuntimeException;

class ObjectStorageLinkService
{
    /** @var Database $db */
    private $db;

    /**
     * ObjectStorageLinkService constructor.
     *
     * @param Database $db
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * @param string $documentType
     * @param int    $documentId
     *
     * @throws InvalidArgumentException
     *
     * @return ObjectStorageLinkCollection
     */
    public function getObjectStorageCollectionFromDocument(
        string $documentType,
        int $documentId
    ): ObjectStorageLinkCollection {
        $this->ensureDocumentType($documentType);
        $positionIds = $this->db->fetchCol(
            "SELECT `id` FROM `{$documentType}_position` WHERE `{$documentType}` = :document_id",
            ['document_id' => $documentId]
        );
        $positionIds = array_map('intval', $positionIds);

        $objectStrorageLinksArray = $this->db->fetchAll(
            'SELECT * FROM `objekt_lager_platz` WHERE `objekt` = :document_type AND `parameter` IN (:position_ids)',
            ['document_type' => $documentType, 'position_ids' => $positionIds]
        );
        $objectStrorageLinks = [];
        foreach ($objectStrorageLinksArray as $objectStrorageLinksRow) {
            $objectStrorageLinks[] = ObjectStorageLink::fromDbState($objectStrorageLinksRow);
        }

        return new ObjectStorageLinkCollection($objectStrorageLinks);
    }

    /**
     * @param string $documentType
     * @param int    $positionId
     *
     * @throws InvalidArgumentException
     *
     * @return ObjectStorageLinkCollection
     */
    public function getObjectStorageCollectionByPosition(
        string $documentType,
        int $positionId
    ): ObjectStorageLinkCollection {
        $this->ensureDocumentType($documentType);
        $objectStrorageLinksArray = $this->db->fetchAll(
            'SELECT * FROM `objekt_lager_platz` WHERE `objekt` = :document_type AND `parameter` = :position_id',
            ['document_type' => $documentType, 'position_id' => $positionId]
        );
        $objectStrorageLinks = [];
        foreach ($objectStrorageLinksArray as $objectStrorageLinksRow) {
            $objectStrorageLinks[] = ObjectStorageLink::fromDbState($objectStrorageLinksRow);
        }

        return new ObjectStorageLinkCollection($objectStrorageLinks);
    }

    /**
     * @param ObjectStorageLink $objectStrorageLink
     *
     * @return int
     */
    public function create(ObjectStorageLink $objectStrorageLink): int
    {
        if ($objectStrorageLink->getId() !== null) {
            throw new InvalidArgumentException('id must be null');
        }
        $createdAt = $objectStrorageLink->getCreatedAt();
        if ($createdAt === null) {
            $createdAt = new DateTime();
        }
        $this->db->perform(
            'INSERT INTO `objekt_lager_platz` 
            (`parameter`, `objekt`, `artikel`, `lager_platz`, 
             `menge`, `kommentar`, `bearbeiter`, `zeitstempel`) 
            VALUES (:position_id, :document_type, :article_id, :storage_location_id, 
                    :quantity, :comment, :employee, :created_at)',
            [
                'position_id'         => $objectStrorageLink->getPositionId(),
                'document_type'       => $objectStrorageLink->getDocumentType(),
                'article_id'          => $objectStrorageLink->getArticleId(),
                'storage_location_id' => $objectStrorageLink->getStorageLocationId(),
                'quantity'            => $objectStrorageLink->getQuantity(),
                'comment'             => $objectStrorageLink->getComment(),
                'employee'            => $objectStrorageLink->getEmployee(),
                'created_at'          => $createdAt->format('Y-m-d H:i:s'),
            ]
        );

        return $this->db->lastInsertId();
    }

    /**
     * @param ObjectStorageLink $objectStrorageLink
     */
    public function update(ObjectStorageLink $objectStrorageLink): void
    {
        if ($objectStrorageLink->getId() === null) {
            throw new InvalidArgumentException('id must be not null');
        }
        $createdAt = $objectStrorageLink->getCreatedAt();
        if ($createdAt === null) {
            $createdAt = new DateTime();
        }
        $this->db->perform(
            'UPDATE `objekt_lager_platz` 
            SET `parameter` = :position_id, 
                `objekt` = :document_type, 
                `artikel` = :article_id, 
                `lager_platz` = :storage_location_id, 
                `menge` = :quantity, 
                `kommentar` = :comment, 
                `bearbeiter` = :employee, 
                `zeitstempel` = :created_at 
            WHERE `id` = :id',
            [
                'position_id'         => $objectStrorageLink->getPositionId(),
                'document_type'       => $objectStrorageLink->getDocumentType(),
                'article_id'          => $objectStrorageLink->getArticleId(),
                'storage_location_id' => $objectStrorageLink->getStorageLocationId(),
                'quantity'            => $objectStrorageLink->getQuantity(),
                'comment'             => $objectStrorageLink->getComment(),
                'employee'            => $objectStrorageLink->getEmployee(),
                'created_at'          => $createdAt->format('Y-m-d H:i:s'),
                'id'                  => $objectStrorageLink->getId(),
            ]
        );
    }

    /**
     * @param int $objectStrorageLinkId
     *
     * @thows RuntimeException
     *
     * @return ObjectStorageLink
     */
    public function getFromId(int $objectStrorageLinkId): ObjectStorageLink
    {
        $entry = $this->db->fetchRow(
            'SELECT * FROM `objekt_lager_platz` WHERE `id` = :id',
            ['id' => $objectStrorageLinkId]
        );
        if (empty($entry)) {
            throw new RuntimeException("storageLink with id {$objectStrorageLinkId} not found");
        }

        return ObjectStorageLink::fromDbState($entry);
    }

    /**
     * @param ObjectStorageLink $objectStrorageLink
     *
     * @thows InvalidArgumentException
     */
    public function delete(ObjectStorageLink $objectStrorageLink): void
    {
        if ($objectStrorageLink->getId() === null) {
            throw new InvalidArgumentException('id must be not null');
        }
        $this->db->perform(
            'DELETE FROM `objekt_lager_platz` WHERE `id` = :id',
            ['id' => $objectStrorageLink->getId()]
        );
    }

    /**
     * @param string $documentType
     *
     * @throws InvalidArgumentException
     */
    private function ensureDocumentType(string $documentType): void
    {
        if ($documentType === '') {
            throw new InvalidArgumentException('documentType must be not empty');
        }
        if (strpos($documentType, '`') !== false) {
            throw new InvalidArgumentException("documentType {$documentType} is not valid");
        }
    }
}
