<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMailer;

use Xentral\Core\DependencyInjection\ContainerInterface;
use Xentral\Modules\SystemMailer\Service\EmailAccountGateway;
use Xentral\Modules\SystemMailer\Service\MailBodyCleaner;
use Xentral\Modules\SystemMailer\Service\MailerTransportFactory;
use Xentral\Modules\SystemMailer\Service\MailLogService;

final class Bootstrap
{
    /**
     * @return array
     */
    public static function registerServices(): array
    {
        return [
            'SystemMailer'           => 'onInitMailer',
            'MailLogService'         => 'onInitMailLogService',
            'MailerTransportFactory' => 'onInitMailerTransportFactory',
            'EmailAccountGateway'    => 'onInitEmailAccountGateway',
        ];
    }

    /**
     * @param ContainerInterface $container
     *
     * @return SystemMailer
     */
    public static function onInitMailer(ContainerInterface $container): SystemMailer
    {
        return new SystemMailer(
            $container->get('MailerTransportFactory'),
            $container->get('EmailAccountGateway'),
            $container->get('MailLogService'),
            new MailBodyCleaner()
        );
    }

    /**
     * @param ContainerInterface $container
     *
     * @return MailLogService
     */
    public static function onInitMailLogService(ContainerInterface $container): MailLogService
    {
        return new MailLogService($container->get('Database'));
    }

    /**
     * @param ContainerInterface $container
     *
     * @return EmailAccountGateway
     */
    public static function onInitEmailAccountGateway(ContainerInterface $container): EmailAccountGateway
    {
        return new EmailAccountGateway($container->get('Database'));
    }

    /**
     * @param ContainerInterface $container
     *
     * @return MailerTransportFactory
     */
    public static function onInitMailerTransportFactory(ContainerInterface $container): MailerTransportFactory
    {
        return new MailerTransportFactory($container);
    }
}
