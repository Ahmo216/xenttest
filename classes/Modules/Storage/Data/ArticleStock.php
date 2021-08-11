<?php

declare(strict_types=1);

namespace Xentral\Modules\Storage\Data;


use DateTimeInterface;
use DateTime;
use Exception;

final class ArticleStock
{
    /** @var int|null $id */
    private $id;

    /** @var int $storageLocationId */
    private $storageLocationId;

    /** @var int $articleId */
    private $articleId;

    /** @var float $quantity */
    private $quantity;

    /** @var string $vpe */
    private $vpe;

    /** @var string $employee */
    private $employee;

    /** @var int $supplierOrderId */
    private $supplierOrderId;

    /** @var int $projectId */
    private $projectId;

    /** @var DateTimeInterface|null $createdAt */
    private $createdAt;

    /** @var float|int $inventoryStock */
    private $inventoryStock;

    /** @var int $vpeStockId */
    private $vpeStockId;

    /**
     * ArticleStock constructor.
     *
     * @param int                    $storageLocationId
     * @param int                    $articleId
     * @param float                  $quantity
     * @param string                 $vpe
     * @param string                 $employee
     * @param int                    $supplierOrderId
     * @param int                    $projectId
     * @param DateTimeInterface|null $createdAt
     * @param float                  $inventoryStock
     * @param int                    $vpeStockId
     * @param int|null               $id
     */
    public function __construct(
        int $storageLocationId,
        int $articleId,
        float $quantity,
        string $vpe = '',
        string $employee = '',
        int $supplierOrderId = 0,
        int $projectId = 0,
        ?DateTimeInterface $createdAt = null,
        float $inventoryStock = 0,
        int $vpeStockId = 0,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->storageLocationId = $storageLocationId;
        $this->articleId = $articleId;
        $this->quantity = $quantity;
        $this->vpe = $vpe;
        $this->employee = $employee;
        $this->supplierOrderId = $supplierOrderId;
        $this->projectId = $projectId;
        $this->createdAt = $createdAt;
        $this->inventoryStock = $inventoryStock;
        $this->vpeStockId = $vpeStockId;
    }

    /**
     * @param array $articleStock
     *
     * @return static
     */
    public static function fromDbState(array $articleStock): self
    {
        try {
            $createdAt = new DateTime($articleStock['logdatei']);
            if ($createdAt->getTimestamp() <= 0) {
                $createdAt = null;
            }
        } catch (Exception $e) {
            $createdAt = null;
        }

        return new self(
            (int)$articleStock['lager_platz'],
            (int)$articleStock['artikel'],
            (float)$articleStock['menge'],
            (string)$articleStock['vpe'],
            (string)$articleStock['bearbeiter'],
            (int)$articleStock['bestellung'],
            (int)$articleStock['projekt'],
            $createdAt,
            (float)$articleStock['inventur'],
            (int)$articleStock['lager_platz_vpe'],
            empty($articleStock['id']) ? null : (int)$articleStock['id']
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $createdAt = $this->getCreatedAt();
        if ($createdAt === null) {
            $createdAt = new DateTime();
        }

        return [
            'id'              => $this->getId(),
            'lager_platz'     => $this->getStorageLocationId(),
            'artikel'         => $this->getArticleId(),
            'menge'           => $this->getQuantity(),
            'vpe'             => $this->getVpe(),
            'bearbeiter'      => $this->getEmployee(),
            'bestellung'      => $this->getSupplierOrderId(),
            'projekt'         => $this->getProjectId(),
            'firma'           => 1,
            'logdatei'        => $createdAt->format('Y-m-d H:i:s'),
            'inventur'        => $this->getInventoryStock(),
            'lager_platz_vpe' => $this->getVpeStockId(),
        ];
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getStorageLocationId(): int
    {
        return $this->storageLocationId;
    }

    /**
     * @param int $storageLocationId
     */
    public function setStorageLocationId(int $storageLocationId): void
    {
        $this->storageLocationId = $storageLocationId;
    }

    /**
     * @return int
     */
    public function getArticleId(): int
    {
        return $this->articleId;
    }

    /**
     * @param int $articleId
     */
    public function setArticleId(int $articleId): void
    {
        $this->articleId = $articleId;
    }

    /**
     * @return float
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

    /**
     * @param float $quantity
     */
    public function setQuantity(float $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return string
     */
    public function getVpe(): string
    {
        return $this->vpe;
    }

    /**
     * @param string $vpe
     */
    public function setVpe(string $vpe): void
    {
        $this->vpe = $vpe;
    }

    /**
     * @return string
     */
    public function getEmployee(): string
    {
        return $this->employee;
    }

    /**
     * @param string $employee
     */
    public function setEmployee(string $employee): void
    {
        $this->employee = $employee;
    }

    /**
     * @return int
     */
    public function getSupplierOrderId(): int
    {
        return $this->supplierOrderId;
    }

    /**
     * @param int $supplierOrderId
     */
    public function setSupplierOrderId(int $supplierOrderId): void
    {
        $this->supplierOrderId = $supplierOrderId;
    }

    /**
     * @return int
     */
    public function getProjectId(): int
    {
        return $this->projectId;
    }

    /**
     * @param int $projectId
     */
    public function setProjectId(int $projectId): void
    {
        $this->projectId = $projectId;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface|null $createdAt
     */
    public function setCreatedAt(?DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return float
     */
    public function getInventoryStock()
    {
        return $this->inventoryStock;
    }

    /**
     * @param float $inventoryStock
     */
    public function setInventoryStock($inventoryStock): void
    {
        $this->inventoryStock = $inventoryStock;
    }

    /**
     * @return int
     */
    public function getVpeStockId(): int
    {
        return $this->vpeStockId;
    }

    /**
     * @param int $vpeStockId
     */
    public function setVpeStockId(int $vpeStockId): void
    {
        $this->vpeStockId = $vpeStockId;
    }
}
