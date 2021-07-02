<?php

declare(strict_types=1);

namespace Xentral\Modules\FileManagement\Service;

use Xentral\Components\Database\Database;
use Xentral\Modules\FileManagement\Data\File;
use Xentral\Modules\FileManagement\Data\FileAssociation;
use Xentral\Modules\FileManagement\Data\FileVersion;
use Xentral\Modules\FileManagement\Exception\InvalidArgumentException;

final class FileManagerService implements FileManagerServiceInterface
{
    /** @var string */
    private const TABLE_FILE = 'datei';

    /** @var string */
    private const TABLE_FILE_VERSION = 'datei_version';

    /** @var string */
    private const TABLE_FILE_ASSOCIATION = 'datei_stichwoerter';

    /** @var Database $db */
    private $db;

    /**
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->db = $database;
    }

    /**
     * Inserts a new entry into the file management.
     *
     * @param File $file
     *
     * @return int
     */
    public function insertFile(File $file): int
    {
        $insert = $this->db->insert()
            ->into(self::TABLE_FILE)
            ->cols(
                [
                    'titel' => $file->getTitle() ?? '',
                    'beschreibung' => $file->getDescription() ?? '',
                    'firma' => 1,
                    'geloescht' => 0,
                ]
            );

        $this->db->perform($insert->getStatement(), $insert->getBindValues());

        return $this->db->lastInsertId();
    }

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
    public function updateFile(File $file): void
    {
        if ($file->getId() < 1) {
            throw new InvalidArgumentException('Invalid file id.');
        }

        $update = $this->db->update()
            ->table(self::TABLE_FILE)
            ->cols(
                [
                    'titel' => $file->getTitle() ?? '',
                    'beschreibung' => $file->getDescription() ?? '',
                ]
            )
            ->where('id = :id')
            ->bindValues(['id' => $file->getId()]);

        $this->db->perform($update->getStatement(), $update->getBindValues());
    }

    /**
     * Marks the file as deleted.
     *
     * @param int $fileId
     *
     * @return void
     */
    public function deleteFile(int $fileId): void
    {
        if ($fileId < 1) {
            throw new InvalidArgumentException('Invalid file id.');
        }

        $update = $this->db->update()
            ->table(self::TABLE_FILE)
            ->cols(['geloescht' => 1])
            ->where('id = :id')
            ->bindValues(['id' => $fileId]);

        $this->db->perform($update->getStatement(), $update->getBindValues());
    }

    /**
     * Inserts a new file version entry.
     *
     * Version number and file id is required.
     *
     * @param FileVersion $fileVersion
     *
     * @return int
     */
    public function insertFileVersion(FileVersion $fileVersion): int
    {
        if (empty($fileVersion->getVersion())) {
            throw new InvalidArgumentException('File version number is required.');
        }
        if ($fileVersion->getFileId() < 1) {
            throw new InvalidArgumentException('File id is required.');
        }

        $insert = $this->db->insert()
            ->into(self::TABLE_FILE_VERSION)
            ->cols(
                [
                    'datei' => $fileVersion->getFileId(),
                    'ersteller' => $fileVersion->getCreatorName() ?? '',
                    'version' => $fileVersion->getVersion(),
                    'dateiname' => $fileVersion->getFileName() ?? '',
                    'bemerkung' => $fileVersion->getDescription() ?? '',
                    'size' => $fileVersion->getSizeInBytes() ?? '',
                ]
            )
            ->set('datum', 'NOW()');

        $this->db->perform($insert->getStatement(), $insert->getBindValues());

        return $this->db->lastInsertId();
    }

    /**
     * Updates a file version entry.
     *
     * Does not update the file id.
     *
     * @param FileVersion $fileVersion
     *
     * @return void
     */
    public function updateFileVersion(FileVersion $fileVersion): void
    {
        if ($fileVersion->getId() < 1) {
            throw new InvalidArgumentException("Invalid file version id {$fileVersion->getId()}");
        }

        $update = $this->db->update()
            ->table(self::TABLE_FILE_VERSION)
            ->cols(
                [
                    'ersteller' => $fileVersion->getCreatorName() ?? '',
                    'dateiname' => $fileVersion->getFileName() ?? '',
                    'size' => $fileVersion->getSizeInBytes() ?? '',
                    'bemerkung' => $fileVersion->getDescription() ?? '',
                ]
            )
            ->where('id = :version_id')
            ->bindValues(['version_id' => $fileVersion->getId()]);

        $this->db->perform($update->getStatement(), $update->getBindValues());
    }

    /**
     * Deletes a file version entry from the database.
     *
     * @param int $fileVersionId
     *
     * @return void
     */
    public function deleteFileVersion(int $fileVersionId): void
    {
        $delete = $this->db->delete()
            ->from(self::TABLE_FILE_VERSION)
            ->where('id = :id')
            ->bindValues(['id' => $fileVersionId]);

        $this->db->perform($delete->getStatement(), $delete->getBindValues());
    }

    /**
     * Inserts a new association entry.
     *
     * File id is required.
     *
     * @param FileAssociation $association
     *
     * @return int
     */
    public function insertAssociation(FileAssociation $association): int
    {
        if ($association->getFileId() < 1) {
            throw  new InvalidArgumentException('File id required.');
        }

        $insert = $this->db->insert()
            ->into(self::TABLE_FILE_ASSOCIATION)
            ->cols(
                [
                    'datei' => $association->getFileId(),
                    'subjekt' => $association->getDocumentType() ?? '',
                    'objekt' => $association->getEntity() ?? '',
                    'parameter' => $association->getEntityId() ?? 0,
                    'sort' => $association->getSort(),
                ]
            );

        $this->db->perform($insert->getStatement(), $insert->getBindValues());

        return $this->db->lastInsertId();
    }

    /**
     * Updates a file association.
     *
     * Does not update the file id.
     *
     * @param FileAssociation $association
     *
     * @return void
     */
    public function updateAssociation(FileAssociation $association): void
    {
        if ($association->getId() < 1) {
            throw new InvalidArgumentException('File association id is required.');
        }

        $update = $this->db->update()
            ->table(self::TABLE_FILE_ASSOCIATION)
            ->cols(
                [
                    'subjekt' => $association->getDocumentType() ?? '',
                    'objekt' => $association->getEntity() ?? '',
                    'parameter' => $association->getEntityId() ?? '',
                    'sort' => $association->getSort(),
                ]
            )
            ->where('id = :id')
            ->bindValues(['id' => $association->getId()]);

        $this->db->perform($update->getStatement(), $update->getBindValues());
    }

    /**
     * Deletes a file association entry from the database.
     *
     * @param int $fileAssociationId
     *
     * @return void
     */
    public function deleteAssociation(int $fileAssociationId): void
    {
        $delete = $this->db->delete()
            ->from(self::TABLE_FILE_ASSOCIATION)
            ->where('id = :id')
            ->bindValues(['id' => $fileAssociationId]);

        $this->db->perform($delete->getStatement(), $delete->getBindValues());
    }
}
