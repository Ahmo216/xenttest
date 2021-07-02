<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\EmailArchive;

use Xentral\Components\Filesystem\Filesystem;
use Xentral\Components\Filesystem\FilesystemInterface;
use Xentral\Components\MailClient\Data\MailAttachmentInterface;
use Xentral\Components\MailClient\Data\MailMessageInterface;
use Xentral\Components\Util\StringUtil;
use Xentral\Modules\SystemMail\Exception\EmailArchiveCannotSaveException;

final class EmailArchive implements EmailArchiveInterface
{
    /** @var Filesystem $fileSystem */
    private $fileSystem;

    /**
     * @param FilesystemInterface $fileSystem
     */
    public function __construct(FilesystemInterface $fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    /**
     * @param MailMessageInterface $message
     * @param int                  $emailBackupId
     *
     * @throws EmailArchiveCannotSaveException
     *
     * @return void
     */
    public function saveMessage(MailMessageInterface $message, int $emailBackupId): void
    {
        $archivePath = (string)$emailBackupId;
        $messageFilePath = $archivePath . DIRECTORY_SEPARATOR . 'mail.txt';

        // It MUST NOT be possible to overwrite files in the email archive.
        if ($this->fileSystem->has($messageFilePath)) {
            return;
        }

        $success = $this->fileSystem->write($messageFilePath, $message->getRawContent());
        foreach ($message->getAttachments() as $attachment) {
            $this->saveAttachment($attachment, $archivePath);
        }

        if (!$success) {
            throw new EmailArchiveCannotSaveException('Unable to write in the email archive. Check permissions!');
        }
    }

    /**
     * @param MailAttachmentInterface $attachment
     * @param string                  $archivePath
     *
     * @return void
     */
    private function saveAttachment(MailAttachmentInterface $attachment, string $archivePath): void
    {
        $attachmentFilePath = $archivePath
            . DIRECTORY_SEPARATOR
            . StringUtil::toFilename($attachment->getFileName());

        // It MUST NOT be possible to overwrite files in the email archive.
        if ($this->fileSystem->has($attachmentFilePath)) {
            return;
        }

        $success = $this->fileSystem->write($attachmentFilePath, $attachment->getContent());

        if (!$success) {
            throw new EmailArchiveCannotSaveException('Unable to write in the email archive. Check permissions!');
        }
    }
}
