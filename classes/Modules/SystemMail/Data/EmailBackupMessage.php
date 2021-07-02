<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\Data;

use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use Xentral\Components\Mailer\Data\EmailRecipient;
use Xentral\Modules\SystemMail\Exception\InvalidArgumentException;

class EmailBackupMessage implements InboxMessageInterface
{
    /** @var int $id */
    private $id;

    /** @var int $emailAccountId */
    private $emailAccountId;

    /** @var string $subject */
    private $subject;

    /** @var EmailRecipient $sender */
    private $sender;

    /** @var string $plainTextBody */
    private $plainTextBody;

    /** @var string $htmlBody */
    private $htmlBody;

    /** @var DateTimeInterface $dateReceived */
    private $dateReceived;

    /** @var bool $hasAttachment */
    private $hasAttachment;

    /** @var string $checksum */
    private $checksum;

    /** @var bool $isQueued */
    private $isQueued;

    /** @var bool $isSpam */
    private $isSpam;

    /** @var bool $isMarkedRead */
    private $isMarkedRead;

    /** @var bool $isMarkedReply */
    private $isMarkedReply;

    /** @var int $addressId */
    private $addressId;

    /** @var int $projectId */
    private $projectId;

    /** @var int $ticketMessageId */
    private $ticketMessageId;

    /** @var EmailRecipient|null */
    private $replyTo;

    /** @var bool $isDeleted */
    private $isDeleted = false;

    /** @var InboxMessageAttachmentCollection $attachments */
    private $attachments;

    /**
     * @param int               $emailAccountId
     * @param string            $subject
     * @param string            $sender
     * @param string            $plainTextBody
     * @param string            $htmlBody
     * @param DateTimeInterface $dateReceived
     * @param bool              $hasAttachment
     * @param string            $replyToAddress
     * @param string            $replyToName
     * @param string            $checksum
     * @param int               $addressId
     * @param int               $projectId
     */
    public function __construct(
        int $emailAccountId,
        string $subject,
        string $sender,
        string $plainTextBody,
        string $htmlBody,
        DateTimeInterface $dateReceived,
        bool $hasAttachment,
        string $replyToAddress,
        string $replyToName,
        string $checksum,
        int $addressId = 0,
        int $projectId = 0
    ) {
        $this->emailAccountId = $emailAccountId;
        $this->subject = $subject;
        $this->sender = new EmailRecipient($sender);
        $this->plainTextBody = $plainTextBody;
        $this->htmlBody = $htmlBody;
        $this->dateReceived = $dateReceived;
        $this->hasAttachment = $hasAttachment;
        $this->setReplyTo($replyToAddress, $replyToName);
        $this->checksum = $checksum;
        $this->addressId = $addressId;
        $this->projectId = $projectId;
        $this->ticketMessageId = 0;
        $this->isQueued = false;
        $this->isSpam = false;
        $this->isMarkedRead = false;
        $this->isMarkedReply = false;
        $this->attachments = new InboxMessageAttachmentCollection();
    }

    /**
     * @param array $data
     *
     * @throws InvalidArgumentException
     *
     * @return static
     */
    public static function fromDbState(array $data): self
    {
        try {
            $instance = new self(
                $data['webmail'],
                $data['subject'],
                $data['sender'],
                $data['action'],
                $data['action_html'],
                new DateTimeImmutable($data['empfang']),
                (int)$data['anhang'] > 0,
                $data['mail_replyto'] ?? '',
                $data['verfasser_replyto'] ?? '',
                $data['checksum']
            );
        } catch (Exception $e) {
            throw new InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }
        $instance->id = (int)$data['id'];
        $instance->addressId = (int)$data['adresse'];
        $instance->projectId = (int)$data['projekt'];
        $instance->ticketMessageId = (int)$data['ticketnachricht'];
        $instance->isSpam = (int)$data['spam'] > 0;
        $instance->isMarkedRead = (int)$data['gelesen'] > 0;
        $instance->isQueued = (int)$data['warteschlange'] > 0;
        $instance->isMarkedReply = (int)$data['antworten'] > 0;
        $instance->isDeleted = $data['geloescht'] > 0;

        return $instance;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param bool $isQueued
     */
    public function setIsQueued(bool $isQueued): void
    {
        $this->isQueued = $isQueued;
    }

    /**
     * @param bool $isSpam
     */
    public function setSpam(bool $isSpam): void
    {
        $this->isSpam = $isSpam;
    }

    /**
     * @param bool $isMarkedRead
     */
    public function setMarkedRead(bool $isMarkedRead): void
    {
        $this->isMarkedRead = $isMarkedRead;
    }

    /**
     * @param bool $isMarkedReply
     */
    public function setMarkedReply(bool $isMarkedReply): void
    {
        $this->isMarkedReply = $isMarkedReply;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getEmailAccountId(): int
    {
        return $this->emailAccountId;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return EmailRecipient
     */
    public function getSender(): EmailRecipient
    {
        return $this->sender;
    }

    /**
     * @return bool
     */
    public function hasAttachment(): bool
    {
        return $this->hasAttachment;
    }

    /**
     * @return string
     */
    public function getChecksum(): string
    {
        return $this->checksum;
    }

    /**
     * @param string $checksum
     */
    public function setChecksum(string $checksum): void
    {
        $this->checksum = $checksum;
    }

    /**
     * @return int
     */
    public function getAddressId(): int
    {
        return $this->addressId;
    }

    /**
     * @param int $addressId
     */
    public function setAddressId(int $addressId): void
    {
        $this->addressId = $addressId;
    }

    /**
     * @return int
     */
    public function getProjectId(): int
    {
        return $this->projectId;
    }

    /**
     * @param int $projectId
     */
    public function setProjectId(int $projectId): void
    {
        $this->projectId = $projectId;
    }

    /**
     * @return int
     */
    public function getTicketMessageId(): int
    {
        return $this->ticketMessageId;
    }

    /**
     * @param int $ticketMessageId
     */
    public function setTicketMessageId(int $ticketMessageId): void
    {
        $this->ticketMessageId = $ticketMessageId;
    }

    /**
     * @return bool
     */
    public function isSpam(): bool
    {
        return $this->isSpam;
    }

    /**
     * @return bool
     */
    public function isMarkedRead(): bool
    {
        return $this->isMarkedRead;
    }

    /**
     * @return bool
     */
    public function isQueued(): bool
    {
        return $this->isQueued;
    }

    /**
     * @return bool
     */
    public function isMarkedReply(): bool
    {
        return $this->isMarkedReply;
    }

    /**
     * @return EmailRecipient|null
     */
    public function getReplyTo(): ?EmailRecipient
    {
        return $this->replyTo;
    }

    /**
     * @param string|null $address
     * @param string|null $name
     *
     * @return void
     */
    public function setReplyTo(?string $address, ?string $name): void
    {
        if (empty($address) && empty($name)) {
            $this->replyTo = null;
        }
        $this->replyTo = new EmailRecipient($address, $name);
    }

    /**
     * @return string
     */
    public function getSenderEmailAddress(): string
    {
        return $this->sender->getEmail();
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->getDateReceived()->getTimestamp();
    }

    /**
     * @return DateTimeInterface
     */
    public function getDateReceived(): DateTimeInterface
    {
        return $this->dateReceived;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return !empty($this->getHtmlBody()) ? $this->getHtmlBody() : $this->getPlainTextBody();
    }

    /**
     * @return string
     */
    public function getHtmlBody(): string
    {
        return $this->htmlBody;
    }

    /**
     * @return string
     */
    public function getPlainTextBody(): string
    {
        return $this->plainTextBody;
    }

    /**
     * @param InboxMessageAttachmentInterface $attachment
     *
     * @return void
     */
    public function addAttachment(InboxMessageAttachmentInterface $attachment): void
    {
        $this->attachments->addAttachment($attachment);
    }

    /**
     * @param InboxMessageAttachmentCollection $attachments
     */
    public function setAttachments(InboxMessageAttachmentCollection $attachments): void
    {
        $this->attachments = $attachments;
    }

    /**
     * @return InboxMessageAttachmentCollection
     */
    public function getAttachments(): InboxMessageAttachmentCollection
    {
        return $this->attachments;
    }

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    /**
     * @param bool $isDeleted
     */
    public function setDeleted(bool $isDeleted): void
    {
        $this->isDeleted = $isDeleted;
    }
}
