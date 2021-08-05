<?php

namespace Xentral\Modules\Api;

use Exception;
use Xentral\Modules\Api\Router\RouteProvider;

class Bootstrap
{
    /**
     * @return array
     */
    public static function registerServices()
    {
        return [
            RouteProvider::class => 'onInitRouteProvider',
        ];
    }

    /**
     * Get a new instance of the RouteProvider service.
     *
     * @return RouteProvider
     *
     * @throws Exception
     */
    public static function onInitRouteProvider(): RouteProvider
    {
        $routeFiles = [
            __DIR__ . DIRECTORY_SEPARATOR . 'routes.php',
            __DIR__ . DIRECTORY_SEPARATOR . 'legacy_routes.php',
        ];

        return new RouteProvider($routeFiles);
    }
}
