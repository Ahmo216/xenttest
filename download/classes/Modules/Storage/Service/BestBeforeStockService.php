<?php

declare(strict_types=1);

namespace Xentral\Modules\Storage\Service;

use DateTime;
use DateTimeInterface;
use Xentral\Components\Database\Database;
use Xentral\Modules\Storage\Data\BestBeforeStockCollection;
use Xentral\Modules\Storage\Data\BestBeforeStock;
use Xentral\Modules\Storage\Exception\InvalidArgumentException;
use Xentral\Modules\Storage\Exception\RuntimeException;
use Xentral\Modules\Storage\Exception\StockEntryNotFoundException;

final class BestBeforeStockService
{
    /** @var Database $db */
    private $db;

    /** @var int $userAddressId */
    private $userAddressId;

    /**
     * BestBeforeStockService constructor.
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
     * @param int                    $articleId
     * @param array                  $storageLocationIds
     * @param DateTimeInterface|null $bestBeforeDate
     * @param string|null            $batch
     *
     * @return BestBeforeStockCollection
     */
    public function getBestBeforeStockFromArticleStorageLocationIds(
        int $articleId,
        array $storageLocationIds,
        ?DateTimeInterface $bestBeforeDate = null,
        ?string $batch = null
    ): BestBeforeStockCollection {
        if ($bestBeforeDate === null) {
            $articleStocks = $this->db->fetchAll(
                'SELECT * 
                FROM `lager_mindesthaltbarkeitsdatum` 
                WHERE `artikel` = :article_id AND `lager_platz` IN (:storage_location_ids)',
                [
                    'article_id'           => $articleId,
                    'storage_location_ids' => $storageLocationIds,
                ]
            );
        } elseif ($batch === null) {
            $articleStocks = $this->db->fetchAll(
                'SELECT * 
                FROM `lager_mindesthaltbarkeitsdatum` 
                WHERE `artikel` = :article_id 
                  AND `lager_platz` IN (:storage_location_ids) 
                  AND `mhddatum` = :best_before_date',
                [
                    'article_id'           => $articleId,
                    'storage_location_ids' => $storageLocationIds,
                    'best_before_date'     => $bestBeforeDate->format('Y-m-d'),
                ]
            );
        } else {
            $articleStocks = $this->db->fetchAll(
                'SELECT * 
                FROM `lager_mindesthaltbarkeitsdatum` 
                WHERE `artikel` = :article_id 
                  AND `lager_platz` IN (:storage_location_ids) 
                  AND `mhddatum` = :best_before_date
                  AND `charge` = :batch',
                [
                    'article_id'           => $articleId,
                    'storage_location_ids' => $storageLocationIds,
                    'best_before_date'     => $bestBeforeDate->format('Y-m-d'),
                    'batch'                => $batch,
                ]
            );
        }

        $bestBeforeStockCollection = new BestBeforeStockCollection();
        foreach ($articleStocks as $articleStock) {
            $bestBeforeStockCollection->add(BestBeforeStock::fromDbState($articleStock));
        }

        return $bestBeforeStockCollection;
    }

    /**
     * @param int $id
     *
     * @return BestBeforeStock
     *
     * @thows StockEntryNotFoundException
     */
    public function getById(int $id): BestBeforeStock
    {
        $bestBeforeStock = $this->db->fetchRow(
            'SELECT * FROM `lager_mindesthaltbarkeitsdatum` WHERE `id` = :id',
            ['id' => $id]
        );
        if (empty($bestBeforeStock)) {
            throw new StockEntryNotFoundException('db entry not found');
        }

        return BestBeforeStock::fromDbState($bestBeforeStock);
    }

    /**
     * @param BestBeforeStock $bestBeforeStock
     *
     * @throws InvalidArgumentException
     *
     * @return int
     */
    public function create(BestBeforeStock $bestBeforeStock)
    {
        if ($bestBeforeStock->getId() !== null) {
            throw new InvalidArgumentException('id must be null');
        }
        $date = $bestBeforeStock->getCreatedAt();
        if ($date === null) {
            $date = new DateTime();
        }
        $bestBeforeDate = $bestBeforeStock->getBestBeforeDate();
        $this->db->perform(
            'INSERT INTO `lager_mindesthaltbarkeitsdatum`
        (`datum`, `mhddatum`, `artikel`, `lager_platz`, `zwischenlagerid`, 
         `charge`, `internebemerkung`, `menge`) 
        VALUES (:date, :best_before_date, :article_id, :storage_location_id, :interim_storage_id,
                :batch, :comment, :quantity)',
            [
                'quantity'            => $bestBeforeStock->getQuantity(),
                'article_id'          => $bestBeforeStock->getArticleId(),
                'storage_location_id' => $bestBeforeStock->getStorageLocationId(),
                'batch'               => $bestBeforeStock->getBatch(),
                'date'                => $date->format('Y-m-d'),
                'interim_storage_id'  => $bestBeforeStock->getInterimStorageId(),
                'comment'             => $bestBeforeStock->getComment(),
                'best_before_date'    => $bestBeforeDate === null ? '0000-00-00' : $bestBeforeDate->format('Y-m-d'),
            ]
        );

        return $this->db->lastInsertId();
    }

    /**
     * @param BestBeforeStock $bestBeforeStock
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function delete(BestBeforeStock $bestBeforeStock): void
    {
        if ($bestBeforeStock->getId() === null) {
            throw new InvalidArgumentException('id must be not null');
        }
        $this->getById($bestBeforeStock->getId());
        $this->db->perform(
            'DELETE FROM `lager_mindesthaltbarkeitsdatum` WHERE `id` = :id',
            ['id' => $bestBeforeStock->getId()]
        );
    }

    /**
     * @param BestBeforeStock $bestBeforeStock
     *
     * @throws InvalidArgumentException
     * @throws StockEntryNotFoundException
     */
    public function update(BestBeforeStock $bestBeforeStock): void
    {
        if ($bestBeforeStock->getId() === null) {
            throw new InvalidArgumentException('id must be not null');
        }
        $this->getById($bestBeforeStock->getId());
        $date = $bestBeforeStock->getCreatedAt();
        if ($date === null) {
            $date = new DateTime();
        }
        $bestBeforeDate = $bestBeforeStock->getBestBeforeDate();

        $this->db->perform(
            'UPDATE `lager_mindesthaltbarkeitsdatum` 
            SET `menge` = :quantity,
                `artikel` = :article_id,
                `charge` = :batch,
                `datum` = :date,
                `lager_platz` = :storage_location_id,
                `zwischenlagerid` = :interim_storage_id,
                `internebemerkung` = :comment,
                `mhddatum` = :best_before_Date
            WHERE `id` = :id',
            [
                'quantity'            => $bestBeforeStock->getQuantity(),
                'article_id'          => $bestBeforeStock->getArticleId(),
                'storage_location_id' => $bestBeforeStock->getStorageLocationId(),
                'batch'               => $bestBeforeStock->getBatch(),
                'date'                => $date->format('Y-m-d'),
                'interim_storage_id'  => $bestBeforeStock->getInterimStorageId(),
                'comment'             => $bestBeforeStock->getComment(),
                'id'                  => $bestBeforeStock->getId(),
                'best_before_Date'    => $bestBeforeDate === null ? '0000-00-00' : $bestBeforeDate->format('Y-m-d'),
            ]
        );

        $this->db->perform(
            'DELETE FROM `lager_mindesthaltbarkeitsdatum` WHERE `id` = :id AND `menge` <= 0',
            ['id' => $bestBeforeStock->getId()]
        );
    }

    /**
     * @param int                    $articleId
     * @param array                  $storageLocationIds
     * @param DateTimeInterface|null $bestBefore
     * @param float                  $quantity
     * @param string|null            $batch
     * @param string                 $comment
     * @param string                 $documentType
     * @param int                    $documentId
     * @param int                    $addressId
     * @param bool                   $isInterim
     * @param int                    $movementId
     *
     * @throws RuntimeException
     * @throws InvalidArgumentException
     *
     * @return array
     */
    public function stockOut(
        int $articleId,
        array $storageLocationIds,
        ?DateTimeInterface $bestBefore,
        float $quantity,
        ?string $batch = null,
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
        $collection = $this->getBestBeforeStockFromArticleStorageLocationIds(
            $articleId,
            $storageLocationIds,
            $bestBefore,
            $batch
        );
        if ($collection->getQuantitySum() < $quantity) {
            throw new RuntimeException('not enough in stock');
        }
        $quantityLeft = $quantity;
        $quantities = [];
        foreach ($collection as $bestBeforeStock) {
            if ($quantityLeft <= 0) {
                break;
            }
            if ($quantityLeft >= $bestBeforeStock->getQuantity()) {
                $this->delete($bestBeforeStock);
                $quantityLeft -= $bestBeforeStock->getQuantity();
                if (!isset($quantities[$bestBeforeStock->getStorageLocationId()])) {
                    $quantities[$bestBeforeStock->getStorageLocationId()] = 0;
                }
                $quantities[$bestBeforeStock->getStorageLocationId()] += $bestBeforeStock->getQuantity();
                continue;
            }
            if ($quantityLeft <= $bestBeforeStock->getQuantity()) {
                $bestBeforeStock->setQuantity($bestBeforeStock->getQuantity() - $quantityLeft);
                $this->update($bestBeforeStock);
                if (!isset($quantities[$bestBeforeStock->getStorageLocationId()])) {
                    $quantities[$bestBeforeStock->getStorageLocationId()] = 0;
                }
                $quantities[$bestBeforeStock->getStorageLocationId()] += $quantityLeft;
                break;
            }
        }
        foreach ($quantities as $storageLocationId => $quantityInStorageLocation) {
            $this->log(
                $articleId,
                $storageLocationId,
                false,
                $bestBefore,
                $quantityInStorageLocation,
                (string)$batch,
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

    private function log(
        int $articleId,
        int $storageLocationId,
        bool $isStockIn,
        ?DateTimeInterface $bestBefore,
        float $quantity,
        string $batch = '',
        string $comment = '',
        string $documentType = '',
        int $documentId = 0,
        int $addressId = 0,
        bool $isInterim = false,
        int $storageMovementId = 0
    ): int {
        $stock = $this->getBestBeforeStockFromArticleStorageLocationIds(
            $articleId,
            [$storageLocationId],
            $bestBefore,
            $batch
        )->getQuantitySum();
        if ($addressId <= 0) {
            $addressId = $this->getAddressIdFromDocument($documentType, $documentId);
        }
        $this->db->perform(
            'INSERT INTO `mhd_log` 
            (`artikel`, `lager_platz`, `eingang`, `mhddatum`, `internebemerkung`, 
             `zeit`, `adresse_mitarbeiter`, `adresse`, `menge`, `doctype`, `doctypeid`, 
             `bestand`, `charge`, `is_interim`, `storage_movement_id`) 
             VALUES (:article_id, :storage_location_id, :in_stock, :best_before, :comment,
                NOW(), :user_address_id, :address_id, :quantity, :document_type, :document_id,
                :stock, :batch, :is_interim, :storage_movement_id)',
            [
                'article_id'          => $articleId,
                'storage_location_id' => $storageLocationId,
                'in_stock'            => (int)$isStockIn,
                'best_before'         => $bestBefore === null ? '0000-00-00 00:00:00' : $bestBefore->format(
                    'Y-m-d H:i:s'
                ),
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
