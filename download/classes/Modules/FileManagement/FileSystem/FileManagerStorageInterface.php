<?php

declare(strict_types=1);

namespace Xentral\Modules\FileManagement\FileSystem;

use Xentral\Components\Filesystem\PathInfo;
use Xentral\Modules\FileManagement\Data\FileVersion;
use Xentral\Modules\FileManagement\Exception\FileManagementWriteException;
use Xentral\Modules\FileManagement\Exception\FileNotFoundException;
use Xentral\Modules\FileManagement\Exception\InvalidArgumentException;

interface FileManagerStorageInterface
{
    /**
     * @param FileVersion $fileVersion
     * @param string      $fileContent
     *
     * @throws InvalidArgumentException
     *
     * @return PathInfo
     */
    public function saveFile(FileVersion $fileVersion, string $fileContent): PathInfo;

    /**
     * @param FileVersion $fileVersion
     * @param resource    $contentStream
     *
     * @throws InvalidArgumentException
     *
     * @return PathInfo
     */
    public function saveFileByStream(FileVersion $fileVersion, $contentStream): PathInfo;

    /**
     * @param FileVersion $fileVersion
     * @param string      $tempFile
     *
     * @throws FileNotFoundException
     * @throws InvalidArgumentException
     * @throws FileManagementWriteException
     *
     * @return PathInfo
     */
    public function saveFileByCopy(FileVersion $fileVersion, string $tempFile): PathInfo;
}
