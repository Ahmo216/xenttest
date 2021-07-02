<?php

declare(strict_types=1);

namespace Xentral\Components\Database;


final class DatabaseProcessKillService implements DatabaseProcessKillInterface
{
    /** @var Database $db */
    private $db;

    /**
     * DatabaseProcessKillService constructor.
     *
     * @param Database $db
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * @param int $processId
     */
    public function kill(int $processId): void
    {
        $this->db->perform("KILL {$processId}");
    }
}
