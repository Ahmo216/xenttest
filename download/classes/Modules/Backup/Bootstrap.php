<?php

namespace Xentral\Modules\Backup;

use ApplicationCore;
use Xentral\Core\DependencyInjection\ContainerInterface;
use Xentral\Modules\Backup\Scheduler\BackupScheduleTask;

final class Bootstrap
{
    public static function registerServices(): array
    {
        return [
            'BackupGateway'                    => 'onInitBackupGateway',
            'BackupService'                    => 'onInitBackupService',
            'BackupSystemConfigurationService' => 'onInitBackupSystemConfigurationService',
            'BackupProcessStarterService'      => 'onInitBackupProcessStarterService',
            'BackupNotificationService'        => 'onInitBackupNotificationService',
            'BackupScheduleTask'               => 'onInitBackupTask',
        ];
    }

    public static function onInitBackupGateway(ContainerInterface $container): BackupGateway
    {
        return new BackupGateway($container->get('Database'));
    }

    public static function onInitBackupService(ContainerInterface $container): BackupService
    {
        return new BackupService(
            $container->get('BackupGateway'),
            $container->get('DatabaseBackup'),
            $container->get('FileBackup'),
            $container->get('BackupProcessStarterService'),
            $container->get('BackupSystemConfigurationService'),
            $container->get('Database'),
            $container->get('BackupLog')
        );
    }

    public static function onInitBackupSystemConfigurationService(ContainerInterface $container): BackupSystemConfigurationService
    {
        /** @var ApplicationCore $app */
        $app = $container->get('LegacyApplication');

        return new BackupSystemConfigurationService($app->erp);
    }

    public static function onInitBackupProcessStarterService(ContainerInterface $container): BackupProcessStarterService
    {
        /** @var ApplicationCore $app */
        $app = $container->get('LegacyApplication');

        return new BackupProcessStarterService($app->erp);
    }

    public static function onInitBackupNotificationService(ContainerInterface $container): BackupNotificationService
    {
        return new BackupNotificationService(
            $container->get('BackupSystemConfigurationService'),
            $container->get('NotificationService')
        );
    }

    public static function onInitBackupTask(ContainerInterface $container): BackupScheduleTask
    {
        /** @var ApplicationCore $app */
        $app = $container->get('LegacyApplication');

        return new BackupScheduleTask(
            $container->get('Database'),
            $container->get('BackupSystemConfigurationService'),
            $container->get('BackupNotificationService'),
            $app,
            $container->get('BackupService')
        );
    }
}
