<?php

declare(strict_types=1);

namespace Xentral\Modules\Storage\Data;


use DateTimeInterface;
use DateTime;
use Exception;

final class ObjectStorageLink
{
    /** @var int|null $id */
    private $id;

    /** @var int $positionId */
    private $positionId;

    /** @var string $documentType */
    private $documentType;

    /** @var int $articleId */
    private $articleId;

    /** @var int $storageLocationId */
    private $storageLocationId;

    /** @var float $quantity */
    private $quantity;

    /** @var string|null $comment */
    private $comment;

    /** @var string $employee */
    private $employee;

    /** @var DateTimeInterface|null $createdAt */
    private $createdAt;

    /**
     * ObjectStorageLink constructor.
     *
     * @param string                 $documentType
     * @param int                    $positionId
     * @param int                    $articleId
     * @param int                    $storageLocationId
     * @param float                  $quantity
     * @param string                 $comment
     * @param string                 $employee
     * @param DateTimeInterface|null $createdAt
     * @param int|null               $id
     */
    public function __construct(
        string $documentType,
        int $positionId,
        int $articleId,
        int $storageLocationId,
        float $quantity,
        ?string $comment = '',
        string $employee = '',
        ?DateTimeInterface $createdAt = null,
        ?int $id = null
    ) {
        $this->documentType = $documentType;
        $this->positionId = $positionId;
        $this->articleId = $articleId;
        $this->storageLocationId = $storageLocationId;
        $this->quantity = $quantity;
        $this->comment = $comment;
        $this->employee = $employee;
        $this->createdAt = $createdAt;
        $this->id = $id;
    }

    /**
     * @param array $objectStorageLink
     *
     * @return static
     */
    public static function fromDbState(array $objectStorageLink): self
    {
        $createdAt = null;
        if (!empty($objectStorageLink['zeitstempel']) && $objectStorageLink['zeitstempel'] !== '0000-00-00 00:00:00') {
            try {
                $createdAt = new DateTime($objectStorageLink['zeitstempel']);
            } catch (Exception $e) {
                $createdAt = null;
            }
        }

        return new self(
            $objectStorageLink['objekt'],
            (int)$objectStorageLink['parameter'],
            (int)$objectStorageLink['artikel'],
            (int)$objectStorageLink['lager_platz'],
            (float)$objectStorageLink['menge'],
            $objectStorageLink['kommentar'],
            (string)$objectStorageLink['bearbeiter'],
            $createdAt,
            empty($objectStorageLink['id']) ? null : (int)$objectStorageLink['id']
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
            'id'          => $this->getId(),
            'objekt'      => $this->getDocumentType(),
            'parameter'   => $this->getPositionId(),
            'artikel'     => $this->getArticleId(),
            'lager_platz' => $this->getStorageLocationId(),
            'menge'       => $this->getQuantity(),
            'kommentar'   => $this->getComment(),
            'bearbeiter'  => $this->getEmployee(),
            'zeitstempel' => $createdAt->format('Y-m-d H:i:s'),
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
    public function getPositionId(): int
    {
        return $this->positionId;
    }

    /**
     * @param int $positionId
     */
    public function setPositionId(int $positionId): void
    {
        $this->positionId = $positionId;
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
     * @return string
     */
    public function getDocumentType(): string
    {
        return $this->documentType;
    }

    /**
     * @param string $documentType
     */
    public function setDocumentType(string $documentType): void
    {
        $this->documentType = $documentType;
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
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param string|null $comment
     */
    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
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
}
