<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemNotification;

use Xentral\Modules\SystemNotification\Data\NotificationMessage;

interface SystemNotificationInterface
{
    /**
     * @param int         $userId
     * @param string      $type
     * @param string      $title
     * @param string|null $message
     * @param bool        $priority
     * @param array       $options
     * @param array       $tags
     *
     * @return NotificationMessage|null
     */
    public function createNotification(
        int $userId,
        string $type,
        string $title,
        string $message = null,
        bool $priority = false,
        array $options = [],
        array $tags = []
    ): ?NotificationMessage;

    /**
     * @param int                 $userId
     * @param NotificationMessage $message
     *
     * @return NotificationMessage|null
     */
    public function addNotification(int $userId, NotificationMessage $message): ?NotificationMessage;

    /**
     * @param int $id
     *
     * @return void
     */
    public function removeNotification(int $id): void;

    /**
     * @param array $tags
     * @param bool  $keepPriorityMessage
     *
     * @return void
     */
    public function removeAllNotificationsByTags(array $tags, bool $keepPriorityMessage = false): void;

    /**
     * @param int   $userId
     * @param array $tags
     * @param bool  $keepPriorityMessage
     *
     * @return void
     */
    public function removeUserNotificationsByTags(int $userId, array $tags, bool $keepPriorityMessage = false): void;

    /**
     * @param int $userId
     *
     * @return NotificationMessage[]|[]
     */
    public function getNotificationsByUser(int $userId): array;
}
