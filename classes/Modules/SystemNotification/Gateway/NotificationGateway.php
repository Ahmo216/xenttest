<?php

namespace Xentral\Modules\SystemNotification\Gateway;

use Aura\SqlQuery\Common\Select;
use Xentral\Components\Database\Database;
use Xentral\Modules\SystemNotification\Data\NotificationMessage;

final class NotificationGateway
{
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
     * @param int $userId
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function findByUserId($userId, $offset = 0, $limit = 500)
    {
        $sql = 'SELECT n.id, n.type, n.title, n.message,n.tags, n.options_json, n.priority 
                FROM notification_message AS n 
                WHERE n.user_id = :user_id 
                ORDER BY n.created_at ASC
                LIMIT :offset, :limit';
        $result = $this->db->fetchAll($sql, [
            'user_id' => (int)$userId,
            'offset'  => (int)$offset,
            'limit'   => (int)$limit,
        ]);

        if (empty($result)) {
            return [];
        }

        return $result;
    }

    /**
     * @param int    $userId
     * @param string $title
     * @param string $message
     *
     * @return bool
     */
    public function hasDuplicatedMessage($userId, $title, $message)
    {
        $query = $this->db->select()
            ->cols(['COUNT(n.id)'])
            ->from('notification_message AS n ')
            ->where('n.user_id = :user_id')
            ->where('n.title = :title');
        if (empty($message)) {
            $query = $query->where('ISNULL(n.message)');
        } else {
            $query = $query->where('n.message = :message');
        }
        $query = $query->limit(1);

        $result = $this->db->fetchValue(
            $query->getStatement(),
            [
                'user_id' => (int)$userId,
                'message' => $message,
                'title'   => $title,
            ]
        );

        return (int)$result > 0;
    }

    /**
     * @param int         $userId
     * @param string      $title
     * @param string|null $message
     *
     * @return NotificationMessage|null
     */
    public function tryGetDuplicateMessage(int $userId, string $title, string $message = null): ?NotificationMessage
    {
        $query = $this->getBaseQuery()->where('n.user_id = :user_id')->where('n.title = :title');

        if ($message === null) {
            $query = $query->where('ISNULL(n.message)');
        } else {
            $query = $query->where('n.message = :message');
        }

        $result = $this->db->fetchRow(
            $query->getStatement(),
            ['user_id' => $userId, 'message' => $message, 'title' => $title]
        );
        if (empty($result)) {
            return null;
        }

        return NotificationMessage::fromDbState($result);
    }

    /**
     * @param int $id
     *
     * @return NotificationMessage|null
     */
    public function tryGetNotificationMessage(int $id): ?NotificationMessage
    {
        if ($id < 1) {
            return null;
        }

        $query = $this->getBaseQuery()->where('n.id = :id');
        $result = $this->db->fetchRow($query->getStatement(), ['id' => $id]);
        if (empty($result)) {
            return null;
        }

        return NotificationMessage::fromDbState($result);
    }

    /**
     * @return Select
     */
    private function getBaseQuery(): Select
    {
        return $this->db->select()
            ->cols(['n.id', 'n.type', 'n.title', 'n.message', 'n.tags', 'n.options_json', 'n.priority'])
            ->from('notification_message as n');
    }
}
