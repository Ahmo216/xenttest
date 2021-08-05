<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\Data;

use DateTimeInterface;
use Xentral\Components\MailClient\Data\MailMessageInterface;
use Xentral\Components\Mailer\Data\EmailRecipient;

class InboxMessage implements InboxMessageInterface
{
    /** @var EmailRecipient $sender */
    private $sender;

    /** @var string $subject */
    private $subject;

    /** @var DateTimeInterface|null $date */
    private $date;

    /** @var string $body */
    private $body;

    /** @var InboxMessageAttachmentCollection $attachments */
    private $attachments;

    /** @var EmailRecipient $replyTo */
    private $replyTo;

    /** @var MailMessageInterface $mailMessage */
    private $mailMessage;

    /**
     * @param EmailRecipient|null                   $sender
     * @param string|null                           $subject
     * @param DateTimeInterface|null                $date
     * @param string|null                           $body
     * @param InboxMessageAttachmentCollection|null $attachments
     * @param EmailRecipient|null                   $replyTo
     */
    public function __construct(
        ?EmailRecipient $sender = null,
        ?string $subject = null,
        ?DateTimeInterface $date = null,
        ?string $body = null,
        ?InboxMessageAttachmentCollection $attachments = null,
        ?EmailRecipient $replyTo = null
    ) {
        $this->sender = $sender ?? new EmailRecipient('');
        $this->subject = $subject ?? '';
        $this->date = $date;
        $this->body = $body ?? '';
        $this->attachments = $attachments ?? new InboxMessageAttachmentCollection();
        $this->replyTo = $replyTo;
    }

    /**
     * @param MailMessageInterface $mailMessage
     *
     * @return static
     */
    public static function createFromMailMessage(MailMessageInterface $mailMessage): self
    {
        $body = $mailMessage->getHtmlBody();
        if (empty($body)) {
            $body = $mailMessage->getPlainTextBody();
        }
        $message = new self(
            $mailMessage->getSender(),
            $mailMessage->getSubject(),
            $mailMessage->getDate(),
            $body,
            null,
            new EmailRecipient($mailMessage->getReplyToAddress())
        );

        $message->mailMessage = $mailMessage;

        return $message;
    }

    /**
     * @return EmailRecipient|null
     */
    public function getReplyTo(): ?EmailRecipient
    {
        return $this->replyTo;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getSenderEmailAddress(): string
    {
        return $this->getSender()->getEmail();
    }

    /**
     * @return EmailRecipient
     */
    public function getSender(): EmailRecipient
    {
        return $this->sender;
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return !empty($this->date) ? $this->date->getTimestamp() : 0;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDateReceived(): ?DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @return InboxMessageAttachmentCollection
     */
    public function getAttachments(): InboxMessageAttachmentCollection
    {
        return $this->attachments;
    }

    /**
     * @return MailMessageInterface|null
     */
    public function getOriginalMessage(): ?MailMessageInterface
    {
        return $this->mailMessage;
    }
}
