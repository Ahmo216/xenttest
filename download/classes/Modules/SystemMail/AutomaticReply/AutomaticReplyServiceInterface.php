<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\AutomaticReply;

use Xentral\Modules\SystemMail\Data\InboxMessageInterface;
use Xentral\Modules\SystemMailer\Data\EmailBackupAccount;

interface AutomaticReplyServiceInterface
{
    /**
     * Creates an automatic reply and queues it.
     * Decides based on EmailAccount Settings
     * whether or not to really send the a reply.
     *
     * @param InboxMessageInterface $message
     * @param EmailBackupAccount    $account
     */
    public function sendAutomaticReply(InboxMessageInterface $message, EmailBackupAccount $account): void;
}
