<?php

namespace Xentral\Modules\Storage\Service;

use Xentral\Components\Database\Database;
use Xentral\Modules\Storage\Data\StorageBin;
use Xentral\Modules\Storage\Exception\StorageBinNameNotUniqueException;
use Xentral\Modules\Storage\Exception\StorageBinNotFoundException;

class StorageBinRepository
{
    /** @var string */
    public const TABLE_NAME = 'lager_platz';

    /** @var Database */
    private $db;

    /**
     * StorageBinRepository constructor.
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
     * @param StorageBin $storageBin
     *
     * @return StorageBin
     */
    public function create(StorageBin $storageBin): StorageBin
    {
        $this->verifyNameIsUnique($storageBin->getName());

        $values = [
            'lager' => $storageBin->getStorageId(),
            'kurzbezeichnung' => $storageBin->getName(),
            'bemerkung' => $storageBin->getComment(),
            'projekt' => $storageBin->getProjectId(),
            'firma' => $storageBin->getCompanyId(),
            'geloescht' => $storageBin->isDeleted(),
            'logdatei' => $storageBin->getTimeModified()->format('Y-m-d H:i:s'),
            'autolagersperre' => $storageBin->isAutomaticShippingDisabled(),
            'verbrauchslager' => $storageBin->isConsumablesStorage(),
            'sperrlager' => $storageBin->isBlockedStock(),
            'laenge' => $storageBin->getLength(),
            'breite' => $storageBin->getWidth(),
            'hoehe' => $storageBin->getHeight(),
            'poslager' => $storageBin->isPosStorage(),
            'adresse' => $storageBin->getAddressId(),
            'abckategorie' => $storageBin->getCategory(),
            'regalart' => $storageBin->getShelfType(),
            'rownumber' => $storageBin->getRowNumber(),
            'allowproduction' => $storageBin->isAvailableForProduction(),
        ];

        $this->db->perform(
            'INSERT INTO ' . self::TABLE_NAME . '
            (
                `lager`,
                `kurzbezeichnung`,
                `bemerkung`,
                `projekt`,
                `firma`,
                `geloescht`,
                `logdatei`,
                `autolagersperre`,
                `verbrauchslager`,
                `sperrlager`,
                `laenge`,
                `breite`,
                `hoehe`,
                `poslager`,
                `adresse`,
                `abckategorie`,
                `regalart`,
                `rownumber`,
                `allowproduction`
            )
            VALUES (
                :lager,
                :kurzbezeichnung,
                :bemerkung,
                :projekt,
                :firma,
                :geloescht,
                :logdatei,
                :autolagersperre,
                :verbrauchslager,
                :sperrlager,
                :laenge,
                :breite,
                :hoehe,
                :poslager,
                :adresse,
                :abckategorie,
                :regalart,
                :rownumber,
                :allowproduction
            )',
            $values
        );

        $storageBin->setId($this->db->lastInsertId());

        return $storageBin;
    }

    /**
     * Update an existing storage bin.
     *
     * @param StorageBin $storageBin
     *
     * @return StorageBin
     */
    public function update(StorageBin $storageBin): StorageBin
    {
        $this->verifyNameIsUnique($storageBin->getName());

        $storageBin->updateTimeModified();

        $this->db->perform(
            'UPDATE ' . self::TABLE_NAME . '
                SET
                `id` = :id,
                `lager` = :lager,
                `kurzbezeichnung` = :kurzbezeichnung,
                `bemerkung` = :bemerkung,
                `projekt` = :projekt,
                `firma` = :firma,
                `geloescht` = :geloescht,
                `logdatei` = :logdatei,
                `autolagersperre` = :autolagersperre,
                `verbrauchslager` = :verbrauchslager,
                `sperrlager` = :sperrlager,
                `laenge` = :laenge,
                `breite` = :breite,
                `hoehe` = :hoehe,
                `poslager` = :poslager,
                `adresse` = :adresse,
                `abckategorie` = :abckategorie,
                `regalart` = :regalart,
                `rownumber` = :rownumber,
                `allowproduction` = :allowproduction
                WHERE `id` = :id',
            [
                'id' => $storageBin->getId(),
                'lager' => $storageBin->getStorageId(),
                'kurzbezeichnung' => $storageBin->getName(),
                'bemerkung' => $storageBin->getComment(),
                'projekt' => $storageBin->getProjectId(),
                'firma' => $storageBin->getCompanyId(),
                'geloescht' => $storageBin->isDeleted(),
                'logdatei' => $storageBin->getTimeModified()->format('Y-m-d H:i:s'),
                'autolagersperre' => $storageBin->isAutomaticShippingDisabled(),
                'verbrauchslager' => $storageBin->isConsumablesStorage(),
                'sperrlager' => $storageBin->isBlockedStock(),
                'laenge' => $storageBin->getLength(),
                'breite' => $storageBin->getWidth(),
                'hoehe' => $storageBin->getHeight(),
                'poslager' => $storageBin->isPosStorage(),
                'adresse' => $storageBin->getAddressId(),
                'abckategorie' => $storageBin->getCategory(),
                'regalart' => $storageBin->getShelfType(),
                'rownumber' => $storageBin->getRowNumber(),
                'allowproduction' => $storageBin->isAvailableForProduction(),
            ]
        );

        return $storageBin;
    }

    /**
     * Create a new storage bin or update an existing one.
     *
     * @param StorageBin $storageBin
     *
     * @throws Exception
     *
     * @return int Id of the saved storage.
     */
    public function save(StorageBin $storageBin): int
    {
        if ($storageBin->getId() !== null) {
            return $this->update($storageBin)->getId();
        }

        return $this->create($storageBin);
    }

    /**
     * Delete a storage.
     *
     * @param StorageBin $storageBin
     */
    public function delete(StorageBin $storageBin)
    {
        $query = $this->db->delete()
            ->from(self::TABLE_NAME)
            ->where('id = :id')
            ->bindValue('id', $storageBin->getId());

        $this->db->perform(
            $query->getStatement(),
            $query->getBindValues()
        );
    }

    /**
     * Get a storage bin based on its id.
     *
     * @param int $id
     *
     * @return StorageBin
     */
    public function getById(int $id): StorageBin
    {
        $select = $this->db
            ->select()
            ->cols(['*'])
            ->from(self::TABLE_NAME)
            ->where('`geloescht` = 0')
            ->where("`id` = {$id}");

        $row = $this->db->fetchRow(
            $select->getStatement()
        );

        if (empty($row)) {
            throw new StorageBinNotFoundException("A storage bin with the id ${id} was not found.");
        }

        return StorageBin::fromDbState($row);
    }

    /**
     * Get a storage bin based on its name.
     *
     * @param string $name
     *
     * @return StorageBin
     */
    public function getByName(string $name): StorageBin
    {
        $select = $this->db
            ->select()
            ->cols(['*'])
            ->from(self::TABLE_NAME)
            ->where('geloescht = 0')
            ->where('kurzbezeichnung = :name')
            ->bindValue('name', $name);

        $row = $this->db->fetchRow(
            $select->getStatement(),
            $select->getBindValues()
        );

        if (empty($row)) {
            throw new StorageBinNotFoundException("A storage bin with the name ${name} was not found.");
        }

        return StorageBin::fromDbState($row);
    }

    /**
     * Get total count of non-deleted storage bins.
     *
     * @return int
     */
    public function getTotalCount(): int
    {
        $select = $this->db
            ->select()
            ->cols(['count(id)'])
            ->from(self::TABLE_NAME)
            ->where('geloescht = 0');

        return $this->db->fetchValue(
            $select->getStatement()
        );
    }

    /**
     * Get all storage bins not marked as deleted.
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
            ->where('geloescht = 0')
            ->offset($offset)
            ->limit($limit);

        $rows = $this->db->fetchAll(
            $select->getStatement()
        );

        $storageBins = [];
        foreach ($rows as $row) {
            $storageBins[] = StorageBin::fromDbState($row);
        }

        return $storageBins;
    }

    /**
     * Check whether the name is already in use.
     *
     * @param $name
     *
     * @return false
     */
    private function verifyNameIsUnique($name)
    {
        try {
            $this->getByName($name);
        } catch (StorageBinNotFoundException $e) {
            return;
        }

        throw new StorageBinNameNotUniqueException(
            "Storage bin name needs to be unique. The name '{$name}' is already in use."
        );
    }
}
