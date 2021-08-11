<?php

declare(strict_types=1);

namespace Xentral\Modules\Storage\Data;

use Iterator;
use Countable;

final class BestBeforeStockCollection implements Iterator, Countable
{
    /** @var int $position */
    private $position = 0;

    /** @var BestBeforeStock[] $bestBeforeStocks */
    private $bestBeforeStocks = [];

    /**
     * ObjectStorageLinkCollection constructor.
     *
     * @param BestBeforeStock[] $bestBeforeStocks
     */
    public function __construct(array $bestBeforeStocks = [])
    {
        foreach ($bestBeforeStocks as $bestBeforeStock) {
            $this->add($bestBeforeStock);
        }
    }

    /**
     * @param BestBeforeStock $bestBeforeStock
     */
    public function add(BestBeforeStock $bestBeforeStock): void
    {
        $this->bestBeforeStocks[] = BestBeforeStock::fromDbState($bestBeforeStock->toArray());
    }

    /**
     * @return BestBeforeStock[]
     */
    public function getBestBeforeStocks(): array
    {
        $bestBeforeStocks = [];

        foreach ($this->bestBeforeStocks as $bestBeforeStock) {
            $bestBeforeStocks[] = BestBeforeStock::fromDbState($bestBeforeStock->toArray());
        }

        return $bestBeforeStocks;
    }

    /**
     * @return array
     */
    public function getStorageLocationsIds(): array
    {
        $storageLocationIds = [];
        foreach ($this->bestBeforeStocks as $bestBeforeStock) {
            $storageLocationIds[] = $bestBeforeStock->getStorageLocationId();
        }

        return array_unique($storageLocationIds);
    }

    /**
     * @return array
     */
    public function getArticleIds(): array
    {
        $articleIds = [];
        foreach ($this->bestBeforeStocks as $bestBeforeStock) {
            $articleIds[] = $bestBeforeStock->getArticleId();
        }

        return array_unique($articleIds);
    }

    /**
     * @return float
     */
    public function getQuantitySum(): float
    {
        $quantity = 0;
        foreach ($this->bestBeforeStocks as $bestBeforeStock) {
            $quantity += $bestBeforeStock->getQuantity();
        }

        return $quantity;
    }

    /**
     * @return BestBeforeStock
     */
    public function current(): BestBeforeStock
    {
        return BestBeforeStock::fromDbState($this->bestBeforeStocks[$this->position]->toArray());
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
        return isset($this->bestBeforeStocks[$this->position]);
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
        return count($this->bestBeforeStocks);
    }
}
