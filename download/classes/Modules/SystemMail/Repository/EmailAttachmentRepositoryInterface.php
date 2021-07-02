<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\Repository;

use Xentral\Components\MailClient\Data\MailAttachmentInterface;
use Xentral\Modules\SystemMail\Data\InboxMessageAttachmentCollection;

interface EmailAttachmentRepositoryInterface
{
    /**
     * @param MailAttachmentInterface $attachment
     * @param int                     $emailBackupId
     *
     * @return int id int the repository attachment table
     */
    public function createAttachment(MailAttachmentInterface $attachment, int $emailBackupId): int;

    /**
     * @param int $emailBackupId
     *
     * @return InboxMessageAttachmentCollection
     */
    public function getAttachments(int $emailBackupId): InboxMessageAttachmentCollection;
}
