<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\Service;

use Xentral\Modules\SystemMail\Data\EmailAttachmentData;
use Xentral\Modules\SystemMail\Exception\EmailAttachmentNotFoundException;
use Xentral\Modules\SystemMail\Exception\UnableToSaveEmailAttachmentException;

interface EmailAttachmentServiceInterface
{
    /**
     * @param int    $emailBackupId
     * @param string $fileName
     * @param string $path
     * @param string $contentType
     * @param string $transferEncoding
     * @param bool   $isInlineAttachment
     * @param string $cid
     *
     * @throws UnableToSaveEmailAttachmentException
     *
     * @return int
     */
    public function createAttachment(
        int $emailBackupId,
        string $fileName,
        string $path,
        string $contentType,
        string $transferEncoding,
        bool $isInlineAttachment,
        string $cid
    ): int;

    /**
     * @param EmailAttachmentData $attachmentData
     *
     * @throws UnableToSaveEmailAttachmentException
     */
    public function updateAttachment(
        EmailAttachmentData $attachmentData
    ): void;

    /**
     * @param int $emailBackupId
     *
     * @return array|EmailAttachmentData[]|null
     */
    public function findAttachments(int $emailBackupId): ?array;

    /**
     * @param int $emailAttachmentId
     *
     * @throws EmailAttachmentNotFoundException
     *
     * @return EmailAttachmentData
     */
    public function getAttachment(int $emailAttachmentId): EmailAttachmentData;

    /**
     * @param int $attachmentId
     */
    public function deleteAttachment(int $attachmentId);
}
