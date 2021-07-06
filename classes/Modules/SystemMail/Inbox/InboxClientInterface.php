<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\Inbox;

use DateTimeInterface;
use Xentral\Modules\SystemMail\Data\InboxMessageCollection;
use Xentral\Modules\SystemMail\Data\InboxMessageInterface;
use Xentral\Modules\SystemMail\Data\InboxMessageOverview;
use Xentral\Modules\SystemMail\Data\InboxOverview;
use Xentral\Modules\SystemMail\Exception\InboxConnectionException;
use Xentral\Modules\SystemMail\Exception\InboxFolderNotFoundException;
use Xentral\Modules\SystemMail\Exception\InboxMessageNotFoundException;
use Xentral\Modules\SystemMail\Exception\InboxProtocolException;

interface InboxClientInterface
{
    /**
     * Fetches a view of the inbox that holds overviews of
     * the messages that match the criteria.
     *
     * @param DateTimeInterface $date
     * @param string|null       $directory
     *
     * @throws InboxFolderNotFoundException
     * @throws InboxConnectionException
     *
     *@return InboxOverview
     */
    public function getInboxOverviewSince(DateTimeInterface $date, ?string $directory = null): InboxOverview;

    /**
     * Fetches all messages from the inbox that are
     * listed in the InboxView.
     *
     * @param InboxOverview $inboxView
     *
     * @throws InboxFolderNotFoundException
     * @throws InboxConnectionException
     *
     * @return InboxMessageCollection
     */
    public function getMessages(InboxOverview $inboxView): InboxMessageCollection;

    /**
     * Fetches a single message from the inbox.
     *
     * @param InboxMessageOverview $overview
     *
     * @throws InboxFolderNotFoundException
     * @throws InboxConnectionException
     *
     * @return InboxMessageInterface
     */
    public function getMessage(InboxMessageOverview $overview): InboxMessageInterface;

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
    public function markMessageAsRead(InboxMessageOverview $overview): void;

    /**
     * Deletes the message From the Inbox
     *
     * @param InboxMessageOverview $overview
     *
     * @throws InboxFolderNotFoundException
     * @throws InboxConnectionException
     * @throws InboxMessageNotFoundException
     * @throws InboxProtocolException
     *
     * @return void
     */
    public function deleteMessage(InboxMessageOverview $overview): void;
}
