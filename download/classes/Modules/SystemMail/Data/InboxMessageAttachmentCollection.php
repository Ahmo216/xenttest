<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\Data;

use Countable;
use Iterator;

class InboxMessageAttachmentCollection implements Iterator, Countable
{
    /** @var int $position */
    private $position = 0;

    /** @var InboxMessageAttachmentInterface[] $attachments */
    private $attachments = [];

    /**
     * @param InboxMessageAttachmentInterface[] $attachments
     */
    public function __construct(array $attachments = [])
    {
        foreach ($attachments as $attachment) {
            $this->addAttachment($attachment);
        }
    }

    /**
     * @param InboxMessageAttachmentInterface $attachment
     *
     * @return void
     */
    public function addAttachment(InboxMessageAttachmentInterface $attachment): void
    {
        $this->attachments[] = $attachment;
    }

    /**
     * @return InboxMessageAttachmentInterface
     */
    public function current(): InboxMessageAttachmentInterface
    {
        return $this->attachments[$this->position];
    }

    /**
     * @return void
     */
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
        return isset($this->attachments[$this->position]);
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
        return count($this->attachments);
    }
}
