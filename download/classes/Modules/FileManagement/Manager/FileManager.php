<?php

declare(strict_types=1);

namespace Xentral\Modules\FileManagement\Manager;

use Xentral\Modules\FileManagement\Data\File;
use Xentral\Modules\FileManagement\Data\FileAssociation;
use Xentral\Modules\FileManagement\Data\FileVersion;
use Xentral\Modules\FileManagement\Exception\FileDataNotFoundException;
use Xentral\Modules\FileManagement\FileSystem\FileManagerStorageInterface;
use Xentral\Modules\FileManagement\Service\FileManagerGatewayInterface;
use Xentral\Modules\FileManagement\Service\FileManagerServiceInterface;

final class FileManager implements FileManagerInterface
{
    /** @var string */
    private const INITIAL_VERSION_COMMENT = 'Initiale Version';

    /** @var FileManagerGatewayInterface $gateway */
    private $gateway;

    /** @var FileManagerServiceInterface $service */
    private $service;

    /** @var FileManagerStorageInterface $fileStorage */
    private $fileStorage;

    public function __construct(
        FileManagerGatewayInterface $gateway,
        FileManagerServiceInterface $service,
        FileManagerStorageInterface $fileStorage
    ) {
        $this->gateway = $gateway;
        $this->service = $service;
        $this->fileStorage = $fileStorage;
    }

    /**
     * @inheritdoc
     */
    public function createFile(
        string $fileContents,
        string $fileName,
        string $description,
        string $creatorName
    ): File {
        // Create new file entry in database.
        $fileId = $this->service->insertFile(new File($fileName, $description));

        // Create new file version and store the actual file.
        $this->createFileVersion(
            $fileId,
            $fileContents,
            $fileName,
            $creatorName,
            self::INITIAL_VERSION_COMMENT
        );

        return $this->getFileById($fileId);
    }

    /**
     * @inheritdoc
     */
    public function createFileByFileCopy(
        string $originalFilePath,
        string $fileName,
        string $description,
        string $creatorName
    ): File {
        // Create new file entry in database.
        $fileId = $this->service->insertFile(new File($fileName, $description));

        // Create new file version and copy the actual file.
        $this->createFileVersionByFileCopy(
            $fileId,
            $originalFilePath,
            $fileName,
            $creatorName,
            self::INITIAL_VERSION_COMMENT
        );

        return $this->getFileById($fileId);
    }

    /**
     * @inheritdoc
     */
    public function createFileVersion(
        int $fileId,
        string $fileContents,
        string $fileName,
        string $creatorName,
        string $versionDescription
    ): FileVersion {
        $storeFileCallback = [$this->fileStorage, 'saveFile'];

        return $this->makeNewFileVersion(
            $fileId,
            $fileContents,
            $fileName,
            $creatorName,
            $versionDescription,
            $storeFileCallback
        );
    }

    /**
     * @inheritdoc
     */
    public function createFileVersionByFileCopy(
        int $fileId,
        string $originalFilePath,
        string $fileName,
        string $creatorName,
        string $versionDescription
    ): FileVersion {
        $storeFileCallback = [$this->fileStorage, 'saveFileByCopy'];

        return $this->makeNewFileVersion(
            $fileId,
            $originalFilePath,
            $fileName,
            $creatorName,
            $versionDescription,
            $storeFileCallback
        );
    }

    /**
     * @inheritdoc
     */
    public function findByEntity(string $owningEntity, int $entityId, ?string $documentType = null): array
    {
        $ids = $this->gateway->findFileIdsByEntity($owningEntity, $entityId, $documentType);

        $files = [];
        foreach ($ids as $fileId) {
            $files[] = $this->gateway->getFileById($fileId);
        }

        return $files;
    }

    /**
     * @inheritdoc
     */
    public function getFileById(int $fileId): File
    {
        return $this->gateway->getFileById($fileId);
    }

    /**
     * @inheritdoc
     */
    public function updateFile(File $file): void
    {
        if (!$this->gateway->existsFileEntry($file->getId())) {
            throw new FileDataNotFoundException("File [id={$file->getId()} does not exist.");
        }

        $this->service->updateFile($file);
        if ($file->getVersionInfo() !== null) {
            $this->saveVersion($file->getVersionInfo());
        }

        foreach ($file->getAssociations() as $association) {
            $this->saveAssociation($association);
        }
    }

    /**
     * @inheritdoc
     */
    public function getAllFileVersions(int $fileId): array
    {
        return $this->gateway->tryGetVersionsByFile($fileId);
    }

    /**
     * Gets the newest version entry of specified file.
     *
     * @param int $fileId
     *
     * @return FileVersion
     */
    private function getLatestVersion(int $fileId): FileVersion
    {
        $allVersions = $this->getAllFileVersions($fileId);
        if (empty($allVersions)) {
            throw new FileDataNotFoundException("File {$fileId} has no versions.");
        }
        uasort($allVersions, function (FileVersion $current, FileVersion $next) {
            return ($current->getVersion() < $next->getVersion()) ? -1 : 1;
        });

        return $allVersions[0];
    }

    /**
     * Creates a new file entry in the file management system.
     *
     * Uses $saveFileCallback to store the actual file to the filesystem.
     *
     * @param int      $fileId
     * @param string   $content
     * @param string   $fileName
     * @param string   $creatorName
     * @param string   $versionDescription
     * @param callable $SaveFileCallback
     *
     * @return FileVersion
     */
    private function makeNewFileVersion(
        int $fileId,
        string $content,
        string $fileName,
        string $creatorName,
        string $versionDescription,
        callable $SaveFileCallback
    ): FileVersion {

        // Determine the new version number.
        try {
            $versionNumber = $this->getLatestVersion($fileId)->getVersion() + 1;
        } catch (FileDataNotFoundException $e) {
            $versionNumber = 1;
        }
        $newVersion = new FileVersion(
            $fileId,
            $fileName,
            $creatorName,
            $versionDescription,
            $versionNumber
        );

        // Create the version entry in the database.
        $insertId = $this->service->insertFileVersion($newVersion);
        $insertedVersion = $this->gateway->getFileVersionById($insertId);

        // Store the actual file in the file system.
        $pathInfo = call_user_func($SaveFileCallback, $insertedVersion, $content);

        // Add the file size to the version entry.
        $insertedVersion->setSizeInBytes($pathInfo->getSize());
        $this->service->updateFileVersion($insertedVersion);

        return $insertedVersion;
    }

    private function saveVersion(FileVersion $fileVersion): void
    {
        if ($this->gateway->existsFileVersionEntry($fileVersion->getId())) {
            $this->service->updateFileVersion($fileVersion);

            return;
        }

        $this->service->insertFileVersion($fileVersion);
    }

    private function saveAssociation(FileAssociation $association): void
    {
        if ($this->gateway->existsFileAssociationEntry($association->getId())) {
            $this->service->updateAssociation($association);

            return;
        }

        $this->service->insertAssociation($association);
    }
}
