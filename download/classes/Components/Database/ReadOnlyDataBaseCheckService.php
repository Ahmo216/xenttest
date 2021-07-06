<?php

declare(strict_types=1);

namespace Xentral\Components\Database;

use InvalidArgumentException;
use Xentral\Components\Database\Exception\QueryFailureException;

final class ReadOnlyDataBaseCheckService
{
    /** @var string $table */
    private $table;

    /** @var string $keyColumn */
    private $keyColumn;

    /** @var string $valueColumn */
    private $valueColumn;

    /** @var string $keyType */
    private $keyType;

    /** @var string $valueType */
    private $valueType;

    /** @var Database $db */
    private $db;

    /** @var Database|null $readOnlyDatabase */
    private $readOnlyDatabase;

    /**
     * ReadOnlyDataBaseCheckService constructor.
     *
     * @param string $table
     * @param string $keyColumn
     * @param string $valueColumn
     * @param string $keyType
     * @param string $valueType
     */
    public function __construct(
        string $table,
        string $keyColumn,
        string $valueColumn,
        string $keyType,
        string $valueType,
        Database $db,
        ?Database $readOnlyDatabase
    ) {
        $this->ensureType($keyType);
        $this->ensureType($valueType);
        $this->table = $table;
        $this->keyColumn = $keyColumn;
        $this->valueColumn = $valueColumn;
        $this->keyType = $keyType;
        $this->valueType = $valueType;
        $this->db = $db;
        $this->readOnlyDatabase = $readOnlyDatabase;
    }

    /**
     * @return bool
     */
    public function isReadonlyDbAvailable(): bool
    {
        return $this->readOnlyDatabase !== null;
    }

    /**
     * @param Database $db
     *
     * @return bool
     */
    public function canDbInsert(): bool
    {
        $key = (int)mt_rand(0, 200000000);
        $value = (int)mt_rand(0, 200000000);
        if ($this->keyType === 'string') {
            $key = 'readonly_db_test_' . $key;
        }
        if ($this->valueType === 'string') {
            $value = 'readonly_db_test_' . $value;
        }

        try {
            $this->readOnlyDatabase->perform(
                "INSERT INTO `{$this->table}` (`{$this->keyColumn}`, `{$this->valueColumn}`)
                    VALUES (:key, :value)",
                [
                    'key'   => $key,
                    'value' => $value,
                ]
            );
            $this->readOnlyDatabase->perform(
                "DELETE FROM `{$this->table}` WHERE `{$this->keyColumn}` = :key AND `{$this->valueColumn}` = :value",
                [
                    'key'   => $key,
                    'value' => $value,
                ]
            );

            return true;
        } catch (QueryFailureException $e) {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function candDbRead(): bool
    {
        try {
            $this->readOnlyDatabase->fetchValue(
                "SELECT * FROM `{$this->table}` LIMIT 1"
            );

            return true;
        } catch (QueryFailureException $e) {
            return false;
        }
    }

    /**
     * @param string $type
     */
    private function ensureType(string $type): void
    {
        if (!in_array($type, ['int', 'string'])) {
            throw new InvalidArgumentException("{$type} is no valid type.");
        }
    }
}
