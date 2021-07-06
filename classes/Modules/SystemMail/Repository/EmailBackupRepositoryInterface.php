<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\Repository;

use DateTimeInterface;
use Xentral\Modules\SystemMail\Data\EmailBackupMessage;
use Xentral\Modules\SystemMail\Data\InboxMessage;
use Xentral\Modules\SystemMail\Data\InboxMessageHashSourceInterface;
use Xentral\Modules\SystemMail\Exception\EmailBackupNotFoundException;
use Xentral\Modules\SystemMail\Exception\InvalidArgumentException;

interface EmailBackupRepositoryInterface
{
    /**
     * Generates a checksum that is unique for each email message.
     *
     * This checksum will be used to check whether an email has already been
     * imported from remote email server to the system.
     *
     * @param string $subject
     * @param string $senderEmailAddress
     * @param int    $timestamp
     *
     * @return string
     */
    public function generateHash(string $subject, string $senderEmailAddress, int $timestamp): string;

    /**
     * Generates a checksum that is unique per email and account.
     *
     * This checksum will be used to check whether an email has already been
     * imported from remote email server to the system.
     *
     * @param InboxMessageHashSourceInterface $message
     *
     * @return string
     */
    public function generateHashByMessage(InboxMessageHashSourceInterface $message): string;

    /**
     * Finds an email message by checksum and email account id.
     *
     * @param string $hash
     * @param int    $emailAccountId
     *
     * @return EmailBackupMessage|null
     */
    public function findByHash(string $hash, int $emailAccountId): ?EmailBackupMessage;

    /**
     * Check whether an email message with the given checksum has been imported
     * For the given account id.
     *
     * @param string $hash
     * @param int    $emailAccountId
     *
     * @return bool
     */
    public function isMessageImported(string $hash, int $emailAccountId): bool;

    /**
     * Get an email message based on its database row id.
     *
     * Throws an exception if the entry is 'geloescht' = 1.
     *
     * @param int $backupId
     *
     * @throws EmailBackupNotFoundException
     *
     * @return EmailBackupMessage
     */
    public function getById(int $backupId): EmailBackupMessage;

    /**
     * Find Emailbackups from a specific email account that
     * were received in a specific time span.
     *
     * @param int                    $emailAccountId
     * @param DateTimeInterface      $begin
     * @param DateTimeInterface|null $end            null = use default('now')
     *
     * @return EmailBackupMessage[] ['checksum' => object<EmailBackupMessage>]
     */
    public function findMessagesInTimePeriod(
        int $emailAccountId,
        DateTimeInterface $begin,
        ?DateTimeInterface $end = null
    ): ?array;

    /**
     * Creates backup from actual email.
     *
     * @param InboxMessage $inboxMessage
     * @param int          $emailAccountId
     *
     * @return EmailBackupMessage
     */
    public function createFromInboxMessage(InboxMessage $inboxMessage, int $emailAccountId): EmailBackupMessage;

    /**
     * This Method only saves the data to the table 'emailbackup_mails'.
     * Attachment file contents do not get considered!
     *
     * @param EmailBackupMessage $message
     *
     * @return int backup id
     */
    public function create(EmailBackupMessage $message): int;

    /**
     * Updates an existing email backup entry in the tables 'emailbackup_mails' and 'email_attachment'.
     * It does not update attachment file contents!
     *
     * @param EmailBackupMessage $message
     *
     * @return void
     */
    public function update(EmailBackupMessage $message): void;

    /**
     * Gets the received-date of the latest (by time) email
     * that was stored for the specified account.
     *
     * @param int $emailAccountId
     *
     * @return DateTimeInterface|null
     */
    public function findLatestImportDateByAccountId(int $emailAccountId): ?DateTimeInterface;

    /**
     * Returns a map of checksum to message id that
     * containing only existing checksums that were
     * provided by the array.
     *
     * example:
     * getChecksumIdMap(['checksum1', 'checksum2'])
     * result:
     * [
     *   'checksum1' => 21,
     *   'checksum2' => 33,
     * ]
     *
     * implementation: combine all checksums into one "WHERE ... IN ..."
     * condition
     *
     * @param string[] $checksums
     * @param int      $emailAccountId
     *
     * @return int[] map of checksum to message id
     */
    public function getChecksumIdMap(array $checksums, int $emailAccountId): array;

    /**
     * Marks the `emailbackup_mails` entry as `geloescht` = 1
     *
     * @param EmailBackupMessage $message
     *
     * @return void
     */
    public function setDeleted(EmailBackupMessage $message): void;

    /**
     * Hard-deletes an entry and all backup files of an email.
     *
     * Usecase:
     * Assume an error occurred during the import.
     * All stored information about the email must be wiped
     * from the system so a retry of the import is possible.
     *
     * @param EmailBackupMessage $message
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function remove(EmailBackupMessage $message): void;
}
