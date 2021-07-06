<?php

namespace Xentral\Modules\Api\Engine;

use Xentral\Components\Http\Collection\ReadonlyParameterCollection;
use Xentral\Components\Http\Request;
use Xentral\Components\Http\Response;
use Xentral\Modules\Api\Auth\DigestAuth;
use Xentral\Modules\Api\Auth\PermissionGuard;
use Xentral\Modules\Api\Controller\Legacy\DefaultController;
use Xentral\Modules\Api\Controller\Legacy\GobNavConnectController;
use Xentral\Modules\Api\Controller\Legacy\MobileApiController;
use Xentral\Modules\Api\Controller\Legacy\OpenTransConnectController;
use Xentral\Modules\Api\Controller\Legacy\ShopimportController;
use Xentral\Modules\Api\Controller\Version1\AbstractController;
use Xentral\Modules\Api\Controller\Version1\ReportsController;
use Xentral\Modules\Api\Converter\Converter;
use Xentral\Modules\Api\Http\Exception\HttpException;
use Xentral\Modules\Api\Http\PathInfoDetector;
use Xentral\Modules\Api\Router\RouteProvider;
use Xentral\Modules\Api\Router\Router;
use Xentral\Modules\Api\Router\RouterResult;

class ApiApplication
{
    /** @var ApiContainer $container */
    protected $container;

    /** @var Converter $converter */
    protected $converter;

    /** @var Request $request */
    protected $request;

    /** @var Response $response */
    protected $response;

    /** @var DigestAuth $auth */
    protected $auth;

    /** @var RouterResult|null $routerResult */
    protected $routerResult;

    /** @var RouteProvider */
    private $routeProvider;

    /**
     * @param ApiContainer $container
     */
    public function __construct(ApiContainer $container)
    {
        $this->converter = $container->get('Converter');
        $this->routeProvider = $container->get('RouteProvider');
        $this->container = $container;
    }

    /**
     * @param Request|null $request
     *
     * @return Response
     */
    public function handle(?Request $request = null)
    {
        $this->request = $request ?: Request::createFromGlobals();
        $this->container->add('Request', $this->request);

        $method = $this->request->getMethod();
        $uri = $this->request->getPathInfo();

        /**
         * Failsafe; falls Webserver-Konfiguration Probleme bereitet.
         * Dann kann der Pfad zur Ressource im Parameter "path" übergeben werden.
         *
         * @example /api/index.php?path=/v1/artikelkategorien&sort=bezeichnung
         */
        if ($uri === '' && $this->request->get->has('path')) {
            $uri = $this->request->get->get('path');
            $queryParams = $this->request->get->all();
            unset($queryParams['path']);
            $this->request->get = new ReadonlyParameterCollection($queryParams);
        }

        try {
            $this->auth = $this->get('DigestAuth');
            $this->auth->checkLogin();

            $this->response = $this->handleApiRequest($method, $uri);
        } catch (HttpException $e) {
            $this->response = $this->createErrorResponse($e);
        }

        return $this->response;
    }

    /**
     * @param string $serviceName
     *
     * @return object
     */
    protected function get($serviceName)
    {
        return $this->container->get($serviceName);
    }

    /**
     * @param string $method
     * @param string $uri
     *
     * @return Response
     */
    protected function handleApiRequest($method, $uri)
    {
        /** @var Router $apiRouter */
        $apiRouter = $this->get('ApiRouter');

        $collection = $apiRouter->createCollection();
        $this->routeProvider->registerRoutes($collection);

        $apiRouter->setCollection($collection);
        $routeInfo = $apiRouter->dispatch($method, $uri);
        $this->routerResult = $routeInfo;

        /*
         * Check permission
         */
        if ($routeInfo->getPermission() !== null) {
            $guard = new PermissionGuard($this->container->get('Database'), $this->auth->getApiAccountId());
            $guard->check($routeInfo->getPermission());
        }

        /*
         * Controller dispatchen
         */

        $this->request->attributes->add($routeInfo->getRouterParams());

        // Legacy-API-Controller
        if ($routeInfo->getControllerClass() === DefaultController::class) {
            $controller = new DefaultController(
                $this->container->get('LegacyApi'),
                $this->container->get('Request'),
                $this->container->get('DigestAuth')->getApiAccountId()
            );
            $action = $routeInfo->getControllerAction();

            return $controller->$action();
        }

        if ($routeInfo->getControllerClass() === GobNavConnectController::class) {
            $controller = new GobNavConnectController(
                $this->container->get('LegacyApplication'),
                $this->container->get('Request')
            );
            $action = $routeInfo->getControllerAction();

            return $controller->$action();
        }

        if ($routeInfo->getControllerClass() === OpenTransConnectController::class) {
            $controller = new OpenTransConnectController(
                $this->container->get('LegacyApplication'),
                $this->container->get('OpenTransConverter'),
                $this->container->get('Request'),
                $this->container->get('DigestAuth')->getApiAccountId()
            );
            $action = $routeInfo->getControllerAction();

            return $controller->$action();
        }

        if ($routeInfo->getControllerClass() === ShopimportController::class) {
            $controller = new ShopimportController(
                $this->container->get('LegacyApplication'),
                $this->container->get('Request'),
                $this->container->get('DigestAuth')->getApiAccountId()
            );
            $action = $routeInfo->getControllerAction();

            return $controller->$action();
        }

        if ($routeInfo->getControllerClass() === MobileApiController::class) {
            $controller = new MobileApiController(
                $this->container->get('LegacyApplication'),
                $this->container->get('Converter'),
                $this->container->get('Database'),
                $this->container->get('Request')
            );
            $action = $routeInfo->getControllerAction();

            return $controller->$action();
        }

        if ($routeInfo->getControllerClass() === ReportsController::class) {
            $controller = new ReportsController(
                $this->container->get('LegacyApplication'),
                $this->container->get('Request'),
                $this->container->get('DigestAuth')->getApiAccountId()
            );
            $action = $routeInfo->getControllerAction();

            return $controller->$action();
        }

        /** @var AbstractController $controller */
        $controller = $this->container->getApiController(
            $routeInfo->getControllerClass()
        );
        $controller->setResourceClass($routeInfo->getResourceClass());

        return $controller->dispatch($routeInfo->getControllerAction());
    }

    /**
     * @param int $errorCode
     *
     * @return string
     */
    private function buildErrorLink($errorCode)
    {
        $pathInfo = $this->request->getPathInfo();
        $fullUrl = $this->request->getFullUrl();

        $apiUrl = $fullUrl;
        if ($pos = strrpos($fullUrl, $pathInfo)) {
            $apiUrl = substr($fullUrl, 0, $pos);
        }

        if ($pos = strrpos($apiUrl, '/index.php')) {
            $apiUrl = substr($apiUrl, 0, $pos);
        }

        return $apiUrl . '/docs.html#error-' . $errorCode;
    }

    /**
     * @param ApiHttpException $e
     *
     * @return Response
     */
    private function createErrorResponse($e)
    {
        // Fehler-Informationen zusammenbauen
        $data = [
            'error' => [
                'code' => $e->getCode(),
                'http_code' => $e->getStatusCode(),
                'message' => $e->getMessage(),
                'href' => $this->buildErrorLink($e->getCode()),
            ],
        ];
        if ($e->hasErrors()) {
            // Validierungsfehler anhängen
            $data['error']['details'] = $e->getErrors();
        }

        if ($this->isDebugModeActive()) {
            $data['debug'] = [];

            // Router-Informationen anhängen
            $data['debug']['router'] = $this->routerResult !== null ? $this->routerResult->toArray() : false;

            // Request-Informationen anhängen
            $pathInfoDetector = new PathInfoDetector($this->request);
            $pathInfo = $pathInfoDetector->detect();
            $data['debug']['request'] = [
                'isFailsafe' => $this->request->isFailsafeUri(),
                'pathInfo' => [
                    'actual' => (string)$this->request->server->get('PATH_INFO'),
                    'expected' => $pathInfo,
                ],
                'info' => [
                    'method' => $this->request->getMethod(),
                    'requestUri' => $this->request->getRequestUri(),
                    'fullUri' => $this->request->getFullUri(true),
                ],
                'serverParams' => $this->request->server->all(),
                'header' => $this->request->header->all(),
                'getParams' => $this->request->get->all(),
                'postParams' => $this->request->post->all(),
                'additionalParams' => $this->request->attributes->all(),
            ];
        }

        // XML oder JSON
        if (in_array('text/html', $this->request->getAcceptableContentTypes(), true)) {
            // Client ist vermutlich ein Browser > JSON ausliefern
            $json = $this->converter->arrayToJson($data);
            $response = new Response(
                $json,
                $e->getStatusCode(),
                ['Content-Type' => 'application/json; charset=UTF-8']
            );
        } else {
            if (in_array('application/xml', $this->request->getAcceptableContentTypes(), true)) {
                $xml = $this->converter->arrayToXml($data['error'], 'error');
                $response = new Response(
                    $xml,
                    $e->getStatusCode(),
                    ['Content-Type' => 'application/xml; charset=UTF-8']
                );
            } else {
                $json = $this->converter->arrayToJson($data);
                $response = new Response(
                    $json,
                    $e->getStatusCode(),
                    ['Content-Type' => 'application/json; charset=UTF-8']
                );
            }
        }

        // Login-Header mitschicken
        $response->setHeader('WWW-Authenticate', $this->auth->generateAuthenticationString());

        return $response;
    }

    /**
     * @return bool
     */
    private function isDebugModeActive()
    {
        return defined('DEBUG_MODE') && (int)DEBUG_MODE === 1;
    }
}
