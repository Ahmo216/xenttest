<?php

declare(strict_types=1);

namespace Xentral\Modules\Log;

use Xentral\Core\DependencyInjection\ContainerInterface;
use Xentral\Modules\Log\Service\DatabaseLogGateway;
use Xentral\Modules\Log\Service\DatabaseLogService;
use Xentral\Modules\Log\Service\LoggerConfigService;
use Xentral\Modules\Log\Wrapper\CompanyConfigWrapper;

final class Bootstrap
{
    /**
     * @return array
     */
    public static function registerServices(): array
    {
        return [
            'DatabaseLogService'  => 'onInitDatabaseLogService',
            'DatabaseLogGateway'  => 'onInitDatabaseLogGateway',
            'LoggerConfigService' => 'onInitLoggerConfigService',
        ];
    }

    /**
     * @param ContainerInterface $container
     *
     * @return DatabaseLogService
     */
    public static function onInitDatabaseLogService(ContainerInterface $container): DatabaseLogService
    {
        return new DatabaseLogService($container->get('Database'));
    }

    /**
     * @param ContainerInterface $container
     *
     * @return DatabaseLogGateway
     */
    public static function onInitDatabaseLogGateway(ContainerInterface $container): DatabaseLogGateway
    {
        return new DatabaseLogGateway($container->get('Database'));
    }

    /**
     * @param ContainerInterface $container
     *
     * @return LoggerConfigService
     */
    public static function onInitLoggerConfigService(ContainerInterface $container): LoggerConfigService
    {
        return new LoggerConfigService(self::onInitCompanyConfigWrapper($container));
    }

    /**
     * @param ContainerInterface $container
     *
     * @return CompanyConfigWrapper
     */
    private static function onInitCompanyConfigWrapper(ContainerInterface $container): CompanyConfigWrapper
    {
        /** @var \ApplicationCore $app */
        $app = $container->get('LegacyApplication');

        return new CompanyConfigWrapper($app->erp);
    }
}
