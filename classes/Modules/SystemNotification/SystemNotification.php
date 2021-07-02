<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemNotification;

use Xentral\Modules\SystemNotification\Gateway\NotificationGateway;
use Xentral\Modules\SystemNotification\Data\NotificationMessage;
use Xentral\Modules\SystemNotification\Service\NotificationService;
use Xentral\Modules\SystemNotification\Exception\RuntimeException;

class SystemNotification implements SystemNotificationInterface
{
    /** @var NotificationGateway $gateway */
    private $gateway;

    /** @var NotificationService $service */
    private $service;

    /**
     * @param NotificationGateway $gateway
     * @param NotificationService $service
     */
    public function __construct(NotificationGateway $gateway, NotificationService $service)
    {
        $this->gateway = $gateway;
        $this->service = $service;
    }

    /**
     * @inheritDoc
     */
    public function createNotification(
        int $userId,
        string $type,
        string $title,
        string $message = null,
        bool $priority = false,
        array $options = [],
        array $tags = []
    ): NotificationMessage {
        if ($this->gateway->hasDuplicatedMessage($userId, $title, $message)) {
            $object = $this->gateway->tryGetDuplicateMessage($userId, $title, $message);
            if (empty($object)) {
                throw new RuntimeException('failed to create notification');
            }

            return $object;
        }
        $id = (int)$this->service->create(
            $userId,
            $type,
            $title,
            $message,
            $priority,
            count($options) > 0 ? $options: null,
            $tags
        );

        return $this->gateway->tryGetNotificationMessage($id);
    }

    /**
     * @inheritDoc
     */
    public function addNotification(int $userId, NotificationMessage $message): NotificationMessage
    {
        return $this->createNotification(
            $userId,
            $message->getType(),
            $message->getTitle(),
            $message->getMessage(),
            $message->isPriority(),
            $message->getOptions(),
            $message->getTags()
        );
    }

    /**
     * @inheritDoc
     */
    public function removeNotification(int $id): void
    {
        $this->service->delete($id);
    }

    /**
     * @inheritDoc
     */
    public function removeAllNotificationsByTags(array $tags, bool $keepPriority = false): void
    {
        $this->service->deleteByTags($tags, null, $keepPriority);
    }

    /**
     * @inheritDoc
     */
    public function removeUserNotificationsByTags(int $userId, array $tags, bool $keepPriority = false): void
    {
        $this->service->deleteByTags($tags, $userId, $keepPriority);
    }

    /**
     * @inheritDoc
     */
    public function getNotificationsByUser(int $userId): array
    {
        $dataArray = $this->gateway->findByUserId($userId);
        $notifications = [];
        foreach ($dataArray as $data) {
            $notifications[] = NotificationMessage::fromDbState($data);
        }

        return $notifications;
    }
}
