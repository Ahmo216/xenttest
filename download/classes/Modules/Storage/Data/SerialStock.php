<?php

declare(strict_types=1);

namespace Xentral\Modules\Storage\Data;

use DateTimeInterface;
use DateTime;
use Exception;

final class SerialStock
{
    /** @var int|null $id */
    private $id;

    /** @var int $articleId */
    private $articleId;

    /** @var int $storageLocationId */
    private $storageLocationId;

    /** @var string $serial */
    private $serial;

    /** @var string|null $comment */
    private $comment;

    /** @var string $batch */
    private $batch;

    /** @var DateTimeInterface|null $bestBefore */
    private $bestBefore;

    /** @var int $interimStorageId */
    private $interimStorageId;

    public function __construct(
        int $articleId,
        int $storageLocationId,
        string $serial,
        ?string $comment = null,
        string $batch = '',
        ?DateTimeInterface $bestBefore = null,
        int $interimStorageId = 0,
        ?int $id = null
    ) {
        $this->articleId = $articleId;
        $this->storageLocationId = $storageLocationId;
        $this->serial = $serial;
        $this->comment = $comment;
        $this->batch = $batch;
        $this->bestBefore = $bestBefore;
        $this->interimStorageId = $interimStorageId;
        $this->id = $id;
    }

    /**
     * @param array $serialStock
     *
     * @return static
     */
    public static function fromDbState(array $serialStock): self
    {
        try {
            $bestBeforeDate = new DateTime($serialStock['mhddatum']);
        } catch (Exception $e) {
            $bestBeforeDate = null;
        }

        return new self(
            (int)$serialStock['artikel'],
            (int)$serialStock['lager_platz'],
            (string)$serialStock['seriennummer'],
            $serialStock['internebemerkung'],
            $serialStock['charge'],
            $bestBeforeDate,
            (int)$serialStock['zwischenlagerid'],
            empty($serialStock['id']) ? null : (int)$serialStock['id']
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id'               => $this->getId(),
            'artikel'          => $this->getArticleId(),
            'lager_platz'      => $this->getStorageLocationId(),
            'zwischenlagerid'  => $this->getInterimStorageId(),
            'seriennummer'     => $this->getSerial(),
            'charge'           => $this->getBatch(),
            'mhddatum'         => $this->getBestBefore() === null ? '0000-00-00' : $this->getBestBefore()
                ->format(
                    'Y-m-d'
                ),
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
     * @return string
     */
    public function getSerial(): string
    {
        return $this->serial;
    }

    /**
     * @param string $serial
     */
    public function setSerial(string $serial): void
    {
        $this->serial = $serial;
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
    public function getBestBefore(): ?DateTimeInterface
    {
        return $this->bestBefore;
    }

    /**
     * @param DateTimeInterface|null $bestBefore
     */
    public function setBestBefore(?DateTimeInterface $bestBefore): void
    {
        $this->bestBefore = $bestBefore;
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


}
