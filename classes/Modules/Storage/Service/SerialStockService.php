<?php

declare(strict_types=1);

namespace Xentral\Modules\Storage\Service;

use DateTimeInterface;
use Xentral\Components\Database\Database;
use Xentral\Modules\Storage\Data\SerialStock;
use Xentral\Modules\Storage\Data\SerialStockCollection;
use Xentral\Modules\Storage\Exception\InvalidArgumentException;
use Xentral\Modules\Storage\Exception\RuntimeException;
use Xentral\Modules\Storage\Exception\StockEntryNotFoundException;

final class SerialStockService
{
    /** @var Database $db */
    private $db;

    /** @var int $userAddressId */
    private $userAddressId;

    public function __construct(Database $db, int $userAddressId = 0)
    {
        $this->db = $db;
        $this->userAddressId = $userAddressId;
    }

    /**
     * @param int         $articleId
     * @param array|null  $storageLocationIds
     * @param string|null $serial
     *
     * @return SerialStockCollection
     */
    public function getSerialStockFromArticleAndStorageLocationIds(
        int $articleId,
        ?array $storageLocationIds,
        ?string $serial = null
    ): SerialStockCollection {
        if ($serial === null) {
            $serialStocks = $this->db->fetchAll(
                'SELECT * FROM `lager_seriennummern` 
                 WHERE `artikel` = :article_id AND `lager_platz` IN (:storage_location_ids)',
                [
                    'article_id'           => $articleId,
                    'storage_location_ids' => $storageLocationIds,
                ]
            );
        } elseif ($storageLocationIds !== null) {
            $serialStocks = $this->db->fetchAll(
                'SELECT * FROM `lager_seriennummern` 
                 WHERE `artikel` = :article_id AND `lager_platz` IN (:storage_location_ids) 
                   AND `seriennummer` = :serial',
                [
                    'article_id'           => $articleId,
                    'storage_location_ids' => $storageLocationIds,
                    'serial'               => $serial,
                ]
            );
        } else {
            $serialStocks = $this->db->fetchAll(
                'SELECT * FROM `lager_seriennummern` 
                 WHERE `artikel` = :article_id  
                   AND `seriennummer` = :serial',
                [
                    'article_id' => $articleId,
                    'serial'     => $serial,
                ]
            );
        }
        $serialStockCollection = new SerialStockCollection();
        foreach ($serialStocks as $serialStock) {
            $serialStockCollection->add(SerialStock::fromDbState($serialStock));
        }

        return $serialStockCollection;
    }

    /**
     * @param int $serialStockId
     *
     * @throws StockEntryNotFoundException
     *
     * @return SerialStock
     */
    public function getById(int $serialStockId): SerialStock
    {
        $serialStock = $this->db->fetchRow(
            'SELECT * FROM `lager_seriennummern` WHERE `id` = :id',
            ['id' => $serialStockId]
        );
        if (empty($serialStock)) {
            throw new StockEntryNotFoundException('db entry not found');
        }

        return SerialStock::fromDbState($serialStock);
    }

    /**
     * @param SerialStock $serialStock
     *
     * @throws InvalidArgumentException
     *
     * @return int
     */
    public function create(SerialStock $serialStock): int
    {
        if ($serialStock->getId() !== null) {
            throw new InvalidArgumentException('id must be null');
        }

        $this->db->perform(
            'INSERT INTO `lager_seriennummern` 
            (`artikel`, `lager_platz`, `zwischenlagerid`, `seriennummer`,
             `charge`, `mhddatum`, `internebemerkung`) 
             VALUES (:article_id, :storage_location_id, :interim_storage_id, :serial,
                     :batch, :best_before_date, :comment
             )',
            [
                'article_id'          => $serialStock->getArticleId(),
                'storage_location_id' => $serialStock->getStorageLocationId(),
                'interim_storage_id'  => $serialStock->getInterimStorageId(),
                'serial'              => $serialStock->getSerial(),
                'batch'               => $serialStock->getBatch(),
                'best_before_date'    => $serialStock->getBestBefore() === null ? '0000-00-00' :
                    $serialStock->getBestBefore()->format('Y-m-d'),
                'comment'             => $serialStock->getComment(),

            ]
        );

        return $this->db->lastInsertId();
    }

    /**
     * @param SerialStock $serialStock
     *
     * @throws InvalidArgumentException
     * @throws StockEntryNotFoundException
     */
    public function update(SerialStock $serialStock): void
    {
        if ($serialStock->getId() === null) {
            throw new InvalidArgumentException('id must be not null');
        }
        $this->getById($serialStock->getId());
        $this->db->perform(
            'UPDATE `lager_seriennummern`
            SET `artikel` = :article_id,
                `lager_platz` = :storage_location_id,
                `zwischenlagerid` = :interim_storage_id,
                `seriennummer` = :serial,
                `charge` = :batch,
                `mhddatum` = :best_before_date,
                `internebemerkung` = :comment
            WHERE `id` = :id',
            [
                'article_id'          => $serialStock->getArticleId(),
                'storage_location_id' => $serialStock->getStorageLocationId(),
                'interim_storage_id'  => $serialStock->getInterimStorageId(),
                'serial'              => $serialStock->getSerial(),
                'batch'               => $serialStock->getBatch(),
                'best_before_date'    => $serialStock->getBestBefore() === null ? '0000-00-00' :
                    $serialStock->getBestBefore()->format('Y-m-d'),
                'comment'             => $serialStock->getComment(),
                'id'                  => $serialStock->getId(),
            ]
        );
    }

    /**
     * @param SerialStock $serialStock
     *
     * @throws InvalidArgumentException
     * @throws StockEntryNotFoundException
     */
    public function delete(SerialStock $serialStock): void
    {
        if ($serialStock->getId() === null) {
            throw new InvalidArgumentException('id must be not null');
        }
        $this->getById($serialStock->getId());
        $this->db->perform(
            'DELETE FROM `lager_seriennummern` WHERE `id` = :id',
            [
                'id' => $serialStock->getId(),
            ]
        );
    }

    /**
     * @param SerialStock $serialStock
     * @param string      $comment
     * @param string      $documentType
     * @param int         $documentId
     * @param int         $addressId
     * @param int         $storageMovementId
     */
    public function stockOutBySerialStock(
        SerialStock $serialStock,
        string $comment = '',
        string $documentType = '',
        int $documentId = 0,
        int $addressId = 0,
        int $storageMovementId = 0
    ): void {
        $this->delete($serialStock);
        $this->log(
            $serialStock->getId(),
            $serialStock->getStorageLocationId(),
            false,
            $serialStock->getSerial(),
            $comment,
            $documentType,
            $documentId,
            $addressId,
            $serialStock->getBestBefore(),
            $serialStock->getBatch(),
            $storageMovementId
        );
    }

    /**
     * @param SerialStock $serialStock
     * @param string      $comment
     * @param string      $documentType
     * @param int         $documentId
     * @param int         $addressId
     * @param int         $storageMovementId
     *
     * @return int
     */
    public function stockIn(
        SerialStock $serialStock,
        string $comment = '',
        string $documentType = '',
        int $documentId = 0,
        int $addressId = 0,
        int $storageMovementId = 0
    ): int {
        $id = $this->create($serialStock);
        $this->log(
            $serialStock->getArticleId(),
            $serialStock->getStorageLocationId(),
            true,
            $serialStock->getSerial(),
            $comment,
            $documentType,
            $documentId,
            $addressId,
            $serialStock->getBestBefore(),
            $serialStock->getBatch(),
            $storageMovementId
        );

        return $id;
    }

    /**
     * @param int                    $articleId
     * @param int                    $storageLocationId
     * @param string                 $serial
     * @param string                 $comment
     * @param string                 $documentType
     * @param int                    $documentId
     * @param int                    $addressId
     * @param DateTimeInterface|null $bestBefore
     * @param string                 $batch
     * @param int                    $storageMovementId
     */
    public function stockOut(
        int $articleId,
        int $storageLocationId,
        string $serial,
        string $comment = '',
        string $documentType = '',
        int $documentId = 0,
        int $addressId = 0,
        ?DateTimeInterface $bestBefore = null,
        string $batch = '',
        int $storageMovementId = 0
    ): void {
        $collection = $this->getSerialStockFromArticleAndStorageLocationIds($articleId, [$storageLocationId], $serial);
        if (count($collection) === 0) {
            throw new RuntimeException("serial {$serial} not found");
        }
        $serialStocks = $collection->getSerialStocks();
        $serialStock = reset($serialStocks);
        $this->delete($serialStock);
        $this->log(
            $articleId,
            $storageLocationId,
            false,
            $serial,
            $comment,
            $documentType,
            $documentId,
            $addressId,
            $bestBefore,
            $batch,
            $storageMovementId
        );
    }

    /**
     * @param int                    $articleId
     * @param int                    $storageLocationId
     * @param bool                   $isStockIn
     * @param string                 $serial
     * @param string                 $comment
     * @param string                 $documentType
     * @param int                    $documentId
     * @param int                    $addressId
     * @param DateTimeInterface|null $bestBefore
     * @param string                 $batch
     * @param int                    $storageMovementId
     *
     * @return int
     */
    private function log(
        int $articleId,
        int $storageLocationId,
        bool $isStockIn,
        string $serial,
        string $comment = '',
        string $documentType = '',
        int $documentId = 0,
        int $addressId = 0,
        ?DateTimeInterface $bestBefore = null,
        string $batch = '',
        int $storageMovementId = 0
    ): int {
        $this->db->perform(
            'INSERT INTO `seriennummern_log` 
                (`artikel`, `lager_platz`, `eingang`, `bezeichnung`, 
                 `internebemerkung`, `zeit`, `adresse_mitarbeiter`, `adresse`, 
                 `menge`, `doctype`, `doctypeid`, `bestbeforedate`, `batch`,
                 `storage_movement_id`) 
                 VALUES (:article_id, :storage_location_id, :in_stock, :serial,
                 :comment, NOW(), :user_address_id, :address_id,
                         1, :document_type, :document_id, :best_before_date, :batch,
                         :storage_movement_id)',
            [
                'article_id'          => $articleId,
                'storage_location_id' => $storageLocationId,
                'in_stock'            => (int)$isStockIn,
                'serial'              => $serial,
                'batch'               => $batch,
                'best_before_date'    => $bestBefore === null ? '0000-00-00' : $bestBefore->format('Y-m-d'),
                'comment'             => $comment,
                'user_address_id'     => $this->userAddressId,
                'address_id'          => $addressId,
                'document_type'       => $documentType,
                'document_id'         => $documentId,
                'storage_movement_id' => $storageMovementId,
            ]
        );

        return $this->db->lastInsertId();
    }
}
