<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\Data;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Xentral\Components\MailClient\Data\MailMessageHeaderCollection;
use Xentral\Components\MailClient\Data\MailMessageHeaderInterface;
use Xentral\Components\MailClient\Data\MailMessageOverview;

class InboxMessageOverview extends MailMessageOverview implements InboxMessageHashSourceInterface
{
    /** @var string */
    private $senderEmailAddress;

    /** @var int */
    private $timestamp;

    /** @var string */
    private $subject;

    /** @var DateTimeImmutable|null $date */
    private $date;

    /**
     * @param int                              $temporaryId
     * @param string|null                      $folder
     * @param MailMessageHeaderCollection|null $headers
     */
    public function __construct(
        int $temporaryId = 0,
        ?string $folder = null,
        ?MailMessageHeaderCollection $headers = null
    ) {
        parent::__construct($temporaryId, $folder, $headers);
        $this->senderEmailAddress = $this->extractEmailAddress($this->getHeaders()->get('From'));
        $this->date = $this->extractDateTime($this->getHeaders()->get('Date'));
        $this->timestamp = $this->extractTimestamp($this->getHeaders()->get('Date'));
        $this->subject = $this->extractValue($this->getHeaders()->get('Subject'));
    }

    /**
     * @return string
     */
    public function getSenderEmailAddress(): string
    {
        return $this->senderEmailAddress;
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @param MailMessageHeaderInterface|null $header
     *
     * @return string
     */
    private function extractEmailAddress(?MailMessageHeaderInterface $header): string
    {
        if ($header === null) {
            return '';
        }
        $matchGroups = [];
        if (!preg_match('/[^<]*?<?([^<>]+)>?$/', $header->getValue(), $matchGroups)) {
            return '';
        }

        return $matchGroups[1];
    }

    /**
     * @param MailMessageHeaderInterface|null $header
     *
     * @return DateTimeImmutable|null
     */
    private function extractDateTime(?MailMessageHeaderInterface $header): ?DateTimeImmutable
    {
        if ($header === null) {
            return null;
        }

        try {
            return new DateTimeImmutable($header->getValue());
        } catch (\Exception $e) {
        }

        return null;
    }

    /**
     * @param MailMessageHeaderInterface|null $header
     *
     * @return int
     */
    private function extractTimestamp(?MailMessageHeaderInterface $header): int
    {
        if ($header === null) {
            return 0;
        }
        $date = DateTime::createFromFormat(DateTimeInterface::RFC2822, $header->getValue());

        return !empty($date) ? $date->getTimestamp() : 0;
    }

    /**
     * @param MailMessageHeaderInterface|null $header
     *
     * @return string
     */
    private function extractValue(?MailMessageHeaderInterface $header): string
    {
        return $header !== null ? $header->getValue() : '';
    }
}
