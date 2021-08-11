<?php

declare(strict_types=1);

namespace Xentral\Modules\Storage\Data;

use DateTimeInterface;
use DateTime;
use Exception;

final class BatchStock
{
    /** @var int|null $id */
    private $id;

    /** @var string $batch */
    private $batch;

    /** @var DateTimeInterface|null $createdAt */
    private $createdAt;

    /** @var int $articleId */
    private $articleId;

    /** @var float $quantity */
    private $quantity;

    /** @var int $storageLocationId */
    private $storageLocationId;

    /** @var int $interimStorageId */
    private $interimStorageId;

    /** @var string|null $comment */
    private $comment;

    /**
     * BatchStock constructor.
     *
     * @param int                    $storageLocationId
     * @param int                    $articleId
     * @param float                  $quantity
     * @param string                 $batch
     * @param DateTimeInterface|null $createdAt
     * @param string|null            $comment
     * @param int                    $interimStorageId
     * @param int|null               $id
     */
    public function __construct(
        int $storageLocationId,
        int $articleId,
        float $quantity,
        string $batch,
        ?DateTimeInterface $createdAt = null,
        ?string $comment = null,
        int $interimStorageId = 0,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->batch = $batch;
        $this->createdAt = $createdAt;
        $this->articleId = $articleId;
        $this->quantity = $quantity;
        $this->storageLocationId = $storageLocationId;
        $this->interimStorageId = $interimStorageId;
        $this->comment = $comment;
    }

    /**
     * @param array $batchStock
     *
     * @return static
     */
    public static function fromDbState(array $batchStock): self
    {
        try {
            $createdAt = new DateTime($batchStock['datum']);
        } catch (Exception $e) {
            $createdAt = null;
        }

        return new self(
            (int)$batchStock['lager_platz'],
            (int)$batchStock['artikel'],
            (float)$batchStock['menge'],
            (string)$batchStock['charge'],
            $createdAt,
            $batchStock['internebemerkung'],
            (int)$batchStock['zwischenlagerid'],
            empty($batchStock['id']) ? null : (int)$batchStock['id']
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
            'id'               => $this->getId(),
            'charge'           => $this->getBatch(),
            'datum'            => $createdAt->format('Y-m-d'),
            'artikel'          => $this->getArticleId(),
            'menge'            => $this->getQuantity(),
            'lager_platz'      => $this->getStorageLocationId(),
            'zwischenlagerid'  => $this->getInterimStorageId(),
            'internebemerkung' => $this->getComment(),
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
     * @return string
     */
    public function getBatch(): string
    {
        return $this->batch;
    }

    /**
     * @param string $batch
     */
    public function setBatch(string $batch): void
    {
        $this->batch = $batch;
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
    public function getInterimStorageId(): int
    {
        return $this->interimStorageId;
    }

    /**
     * @param int $interimStorageId
     */
    public function setInterimStorageId(int $interimStorageId): void
    {
        $this->interimStorageId = $interimStorageId;
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
}
