<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\Data;

use DateTimeInterface;
use Xentral\Components\Mailer\Data\EmailRecipient;

interface InboxMessageInterface extends InboxMessageHashSourceInterface
{
    /**
     * @return EmailRecipient
     */
    public function getSender(): EmailRecipient;

    /**
     * @return EmailRecipient|null
     */
    public function getReplyTo(): ?EmailRecipient;

    /**
     * @return string
     */
    public function getSubject(): string;

    /**
     * @return string
     */
    public function getSenderEmailAddress(): string;

    /**
     * @return int
     */
    public function getTimestamp(): int;

    /**
     * @return string
     */
    public function getBody(): string;

    /**
     * @return DateTimeInterface|null
     */
    public function getDateReceived(): ?DateTimeInterface;

    /**
     * @return InboxMessageAttachmentCollection
     */
    public function getAttachments(): InboxMessageAttachmentCollection;
}
