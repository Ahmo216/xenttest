<?php

declare(strict_types=1);

namespace Xentral\Components\MailClient;

use Xentral\Components\MailClient\Client\MimeMessageFormatter;
use Xentral\Components\MailClient\Client\MimeMessageFormatterInterface;
use Xentral\Core\DependencyInjection\ContainerInterface;

final class Bootstrap
{
    /**
     * @return array
     */
    public static function registerServices(): array
    {
        return [
            'MailClientFactory'              => 'onInitMailClientFactory',
            'MailClientMimeMessageFormatter' => 'onInitMimeMessageFormatter',
        ];
    }

    /**
     * @param ContainerInterface $container
     *
     * @return MailClientFactory
     */
    public static function onInitMailClientFactory(ContainerInterface $container): MailClientFactory
    {
        return new MailClientFactory();
    }

    /**
     * @param ContainerInterface $container
     *
     * @return MimeMessageFormatterInterface
     */
    public static function onInitMimeMessageFormatter(ContainerInterface $container): MimeMessageFormatterInterface
    {
        return new MimeMessageFormatter();
    }
}
