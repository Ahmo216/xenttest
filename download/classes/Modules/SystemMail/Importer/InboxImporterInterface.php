<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\Importer;

use Xentral\Modules\SystemMail\Data\InboxMessageCollection;
use Xentral\Modules\SystemMail\Inbox\InboxClientInterface;
use Xentral\Modules\SystemMailer\Data\EmailBackupAccount;

interface InboxImporterInterface
{
    /**
     * Imports emails according to the settings defined in the account.
     *
     * This interface does not have opinion on where the email should be
     * imported from. The source could be a remote third-party email server,
     * or the messages could be already in the system, and for example
     * imported internally from the local database into the local ticket system.
     *
     * @param InboxClientInterface $client
     * @param EmailBackupAccount   $account
     *
     * @return InboxMessageCollection
     */
    public function import(InboxClientInterface $client, EmailBackupAccount $account): InboxMessageCollection;
}
