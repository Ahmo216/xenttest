<?php

declare(strict_types=1);

namespace Xentral\Modules\Storage\Data;

use Iterator;
use Countable;

final class BatchStockCollection implements Iterator, Countable
{
    /** @var int $position */
    private $position = 0;

    /** @var BatchStock[] $batchStocks */
    private $batchStocks = [];

    /**
     * ObjectStorageLinkCollection constructor.
     *
     * @param BatchStock[] $batchStocks
     */
    public function __construct(array $batchStocks = [])
    {
        foreach ($batchStocks as $batchStock) {
            $this->add($batchStock);
        }
    }

    /**
     * @param BatchStock $batchStock
     */
    public function add(BatchStock $batchStock): void
    {
        $this->batchStocks[] = BatchStock::fromDbState($batchStock->toArray());
    }

    /**
     * @return BatchStock[]
     */
    public function getBatchStocks(): array
    {
        $batchStocks = [];

        foreach ($this->batchStocks as $batchStock) {
            $batchStocks[] = BatchStock::fromDbState($batchStock->toArray());
        }

        return $batchStocks;
    }

    /**
     * @return array
     */
    public function getStorageLocationsIds(): array
    {
        $storageLocationIds = [];
        foreach ($this->batchStocks as $batchStock) {
            $storageLocationIds[] = $batchStock->getStorageLocationId();
        }

        return array_unique($storageLocationIds);
    }

    /**
     * @return float
     */
    public function getQuantitySum(): float
    {
        $quantity = 0;
        foreach ($this->batchStocks as $batchStock) {
            $quantity += $batchStock->getQuantity();
        }

        return $quantity;
    }

    /**
     * @return array
     */
    public function getArticleIds(): array
    {
        $articleIds = [];
        foreach ($this->batchStocks as $batchStock) {
            $articleIds[] = $batchStock->getArticleId();
        }

        return array_unique($articleIds);
    }

    /**
     * @return BatchStock
     */
    public function current(): BatchStock
    {
        return BatchStock::fromDbState($this->batchStocks[$this->position]->toArray());
    }

    public function next(): void
    {
        $this->position++;
    }

    /**
     * @return int
     */
    public function key(): int
    {
        return $this->position;
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return isset($this->batchStocks[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->batchStocks);
    }
}
