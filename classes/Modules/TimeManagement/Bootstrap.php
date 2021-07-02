<?php

namespace Xentral\Modules\TimeManagement;

use ApplicationCore;
use Mitarbeiterzeiterfassung;
use Xentral\Core\DependencyInjection\ContainerInterface;
use Xentral\Modules\TimeManagement\Service\GroupGateway;
use Xentral\Modules\TimeManagement\Service\HolidayGateway;
use Xentral\Modules\TimeManagement\Service\TimeManagementHistoryService;
use Xentral\Modules\TimeManagement\Service\TimeManagementSettingGateway;
use Xentral\Modules\TimeManagement\Service\TimeManagementTargetHourGateway;
use Xentral\Modules\TimeManagement\Service\TimeManagementTargetHourService;
use Xentral\Modules\TimeManagement\Wrapper\TimeManagementMailerWrapper;
use Xentral\Modules\TimeManagement\Wrapper\TimeManagementTargetHourWrapper;

final class Bootstrap
{
    public static function registerServices(): array
    {
        return [
            'TimeManagementModule' => 'onInitTimeManagementModule',
            'TimeManagementMailer' => 'onInitTimeManagementMailer',
        ];
    }

    public static function onInitTimeManagementModule(ContainerInterface $container): TimeManagementModule
    {
        return new TimeManagementModule(
            self::onInitTimeManagementTargetHourGateway($container),
            self::onInitTimeManagementTargetHourService($container),
            self::onInitTimeManagementSettingGateway($container),
            self::onInitHolidayGateway($container),
            self::onInitGroupGateway($container),
            self::onInitTimeManagementTargetHourWrapper($container),
            self::onInitTimeManagementHistoryService($container)
        );
    }

    private static function onInitTimeManagementTargetHourService(ContainerInterface $container
    ): TimeManagementTargetHourService {
        return new TimeManagementTargetHourService(
            $container->get('Database')
        );
    }

    private static function onInitTimeManagementTargetHourGateway(ContainerInterface $container
    ): TimeManagementTargetHourGateway {
        return new TimeManagementTargetHourGateway(
            $container->get('Database')
        );
    }

    private static function onInitTimeManagementSettingGateway(ContainerInterface $container
    ): TimeManagementSettingGateway {
        return new TimeManagementSettingGateway(
            $container->get('Database')
        );
    }

    private static function onInitHolidayGateway(ContainerInterface $container): HolidayGateway
    {
        return new HolidayGateway(
            $container->get('Database')
        );
    }

    private static function onInitGroupGateway(ContainerInterface $container): GroupGateway
    {
        return new GroupGateway(
            $container->get('Database')
        );
    }

    private static function onInitTimeManagementTargetHourWrapper(ContainerInterface $container
    ): TimeManagementTargetHourWrapper {
        /** @var ApplicationCore $app */
        $app = $container->get('LegacyApplication');

        /** @var Mitarbeiterzeiterfassung $timeRecordingModule */
        $timeRecordingModule = $app->erp->LoadModul('mitarbeiterzeiterfassung');

        return new TimeManagementTargetHourWrapper($timeRecordingModule);
    }

    private static function onInitTimeManagementHistoryService(ContainerInterface $container
    ): TimeManagementHistoryService {
        return new TimeManagementHistoryService(
            $container->get('Database')
        );
    }

    public static function onInitTimeManagementMailer(ContainerInterface $container): TimeManagementMailerWrapper
    {
        /** @var ApplicationCore $app */
        $app = $container->get('LegacyApplication');

        return new TimeManagementMailerWrapper(
            $app->erp
        );
    }
}
