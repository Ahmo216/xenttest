<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\Repository;

use Xentral\Components\MailClient\Data\MailAttachmentInterface;
use Xentral\Modules\FileManagement\Data\FileAssociation;
use Xentral\Modules\FileManagement\Manager\FileManagerInterface;
use Xentral\Modules\SystemMail\Data\InboxMessageAttachment;
use Xentral\Modules\SystemMail\Data\InboxMessageAttachmentCollection;

final class EmailAttachmentRepository implements EmailAttachmentRepositoryInterface
{
    /** @var string */
    private const FILE_MANAGER_ENTITY = 'E-Mail';

    /** @var string */
    private const FILE_MANAGER_DOCTYPE = 'anhang';

    /** @var string */
    private const FILE_MANAGER_CREATOR = 'inbox_importer';

    /** @var FileManagerInterface $fileManager */
    private $fileManager;

    /**
     * @param FileManagerInterface $fileManager
     */
    public function __construct(FileManagerInterface $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    /**
     * Saves the Attachment in the FileManagement.
     *
     * @param MailAttachmentInterface $attachment
     * @param int                     $emailBackupId
     *
     * @return int
     */
    public function createAttachment(MailAttachmentInterface $attachment, int $emailBackupId): int
    {
        $fileData = $this->fileManager->createFile(
            $attachment->getContent(),
            $attachment->getFileName(),
            $attachment->getContentType(),
            self::FILE_MANAGER_CREATOR
        );

        $fileData->addAssociation(new FileAssociation(
            $fileData->getId(),
            self::FILE_MANAGER_DOCTYPE,
            self::FILE_MANAGER_ENTITY,
            $emailBackupId
        ));

        $this->fileManager->updateFile($fileData);

        return $fileData->getId();
    }

    /**
     * Gets all attachments for a specific email backup from the
     * FileManagementService.
     *
     * @param int $emailBackupId
     *
     * @return InboxMessageAttachmentCollection
     */
    public function getAttachments(int $emailBackupId): InboxMessageAttachmentCollection
    {
        $files = $this->fileManager->findByEntity(
            self::FILE_MANAGER_ENTITY,
            $emailBackupId,
            self::FILE_MANAGER_DOCTYPE
        );
        $attachments = new InboxMessageAttachmentCollection();
        foreach ($files as $file) {
            $attachments->addAttachment(InboxMessageAttachment::createFromFileData($file));
        }

        return $attachments;
    }
}
