<?php

declare(strict_types=1);

namespace Xentral\Modules\FileManagement\Manager;

use Xentral\Modules\FileManagement\Data\File;
use Xentral\Modules\FileManagement\Data\FileVersion;
use Xentral\Modules\FileManagement\Exception\FileDataNotFoundException;
use Xentral\Modules\FileManagement\Exception\InvalidArgumentException;

interface FileManagerInterface
{
    /**
     * Stores string contents as a new file in the file management system.
     *
     * @param string $fileContents content to be written to the file
     * @param string $fileName     the display name of the file
     * @param string $description  a description of the file
     * @param string $creatorName  module or user creating the file
     *
     * @throws InvalidArgumentException
     *
     * @return File
     */
    public function createFile(
        string $fileContents,
        string $fileName,
        string $description,
        string $creatorName
    ): File;

    /**
     * Stores a copy of specified file as a new file in the file management system.
     *
     * Original file is NOT deleted.
     *
     * @param string $originalFilePath content of this file will be written to the new file
     * @param string $fileName
     * @param string $description
     * @param string $creatorName
     *
     * @return File
     */
    public function createFileByFileCopy(
        string $originalFilePath,
        string $fileName,
        string $description,
        string $creatorName
    ): File;

    /**
     * Stores string contents as a new version of a file.
     *
     * @param int    $fileId
     * @param string $fileContents
     * @param string $fileName
     * @param string $creatorName
     * @param string $versionDescription
     *
     * @return FileVersion
     */
    public function createFileVersion(
        int $fileId,
        string $fileContents,
        string $fileName,
        string $creatorName,
        string $versionDescription
    ): FileVersion;

    /**
     * Stores a copy of specified file as a new version of a file.
     *
     * @param int    $fileId
     * @param string $originalFilePath
     * @param string $fileName
     * @param string $creatorName
     * @param string $versionDescription
     *
     * @return FileVersion
     */
    public function createFileVersionByFileCopy(
        int $fileId,
        string $originalFilePath,
        string $fileName,
        string $creatorName,
        string $versionDescription
    ): FileVersion;

    /**
     * Finds all files that are associated to the entity and match the document type.
     *
     * If $documentType is null, all files associated
     * to the entity will be returned.
     *
     * @param string      $owningEntity case in-sensitive
     * @param int         $entityId
     * @param string|null $documentType
     *
     * @return File[]
     */
    public function findByEntity(string $owningEntity, int $entityId, ?string $documentType = null): array;

    /**
     * Gets the specified file by id.
     *
     * @param int $fileId
     *
     * @throws FileDataNotFoundException
     *
     * @return File
     */
    public function getFileById(int $fileId): File;

    /**
     * Updates the File entry, version and all associations.
     *
     * @param File $file
     *
     * @throws FileDataNotFoundException
     *
     * @return void
     */
    public function updateFile(File $file): void;

    /**
     * Gets all available file versions of specified file.
     *
     * @param int $fileId
     *
     * @return FileVersion[]
     */
    public function getAllFileVersions(int $fileId): array;
}
