<?php

declare(strict_types=1);

namespace Xentral\Modules\Storage\Data;

use DateTimeInterface;
use DateTime;
use Exception;

final class BestBeforeStock
{
    /** @var int|null $id */
    private $id;

    /** @var DateTimeInterface|null $createdAt */
    private $createdAt;

    /** @var DateTimeInterface|null $bestBeforeDate */
    private $bestBeforeDate;

    /** @var int $articleId */
    private $articleId;

    /** @var int $storageLocationId */
    private $storageLocationId;

    /** @var float $quantity */
    private $quantity;

    /** @var int $interimStorageId */
    private $interimStorageId;

    /** @var string $batch */
    private $batch;

    /** @var string|null $comment */
    private $comment;

    /**
     * BestBeforeStock constructor.
     *
     * @param int                    $storageLocationId
     * @param int                    $articleId
     * @param float                  $quantity
     * @param DateTimeInterface|null $bestBeforeDate
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
        ?DateTimeInterface $bestBeforeDate,
        string $batch = '',
        ?DateTimeInterface $createdAt = null,
        ?string $comment = null,
        int $interimStorageId = 0,
        ?int $id = null
    ) {
        $this->storageLocationId = $storageLocationId;
        $this->articleId = $articleId;
        $this->quantity = $quantity;
        $this->bestBeforeDate = $bestBeforeDate;
        $this->batch = $batch;
        $this->createdAt = $createdAt;
        $this->comment = $comment;
        $this->interimStorageId = $interimStorageId;
        $this->id = $id;
    }

    /**
     * @param array $bestBeforeStock
     *
     * @return static
     */
    public static function fromDbState(array $bestBeforeStock): self
    {
        try {
            $createdAt = new DateTime($bestBeforeStock['datum']);
        } catch (Exception $e) {
            $createdAt = null;
        }
        try {
            $bestBeforeDate = new DateTime($bestBeforeStock['mhddatum']);
        } catch (Exception $e) {
            $bestBeforeDate = null;
        }

        return new self(
            (int)$bestBeforeStock['lager_platz'],
            (int)$bestBeforeStock['artikel'],
            (float)$bestBeforeStock['menge'],
            $bestBeforeDate,
            $bestBeforeStock['charge'],
            $createdAt,
            $bestBeforeStock['internebemerkung'],
            (int)$bestBeforeStock['zwischenlagerid'],
            empty($bestBeforeStock['id']) ? null : (int)$bestBeforeStock['id']
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
        $bestBeforeDate = $this->getBestBeforeDate();

        return [
            'id'               => $this->getId(),
            'datum'            => $createdAt->format('Y-m-d'),
            'mhddatum'         => $bestBeforeDate === null ? '0000-00-00' : $bestBeforeDate->format('Y-m-d'),
            'artikel'          => $this->getArticleId(),
            'menge'            => $this->getQuantity(),
            'lager_platz'      => $this->getStorageLocationId(),
            'zwischenlagerid'  => $this->getInterimStorageId(),
            'charge'           => $this->getBatch(),
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
     * @return DateTimeInterface|null
     */
    public function getBestBeforeDate(): ?DateTimeInterface
    {
        return $this->bestBeforeDate;
    }

    /**
     * @param DateTimeInterface|null $bestBeforeDate
     */
    public function setBestBeforeDate(?DateTimeInterface $bestBeforeDate): void
    {
        $this->bestBeforeDate = $bestBeforeDate;
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
