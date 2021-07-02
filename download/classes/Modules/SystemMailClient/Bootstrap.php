<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMailClient;

use Xentral\Core\DependencyInjection\ContainerInterface;

final class Bootstrap
{
    /**
     * @return array
     */
    public static function registerServices(): array
    {
        return [
            'MailClientConfigProvider' => 'onInitMailClientConfigProvider',
            'MailClientProvider'       => 'onInitMailClientProvider',
        ];
    }

    /**
     * @param ContainerInterface $container
     *
     * @return MailClientConfigProvider
     */
    public static function onInitMailClientConfigProvider(ContainerInterface $container): MailClientConfigProvider
    {
        return new MailClientConfigProvider(
            $container->get('EmailAccountGateway'),
            $container->get('GoogleAccountGateway'),
            $container->get('GoogleApiClientFactory')
        );
    }

    /**
     * @param ContainerInterface $container
     *
     * @return MailClientProvider
     */
    public static function onInitMailClientProvider(ContainerInterface $container): MailClientProvider
    {
        return new MailClientProvider(
            $container->get('MailClientFactory'),
            $container->get('MailClientConfigProvider'),
            $container->get('EmailAccountGateway')
        );
    }
}
