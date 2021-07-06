<?php

declare(strict_types=1);

namespace Xentral\Modules\FileManagement\Data;

use DateTime;
use Exception;

class FileVersion
{
    /** @var int */
    private $id;

    /** @var string */
    private $fileName;

    /** @var string|null */
    private $creatorName;

    /** @var string */
    private $description = '';

    /** @var int|null */
    private $sizeInBytes;

    /** @var int|null */
    private $version;

    /** @var string|null */
    private $filePath;

    /** @var DateTime|null */
    private $dateCreated;

    /** @var int $fileId */
    private $fileId;

    public function __construct(
        int $fileId,
        ?string $fileName = null,
        ?string $creatorName = null,
        ?string $description = null,
        ?int $version = null
    ) {
        $this->fileName = $fileName;
        $this->creatorName = $creatorName;
        $this->description = $description;
        $this->version = $version;
        $this->id = 0;
        $this->setSizeInBytes(0);
        $this->fileId = $fileId;
    }

    /**
     * @param array $data
     *
     * @return static
     */
    public static function fromDbState(array $data): self
    {
        $fileVersion = new self($data['file_id'] ?? 0);

        $fileVersion->id = $data['file_version_id'] ?? 0;
        $fileVersion->fileName = $data['file_name'] ?? null;
        $fileVersion->description = $data['note'] ?? null;
        $fileVersion->creatorName = $data['creator_name'] ?? null;
        $fileVersion->setSizeInBytes(isset($data['size']) ? (int)$data['size'] : null);
        $fileVersion->version = $data['version'] ?? null;
        $fileVersion->filePath = $data['file_path'] ?? null;

        if (empty($data['date_created']) || $data['date_created'] === '0000-00-00') {
            return $fileVersion;
        }

        try {
            $dateTime = new DateTime($data['date_created']);
            $fileVersion->dateCreated = !empty($dateTime) ? $dateTime : null;
        } catch (Exception $e) {
            $fileVersion->dateCreated = null;
        }

        return $fileVersion;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * @param string|null $fileName
     */
    public function setFileName(?string $fileName): void
    {
        $this->fileName = $fileName;
    }

    /**
     * @return string|null
     */
    public function getCreatorName(): ?string
    {
        return $this->creatorName;
    }

    /**
     * @param string|null $creatorName
     */
    public function setCreatorName(?string $creatorName): void
    {
        $this->creatorName = $creatorName;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int|null
     */
    public function getSizeInBytes(): ?int
    {
        return $this->sizeInBytes;
    }

    /**
     * @param int|null $sizeInBytes
     */
    public function setSizeInBytes(?int $sizeInBytes): void
    {
        $this->sizeInBytes = $sizeInBytes;
    }

    /**
     * @return int|null
     */
    public function getVersion(): ?int
    {
        return $this->version;
    }

    /**
     * @param int|null $version
     */
    public function setVersion(?int $version): void
    {
        $this->version = $version;
    }

    /**
     * @return string|null
     */
    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    /**
     * @param string|null $filePath
     */
    public function setFilePath(?string $filePath): void
    {
        $this->filePath = $filePath;
    }

    /**
     * @return DateTime|null
     */
    public function getDateCreated(): ?DateTime
    {
        return $this->dateCreated;
    }

    /**
     * @return int
     */
    public function getFileId(): int
    {
        return $this->fileId;
    }
}
