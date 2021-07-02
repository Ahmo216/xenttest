<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\Repository;

use DateTimeInterface;
use Xentral\Modules\SystemMail\Data\EmailBackupMessage;
use Xentral\Modules\SystemMail\Data\InboxMessage;
use Xentral\Modules\SystemMail\Data\InboxMessageHashSourceInterface;
use Xentral\Modules\SystemMail\Exception\EmailBackupNotFoundException;
use Xentral\Modules\SystemMail\Exception\InvalidArgumentException;
use Xentral\Modules\SystemMail\Service\EmailBackupService;

final class EmailBackupRepository implements EmailBackupRepositoryInterface
{
    /** @var EmailAttachmentRepositoryInterface */
    private $attachmentRepository;

    /** @var EmailBackupService */
    private $emailBackupService;

    /**
     * @param EmailBackupService                 $emailBackupService
     * @param EmailAttachmentRepositoryInterface $attachmentRepository
     */
    public function __construct(
        EmailBackupService $emailBackupService,
        EmailAttachmentRepositoryInterface $attachmentRepository
    ) {
        $this->emailBackupService = $emailBackupService;
        $this->attachmentRepository = $attachmentRepository;
    }

    /**
     * @param string $subject
     * @param string $senderEmailAddress
     * @param int    $timestamp
     *
     * @return string
     */
    public function generateHash(string $subject, string $senderEmailAddress, int $timestamp): string
    {
        return md5($senderEmailAddress . $subject . $timestamp);
    }

    /**
     * @param InboxMessageHashSourceInterface $message
     *
     * @return string
     */
    public function generateHashByMessage(InboxMessageHashSourceInterface $message): string
    {
        return $this->generateHash($message->getSubject(), $message->getSenderEmailAddress(), $message->getTimestamp());
    }

    /**
     * @param string $hash
     * @param int    $emailAccountId
     *
     * @return EmailBackupMessage|null
     */
    public function findByHash(string $hash, int $emailAccountId): ?EmailBackupMessage
    {
        $message = $this->emailBackupService->findByHash($hash, $emailAccountId);
        if (empty($message)) {
            return null;
        }

        return $this->addAttachmentsToEmailBackupMessage($message);
    }

    public function isMessageImported(string $hash, int $emailAccountId): bool
    {
        return !empty($this->emailBackupService->findByHash($hash, $emailAccountId));
    }

    /**
     * @param int $backupId
     *
     * @throws EmailBackupNotFoundException
     *
     * @return EmailBackupMessage
     */
    public function getById(int $backupId): EmailBackupMessage
    {
        $message = $this->emailBackupService->getById($backupId);

        return $this->addAttachmentsToEmailBackupMessage($message);
    }

    /**
     * @param int                    $emailAccountId
     * @param DateTimeInterface      $begin
     * @param DateTimeInterface|null $end
     *
     * @return EmailBackupMessage[]
     */
    public function findMessagesInTimePeriod(
        int $emailAccountId,
        DateTimeInterface $begin,
        ?DateTimeInterface $end = null
    ): array {
        $messages = $this->emailBackupService->findMessagesInTimePeriod($emailAccountId, $begin, $end);
        if (empty($messages)) {
            return [];
        }

        $return = [];
        foreach ($messages as $message) {
            $return[$message->getChecksum()] = $this->addAttachmentsToEmailBackupMessage($message);
        }

        return $return;
    }

    /**
     * @param InboxMessage $inboxMessage
     * @param int          $emailAccountId
     *
     * @return EmailBackupMessage
     */
    public function createFromInboxMessage(InboxMessage $inboxMessage, int $emailAccountId): EmailBackupMessage
    {
        $emailBackupMessage = new EmailBackupMessage(
            $emailAccountId,
            $inboxMessage->getSubject(),
            $inboxMessage->getSender()->getEmail(),
            '',
            $inboxMessage->getBody(),
            $inboxMessage->getDateReceived(),
            $inboxMessage->getOriginalMessage() !== null
                && count($inboxMessage->getOriginalMessage()->getAttachments()) > 0,
            $inboxMessage->getReplyTo()->getEmail(),
            $inboxMessage->getReplyTo()->getName(),
            $this->generateHash(
                $inboxMessage->getSubject(),
                $inboxMessage->getSenderEmailAddress(),
                $inboxMessage->getTimestamp()
            )
        );

        $emailBackupId = $this->emailBackupService->insert($emailBackupMessage);
        $emailBackupMessage->setId($emailBackupId);

        if (
            $inboxMessage->getOriginalMessage() !== null
            && count($inboxMessage->getOriginalMessage()->getAttachments()) > 0
        ) {
            foreach ($inboxMessage->getOriginalMessage()->getAttachments() as $attachment) {
                $this->attachmentRepository->createAttachment($attachment, $emailBackupId);
            }
        }

        return $this->addAttachmentsToEmailBackupMessage($emailBackupMessage);
    }

    /**
     * This Method only saves the data to the table 'emailbackup_mails'.
     * Attachment file contents do not get considered!
     *
     * @param EmailBackupMessage $message
     *
     * @return int
     */
    public function create(EmailBackupMessage $message): int
    {
        return $this->emailBackupService->insert($message);
    }

    /**
     * Updates an existing email backup entry int the tables 'emailbackup_mails' and 'email_attachment'.
     * It does not update attachment file contents!
     *
     * @param EmailBackupMessage $message
     *
     * @throws EmailBackupNotFoundException
     */
    public function update(EmailBackupMessage $message): void
    {
        $this->emailBackupService->update($message);
    }

    /**
     * @param int $emailAccountId
     *
     * @return DateTimeInterface|null
     */
    public function findLatestImportDateByAccountId(int $emailAccountId): ?DateTimeInterface
    {
        return $this->emailBackupService->findLatestImportDateByAccountId($emailAccountId);
    }

    /**
     * @inheritdoc
     */
    public function getChecksumIdMap(array $checksums, int $emailAccountId): array
    {
        return $this->emailBackupService->getChecksumIdMap($checksums, $emailAccountId);
    }

    /**
     * @inheritdoc
     */
    public function setDeleted(EmailBackupMessage $message): void
    {
        $this->emailBackupService->setDeleted($message->getId());
    }

    /**
     * @inheritdoc
     */
    public function remove(EmailBackupMessage $message): void
    {
        if (empty($message->getId())) {
            throw new InvalidArgumentException(
                "Email-backup-id is missing for: {$message->getSubject()}"
            );
        }

        $this->emailBackupService->delete($message->getId());
    }

    /**
     * @param EmailBackupMessage $message
     *
     * @return EmailBackupMessage
     */
    private function addAttachmentsToEmailBackupMessage(EmailBackupMessage $message): EmailBackupMessage
    {
        $attachments = $this->attachmentRepository->getAttachments($message->getId());

        if (!empty($attachments)) {
            $message->setAttachments($attachments);
        }

        return $message;
    }
}
