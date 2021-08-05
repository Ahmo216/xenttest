<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\Data;

use Countable;
use Iterator;

class InboxMessageCollection implements Iterator, Countable
{
    /** @var InboxMessageInterface[] $messages */
    private $messages;

    /** @var int $position */
    private $position;

    /**
     * @param InboxMessageInterface[] $messages
     */
    public function __construct(array $messages = [])
    {
        $this->messages = [];
        foreach ($messages as $message) {
            $this->add($message);
        }
    }

    /**
     * @param InboxMessageInterface $message
     *
     * @return void
     */
    public function add(InboxMessageInterface $message): void
    {
        $this->messages[] = $message;
    }

    /**
     * @return InboxMessageInterface
     */
    public function current(): InboxMessageInterface
    {
        return $this->messages[$this->position];
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
        return isset($this->messages[$this->position]);
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
        return count($this->messages);
    }
}
