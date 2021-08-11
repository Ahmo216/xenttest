<?php

declare(strict_types=1);

namespace Xentral\Modules\Storage\Service;

use DateTime;
use Xentral\Components\Database\Database;
use Xentral\Modules\Storage\Data\BatchStockCollection;
use Xentral\Modules\Storage\Data\BatchStock;
use Xentral\Modules\Storage\Exception\InvalidArgumentException;
use Xentral\Modules\Storage\Exception\RuntimeException;
use Xentral\Modules\Storage\Exception\StockEntryNotFoundException;

final class BatchStockService
{
    /** @var Database $db */
    private $db;

    /** @var int $userAddressId */
    private $userAddressId;

    /**
     * BatchStockService constructor.
     *
     * @param Database $db
     * @param int      $userAddressId
     */
    public function __construct(Database $db, int $userAddressId = 0)
    {
        $this->db = $db;
        $this->userAddressId = $userAddressId;
    }

    /**
     * @param int         $articleId
     * @param array       $storageLocationIds
     * @param string|null $batch
     *
     * @return BatchStockCollection
     */
    public function getBatchStockFromArticleStorageLocationIds(
        int $articleId,
        array $storageLocationIds,
        ?string $batch = null
    ): BatchStockCollection {
        if ($batch === null) {
            $articleStocks = $this->db->fetchAll(
                'SELECT * 
                FROM `lager_charge` 
                WHERE `artikel` = :article_id AND `lager_platz` IN (:storage_location_ids)',
                ['article_id' => $articleId, 'storage_location_ids' => $storageLocationIds]
            );
        } else {
            $articleStocks = $this->db->fetchAll(
                'SELECT * 
                FROM `lager_charge` 
                WHERE `artikel` = :article_id AND `lager_platz` IN (:storage_location_ids) AND `charge` = :batch',
                ['article_id' => $articleId, 'storage_location_ids' => $storageLocationIds, 'batch' => $batch]
            );
        }
        $batchStockCollection = new BatchStockCollection();
        foreach ($articleStocks as $articleStock) {
            $batchStockCollection->add(BatchStock::fromDbState($articleStock));
        }

        return $batchStockCollection;
    }

    /**
     * @param int $id
     *
     * @throws StockEntryNotFoundException
     * @return BatchStock
     *
     */
    public function getById(int $id): BatchStock
    {
        $batchStock = $this->db->fetchRow(
            'SELECT * FROM `lager_charge` WHERE `id` = :id',
            ['id' => $id]
        );
        if (empty($batchStock)) {
            throw new StockEntryNotFoundException('db entry not found');
        }

        return BatchStock::fromDbState($batchStock);
    }

    /**
     * @param BatchStock $batchStock
     *
     * @throws InvalidArgumentException
     * @throws StockEntryNotFoundException
     */
    public function delete(BatchStock $batchStock): void
    {
        if ($batchStock->getId() === null) {
            throw new InvalidArgumentException('id must be not null');
        }
        $this->getById($batchStock->getId());
        $this->db->perform(
            'DELETE FROM `lager_charge` WHERE `id` = :id',
            ['id' => $batchStock->getId()]
        );
    }

    /**
     * @param BatchStock $batchStock
     *
     * @throws InvalidArgumentException
     *
     * @return int
     */
    public function create(BatchStock $batchStock): int
    {
        if ($batchStock->getId() !== null) {
            throw new InvalidArgumentException('id must be null');
        }
        $date = $batchStock->getCreatedAt();
        if ($date === null) {
            $date = new DateTime();
        }
        $this->db->perform(
            'INSERT INTO `lager_charge` 
            (`charge`, `menge`, `datum`, `artikel`, `lager_platz`, `zwischenlagerid`, `internebemerkung`) 
            VALUES (:batch, :quantity, :date, :article_id, :storage_location_id, :interim_storage_id, :comment)',
            [
                'quantity'            => $batchStock->getQuantity(),
                'article_id'          => $batchStock->getArticleId(),
                'storage_location_id' => $batchStock->getStorageLocationId(),
                'batch'               => $batchStock->getBatch(),
                'date'                => $date->format('Y-m-d'),
                'interim_storage_id'  => $batchStock->getInterimStorageId(),
                'comment'             => $batchStock->getComment(),
            ]
        );

        return $this->db->lastInsertId();
    }

    /**
     * @param BatchStock $batchStock
     *
     * @throws InvalidArgumentException
     * @throws StockEntryNotFoundException
     */
    public function update(BatchStock $batchStock): void
    {
        if ($batchStock->getId() === null) {
            throw new InvalidArgumentException('id must be not null');
        }
        $this->getById($batchStock->getId());
        $date = $batchStock->getCreatedAt();
        if ($date === null) {
            $date = new DateTime();
        }
        $this->db->perform(
            'UPDATE `lager_charge` 
            SET `menge` = :quantity,
                `artikel` = :article_id,
                `charge` = :batch,
                `datum` = :date,
                `lager_platz` = :storage_location_id,
                `zwischenlagerid` = :interim_storage_id,
                `internebemerkung` = :comment
            WHERE `id` = :id',
            [
                'quantity'            => $batchStock->getQuantity(),
                'article_id'          => $batchStock->getArticleId(),
                'storage_location_id' => $batchStock->getStorageLocationId(),
                'batch'               => $batchStock->getBatch(),
                'date'                => $date->format('Y-m-d'),
                'interim_storage_id'  => $batchStock->getInterimStorageId(),
                'comment'             => $batchStock->getComment(),
                'id'                  => $batchStock->getId(),
            ]
        );
        $this->db->perform(
            'DELETE FROM `lager_charge` WHERE `id` = :id AND `menge` <= 0',
            ['id' => $batchStock->getId()]
        );
    }

    /**
     * @param int    $articleId
     * @param array  $storageLocationIds
     * @param float  $quantity
     * @param string $batch
     * @param string $comment
     * @param string $documentType
     * @param int    $documentId
     * @param int    $addressId
     * @param bool   $isInterim
     * @param int    $movementId
     *
     * @throws RuntimeException
     * @throws InvalidArgumentException
     *
     * @return array
     */
    public function stockOut(
        int $articleId,
        array $storageLocationIds,
        float $quantity,
        string $batch,
        string $comment = '',
        string $documentType = '',
        int $documentId = 0,
        int $addressId = 0,
        bool $isInterim = false,
        int $movementId = 0
    ): array {
        if ($quantity <= 0) {
            throw new InvalidArgumentException('quantity must be greater 0');
        }
        $collection = $this->getBatchStockFromArticleStorageLocationIds(
            $articleId,
            $storageLocationIds,
            $batch
        );
        if ($collection->getQuantitySum() < $quantity) {
            throw new RuntimeException('not enough in stock');
        }
        $quantityLeft = $quantity;
        $quantities = [];
        foreach ($collection as $batchStock) {
            if ($quantityLeft <= 0) {
                break;
            }
            if ($quantityLeft >= $batchStock->getQuantity()) {
                $this->delete($batchStock);
                $quantityLeft -= $batchStock->getQuantity();
                if (!isset($quantities[$batchStock->getStorageLocationId()])) {
                    $quantities[$batchStock->getStorageLocationId()] = 0;
                }
                $quantities[$batchStock->getStorageLocationId()] += $batchStock->getQuantity();
                continue;
            }
            if ($quantityLeft < $batchStock->getQuantity()) {
                $batchStock->setQuantity($batchStock->getQuantity() - $quantityLeft);
                if (!isset($quantities[$batchStock->getStorageLocationId()])) {
                    $quantities[$batchStock->getStorageLocationId()] = 0;
                }
                $quantities[$batchStock->getStorageLocationId()] += $quantityLeft;
                $this->update($batchStock);
                break;
            }
        }
        foreach ($quantities as $storageLocationId => $quantityInStorageLocation) {
            $this->log(
                $articleId,
                $storageLocationId,
                false,
                $batch,
                $quantityInStorageLocation,
                $comment,
                $documentType,
                $documentId,
                $addressId,
                $isInterim,
                $movementId
            );
        }

        return $quantities;
    }

    /**
     * @param int    $articleId
     * @param int    $storageLocationId
     * @param bool   $isStockIn
     * @param string $batch
     * @param float  $quantity
     * @param string $comment
     * @param string $documentType
     * @param int    $documentId
     * @param int    $addressId
     * @param bool   $isInterim
     * @param int    $storageMovementId
     *
     * @return int
     */
    private function log(
        int $articleId,
        int $storageLocationId,
        bool $isStockIn,
        string $batch,
        float $quantity,
        string $comment = '',
        string $documentType = '',
        int $documentId = 0,
        int $addressId = 0,
        bool $isInterim = false,
        int $storageMovementId = 0
    ): int {
        $collection = $this->getBatchStockFromArticleStorageLocationIds($articleId, [$storageLocationId], $batch);
        $stock = $collection->getQuantitySum();
        if ($addressId <= 0) {
            $addressId = $this->getAddressIdFromDocument($documentType, $documentId);
        }
        $this->db->perform(
            'INSERT INTO `chargen_log` 
              (`artikel`, `lager_platz`, `eingang`, `bezeichnung`, `internebemerkung`, 
             `zeit`, `adresse_mitarbeiter`, `adresse`, `menge`, `doctype`, `doctypeid`,
             `bestand`, `is_interim`, `storage_movement_id`) 
             VALUES (:article_id, :storage_location_id, :in_stock, :batch, :comment,
                NOW(), :user_address_id, :address_id, :quantity, :document_type, :document_id,
                :stock, :is_interim, :storage_movement_id) ',
            [
                'article_id'          => $articleId,
                'storage_location_id' => $storageLocationId,
                'in_stock'            => (int)$isStockIn,
                'batch'               => $batch,
                'comment'             => $comment,
                'user_address_id'     => $this->userAddressId,
                'address_id'          => $addressId,
                'quantity'            => $quantity,
                'document_type'       => $documentType,
                'document_id'         => $documentId,
                'stock'               => $stock,
                'is_interim'          => (int)$isInterim,
                'storage_movement_id' => $storageMovementId,
            ]
        );

        return $this->db->lastInsertId();
    }

    /**
     * @param string $document
     * @param int    $documentId
     *
     * @return int
     */
    private function getAddressIdFromDocument(string $document, int $documentId): int
    {
        if (empty($document) || $documentId <= 0) {
            return 0;
        }

        return (int)$this->db->fetchValue(
            "SELECT `adresse` FROM `{$document}` WHERE `id` = :id",
            [
                'id' => $documentId,
            ]
        );
    }
}
