<?php

namespace Xentral\Modules\ImportTemplate;

use Xentral\Core\DependencyInjection\ContainerInterface;
use Xentral\Modules\ImportTemplate\Service\ImportTemplateGateway;
use Xentral\Modules\ImportTemplate\Service\ImportTemplateJsonService;
use Xentral\Modules\ImportTemplate\Service\ImportTemplateService;

final class Bootstrap
{
    public static function registerServices(): array
    {
        return [
            'ImportTemplateJsonService' => 'onInitImportTemplateJsonService',
            'ImportTemplateService'     => 'onInitImportTemplateService',
            'ImportTemplateGateway'     => 'onInitImportTemplateGateway',
        ];
    }

    public static function onInitImportTemplateJsonService(ContainerInterface $container): ImportTemplateJsonService
    {
        return new ImportTemplateJsonService(
            $container->get('ImportTemplateService'),
            $container->get('ImportTemplateGateway')
        );
    }

    public static function onInitImportTemplateService(ContainerInterface $container): ImportTemplateService
    {
        return new ImportTemplateService(
            $container->get('Database')
        );
    }

    public static function onInitImportTemplateGateway(ContainerInterface $container): ImportTemplateGateway
    {
        return new ImportTemplateGateway(
            $container->get('Database')
        );
    }
}
