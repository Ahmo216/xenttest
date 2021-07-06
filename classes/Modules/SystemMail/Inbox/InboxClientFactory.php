<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\Inbox;

use Xentral\Components\Logger\LoggerAwareTrait;
use Xentral\Components\MailClient\Client\MailClientInterface;

class InboxClientFactory implements InboxClientFactoryInterface
{
    use LoggerAwareTrait;

    /**
     * @param MailClientInterface $mailClient
     *
     * @return InboxClientInterface
     */
    public function createInboxClient(MailClientInterface $mailClient): InboxClientInterface
    {
        $client = new ImapInboxClient($mailClient);
        $client->setLogger($this->logger);

        return $client;
    }
}
