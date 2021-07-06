<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\Importer;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use Xentral\Components\Logger\LoggerAwareTrait;
use Xentral\Components\MailClient\Data\MailMessageInterface;
use Xentral\Modules\SystemMail\Data\EmailBackupMessage;
use Xentral\Modules\SystemMail\Data\InboxMessage;
use Xentral\Modules\SystemMail\Data\InboxMessageCollection;
use Xentral\Modules\SystemMail\Data\InboxMessageInterface;
use Xentral\Modules\SystemMail\Data\InboxMessageOverview;
use Xentral\Modules\SystemMail\Data\InboxMessageOverviewCollection;
use Xentral\Modules\SystemMail\EmailArchive\EmailArchiveInterface;
use Xentral\Modules\SystemMail\Exception\EmailBackupNotFoundException;
use Xentral\Modules\SystemMail\Exception\InboxImportException;
use Xentral\Modules\SystemMail\Exception\SystemMailExceptionInterface;
use Xentral\Modules\SystemMail\Inbox\InboxClientInterface;
use Xentral\Modules\SystemMail\Repository\EmailBackupRepositoryInterface;
use Xentral\Modules\SystemMailer\Data\EmailBackupAccount;

class InboxImporter implements InboxImporterInterface
{
    use LoggerAwareTrait;

    /**
     * @var int DEFAULT_PAST_DAYS
     *
     * If an account has it's very first import and no
     * import start date was defined by the user, the
     * importer will use a date that is X days in the past
     */
    private const DEFAULT_PAST_DAYS = 5;

    /** @var EmailBackupRepositoryInterface $backupRepository */
    private $backupRepository;

    /** @var EmailArchiveInterface $archive */
    private $archive;

    /**
     * @param EmailBackupRepositoryInterface $backupRepository
     * @param EmailArchiveInterface          $archive
     */
    public function __construct(
        EmailBackupRepositoryInterface $backupRepository,
        EmailArchiveInterface $archive
    ) {
        $this->backupRepository = $backupRepository;
        $this->archive = $archive;
    }

    /**
     * @param InboxClientInterface $client
     * @param EmailBackupAccount   $account
     *
     * @return InboxMessageCollection
     */
    public function import(InboxClientInterface $client, EmailBackupAccount $account): InboxMessageCollection
    {
        // Fetches an overview over all messages that should be imported.
        $importStartDate = $this->chooseImportDateTime($account);
        $inboxOverview = $client->getInboxOverviewSince($importStartDate, 'INBOX');

        $storedMessages = $this->backupRepository->findMessagesInTimePeriod(
            $account->getId(),
            $importStartDate
        );

        // Get a map (checksum to id) of already imported messages.
        $importedChecksums = $this->getImportedChecksumMap(
            $inboxOverview->getOverviews(),
            $account->getId()
        );

        $messageCollection = new InboxMessageCollection();
        foreach ($inboxOverview->getOverviews() as $messageOverview) {
            $checksum = $this->backupRepository->generateHashByMessage($messageOverview);

            // Case: Email is already in stored messages array.
            if (isset($storedMessages[$checksum])) {
                $messageCollection->add($storedMessages[$checksum]);
                unset($storedMessages[$checksum]);

                continue;
            }

            // Case: Email was already imported but is not in stored messages.
            if (isset($importedChecksums[$checksum])) {
                try {
                    $message = $this->backupRepository->getById($importedChecksums[$checksum]);
                    $messageCollection->add($message);
                } catch (EmailBackupNotFoundException $e) {
                    // The email has been deleted locally and therefore MUST NOT be imported again.
                }

                continue;
            }

            // Case: Email must be fetched from the server.
            try {
                $message = $this->importServerMessage($client, $account, $messageOverview);
                $messageCollection->add($message);
            } catch (InboxImportException $e) {
                $this->logger->error(
                    'Failed to import email',
                    [
                        'recipient' => $account->getEmailAddress(),
                        'sender' => $messageOverview->getSenderEmailAddress(),
                        'subject' => $messageOverview->getSubject(),
                        'date' => $messageOverview->getDate() !== null
                            ? $messageOverview->getDate()->format(DateTimeInterface::RFC3339_EXTENDED)
                            : '',
                    ]
                );
            }
        }

        // Adds any stored messages that were not in the server Inbox
        foreach ($storedMessages as $message) {
            $messageCollection->add($message);
        }

        return $messageCollection;
    }

    /**
     * @param InboxClientInterface $client
     * @param EmailBackupAccount   $account
     * @param InboxMessageOverview $overview
     *
     * @throws InboxImportException
     *
     * @return InboxMessage
     */
    public function importServerMessage(
        InboxClientInterface $client,
        EmailBackupAccount $account,
        InboxMessageOverview $overview
    ): InboxMessage {
        // Fetches the message from the server.
        try {
            /** @var InboxMessage $message */
            $message = $client->getMessage($overview);
        } catch (SystemMailExceptionInterface $e) {
            throw new InboxImportException('Failed to fetch message from server', $e->getCode(), $e);
        }

        // Creates an entry in `emailbackup_mails`.
        $backup = $this->backupRepository->createFromInboxMessage($message, $account->getId());

        // Stores the raw email in the fire-and-forget storage.
        if (
            $message->getOriginalMessage() !== null
            && $message->getOriginalMessage() instanceof MailMessageInterface
        ) {
            $this->archive->saveMessage($message->getOriginalMessage(), $backup->getId());
        }

        // TODO: maybe delete the message if it is more days old than allowed
        if ($account->isDeleteAfterImportEnabled()) {
            try {
                $client->deleteMessage($overview);
            } catch (SystemMailExceptionInterface $e) {
                $this->logger->warning(
                    'Failed to delete email from server',
                    [
                        'recipient' => $account->getEmailAddress(),
                        'sender' => $overview->getSenderEmailAddress(),
                        'subject' => $overview->getSubject(),
                        'date' => $overview->getDate() !== null
                            ? $overview->getDate()->format(DateTimeInterface::RFC3339_EXTENDED)
                            : '',
                    ]
                );
            }

            return $message;
        }

        if (!$this->isMessageMarkedAsRead($message)) {
            try {
                $client->markMessageAsRead($overview);
            } catch (SystemMailExceptionInterface $e) {
                $this->logger->warning(
                    'Failed to mark email as read',
                    [
                        'recipient' => $account->getEmailAddress(),
                        'sender' => $overview->getSenderEmailAddress(),
                        'subject' => $overview->getSubject(),
                        'date' => $overview->getDate() !== null
                            ? $overview->getDate()->format(DateTimeInterface::RFC3339_EXTENDED)
                            : '',
                    ]
                );
            }
        }

        return $message;
    }

    /**
     * @param InboxMessageInterface $message
     *
     * @return bool
     */
    private function isMessageMarkedAsRead(InboxMessageInterface $message): bool
    {
        $class = get_class($message);
        if ($class === InboxMessage::class) {
            /** @var InboxMessage $message */
            /** @var MailMessageInterface|null $imapMessage */
            $imapMessage = $message->getOriginalMessage();
            if ($imapMessage === null) {
                return false;
            }

            return $imapMessage->hasFlag('\\Seen');
        }
        // Emails coming from the internal storage are regarded as read.
        if ($class === EmailBackupMessage::class) {
            return true;
        }

        return false;
    }

    /**
     * Chooses the start date for the import.
     * precedence:
     *  1. start from the date that is specified in the account
     *  2. start from last import date
     *  3. start from 5 days in the past
     *
     * @param EmailBackupAccount $account
     *
     * @return DateTimeInterface
     */
    private function chooseImportDateTime(EmailBackupAccount $account): DateTimeInterface
    {
        $importDate = $account->getImportStartDate();

        if (empty($importDate)) {
            $importDate = $this->backupRepository->findLatestImportDateByAccountId($account->getId());
        }

        if (empty($importDate)) {
            $startFromPastDays = self::DEFAULT_PAST_DAYS;
            $importDate = (new DateTimeImmutable('now'))
                ->sub(new DateInterval("P{$startFromPastDays}D"));
        }

        return $importDate;
    }

    /**
     * @param InboxMessageOverviewCollection $overviews
     * @param int                            $accountId
     *
     * @return int[] array of checksums of already imported messages
     */
    private function getImportedChecksumMap(InboxMessageOverviewCollection $overviews, int $accountId): array
    {
        $searchChecksums = [];
        foreach ($overviews as $overview) {
            $searchChecksums[] = $this->backupRepository->generateHashByMessage($overview);
        }

        return $this->backupRepository->getChecksumIdMap($searchChecksums, $accountId);
    }
}
