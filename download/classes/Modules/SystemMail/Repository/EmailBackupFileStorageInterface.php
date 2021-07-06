<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\Repository;

use Xentral\Components\MailClient\Data\MailMessageInterface;
use Xentral\Modules\SystemMail\Exception\EmailBackupFileSystemException;

interface EmailBackupFileStorageInterface
{
    /**
     * Saves the complete encoded raw email message including headers, attachments, etc.
     *
     * @param int                  $backupId
     * @param MailMessageInterface $message
     *
     * @throws EmailBackupFileSystemException
     *
     * @return void
     */
    public function saveRawBackup(int $backupId, MailMessageInterface $message): void;
}
