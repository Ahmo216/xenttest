<?php

declare(strict_types=1);

namespace Xentral\Modules\Storage\Data;

use DateTimeInterface;
use Iterator;
use Countable;

final class SerialStockCollection implements Iterator, Countable
{
    /** @var int $position */
    private $position = 0;

    /** @var SerialStock[] $serialStocks */
    private $serialStocks = [];

    /**
     * SerialStockCollection constructor.
     *
     * @param SerialStock[] $serialStocks
     */
    public function __construct(array $serialStocks = [])
    {
        foreach ($serialStocks as $serialStock) {
            $this->add($serialStock);
        }
    }

    /**
     * @param SerialStock $serialStock
     */
    public function add(SerialStock $serialStock): void
    {
        $this->serialStocks[] = SerialStock::fromDbState($serialStock->toArray());
    }

    /**
     * @return SerialStock[]
     */
    public function getSerialStocks(): array
    {
        $serialStocks = [];
        foreach ($this->serialStocks as $serialStock) {
            $serialStocks[] = SerialStock::fromDbState($serialStock->toArray());
        }

        return $serialStocks;
    }

    /**
     * @return array
     */
    public function getArticleIds(): array
    {
        $articleIds = [];
        foreach ($this->serialStocks as $serialStock) {
            $articleIds[] = $serialStock->getArticleId();
        }

        return array_unique($articleIds);
    }

    /**
     * @return array
     */
    public function getStorageLocationsIds(): array
    {
        $storageLocationIds = [];
        foreach ($this->serialStocks as $serialStock) {
            $storageLocationIds[] = $serialStock->getStorageLocationId();
        }

        return array_unique($storageLocationIds);
    }

    /**
     * @return array
     */
    public function getSerials(): array
    {
        $serials = [];
        foreach ($this->serialStocks as $serialStock) {
            $serials[] = $serialStock->getSerial();
        }

        return array_unique($serials);
    }

    /**
     * @param string $batch
     *
     * @return $this
     */
    public function filterBatch(string $batch): self
    {
        $instance = new self();
        foreach ($this as $serialStock) {
            if ($serialStock->getBatch() === $batch) {
                $instance->add($serialStock);
            }
        }

        return $instance;
    }

    public function filterBestBefore(?DateTimeInterface $bestBefore): self
    {
        $instance = new self();
        foreach ($this as $serialStock) {
            if (
                ($bestBefore === null && $serialStock->getBestBefore())
                || ($serialStock->getBestBefore() !== null && $bestBefore->format(
                        'Y-m-d'
                    ) === $serialStock->getBestBefore()->format('Y-m-d'))
            ) {
                $instance->add($serialStock);
            }
        }

        return $instance;
    }

    /**
     * @return SerialStock
     */
    public function current(): SerialStock
    {
        return SerialStock::fromDbState($this->serialStocks[$this->position]->toArray());
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
        return isset($this->serialStocks[$this->position]);
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
        return count($this->serialStocks);
    }
}
