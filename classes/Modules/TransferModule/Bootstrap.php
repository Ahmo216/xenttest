<?php

declare(strict_types=1);

namespace Xentral\Modules\TransferModule;

use Xentral\Core\DependencyInjection\ContainerInterface;

final class Bootstrap
{
    /**
     * @return array
     */
    public static function registerServices()
    {
        return [
            'ApiRequestService'      => 'onInitApiRequestService',
        ];
    }

    /**
     * @param ContainerInterface $container
     *
     * @return ApiRequestService
     */
    public static function onInitApiRequestService(ContainerInterface $container): ApiRequestService
    {
        return new ApiRequestService(
            $container->get('Database'),
            $container->get('LabelGateway'),
            $container->get('LabelService')
        );
    }
}
