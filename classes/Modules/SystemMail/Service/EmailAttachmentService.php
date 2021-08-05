<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\Service;

use Xentral\Components\Database\Database;
use Xentral\Modules\SystemMail\Data\EmailAttachmentData;
use Xentral\Modules\SystemMail\Exception\EmailAttachmentNotFoundException;
use Xentral\Modules\SystemMail\Exception\UnableToSaveEmailAttachmentException;

final class EmailAttachmentService implements EmailAttachmentServiceInterface
{
    /** @var Database */
    private $db;

    /**
     * @param Database $db
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * @param int    $emailBackupId
     * @param string $fileName
     * @param string $path
     * @param string $contentType
     * @param string $transferEncoding
     * @param bool   $isInlineAttachment
     * @param string $cid
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
    ): int {
        $sql =
            'INSERT INTO `email_attachment`
            (`email_backup_id`, `file_name`, `file_path`, `content_type`, `transfer_encoding`, `content_id`, `is_inline_attachment`) 
            VALUES
            (:email_backup_id, :file_name, :file_path, :content_type, :transfer_encoding, :content_id, :is_inline_attachment)
            ';

        $data = [
            'email_backup_id' => $emailBackupId,
            'file_name' => $fileName,
            'file_path' => $path,
            'content_type' => $contentType,
            'transfer_encoding' => $transferEncoding,
            'content_id' => $cid,
            'is_inline_attachment' => $isInlineAttachment,
        ];

        $this->db->perform($sql, $data);
        $insertId = $this->db->lastInsertId();
        if ($insertId === 0) {
            throw new UnableToSaveEmailAttachmentException(
                "Attachment {$fileName} could not be saved."
            );
        }

        return $insertId;
    }

    /**
     * @param EmailAttachmentData $attachmentData
     *
     * @throws UnableToSaveEmailAttachmentException
     */
    public function updateAttachment(
        EmailAttachmentData $attachmentData
    ): void {
        if (empty($attachmentData->getId())) {
            throw new UnableToSaveEmailAttachmentException(
                "Attachment {$attachmentData->getFileName()} could not be saved. ID is missing"
            );
        }

        $sql =
            'UPDATE `email_attachment` SET
            `email_backup_id` = :email_backup_id, 
            `file_name` = :file_name, 
            `file_path` = :file_path, 
            `content_type` = :content_type, 
            `transfer_encoding` = :transfer_encoding, 
            `content_id` = :content_id, 
            `is_inline_attachment` =  :is_inline_attachment
            WHERE `id` = :id';

        $data = [
            'id' => $attachmentData->getId(),
            'email_backup_id' => $attachmentData->getEmailBackupId(),
            'file_name' => $attachmentData->getFileName(),
            'file_path' => $attachmentData->getFilePath(),
            'content_type' => $attachmentData->getContentType(),
            'transfer_encoding' => $attachmentData->getTransferEncoding(),
            'content_id' => $attachmentData->getCid(),
            'is_inline_attachment' => $attachmentData->isInlineAttachment(),
        ];

        $this->db->perform($sql, $data);
    }

    /**
     * @param int $emailBackupId
     *
     * @return array|EmailAttachmentData[]|null
     */
    public function findAttachments(int $emailBackupId): ?array
    {
        $sql =
            'SELECT 
            ea.id,
            ea.email_backup_id,
            ea.file_name,
            ea.file_path,
            ea.content_type,
            ea.transfer_encoding,
            ea.content_id,
            ea.is_inline_attachment
            FROM `email_attachment` AS `ea`
            WHERE ea.email_backup_id = :email_backup_id';

        $results = $this->db->fetchAll($sql, ['email_backup_id' => $emailBackupId]);

        if (empty($results)) {
            return null;
        }
        $return = [];
        foreach ($results as $result) {
            $return[] = EmailAttachmentData::fromDbState($result);
        }

        return $return;
    }

    /**
     * @param int $emailAttachmentId
     *
     * @throws EmailAttachmentNotFoundException
     *
     * @return EmailAttachmentData
     */
    public function getAttachment(int $emailAttachmentId): EmailAttachmentData
    {
        $sql =
            'SELECT 
            ea.id,
            ea.email_backup_id,
            ea.file_name,
            ea.file_path,
            ea.content_type,
            ea.transfer_encoding,
            ea.content_id,
            ea.is_inline_attachment
            FROM `email_attachment` AS `ea`
            WHERE ea.id = :email_attachment_id';

        $result = $this->db->fetchRow($sql, ['email_attachment_id' => $emailAttachmentId]);

        if (empty($result)) {
            throw new EmailAttachmentNotFoundException(
                "The attachment with the id {$emailAttachmentId} does not exist"
            );
        }

        return EmailAttachmentData::fromDbState($result);
    }

    /**
     * @param int $attachmentId
     */
    public function deleteAttachment(int $attachmentId): void
    {
        $sql =
            'DELETE FROM `email_attachment` WHERE id = :attachment_id';
        $this->db->perform($sql, ['attachment_id' => $attachmentId]);
    }
}
