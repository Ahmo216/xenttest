<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\Inbox;

use DateTimeInterface;
use Exception;
use Xentral\Components\Logger\LoggerAwareTrait;
use Xentral\Components\MailClient\Client\MailClientInterface;
use Xentral\Components\MailClient\Exception\FolderNotFoundException;
use Xentral\Components\MailClient\Exception\MessageNotFoundException;
use Xentral\Components\MailClient\Exception\ProtocolException;
use Xentral\Modules\SystemMail\Data\InboxMessage;
use Xentral\Modules\SystemMail\Data\InboxMessageCollection;
use Xentral\Modules\SystemMail\Data\InboxMessageInterface;
use Xentral\Modules\SystemMail\Data\InboxMessageOverview;
use Xentral\Modules\SystemMail\Data\InboxMessageOverviewCollection;
use Xentral\Modules\SystemMail\Data\InboxOverview;
use Xentral\Modules\SystemMail\Exception\InboxConnectionException;
use Xentral\Modules\SystemMail\Exception\InboxFolderNotFoundException;
use Xentral\Modules\SystemMail\Exception\InboxMessageNotFoundException;
use Xentral\Modules\SystemMail\Exception\InboxProtocolException;

class ImapInboxClient implements InboxClientInterface
{
    use LoggerAwareTrait;

    /** @var string */
    private const DEFAULT_FOLDER = 'INBOX';

    /** @var MailClientInterface $mailClient */
    private $mailClient;

    /** @var bool */
    private $isConnected;

    /** @var string */
    private $selectedFolder;

    /**
     * InboxClient constructor.
     *
     * @param MailClientInterface $mailCleint
     */
    public function __construct(MailClientInterface $mailCleint)
    {
        $this->mailClient = $mailCleint;
        $this->isConnected = false;
    }

    /**
     * @param DateTimeInterface $date
     * @param string|null       $directory
     *
     * @throws InboxConnectionException
     * @throws InboxFolderNotFoundException
     * @throws InboxProtocolException
     *
     * @return InboxOverview
     */
    public function getInboxOverviewSince(DateTimeInterface $date, ?string $directory = null): InboxOverview
    {
        //establish connection and select the correct folder
        $this->connect();
        $folder = $directory ?? self::DEFAULT_FOLDER;
        $this->selectFolder($folder);

        $searchCriteria = $this->createImapSearchString($date);

        try {
            $messageIds = $this->mailClient->searchMessages($searchCriteria);
        } catch (Exception $e) {
            throw new InboxProtocolException($e->getMessage(), $e->getCode(), $e);
        }
        if (empty($messageIds)) {
            return new InboxOverview($folder);
        }

        $messages = new InboxMessageOverviewCollection();
        foreach ($messageIds as $messageId) {
            try {
                $mailOverview = $this->mailClient->fetchMessageOverview((int)$messageId);
            } catch (MessageNotFoundException $e) {
                continue;
            }

            $overview = new InboxMessageOverview(
                $mailOverview->getTemporaryId(),
                $folder,
                $mailOverview->getHeaders()
            );

            if (
                $overview->getDate() === null
                || $overview->getDate()->getTimestamp() > $date->getTimestamp()
            ) {
                $messages->add($overview);
            }
        }

        return new InboxOverview($folder, $messages);
    }

    /**
     * @param InboxOverview $inboxView
     *
     * @throws InboxConnectionException
     * @throws InboxFolderNotFoundException
     *
     * @return InboxMessageCollection
     */
    public function getMessages(InboxOverview $inboxView): InboxMessageCollection
    {
        $this->connect();

        $messages = new InboxMessageCollection();
        foreach ($inboxView->getOverviews() as $overview) {
            try {
                $messages->add($this->getMessage($overview));
            } catch (InboxMessageNotFoundException $e) {
                $this->logger->error(
                    'Message not found.',
                    [
                        'inbox' => $overview->getHeaders()->has('To')
                            ? $overview->getHeaders()->get('To')->getValue()
                            : null,
                        'folder' => $overview->getFolder(),
                        'subject' => $overview->getSubject(),
                        'date' => !empty($overview->getDate())
                            ? $overview->getDate()->format('Y-m-d H:i:s')
                            : null,
                    ]
                );

                continue;
            }
        }

        return $messages;
    }

    /**
     * @param InboxMessageOverview $overview
     *
     * @throws InboxConnectionException
     * @throws InboxFolderNotFoundException
     * @throws InboxMessageNotFoundException
     *
     * @return InboxMessageInterface
     */
    public function getMessage(InboxMessageOverview $overview): InboxMessageInterface
    {
        //establish connection and select the correct folder
        $this->connect();
        $folder = $overview->getFolder() ?? self::DEFAULT_FOLDER;
        $this->selectFolder($folder);

        $id = $overview->getTemporaryId();

        try {
            $mailMessage = $this->mailClient->fetchMessage($id);

            return InboxMessage::createFromMailMessage($mailMessage);
        } catch (MessageNotFoundException $e) {
            throw new InboxMessageNotFoundException(
                "Message '{$id}' not found in '{$folder}'",
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * @param InboxMessageOverview $overview
     *
     * @throws InboxConnectionException
     * @throws InboxFolderNotFoundException
     * @throws InboxMessageNotFoundException
     * @throws InboxProtocolException
     *
     * @return void
     */
    public function deleteMessage(InboxMessageOverview $overview): void
    {
        if ($overview->getTemporaryId() < 1) {
            throw new InboxMessageNotFoundException('Message not found by ID');
        }

        $this->connect();
        $this->selectFolder($overview->getFolder() ?? self::DEFAULT_FOLDER);

        try {
            $this->mailClient->deleteMessage($overview->getTemporaryId());
        } catch (ProtocolException $e) {
            throw new InboxProtocolException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param InboxMessageOverview $overview
     *
     * @throws InboxFolderNotFoundException
     * @throws InboxConnectionException
     * @throws InboxMessageNotFoundException
     * @throws InboxProtocolException
     *
     * @return void
     */
    public function markMessageAsRead(InboxMessageOverview $overview): void
    {
        if ($overview->getTemporaryId() < 1) {
            throw new InboxMessageNotFoundException('Message not found by ID');
        }

        $this->connect();
        $this->selectFolder($overview->getFolder() ?? self::DEFAULT_FOLDER);

        try {
            $this->mailClient->setFlags($overview->getTemporaryId(), ['\\Seen']);
            $this->mailClient->expunge();
        } catch (ProtocolException $e) {
            throw new InboxProtocolException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws InboxConnectionException
     *
     * @return void
     */
    private function connect(): void
    {
        if ($this->isConnected) {
            return;
        }

        try {
            $this->mailClient->connect();
            $this->isConnected = true;
        } catch (Exception $e) {
            throw new InboxConnectionException('Inbox connection failed.', $e->getCode(), $e);
        }
    }

    /**
     * @param string $folder
     *
     * @throws InboxFolderNotFoundException
     *
     * @return void
     */
    private function selectFolder(string $folder): void
    {
        if ($this->selectedFolder === $folder) {
            return;
        }
        try {
            $this->mailClient->selectFolder($folder);
            $this->selectedFolder = $folder;
        } catch (FolderNotFoundException $e) {
            throw new InboxFolderNotFoundException("Folder {$folder} not found in Inbox", $e->getCode(), $e);
        }
    }

    /**
     * Create search criteria string for the IMAP SEARCH function.
     *
     * @param DateTimeInterface $date
     *
     * @return string
     */
    private function createImapSearchString(DateTimeInterface $date): string
    {
        $dateString = $date->format('d-M-Y');

        return "SINCE {$dateString}";
    }
}
