<?php

declare(strict_types=1);

namespace Xentral\Modules\FileManagement\Service;

use Xentral\Modules\FileManagement\Data\File;
use Xentral\Modules\FileManagement\Data\FileAssociation;
use Xentral\Modules\FileManagement\Data\FileVersion;
use Xentral\Modules\FileManagement\Exception\InvalidArgumentException;

interface FileManagerServiceInterface
{
    /**
     * Inserts a new entry into the file management system.
     *
     * @param File $file
     *
     * @return int
     */
    public function insertFile(File $file): int;

    /**
     * Updates a file entry.
     *
     * A valid file id is required.
     *
     * @param File $file
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function updateFile(File $file): void;

    /**
     * Marks the file as deleted.
     *
     * @param int $fileId
     *
     * @return void
     */
    public function deleteFile(int $fileId): void;

    /**
     * Inserts a new file version entry.
     *
     * Version number and file id is required.
     *
     * @param FileVersion $fileVersion
     *
     * @return int
     */
    public function insertFileVersion(FileVersion $fileVersion): int;

    /**
     * Updates a file version entry.
     *
     * Does not update the file id.
     *
     * @param FileVersion $fileVersion
     *
     * @return void
     */
    public function updateFileVersion(FileVersion $fileVersion): void;

    /**
     * Deletes a file version entry from the database.
     *
     * @param int $fileVersionId
     *
     * @return void
     */
    public function deleteFileVersion(int $fileVersionId): void;

    /**
     * Inserts a new association entry.
     *
     * File id is required.
     *
     * @param FileAssociation $association
     *
     * @return int
     */
    public function insertAssociation(FileAssociation $association): int;

    /**
     * Updates a file association.
     *
     * Does not update the file id.
     *
     * @param FileAssociation $association
     *
     * @return void
     */
    public function updateAssociation(FileAssociation $association): void;

    /**
     * Deletes a file association entry from the database.
     *
     * @param int $fileAssociationId
     *
     * @return void
     */
    public function deleteAssociation(int $fileAssociationId): void;
}
