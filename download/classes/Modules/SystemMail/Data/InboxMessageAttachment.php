<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\Data;

use Xentral\Modules\FileManagement\Data\File;

class InboxMessageAttachment implements InboxMessageAttachmentInterface
{
    /** @var string|null $name */
    private $name;

    /** @var string|null $filePath */
    private $filePath;

    /** @var int $fileDataId */
    private $fileDataId;

    /**
     * @param string|null $name
     * @param string|null $filePath
     * @param int         $fileDataId
     */
    public function __construct(
        ?string $name = null,
        ?string $filePath = null,
        int $fileDataId = 0
    ) {
        $this->name = $name;
        $this->filePath = $filePath;
        $this->fileDataId = $fileDataId;
    }

    /**
     * @param File $fileData
     *
     * @return static
     */
    public static function createFromFileData(File $fileData): self
    {
        return new self(
            $fileData->getTitle(),
            $fileData->getVersionInfo()->getFilePath(),
            $fileData->getId()
        );
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name ?? '';
    }

    /**
     * @return string|null
     */
    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    /**
     * @return int
     */
    public function getFileDataId(): int
    {
        return $this->fileDataId;
    }
}
