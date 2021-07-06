<?php

namespace Xentral\Modules\Report;

use ApplicationCore;
use Xentral\Core\DependencyInjection\ContainerInterface;
use Xentral\Modules\Report\Service\ReportColumnFormatter;

final class Bootstrap
{
    public static function registerServices(): array
    {
        return [
            'ReportGateway' => 'onInitReportGateway',
            'ReportService' => 'onInitReportService',
            'ReportCsvExportService' => 'onInitReportCsvExportService',
            'ReportPdfExportService' => 'onInitReportPdfExportService',
            'ReportJsonExportService' => 'onInitReportJsonExportService',
            'ReportJsonImportService' => 'onInitReportJsonImportService',
            'ReportLegacyConverterService' => 'onInitReportLegacyConverterService',
            'ReportResolveParameterService' => 'onInitReportResolveParameterService',
            'ReportChartService' => 'onInitReportChartService',
            'ReportColumnFormatter' => 'onInitReportColumnFormatter',
        ];
    }

    public static function onInitReportGateway(ContainerInterface $container): ReportGateway
    {
        return new ReportGateway($container->get('Database'));
    }

    public static function onInitReportService(ContainerInterface $container): ReportService
    {
        return new ReportService(
            $container->get('Database'),
            $container->get('ReportGateway'),
            $container->get('ReportResolveParameterService')
        );
    }

    public static function onInitReportCsvExportService(ContainerInterface $container): ReportCsvExportService
    {
        /** @var ApplicationCore $app */
        $app = $container->get('LegacyApplication');

        return new ReportCsvExportService(
            $container->get('ReadOnlyDatabase'),
            $container->get('ReportGateway'),
            $container->get('ReportService'),
            $app->erp->GetTMP()
        );
    }

    public static function onInitReportPdfExportService(ContainerInterface $container): ReportPdfExportService
    {
        /** @var ApplicationCore $app */
        $app = $container->get('LegacyApplication');

        return new ReportPdfExportService(
            $container->get('ReadOnlyDatabase'),
            $container->get('ReportGateway'),
            $container->get('ReportService'),
            $app->erp->GetTMP()
        );
    }

    public static function onInitReportJsonExportService(ContainerInterface $container): ReportJsonExportService
    {
        /** @var ApplicationCore $app */
        $app = $container->get('LegacyApplication');

        return new ReportJsonExportService(
            $container->get('ReadOnlyDatabase'),
            $container->get('ReportGateway'),
            $container->get('ReportService'),
            $app->erp->GetTMP()
        );
    }

    public static function onInitReportJsonImportService(ContainerInterface $container): ReportJsonImportService
    {
        return new ReportJsonImportService(
            $container->get('ReportGateway'),
            $container->get('ReportService')
        );
    }

    public static function onInitReportLegacyConverterService(ContainerInterface $container): ReportLegacyConverterService
    {
        return new ReportLegacyConverterService(
            $container->get('Database'),
            $container->get('ReportService')
        );
    }

    public static function onInitReportResolveParameterService(ContainerInterface $container): ReportResolveParameterService
    {
        /** @var ApplicationCore $app */
        $app = $container->get('LegacyApplication');
        $userId = $app->User->GetID();
        $userProjects = $app->User->getUserProjects();
        $userIsAdmin = $app->User->GetType() === 'admin';

        return new ReportResolveParameterService(
            $userId,
            $userProjects,
            $userIsAdmin
        );
    }

    public static function onInitReportChartService(ContainerInterface $container): ReportChartService
    {
        return new ReportChartService(
            $container->get('ReadOnlyDatabase'),
            $container->get('ReportService'),
            $container->get('ReportGateway')
        );
    }

    public static function onInitReportColumnFormatter(ContainerInterface $container): ReportColumnFormatter
    {
        return new ReportColumnFormatter();
    }
}
