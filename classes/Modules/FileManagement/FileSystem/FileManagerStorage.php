<?php

declare(strict_types=1);

namespace Xentral\Modules\FileManagement\FileSystem;

use Exception;
use Xentral\Components\Filesystem\Exception\FileNotFoundException as FileSystemFileNotFoundException;
use Xentral\Components\Filesystem\Filesystem;
use Xentral\Components\Filesystem\PathInfo;
use Xentral\Modules\FileManagement\Data\FileVersion;
use Xentral\Modules\FileManagement\Exception\FileManagementWriteException;
use Xentral\Modules\FileManagement\Exception\FileNotFoundException;
use Xentral\Modules\FileManagement\Exception\InvalidArgumentException;
use Xentral\Modules\FileManagement\Helper\DmsPathHelper;

final class FileManagerStorage implements FileManagerStorageInterface
{
    /** @var DmsPathHelper $pathHelper */
    private $pathHelper;

    /** @var Filesystem $filesystem */
    private $filesystem;

    /**
     * @param DmsPathHelper     $pathHelper
     * @param Filesystem        $filesystem
     */
    public function __construct(DmsPathHelper $pathHelper, Filesystem $filesystem)
    {
        $this->pathHelper = $pathHelper;
        $this->filesystem = $filesystem;
    }

    /**
     * @param FileVersion $fileVersion
     * @param string      $fileContent
     *
     * @throws InvalidArgumentException
     *
     * @return PathInfo
     */
    public function saveFile(FileVersion $fileVersion, string $fileContent): PathInfo
    {
        $this->validateFileVersion($fileVersion);

        $filePath = $this->pathHelper->getFilePathFromVersion($fileVersion->getId());

        if ($this->existsDmsFile($filePath)) {
            return $this->filesystem->getInfo($filePath);
        }

        $this->filesystem->write($filePath, $fileContent);

        return $this->filesystem->getInfo($filePath);
    }

    /**
     * @param FileVersion $fileVersion
     * @param resource  $contentStream
     *
     * @throws InvalidArgumentException
     *
     * @return PathInfo
     */
    public function saveFileByStream(FileVersion $fileVersion, $contentStream): PathInfo
    {
        if (!is_resource($contentStream)) {
            throw new InvalidArgumentException('Content stream must be a resource.');
        }
        $this->validateFileVersion($fileVersion);

        $filePath = $this->pathHelper->getFilePathFromVersion($fileVersion->getId());

        if ($this->existsDmsFile($filePath)) {
            return $this->filesystem->getInfo($filePath);
        }

        $this->filesystem->writeStream($filePath, $contentStream);

        return $this->filesystem->getInfo($filePath);
    }

    /**
     * @param FileVersion $fileVersion
     * @param string $tempFile
     *
     * @throws FileNotFoundException
     * @throws InvalidArgumentException
     * @throws FileManagementWriteException
     *
     * @return PathInfo
     */
    public function saveFileByCopy(FileVersion $fileVersion, string $tempFile): PathInfo
    {
        if (!is_file($tempFile)) {
            throw new FileNotFoundException("File not found {$tempFile}");
        }
        $this->validateFileVersion($fileVersion);

        $tempFileStream = fopen($tempFile, 'r');
        try {
            return $this->saveFileByStream($fileVersion, $tempFileStream);
        } catch (Exception $e) {
            throw new FileManagementWriteException($e->getMessage(), $e->getCode(), $e);
        } finally {
            fclose($tempFileStream);
        }
    }

    /**
     * Checks whether the path is an existing file relative to the dms base path.
     *
     * @param string $filePath
     *
     * @return bool
     */
    private function existsDmsFile(string $filePath): bool
    {
        try {
            $info = $this->filesystem->getInfo($filePath);

            return $info->isFile();
        } catch (FileSystemFileNotFoundException $e) {
            // Exception does not need to be handled.
        }

        return false;
    }

    /**
     * @param FileVersion $fileVersion
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    private function validateFileVersion(FileVersion $fileVersion): void
    {
        if ($fileVersion->getId() < 1) {
            throw new InvalidArgumentException('File version id is required.');
        }
    }
}
