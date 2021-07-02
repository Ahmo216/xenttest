<?php

declare(strict_types=1);

namespace Xentral\Modules\FileManagement\Service;

use Xentral\Modules\FileManagement\Data\File;
use Xentral\Modules\FileManagement\Data\FileVersion;
use Xentral\Modules\FileManagement\Exception\FileDataNotFoundException;

interface FileManagerGatewayInterface
{
    /**
     * Gets a specific file with it's latest version.
     *
     * @param int $fileId
     *
     * @throws FileDataNotFoundException
     *
     * @return File
     */
    public function getFileById(int $fileId): File;

    /**
     * Finds a file by specified criteria.
     *
     * @param string $documentType case insensitive
     * @param string $entity       case insensitive
     * @param int    $entityId
     *
     * @return ?File
     */
    public function tryGetFileByAssociation(string $documentType, string $entity, int $entityId): ?File;

    /**
     * @param string      $entity
     * @param int         $entityId
     * @param string|null $documentType
     *
     * @return int[]
     */
    public function findFileIdsByEntity(string $entity, int $entityId, ?string $documentType = null): array;

    /**
     * Gets all available versions of the specified file
     * sorted by version (latest first).
     *
     * @param int $fileId
     *
     * @return FileVersion[]
     */
    public function tryGetVersionsByFile(int $fileId): array;

    /**
     * @param int $fileId
     *
     * @return bool
     */
    public function existsFileEntry(int $fileId): bool;

    /**
     * @param int $fileVersionId
     *
     * @return bool
     */
    public function existsFileVersionEntry(int $fileVersionId): bool;

    /**
     * @param int $fileAssociationId
     *
     * @return bool
     */
    public function existsFileAssociationEntry(int $fileAssociationId): bool;

    /**
     * @param int $fileVersionId
     *
     * @throws FileDataNotFoundException
     *
     * @return FileVersion
     */
    public function getFileVersionById(int $fileVersionId): FileVersion;
}
