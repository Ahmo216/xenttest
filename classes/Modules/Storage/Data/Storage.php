<?php

namespace Xentral\Modules\Storage\Data;

use DateTimeImmutable;
use Exception;
use Xentral\Modules\Storage\Exception\InvalidArgumentException;

/**
 * Represents a storage used in stock and inventory management.
 *
 * A storage is a place that contains storage bins. It can be for example a building, a room
 * or a shelf. The second level (saved in the `lager_platz` table) represents the storage
 * bins that contain the actual stocks.
 *
 * An instance of the class represents a single row from the `lager` database table:
 *
 *     SELECT * FROM lager;
 *     +----+-------------+--------------+---------+-------+-----------+---------------------+---------+---------+
 *     | id | bezeichnung | beschreibung | manuell | firma | geloescht | logdatei            | projekt | adresse |
 *     +----+-------------+--------------+---------+-------+-----------+---------------------+---------+---------+
 *     |  1 | Hauptlager  |              |       0 |     1 |         0 | 2020-02-16 23:00:00 |       1 |       4 |
 *     +----+-------------+--------------+---------+-------+-----------+---------------------+---------+---------+
 */
class Storage
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var bool
     *
     * @deprecated
     */
    private $isManual;

    /**
     * @var int
     *
     * @deprecated
     */
    private $companyId;

    /**
     * @var bool
     */
    private $isDeleted;

    /**
     * @var string
     */
    private $timeModified;

    /**
     * @var int
     */
    private $projectId;

    /**
     * @var int
     */
    private $addressId;

    /**
     * Storage constructor.
     *
     * @param string   $name
     * @param string   $description
     * @param bool     $isManual
     * @param int      $companyId
     * @param bool     $isDeleted
     * @param string   $timeModified
     * @param int      $projectId
     * @param int      $addressId
     * @param int|null $id
     */
    public function __construct(
        string $name,
        string $description,
        bool $isManual,
        int $companyId,
        bool $isDeleted,
        string $timeModified,
        int $projectId,
        int $addressId,
        int $id = null
    ) {
        if (empty($name)) {
            throw new InvalidArgumentException("The name of a storage cannot be empty.");
        }

        $this->name = $name;
        $this->description = $description;
        $this->isManual = $isManual;
        $this->companyId = $companyId;
        $this->isDeleted = $isDeleted;
        $this->timeModified = $timeModified;
        $this->projectId = $projectId;
        $this->addressId = $addressId;
        $this->id = $id;
    }

    /**
     * Create a new Storage instance from a database row.
     *
     * @param array $data
     *
     * @return $this
     */
    public static function fromDbState(array $data): self
    {
        return new self(
            (string)$data['bezeichnung'],
            (string)$data['beschreibung'],
            (bool)$data['manuell'],
            (int)$data['firma'],
            (bool)$data['geloescht'],
            $data['logdatei'],
            (int)$data['projekt'],
            (int)$data['adresse'],
            (int)$data['id']
        );
    }

    /**
     * Create a new Storage instance from an array of key-value pairs.
     *
     * @param array $data
     *
     * @return Storage
     */
    public static function fromArray(array $data): self
    {
        $default_values = [
            'id' => null,
            'name' => '',
            'description' => '',
            'is_manual' => false,
            'company_id' => 1,
            'is_deleted' => false,
            'project_id' => 0,
            'address_id' => 0,
            'time_modified' => date('Y-m-d H:i:s'),
        ];

        foreach ($default_values as $key => $value) {
            if (!isset($data[$key])) {
                $data[$key] = $value;
            }
        }

        return new self(
            $data['name'],
            $data['description'],
            $data['is_manual'],
            $data['company_id'],
            $data['is_deleted'],
            $data['time_modified'],
            $data['project_id'],
            $data['address_id'],
            $data['id']
        );
    }

    /**
     * Convert the object properties into an associative array that uses snake_case keys.
     *
     * Main use case for this is to get content for REST API responses.
     *
     * @return array
     */
    public function toArray(): array
    {
        $properties = get_object_vars($this);

        // Exclude deprecated properties from the return value.
        unset($properties['isManual']);
        unset($properties['companyId']);

        // These are meant only for internal use, and should not be exposed to third parties.
        unset($properties['isDeleted']);
        unset($properties['timeModified']);

        // Map camelCase property names to snake_case names (used i.e. in REST API responses).
        $map = [
            'id' => 'id',
            'name' => 'name',
            'description' => 'description',
            'projectId' => 'project_id',
            'addressId' => 'address_id',
        ];

        $values = [];
        foreach ($properties as $camelCaseName => $value) {
            $values[$map[$camelCaseName]] = $value;
        }

        return $values;
    }

    /**
     * Set the ID of the storage.
     *
     * @param int $id
     *
     * @return $this
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the unique ID of the storage.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set a human-readable name for the storage.
     *
     * This could be for example "Warehouse A", "Storage room 123", etc.
     *
     * @param string $name
     *
     * @return Storage
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the human-readable name of the storage.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set human-readable description for the storage.
     *
     * This could be for example "The second storage room on the left in the corridor".
     *
     * @param string $description
     * @return Storage
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get human-readable description of the storage.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * DO NOT USE. This field has been deprecated.
     *
     * Benedikt has confirmed that this database column can be deleted completely.
     *
     * @param bool $isManual
     *
     * @return Storage
     * @deprecated
     */
    public function setIsManual(bool $isManual): self
    {
        $this->isManual = $isManual;

        return $this;
    }

    /**
     * DO NOT USE. This field has been deprecated.
     *
     * Benedikt has confirmed that this database column can be deleted completely.
     *
     * @return bool
     *
     * @deprecated
     */
    public function getIsManual(): bool
    {
        return $this->isManual;
    }

    /**
     * DO NOT USE. This has been deprecated in favor of setProjectId().
     *
     * Benedikt has confirmed that this database column can be deleted completely.
     *
     * @param int $companyId
     *
     * @return Storage
     * @deprecated Use setProjectId() instead of setCompanyId().
     */
    public function setCompanyId(int $companyId): self
    {
        $this->companyId = $companyId;

        return $this;
    }

    /**
     * DO NOT USE. This has been deprecated in favor of getProjectId().
     *
     * Benedikt has confirmed that this database column can be deleted completely.
     *
     * @return int
     *
     * @deprecated Use getProjectId() instead of getCompanyId().
     */
    public function getCompanyId(): int
    {
        return $this->companyId;
    }

    /**
     * Mark the warehouse as active or deleted.
     *
     * Warehouse should not be deleted but only marked as such, if an
     * entry in the lager_platz table has been linked to it.
     *
     * @param bool $isDeleted
     * @return Storage
     */
    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * Is the warehouse active or deleted?
     *
     * @return bool
     */
    public function getIsDeleted(): bool
    {
        return $this->isDeleted;
    }

    /**
     * Update the modification time to the current time.
     *
     * @return $this
     */
    public function updateTimeModified(): self
    {
        $this->timeModified = date('Y-m-d H:i:s');

        return $this;
    }

    /**
     * Get the last modification time of the storage data.
     *
     * @return DateTimeImmutable
     *
     * @throws Exception
     */
    public function getTimeModified(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->timeModified);
    }

    /**
     * Set id of the project to associate with this storage.
     *
     * @param int $projectId
     *
     * @return $this
     */
    public function setProjectId(int $projectId): self
    {
        $this->projectId = $projectId;

        return $this;
    }

    /**
     * Get id of the project associated with this storage.
     *
     * @return int
     */
    public function getProjectId(): int
    {
        return $this->projectId;
    }

    /**
     * Set id of the address to associate with this storage place.
     *
     * @param int $addressId
     *
     * @return Storage
     */
    public function setAddressId(int $addressId): self
    {
        $this->addressId = $addressId;

        return $this;
    }

    /**
     * Get id of the address associated with this storage place.
     *
     * @return int
     */
    public function getAddressId(): int
    {
        return $this->addressId;
    }
}
