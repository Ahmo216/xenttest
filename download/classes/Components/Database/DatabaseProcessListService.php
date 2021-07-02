<?php

declare(strict_types=1);

namespace Xentral\Components\Database;

use Exception;
use Xentral\Components\Database\Data\ProcessListQueryCollection;

final class DatabaseProcessListService implements DatabaseProcessListInterface
{
    /** @var Database $db */
    private $db;

    /**
     * DatabaseProcessListService constructor.
     *
     * @param Database $db
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * @return ProcessListQueryCollection
     */
    public function getProcessList(): ProcessListQueryCollection
    {
        try {
            return ProcessListQueryCollection::fromDbState($this->db->fetchAll('SHOW PROCESSLIST'));
        } catch (Exception $e) {
            return new ProcessListQueryCollection();
        }
    }
}
