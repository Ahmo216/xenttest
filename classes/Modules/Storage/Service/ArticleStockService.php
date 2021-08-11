<?php

declare(strict_types=1);

namespace Xentral\Modules\Storage\Service;

use DateTime;
use Xentral\Components\Database\Database;
use Xentral\Modules\Storage\Data\ArticleStock;
use Xentral\Modules\Storage\Data\ArticleStockCollection;
use Xentral\Modules\Storage\Exception\InvalidArgumentException;
use Xentral\Modules\Storage\Exception\RuntimeException;
use Xentral\Modules\Storage\Exception\StockEntryNotFoundException;

final class ArticleStockService
{
    /** @var Database $db */
    private $db;

    /** @var string $userName */
    private $userName = '';

    /**
     * StockService constructor.
     *
     * @param Database $db
     * @param string   $userName
     */
    public function __construct(Database $db, string $userName = '')
    {
        $this->db = $db;
        $this->userName = $userName;
    }

    /**
     * @param int $articleId
     * @param int $storageLocationId
     *
     * @return ArticleStockCollection
     */
    public function getStocksFromArticleStorageLocationId(
        int $articleId,
        int $storageLocationId
    ): ArticleStockCollection {
        $articleStocks = $this->db->fetchAll(
            'SELECT * 
            FROM `lager_platz_inhalt` 
            WHERE `artikel` = :article_id AND `lager_platz` = :storage_location_id',
            ['article_id' => $articleId, 'storage_location_id' => $storageLocationId]
        );
        $articleStockCollection = new ArticleStockCollection();
        foreach ($articleStocks as $articleStock) {
            $articleStockCollection->add(ArticleStock::fromDbState($articleStock));
        }

        return $articleStockCollection;
    }

    /**
     * @param int $articleStockId
     *
     * @throws StockEntryNotFoundException
     *
     * @return ArticleStock
     */
    public function getById(int $articleStockId): ArticleStock
    {
        $articleStock = $this->db->fetchRow(
            'SELECT * FROM `lager_platz_inhalt` WHERE `id`= :id',
            ['id' => $articleStockId]
        );
        if (empty($articleStock)) {
            throw new StockEntryNotFoundException('articleStock not found');
        }

        return ArticleStock::fromDbState($articleStock);
    }

    /**
     * @param ArticleStock $articleStock
     *
     * @throws InvalidArgumentException
     *
     * @return int
     */
    public function create(ArticleStock $articleStock): int
    {
        if ($articleStock->getId() !== null) {
            throw new InvalidArgumentException('id must be null');
        }
        $createdAt = $articleStock->getCreatedAt();
        if ($createdAt === null) {
            $createdAt = new DateTime();
        }
        $this->db->perform(
            'INSERT INTO `lager_platz_inhalt` 
            (`lager_platz`, `artikel`, `menge`, `vpe`, `bearbeiter`, `bestellung`, 
             `projekt`, `firma`, `logdatei`, `inventur`, `lager_platz_vpe`)
             VALUES (:storage_location_id, :article_id, :quantity, :vpe, :employee, :supplier_order_id,
             :project_id, 1, :date, :inventory_stock, :vpe_stock_id
             )',
            [
                'storage_location_id' => $articleStock->getStorageLocationId(),
                'article_id'          => $articleStock->getArticleId(),
                'quantity'            => $articleStock->getQuantity(),
                'vpe'                 => $articleStock->getVpe(),
                'employee'            => $articleStock->getEmployee(),
                'supplier_order_id'   => $articleStock->getSupplierOrderId(),
                'project_id'          => $articleStock->getProjectId(),
                'date'                => $createdAt->format('Y-m-d H:i:s'),
                'inventory_stock'     => $articleStock->getInventoryStock(),
                'vpe_stock_id'        => $articleStock->getVpeStockId(),
            ]
        );

        return $this->db->lastInsertId();
    }

    /**
     * @param ArticleStock $articleStock
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function update(ArticleStock $articleStock): void
    {
        if ($articleStock->getId() === null) {
            throw new InvalidArgumentException('id must be not null');
        }
        $createdAt = $articleStock->getCreatedAt();
        if ($createdAt === null) {
            $createdAt = new DateTime();
        }
        $this->getById($articleStock->getId());
        $this->db->perform(
            'UPDATE `lager_platz_inhalt` 
            SET `lager_platz` = :storage_location_id,
                `artikel` = :article_id,
                `menge` = :quantity,
                `vpe` = :vpe,
                `bearbeiter` = :employee,
                `bestellung` = :supplier_order_id,
                `projekt` = :project_id,
                `inventur` = :inventory_stock,
                `lager_platz_vpe` = :vpe_stock_id
            WHERE `id` = :id',
            [
                'storage_location_id' => $articleStock->getStorageLocationId(),
                'article_id'          => $articleStock->getArticleId(),
                'quantity'            => $articleStock->getQuantity(),
                'vpe'                 => $articleStock->getVpe(),
                'employee'            => $articleStock->getEmployee(),
                'supplier_order_id'   => $articleStock->getSupplierOrderId(),
                'project_id'          => $articleStock->getProjectId(),
                'date'                => $createdAt->format('Y-m-d H:i:s'),
                'inventory_stock'     => $articleStock->getInventoryStock(),
                'vpe_stock_id'        => $articleStock->getVpeStockId(),
                'id'                  => $articleStock->getId(),
            ]
        );
    }

    /**
     * @param ArticleStock $articleStock
     *
     * @throws InvalidArgumentException
     */
    public function delete(ArticleStock $articleStock): void
    {
        if ($articleStock->getId() === null) {
            throw new InvalidArgumentException('id must be not null');
        }
        $this->db->perform(
            'DELETE FROM `lager_platz_inhalt` WHERE `id` = :id',
            ['id' => $articleStock->getId()]
        );
    }

    /**
     * @param int       $articleId
     * @param int       $storageLocationId
     * @param float     $quantity
     * @param string    $comment
     * @param string    $documentType
     * @param int       $documentId
     * @param int       $addressId
     * @param bool      $isInterim
     * @param int       $storageMovementId
     * @param string    $vpe
     * @param int       $projectId
     * @param float|int $inventoryStock
     * @param int       $parcelId
     * @param int       $vpeId
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function stockOut(
        int $articleId,
        int $storageLocationId,
        float $quantity,
        string $comment = '',
        string $documentType = '',
        int $documentId = 0,
        int $addressId = 0,
        bool $isInterim = false,
        int $storageMovementId = 0,
        string $vpe = '',
        int $projectId = 0,
        float $inventoryStock = 0,
        int $parcelId = 0,
        int $vpeId = 0
    ): void {
        if ($quantity <= 0) {
            throw new InvalidArgumentException('quantity must be greater 0');
        }
        $collection = $this->getStocksFromArticleStorageLocationId(
            $articleId,
            $storageLocationId
        );
        if ($collection->getQuantitySum() < $quantity) {
            throw new RuntimeException('not enough in stock');
        }
        $quantityLeft = $quantity;

        foreach ($collection as $articleStock) {
            if ($quantityLeft <= 0) {
                break;
            }
            if ($quantityLeft >= $articleStock->getQuantity()) {
                $this->delete($articleStock);
                $quantityLeft -= $articleStock->getQuantity();
                continue;
            }
            if ($quantityLeft <= $articleStock->getQuantity()) {
                $articleStock->setQuantity($articleStock->getQuantity() - $quantityLeft);
                $this->update($articleStock);
                break;
            }
        }
        $this->log(
            $articleId,
            $storageLocationId,
            false,
            $quantity,
            $comment,
            $documentType,
            $documentId,
            $addressId,
            $isInterim,
            $storageMovementId,
            $vpe,
            $projectId,
            $inventoryStock,
            $parcelId,
            $vpeId
        );
    }

    /**
     * @param int       $articleId
     * @param int       $storageLocationId
     * @param bool      $isStockIn
     * @param float     $quantity
     * @param string    $comment
     * @param string    $documentType
     * @param int       $documentId
     * @param int       $addressId
     * @param bool      $isInterim
     * @param int       $storageMovementId
     * @param string    $vpe
     * @param int       $projectId
     * @param float|int $inventoryStock
     * @param int       $parcelId
     * @param int       $vpeId
     *
     * @return int
     */
    private function log(
        int $articleId,
        int $storageLocationId,
        bool $isStockIn,
        float $quantity,
        string $comment = '',
        string $documentType = '',
        int $documentId = 0,
        int $addressId = 0,
        bool $isInterim = false,
        int $storageMovementId = 0,
        string $vpe = '',
        int $projectId = 0,
        float $inventoryStock = 0,
        int $parcelId = 0,
        int $vpeId = 0
    ): int {
        $stock = $this->getStocksFromArticleStorageLocationId($articleId, $storageLocationId)->getQuantitySum();
        if ($addressId <= 0) {
            $addressId = $this->getAddressIdFromDocument($documentType, $documentId);
        }
        $this->db->perform(
            'INSERT INTO `lager_bewegung` 
              (`lager_platz`, `artikel`, `menge`, `vpe`, `eingang`, `zeit`, `referenz`, 
               `bearbeiter`, `projekt`, `firma`, `logdatei`, `adresse`, `bestand`, 
               `permanenteinventur`, `paketannahme`, `doctype`, `doctypeid`, 
               `vpeid`, `is_interim`) 
             VALUES (:storage_location_id, :article_id, :quantity, :vpe, :in_stock, NOW(), :comment,
                     :username, :project_id, 1, NOW(), :address_id, :stock,
                     :inventory_stock, :parcel_id, :document_type, :document_id, 
                     :vpe_id, :is_interim)',
            [
                'article_id'          => $articleId,
                'storage_location_id' => $storageLocationId,
                'in_stock'            => (int)$isStockIn,
                'comment'             => $comment,
                'username'            => $this->userName,
                'address_id'          => $addressId,
                'quantity'            => $quantity,
                'vpe'                 => $vpe,
                'project_id'          => $projectId,
                'document_type'       => $documentType,
                'document_id'         => $documentId,
                'stock'               => $stock,
                'is_interim'          => (int)$isInterim,
                'storage_movement_id' => $storageMovementId,
                'inventory_stock'     => $inventoryStock,
                'parcel_id'           => $parcelId,
                'vpe_id'              => $vpeId,
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
