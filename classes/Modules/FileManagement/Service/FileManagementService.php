<?php

declare(strict_types=1);

namespace Xentral\Modules\FileManagement\Service;

use erpAPI;
use RuntimeException;
use Xentral\Components\Database\Database;
use Xentral\Components\Database\SqlQuery\SelectQuery;
use Xentral\Components\Filesystem\FilesystemFactory;
use Xentral\Modules\FileManagement\Data\FileData;

final class FileManagementService
{
    /** @var Database */
    private $db;

    /** @var erpAPI */
    private $erp;

    /** @var FilesystemFactory */
    private $fileSystem;

    public function __construct($erp, Database $database, FilesystemFactory $filesystemFactory)
    {
        $this->erp = $erp;
        $this->db = $database;
        $this->fileSystem = $filesystemFactory;
    }

    public function saveFileData(FileData $fileData): void
    {
        $this->validateFileData($fileData);

        /**
         * Step 1: create an entry in the `datei` table
         */
        if ($fileData->getFileId() === null) {
            $this->createFileInformation($fileData);
        } else {
            $this->saveFileInformation($fileData);
        }

        /**
         * Step 2: create an entry in the `datei_version` table
         */
        if ($fileData->getFileVersionId() === null) {
            $this->createNewFileVersionInDatabase($fileData);
        } else {
            $this->saveFileVersionInformation($fileData);
        }

        /**
         * Step 3: write the file to the dms
         */
        if ($fileData->getTemporaryFilePath() !== null) {
            $fileData->setLocalFilePath($this->getDmsPath($fileData));
            $this->writeFileToDms($fileData);
            $fileData->setTemporaryFilePath(null);
        }

        /**
         * Step 4: associate the file to an owning entity in the `datei_stichwoerter` table
         */
        if ($fileData->getFileAssociationId() === null) {
            $this->createFileAssociation($fileData);
        } else {
            $this->saveFileAssociation($fileData);
        }
    }

    private function validateFileData(FileData $fileData): void
    {
        if (empty($fileData->getLocalFilePath()) && empty($fileData->getTemporaryFilePath())) {
            throw new RuntimeException(
                'FileData must have a temporary path to copy the data from, or a path pointing at an existing file'
            );
        }
        if (empty($fileData->getCreatorName())) {
            throw new RuntimeException('The name of the creator must not be empty, e.g. bob');
        }
        if (empty($fileData->getFileName())) {
            throw new RuntimeException('The name of the file must not be empty, e.g. picture_of_cats.png');
        }
        if (empty($fileData->getOwningEntity())) {
            throw new RuntimeException('FileData must have information about owning entity, e.g. Artikel');
        }
        if ($fileData->getOwningEntityId() === null) {
            throw new RuntimeException('FileData is missing the id of the owning entity');
        }
        if (empty($fileData->getDocumentType())) {
            throw new RuntimeException('FileData must have information about file type, e.g. Shopbild');
        }
    }

    private function createFileInformation(FileData $fileData): void
    {
        /**
         * titel - the title of the file (not the filename)
         * beschreibung - description of the file
         * nummer - out of commission - unknown origin
         * firma - out of commission - precursor to the project mechanic
         * geloescht - marks the file as deleted
         */
        $sql = 'INSERT INTO `datei` (`titel`, `beschreibung`, `nummer`, `firma`, `geloescht`)
                 VALUES (:title, :description, \'\', 1, 0)';
        $values = [
            'title'       => $fileData->getTitle(),
            'description' => $fileData->getDescription(),
        ];
        $this->db->perform($sql, $values);
        $fileData->setFileId((int)$this->db->lastInsertId());
    }

    private function saveFileInformation(FileData $fileData): void
    {
        $sql = 'UPDATE `datei` SET
            `titel` = :title,
            `beschreibung` = :description,
            `geloescht` = :deleted
            WHERE `id` = :file_id';
        $values = [
            'title'       => $fileData->getTitle(),
            'description' => $fileData->getDescription(),
            'deleted'     => $fileData->isDeleted(),
            'file_id'     => $fileData->getFileId(),
        ];
        $this->db->perform($sql, $values);
    }

    public function createNewFileVersionInDatabase(FileData $fileData): void
    {
        $sql = 'SELECT ifnull(max(`version`),0) FROM `datei_version` WHERE `datei` = :id';
        $currentVersionOfFile = (int)$this->db->fetchValue($sql, ['id' => $fileData->getFileId()]);

        /**
         * datei - the id of the corresponding database entry in the 'datei' table
         * ersteller - name (not id or anything) of the person/mechanic responsible for this new file version
         * datum - the point in time when this version of the file was introduced
         * version - iteration of the file
         * dateiname - name of the file e.g. "picture_of_my_cat.png"
         * bemerkung - description of the file e.g. "This picture shows my cat"
         * size - size of the file in the file system
         */
        $sql = 'INSERT INTO `datei_version` (`datei`, `ersteller`, `datum`, `version`, `dateiname`, `bemerkung`, `size`)
                 VALUES (:file_id, :creating_user, NOW(), :version, :file_name, :note, :size)';
        $values = [
            'file_id'       => $fileData->getFileId(),
            'creating_user' => $fileData->getCreatorName(),
            'version'       => $currentVersionOfFile + 1,
            'file_name'     => $fileData->getFileName(),
            'note'          => $fileData->getFileNote(),
            'size'          => $fileData->getSizeInBytes(),
        ];
        $this->db->perform($sql, $values);
        $fileData->setFileVersionId((int)$this->db->lastInsertId());
    }

    private function saveFileVersionInformation(FileData $fileData): void
    {
        $sql = 'UPDATE `datei_version` SET
            `ersteller` = :creating_user,
            `dateiname` = :file_name,
            `bemerkung` = :note,
            `size` = :size,
            `datei` = :file_id
            WHERE `id` = :file_version_id';
        $values = [
            'creating_user'   => $fileData->getCreatorName(),
            'file_name'       => $fileData->getFileName(),
            'note'            => $fileData->getFileNote(),
            'size'            => $fileData->getSizeInBytes(),
            'file_id'         => $fileData->getFileId(),
            'file_version_id' => $fileData->getFileVersionId(),
        ];
        $this->db->perform($sql, $values);
    }

    private function getDmsPath(FileData $fileData): string
    {
        return $this->erp->CreateDMSPath('', $fileData->getFileVersionId());
    }

    private function writeFileToDms(FileData $fileData): void
    {
        $local = $this->fileSystem->createLocal($fileData->getLocalFilePath());

        $stream = fopen($fileData->getTemporaryFilePath(), 'rb+');
        $local->writeStream(
            $fileData->getFileVersionId(),
            $stream
        );

        if (is_resource($stream)) {
            fclose($stream);
        }
    }

    private function createFileAssociation(FileData $fileData): void
    {
        $sql = 'SELECT IFNULL(MAX(`sort`),0) FROM `datei_stichwoerter` 
            WHERE `subjekt` = :subject AND `objekt` = :object AND `parameter` = :parameter';
        $values = [
            'subject'   => $fileData->getDocumentType(),
            'object'    => $fileData->getOwningEntity(),
            'parameter' => $fileData->getOwningEntityId(),
        ];
        $targetSorting = (int)$this->db->fetchValue($sql, $values) + 1;

        /**
         * datei - id of the associated file in the `datei` table
         * subjekt - type of the file
         * objekt - entity associated with the file
         * parameter - id of the entity associated with the file
         * sort - sorting of the file
         */
        $sql = 'INSERT INTO `datei_stichwoerter` (datei, subjekt, objekt, parameter, sort)
                 VALUES (:file_id, :subject, :object, :parameter, :sort)';
        $values = [
            'file_id'   => $fileData->getFileId(),
            'subject'   => $fileData->getDocumentType(),
            'object'    => $fileData->getOwningEntity(),
            'parameter' => $fileData->getOwningEntityId(),
            'sort'      => $targetSorting,
        ];
        $this->db->perform($sql, $values);

        $fileData->setSort($targetSorting);
        $fileData->setFileAssociationId((int)$this->db->lastInsertId());
    }

    private function saveFileAssociation(FileData $fileData): void
    {
        $sql = 'UPDATE `datei_stichwoerter` SET
            `subjekt` = :subject,
            `objekt` = :object,
            `parameter` = :parameter,
            `sort` = :sort,
            `datei` = :file_id
            WHERE `id` = :file_association_id';
        $values = [
            'subject'             => $fileData->getDocumentType(),
            'object'              => $fileData->getOwningEntity(),
            'parameter'           => $fileData->getOwningEntityId(),
            'sort'                => $fileData->getSort(),
            'file_id'             => $fileData->getFileId(),
            'file_association_id' => $fileData->getFileAssociationId(),
        ];
        $this->db->perform($sql, $values);
    }

    /**
     * @param int         $fileId
     * @param string      $owningEntity   - describes the entity owning the file, e.g. Artikel, Adressen, etc.
     * @param string      $owningEntityId - the id of the entity owning the file
     * @param string|null $typeOfDocument - describes the type of the file, e.g. Shopbild, Deckblatt, etc.
     *
     * @return FileData|null
     */
    public function getCurrentFileDataByFileId(
        int $fileId,
        string $owningEntity,
        string $owningEntityId,
        ?string $typeOfDocument = null
    ): ?FileData {
        $query = $this->getQuery($fileId, $owningEntity, $owningEntityId, $typeOfDocument, true)
            ->orderBy(['v.id DESC'])
            ->limit(1);

        $data = $this->db->fetchRow($query->getStatement(), $query->getBindValues());
        if (empty($data)) {
            return null;
        }
        $fileData = FileData::fromDbState($data);

        return $fileData->setLocalFilePath($this->getDmsPath($fileData));
    }

    private function getQuery(
        ?int $fileId,
        ?string $owningEntity,
        ?string $owningEntityId,
        ?string $typeOfDocument,
        bool $findDeleted
    ): SelectQuery {
        $query = $this->db
            ->select()
            ->cols(
                [
                    'd.id AS file_id',
                    'd.titel AS title',
                    'd.beschreibung AS description',
                    'd.geloescht AS deleted',
                    'v.id AS file_version_id',
                    'v.dateiname AS file_name',
                    'v.bemerkung AS note',
                    'v.ersteller AS creator_name',
                    'v.size AS size',
                    'v.version AS version',
                    's.id AS file_association_id',
                    's.subjekt AS subject',
                    's.objekt AS object',
                    's.parameter AS parameter',
                    's.sort AS sort',
                ]
            )
            ->from('datei AS d')
            ->join(
                'INNER',
                'datei_version AS v',
                'd.id = v.datei'
            )
            ->join(
                'INNER',
                'datei_stichwoerter AS s',
                'd.id = s.datei'
            )
            ->where('d.geloescht = :deleted')
            ->bindValue('deleted', (int)$findDeleted);

        if ($fileId !== null) {
            $query->where("d.id = :file_id")
                ->bindValue('file_id', $fileId);
        }

        if ($owningEntity !== null) {
            $query->where("s.objekt = :object")
                ->bindValue('object', $owningEntity);
        }
        if ($typeOfDocument !== null) {
            $query->where("s.subjekt = :subject")
                ->bindValue('subject', $typeOfDocument);
        }
        if ($owningEntityId !== null) {
            $query->where("s.parameter = :parameter")
                ->bindValue('parameter', $owningEntityId);
        }

        return $query;
    }

    public function getCurrentFileDataByAssociation(
        string $owningEntity,
        string $owningEntityId,
        string $typeOfDocument
    ): ?FileData {
        $query = $this->getQuery(null, $owningEntity, $owningEntityId, $typeOfDocument, false)
            ->orderBy(['v.id DESC'])
            ->limit(1);

        $data = $this->db->fetchRow($query->getStatement(), $query->getBindValues());
        if (empty($data)) {
            return null;
        }
        $fileData = FileData::fromDbState($data);

        return $fileData->setLocalFilePath($this->getDmsPath($fileData));
    }

    /**
     * @param int         $fileId
     * @param string      $owningEntity   - describes the entity owning the file, e.g. Artikel, Adressen etc.
     * @param string      $owningEntityId - the id of the entity owning the file
     * @param string|null $typeOfDocument - describes the type of the file, e.g. Shopbild, lieferschein, Deckblatt,
     *                                    etc.
     *
     * @return FileData[]
     */
    public function getAllFileVersionsByFileId(
        int $fileId,
        ?string $owningEntity = null,
        ?string $owningEntityId = null,
        ?string $typeOfDocument = null
    ): array {
        $query = $this->getQuery($fileId, $owningEntity, $owningEntityId, $typeOfDocument, true);

        return array_map(
            function (array $data) {
                $fileData = FileData::fromDbState($data);

                return $fileData->setLocalFilePath($this->getDmsPath($fileData));
            },
            $this->db->fetchAll($query->getStatement(), $query->getBindValues())
        );
    }
}
