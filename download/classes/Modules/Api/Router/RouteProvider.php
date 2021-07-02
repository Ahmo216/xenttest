<?php

namespace Xentral\Modules\Api\Router;

use Exception;
use FastRoute\RouteCollector;

/**
 * Wraps the route files into a service so it can be mocked.
 */
class RouteProvider
{
    /**
     * @var array
     */
    private $routes;

    /**
     * RouteProvider constructor.
     *
     * @param array $routeFiles
     *
     * @throws Exception
     */
    public function __construct(array $routeFiles)
    {
        $routes = [];
        foreach ($routeFiles as $file) {
            if (!file_exists($file)) {
                throw new Exception("REST API route definition {$file} not found.");
            }

            $newRoutes = require_once $file;

            $routes = array_merge($routes, $newRoutes);
        }

        $this->routes = $routes;
    }

    /**
     * Add the routes to the route collector.
     *
     * @param RouteCollector $collection
     */
    public function registerRoutes(RouteCollector $collection) {
        foreach ($this->routes as $routeDefinition) {
            $method = $routeDefinition[0];
            $route = $routeDefinition[1];
            $handler = $routeDefinition[2];

            $collection->addRoute($method, $route, $handler);
        }
    }
}
