<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\EmailArchive;

use Xentral\Components\MailClient\Data\MailMessageInterface;
use Xentral\Modules\SystemMail\Exception\EmailArchiveCannotSaveException;

interface EmailArchiveInterface
{
    /**
     * Stores a raw email and it's attachments in the email archive
     *
     * @param MailMessageInterface $message
     * @param int                  $emailBackupId
     *
     * @throws EmailArchiveCannotSaveException
     *
     * @return void
     */
    public function saveMessage(MailMessageInterface $message, int $emailBackupId): void;
}
