<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\Inbox;

use Xentral\Components\MailClient\Client\MailClientInterface;

interface InboxClientFactoryInterface
{
    /**
     * @param MailClientInterface $mailClient
     *
     * @return InboxClientInterface
     */
    public function createInboxClient(MailClientInterface $mailClient): InboxClientInterface;
}
