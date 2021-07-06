<?php

declare(strict_types=1);

namespace Xentral\Modules\Storage\Service;

use Exception;
use Xentral\Components\Database\Database;
use Xentral\Modules\Storage\Data\Storage;
use Xentral\Modules\Storage\Exception\StorageNotFoundException;

/**
 * Provides CRUD functionality for Storage objects.
 */
class StorageRepository
{
    /** @var string */
    public const TABLE_NAME = 'lager';

    /** @var Database */
    private $db;

    /**
     * StorageRepository constructor.
     *
     * @param Database $db
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Create a new storage.
     *
     * @param Storage $storage
     *
     * @throws Exception
     *
     * @return Storage
     */
    public function create(Storage $storage): Storage
    {
        $storage->updateTimeModified();

        $values = [
            'bezeichnung' => $storage->getName(),
            'beschreibung' => $storage->getDescription(),
            'manuell' => $storage->getIsManual(),
            'firma' => $storage->getCompanyId(),
            'geloescht' => $storage->getIsDeleted(),
            'logdatei' => $storage->getTimeModified()->format('Y-m-d H:i:s'),
            'projekt' => $storage->getProjectId(),
            'adresse' => $storage->getAddressId(),
        ];

        $this->db->perform(
            'INSERT INTO `lager`
            (
                `bezeichnung`,
                `beschreibung`,
                `manuell`,
                `firma`,
                `geloescht`,
                `logdatei`,
                `projekt`,
                `adresse`
            )
            VALUES (
                :bezeichnung,
                :beschreibung,
                :manuell,
                :firma,
                :geloescht,
                :logdatei,
                :projekt,
                :adresse
            )',
            $values
        );

        $storage->setId($this->db->lastInsertId());

        return $storage;
    }

    /**
     * Update an existing storage.
     *
     * @param Storage $storage
     *
     * @throws Exception
     *
     * @return int
     */
    public function update(Storage $storage): Storage
    {
        $storage->updateTimeModified();

        $values = [
            'id' => $storage->getId(),
            'bezeichnung' => $storage->getName(),
            'beschreibung' => $storage->getDescription(),
            'manuell' => $storage->getIsManual(),
            'firma' => $storage->getCompanyId(),
            'geloescht' => $storage->getIsDeleted(),
            'logdatei' => $storage->getTimeModified()->format('Y-m-d H:i:s'),
            'projekt' => $storage->getProjectId(),
            'adresse' => $storage->getAddressId(),
        ];

        $this->db->perform(
            'UPDATE `lager`
                SET
                    `bezeichnung` = :bezeichnung,
                    `beschreibung` = :beschreibung,
                    `manuell` = :manuell,
                    `firma` = :firma,
                    `geloescht` = :geloescht,
                    `logdatei` = :logdatei,
                    `projekt` = :projekt,
                    `adresse` = :adresse
                WHERE `id` = :id',
            $values
        );

        return $storage;
    }

    /**
     * Create a new storage or update an existing one.
     *
     * @param Storage $storage
     *
     * @throws Exception
     *
     * @return Storage The saved storage.
     */
    public function save(Storage $storage): Storage
    {
        if ($storage->getId() !== null) {
            return $this->update($storage)->getId();
        }

        return $this->create($storage);
    }

    /**
     * Delete a storage.
     *
     * @param Storage $storage
     */
    public function delete(Storage $storage)
    {
        return $this->db->perform(
            'DELETE FROM `lager` WHERE `id` = :id',
            ['id' => $storage->getId()]
        );
    }

    /**
     * Get a storage based on its id.
     *
     * @param int $id
     *
     * @return Storage
     */
    public function getById(int $id): Storage
    {
        $select = $this->db
            ->select()
            ->cols(['*'])
            ->from(self::TABLE_NAME)
            ->where('geloescht = 0')
            ->where('id = :id')
            ->bindValue('id', $id);

        $row = $this->db->fetchRow(
            $select->getStatement(),
            $select->getBindValues()
        );

        if (empty($row)) {
            throw new StorageNotFoundException("A storage with the id ${id} was not found.");
        }

        return Storage::fromDbState($row);
    }

    /**
     * Get a storage based on its name.
     *
     * Storages marked as deleted will be ignored, as they might share the same name.
     *
     * @param string $name
     *
     * @return Storage
     */
    public function getByName(string $name): Storage
    {
        $row = $this->db->fetchRow(
            'SELECT * FROM `lager`
            WHERE `bezeichnung` = :name
            AND `geloescht` = 0',
            ['name' => $name]
        );

        if (empty($row)) {
            throw new Exception("A storage with the name ${$name} was not found.");
        }

        return Storage::fromDbState($row);
    }

    /**
     * Get total count of non-deleted storages.
     *
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->db->fetchValue('SELECT count(id) FROM `lager` WHERE `geloescht` = 0');
    }

    /**
     * Get all storages not marked as deleted.
     *
     * @param mixed|null $offset
     * @param mixed|null $limit
     *
     * @return array
     */
    public function getList($offset = null, $limit = null): array
    {
        $select = $this->db
            ->select()
            ->cols(['*'])
            ->from(self::TABLE_NAME)
            ->where('`geloescht` = 0')
            ->offset($offset)
            ->limit($limit);

        $rows = $this->db->fetchAll(
            $select->getStatement()
        );

        $storages = [];
        foreach ($rows as $row) {
            $storages[] = Storage::fromDbState($row);
        }

        return $storages;
    }
}
