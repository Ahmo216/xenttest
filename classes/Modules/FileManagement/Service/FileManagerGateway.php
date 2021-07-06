<?php

declare(strict_types=1);

namespace Xentral\Modules\FileManagement\Service;

use Xentral\Components\Database\Database;
use Xentral\Components\Database\SqlQuery\SelectQuery;
use Xentral\Modules\FileManagement\Data\File;
use Xentral\Modules\FileManagement\Data\FileAssociation;
use Xentral\Modules\FileManagement\Data\FileVersion;
use Xentral\Modules\FileManagement\Exception\FileDataNotFoundException;
use Xentral\Modules\FileManagement\Helper\DmsPathHelper;

final class FileManagerGateway implements FileManagerGatewayInterface
{
    /** @var string */
    private const FILE_TABLE = 'datei as d';

    /** @var string */
    private const VERSION_TABLE = 'datei_version as v';

    /** @var string */
    private const ASSOCIATION_TABLE = 'datei_stichwoerter as s';

    /** @var string */
    private const CONDITION_NOT_DELETED = 'd.geloescht != 1';

    /** @var string[] */
    private const FILE_COLUMNS = [
        'd.id AS file_id',
        'd.titel AS title',
        'd.beschreibung AS description',
    ];

    /** @var string[] */
    private const VERSION_COLUMNS = [
        'v.id AS file_version_id',
        'v.datei AS file_id',
        'v.dateiname AS file_name',
        'v.bemerkung AS note',
        'v.ersteller AS creator_name',
        'v.size AS size',
        'v.version AS version',
        'v.datum AS date_created',
    ];

    /** @var string[] */
    private const ASSOCIATION_COLUMNS = [
        's.datei AS file_id',
        's.id AS file_association_id',
        's.subjekt AS subject',
        's.objekt AS object',
        's.parameter AS parameter',
        's.sort AS sort',
    ];

    /** @var Database $db */
    private $db;

    /** @var DmsPathHelper $pathHelper */
    private $pathHelper;

    public function __construct(Database $database, DmsPathHelper $pathHelper)
    {
        $this->db = $database;
        $this->pathHelper = $pathHelper;
    }

    /**
     * @inheritdoc
     */
    public function getFileById(int $fileId): File
    {
        $sql = $this->createFileDataQuery()
            ->where('d.id = :file_id')
            ->where(self::CONDITION_NOT_DELETED)
            ->getStatement();
        $row = $this->db->fetchRow($sql, ['file_id' => $fileId]);
        if (empty($row)) {
            throw new FileDataNotFoundException("FileData {$fileId} not found.");
        }
        $row['file_path'] = $this->pathHelper->getAbsoluteFilePathFromVersion($row['file_version_id']);

        return $this->createFileDataObject($row);
    }

    /**
     * @inheritdoc
     */
    public function tryGetFileByAssociation(string $documentType, string $entity, int $entityId): ?File
    {
        $sql = $this->db->select()
            ->cols(['s.datei'])
            ->from(self::ASSOCIATION_TABLE)
            ->join('INNER', self::FILE_TABLE, 'd.id = s.datei')
            ->where('s.subjekt LIKE :document_type')
            ->where('s.objekt LIKE :entity')
            ->where('s.parameter = :entity_id')
            ->where('d.geloescht != 1')
            ->getStatement();

        $fileId = $this->db->fetchValue(
            $sql,
            [
                'document_type' => $documentType,
                'entity' => $entity,
                'entity_id' => $entityId,
            ]
        );

        if (empty($fileId) || !is_integer($fileId)) {
            return null;
        }

        return $this->getFileById($fileId);
    }

    /**
     * @inheritdoc
     */
    public function findFileIdsByEntity(string $entity, int $entityId, ?string $documentType = null): array
    {
        $select = $this->db->select()
            ->cols(['d.id'])
            ->from(self::FILE_TABLE)
            ->join('INNER', 'datei_stichwoerter AS ds', 'ds.datei = d.id')
            ->where(self::CONDITION_NOT_DELETED)
            ->where('ds.objekt = :entity')
            ->where('ds.parameter = :entity_id')
            ->bindValues(['entity' => $entity, 'entity_id' => $entityId]);
        if ($documentType !== null) {
            $select->where('ds.subjekt = :document_type')
                ->bindValues(['document_type' => $documentType]);
        }
        $select->orderBy(['ds.sort']);

        $result = $this->db->fetchAll($select->getStatement(), $select->getBindValues());

        return array_map(function ($item) {
            return $item['id'];
        }, $result);
    }

    /**
     * @inheritdoc
     */
    public function tryGetVersionsByFile(int $fileId): array
    {
        $sql = $this->db->select()
            ->cols(self::VERSION_COLUMNS)
            ->from(self::VERSION_TABLE)
            ->where('v.datei = :file_id')
            ->orderBy(['v.version DESC']);

        $rows = $this->db->fetchAll($sql, ['file_id' => $fileId]);

        $versions = [];
        foreach ($rows as $row) {
            $row['file_path'] = $this->pathHelper->getAbsoluteFilePathFromVersion($row['file_version_id']);
            $versions[] = FileVersion::fromDbState($row);
        }

        return $versions;
    }

    /**
     * @inheritdoc
     */
    public function existsFileEntry(int $fileId): bool
    {
        return $this->existsEntry('datei', $fileId);
    }

    /**
     * @inheritdoc
     */
    public function existsFileVersionEntry(int $fileVersionId): bool
    {
        return $this->existsEntry('datei_version', $fileVersionId);
    }

    /**
     * @inheritdoc
     */
    public function existsFileAssociationEntry(int $fileAssociationId): bool
    {
        return $this->existsEntry('datei_stichwoerter', $fileAssociationId);
    }

    /**
     * @inheritdoc
     */
    public function getFileVersionById(int $fileVersionId): FileVersion
    {
        $select = $this->db->select()
            ->cols(self::VERSION_COLUMNS)
            ->from(self::VERSION_TABLE)
            ->where('v.id = :id')
            ->bindValues(['id' => $fileVersionId]);
        $row = $this->db->fetchRow($select->getStatement(), $select->getBindValues());
        if (empty($row)) {
            throw new FileDataNotFoundException('File version entry not found.');
        }

        $row['file_path'] = $this->pathHelper->getAbsoluteFilePathFromVersion($row['file_version_id']);

        return FileVersion::fromDbState($row);
    }

    /**
     * Gets all file associations of specified file.
     *
     * @param int $fileId
     *
     * @return array
     */
    private function tryGetAssociationsByFile(int $fileId): array
    {
        $sql = $this->db->select()
            ->cols(self::ASSOCIATION_COLUMNS)
            ->from(self::ASSOCIATION_TABLE)
            ->where('s.datei = :file_id')
            ->orderBy(['s.sort ASC']);

        $rows = $this->db->fetchAll($sql, ['file_id' => $fileId]);
        if (empty($rows)) {
            return [];
        }

        $associations = [];
        foreach ($rows as $row) {
            $associations[] = FileAssociation::fromDbState($row);
        }

        return $associations;
    }

    /**
     * Creates FileData object and fetches and adds associations.
     *
     * @param array $fileDataRow
     *
     * @return File
     */
    private function createFileDataObject(array $fileDataRow): File
    {
        $fileData = File::fromDbState($fileDataRow);
        $fileData->setVersionInfo(FileVersion::fromDbState($fileDataRow));
        foreach ($this->tryGetAssociationsByFile($fileData->getId()) as $association) {
            $fileData->addAssociation($association);
        }

        return $fileData;
    }

    /**
     * @return SelectQuery
     */
    private function createFileDataQuery(): SelectQuery
    {
        $maxVersionSubquery = 'SELECT MAX(`version`) FROM `datei_version` WHERE `datei` = :file_id';

        return $this->db->select()
            ->cols(array_merge(self::FILE_COLUMNS, self::VERSION_COLUMNS))
            ->from(self::FILE_TABLE)
            ->join(
                'INNER',
                self::VERSION_TABLE,
                "v.datei = d.id AND v.version = ({$maxVersionSubquery})"
            );
    }

    /**
     * @param string $table
     * @param int    $id
     *
     * @return bool
     */
    private function existsEntry(string $table, int $id): bool
    {
        if ($id < 1) {
            return false;
        }

        $select = $this->db->select()
            ->cols(['id'])
            ->from($table)
            ->where('id = :id')
            ->bindValues(['id' => $id]);

        return $id === $this->db->fetchValue($select->getStatement(), $select->getBindValues());
    }
}
