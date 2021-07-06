<?php

declare(strict_types=1);

namespace Xentral\Components\Logger;

use Xentral\Components\Logger\Context\ContextHelper;
use Xentral\Components\Logger\Handler\DatabaseLogHandler;
use Xentral\Core\DependencyInjection\ContainerInterface;
use Xentral\Modules\Log\Exception\InvalidLoglevelException;
use Xentral\Modules\Log\Service\LoggerConfigService;

final class Bootstrap
{
    /**
     * @return array
     */
    public static function registerServices(): array
    {
        return [
            'Logger' => 'onInitLogger',
        ];
    }

    /**
     * @param ContainerInterface $container
     *
     * @return Logger
     */
    public static function onInitLogger(ContainerInterface $container): Logger
    {
        $request = $container->get('Request');
        $logLevel = LogLevel::ERROR;

        if ($container->has('LoggerConfigService')) {
            /** @var LoggerConfigService $cfg */
            $cfg = $container->get('LoggerConfigService');
            try {
                $logLevel = $cfg->getLogLevel();
            } catch (InvalidLoglevelException $e) {
            }
        }

        $contextHelper = new ContextHelper($request);
        $logger = new Logger($contextHelper);
        $handler = new DatabaseLogHandler($container->get('Database'), $logLevel);
        $logger->pushHandler($handler);

        return $logger;
    }
}
