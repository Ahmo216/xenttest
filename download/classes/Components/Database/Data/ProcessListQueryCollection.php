<?php

declare(strict_types=1);

namespace Xentral\Components\Database\Data;

use ArrayIterator;
use Countable;
use IteratorAggregate;

final class ProcessListQueryCollection implements IteratorAggregate, Countable
{
    /** @var ProcessListQuery[] $processListQueries */
    private $processListQueries = [];

    /**
     * ProcessListQueryCollection constructor.
     *
     * @param array $processListQueries
     */
    public function __construct(array $processListQueries = [])
    {
        foreach ($processListQueries as $processListQuery) {
            $this->add($processListQuery);
        }
    }

    /**
     * @param array $dbState
     *
     * @return static
     */
    public static function fromDbState(array $dbState): self
    {
        $instance = new self();
        foreach ($dbState as $queryDbState) {
            $instance->add(ProcessListQuery::fromDbState($queryDbState));
        }

        return $instance;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $dbState = [];
        foreach ($this->processListQueries as $processListQuery) {
            $dbState[] = $processListQuery->toArray();
        }

        return $dbState;
    }

    /**
     * @param ProcessListQuery $processListQuery
     */
    public function add(ProcessListQuery $processListQuery): void
    {
        $this->processListQueries[] = ProcessListQuery::fromDbState($processListQuery->toArray());
    }

    /**
     * @return array
     */
    public function getProcessListQueries(): array
    {
        $processListQueries = [];
        foreach ($this->processListQueries as $processListQuery) {
            $processListQueries[] = ProcessListQuery::fromDbState($processListQuery->toArray());
        }

        return $processListQueries;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->processListQueries);
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->processListQueries);
    }
}
