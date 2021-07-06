<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\Data;

use Countable;
use Iterator;

class InboxMessageOverviewCollection implements Iterator, Countable
{
    /** @var InboxMessageOverview[] */
    private $messageOverviews;

    /** @var int */
    private $position;

    /**
     * @param InboxMessageOverview[] $overviews
     */
    public function __construct(array $overviews = [])
    {
        $this->messageOverviews = [];
        foreach ($overviews as $overview) {
            $this->add($overview);
        }
    }

    /**
     * @return InboxMessageOverview
     */
    public function current(): InboxMessageOverview
    {
        return $this->messageOverviews[$this->position];
    }

    /**
     * @return void
     */
    public function next(): void
    {
        ++$this->position;
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
        return isset($this->messageOverviews[$this->position]);
    }

    /**
     * @return void
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->messageOverviews);
    }

    /**
     * @param InboxMessageOverview $overview
     *
     * @return void
     */
    public function add(InboxMessageOverview $overview): void
    {
        $this->messageOverviews[] = $overview;
    }
}
