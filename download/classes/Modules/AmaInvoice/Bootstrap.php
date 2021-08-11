<?php

namespace Xentral\Modules\AmaInvoice;

use Xentral\Core\DependencyInjection\ContainerInterface;
use Xentral\Modules\AmaInvoice\Scheduler\AmaInvoiceTask;
use Xentral\Modules\AmaInvoice\Service\AmaInvoiceService;
use Xentral\Modules\AmaInvoice\Wrapper\TurnoverThresholdWrapper;
use Xentral\Modules\SuperSearch\Wrapper\CompanyConfigWrapper;

final class Bootstrap
{
    /**
     * @return array
     */
    public static function registerServices(): array
    {
        return [
            'AmaInvoiceService' => 'onInitAmaInvoiceService',
            // Cronjob-Tasks
            'AmaInvoiceTask' => 'onInitAmaInvoiceTask',
        ];
    }

    /**
     * @param ContainerInterface $container
     *
     * @return AmaInvoiceTask
     */
    public static function onInitAmaInvoiceTask(ContainerInterface $container): AmaInvoiceTask
    {
        return new AmaInvoiceTask(
            $container->get('AmaInvoiceService'),
            self::onInitCompanyConfigWrapper($container)
        );
    }

    /**
     * @param ContainerInterface $container
     *
     * @return AmaInvoiceService
     */
    public static function onInitAmaInvoiceService(ContainerInterface $container): AmaInvoiceService
    {
        $legacyApp = $container->get('LegacyApplication');

        return new AmaInvoiceService(
            $container->get('Database'),
            $legacyApp,
            new TurnoverThresholdWrapper($legacyApp, $container->get('Database'))
        );
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
