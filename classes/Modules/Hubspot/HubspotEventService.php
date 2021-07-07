<?php

declare(strict_types=1);

namespace Xentral\Modules\Hubspot;

use Xentral\Components\Database\Database;
use Xentral\Components\Database\Exception\DatabaseExceptionInterface;
use Xentral\Modules\Hubspot\Exception\HubspotException;

final class HubspotEventService
{

    /** @var Database $db */
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * @param string $event
     *
     * @return int
     */
    public function add(string $event): int
    {
        $add = 'INSERT INTO `hubspot_events` (`event`, `created_at` )
                    VALUES (:event, NOW())';
        try {
            $this->db->perform($add, ['event' => $event]);
        } catch (DatabaseExceptionInterface $exception) {
            throw new HubspotException($exception->getMessage());
        }

        return $this->db->lastInsertId();
    }

    /**
     * @param int $id
     *
     * @return void
     */
    public function deleteById(int $id): void
    {
        $this->db->perform('DELETE FROM `hubspot_events` WHERE id = :id', ['id' => $id]);
    }

    /**
     * @return void
     */
    public function deleteAll(): void
    {
        $this->db->perform('DELETE FROM `hubspot_events`');
    }

    /**
     * @param int $days
     *
     * @return void
     */
    public function deleteByInterval(int $days = 30): void
    {
        $sql = 'DELETE FROM `hubspot_events` WHERE `created_at` < DATE_SUB(NOW(), INTERVAL :days DAY)';
        $this->db->perform($sql, ['days' => $days]);
    }
}
