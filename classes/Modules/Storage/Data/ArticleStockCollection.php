<?php

declare(strict_types=1);

namespace Xentral\Modules\Storage\Data;

use Iterator;
use Countable;

final class ArticleStockCollection implements Iterator, Countable
{
    /** @var int $position */
    private $position = 0;

    /** @var ArticleStock[] $articleStocks */
    private $articleStocks = [];

    /**
     * ArticleStockCollection constructor.
     *
     * @param ArticleStock[] $articleStocks
     */
    public function __construct(array $articleStocks = [])
    {
        foreach ($articleStocks as $articleStock) {
            $this->add($articleStock);
        }
    }

    /**
     * @param ArticleStock $articleStock
     */
    public function add(ArticleStock $articleStock): void
    {
        $this->articleStocks[] = ArticleStock::fromDbState($articleStock->toArray());
    }

    /**
     * @return ObjectStorageLink[]
     */
    public function getArticleStocks(): array
    {
        $articleStocks = [];

        foreach ($this->articleStocks as $articleStock) {
            $articleStocks[] = ArticleStock::fromDbState($articleStock->toArray());
        }

        return $articleStocks;
    }

    /**
     * @return float
     */
    public function getQuantitySum(): float
    {
        $quantity = 0;
        foreach ($this->articleStocks as $articleStock) {
            $quantity += $articleStock->getQuantity();
        }

        return $quantity;
    }

    /**
     * @return ArticleStock
     */
    public function current(): ArticleStock
    {
        return ArticleStock::fromDbState($this->articleStocks[$this->position]->toArray());
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
        return isset($this->articleStocks[$this->position]);
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
        return count($this->articleStocks);
    }
}
