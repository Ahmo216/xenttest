<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\Data;

use DateTimeImmutable;
use DateTimeInterface;
use Exception;

final class EmailAttachmentData
{
    /** @var int $id */
    private $id;

    /** @var int $emailBackupId */
    private $emailBackupId;

    /** @var string $fileName */
    private $fileName;

    /** @var string $filePath */
    private $filePath;

    /** @var string $contentType */
    private $contentType;

    /** @var string $transferEncoding */
    private $transferEncoding;

    /** @var string $contentId */
    private $contentId;

    /** @var bool $isInlineAttachment */
    private $isInlineAttachment;

    /** @var DateTimeInterface $createdAt */
    private $createdAt;

    private function __construct()
    {
    }

    /**
     * @param array $data
     *
     * @return EmailAttachmentData
     */
    public static function fromDbState(array $data): EmailAttachmentData
    {
        $instance = new self();

        $instance->emailBackupId = (int)$data['email_backup_id'];
        $instance->fileName = $data['file_name'];
        $instance->filePath = $data['file_path'];
        $instance->contentType = $data['content_type'];
        $instance->transferEncoding = $data['transfer_encoding'];
        $instance->isInlineAttachment = (bool)$data['is_inline_attachment'];
        $instance->id = (int)$data['id'];
        if (!empty($data['content_id'])) {
            $instance->contentId = $data['content_id'];
        }

        try {
            $instance->createdAt = new DateTimeImmutable($data['created_at']);
        } catch (Exception $e) {
            $instance->createdAt = null;
        }

        return $instance;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getEmailBackupId(): int
    {
        return $this->emailBackupId;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * @return string
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }

    /**
     * @return string
     */
    public function getTransferEncoding(): string
    {
        return $this->transferEncoding;
    }

    /**
     * @return string|null
     */
    public function getCid(): ?string
    {
        return $this->contentId;
    }

    /**
     * @return bool
     */
    public function isInlineAttachment(): bool
    {
        return $this->isInlineAttachment;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param int $emailBackupId
     */
    public function setEmailBackupId(int $emailBackupId): void
    {
        $this->emailBackupId = $emailBackupId;
    }

    public function getContent()
    {
    }

    public function setContent()
    {
    }
}
