<?php

namespace App\Core;

use App\Core\Exceptions\ServiceNotFoundException;
use Illuminate\Container\EntryNotFoundException;
use Illuminate\Foundation\Application as BaseApplication;
use RuntimeException;
use Xentral\Components\Logger\LoggerAwareTrait;
use Xentral\Core\DependencyInjection\ContainerInterface;
use Xentral\Core\Installer\ClassMapGenerator;
use Xentral\Core\Installer\Installer;
use Xentral\Core\Installer\InstallerCacheConfig;
use Xentral\Core\Installer\InstallerCacheWriter;
use Xentral\Core\Installer\Psr4ClassNameResolver;
use Xentral\Core\LegacyConfig\ConfigLoader;
use Xentral\Core\LegacyConfig\Exception\MultiDbConfigNotFoundException;

class Application extends BaseApplication implements ContainerInterface
{
    public function __construct($basePath = null)
    {
        parent::__construct($basePath);

        try {
            $legacyConfig = ConfigLoader::load();
        } catch (MultiDbConfigNotFoundException $exception) {
            setcookie('DBSELECTED', '', time() - 86400);
            throw $exception;
        }
        $this->instance(\Config::class, $legacyConfig);

        $cacheConfig = new InstallerCacheConfig($legacyConfig->WFuserdata . '/tmp/' . $legacyConfig->WFdbname);
        $serviceCacheFile = $cacheConfig->getServiceCacheFile();
        $factoryServiceMap = @include $serviceCacheFile;

        if (!is_file($serviceCacheFile)) {

            // Installer ausführen wenn ServiceMap nicht vorhanden ist
            $resolver = new Psr4ClassNameResolver();
            $resolver->addNamespace('Xentral\\', dirname(__DIR__, 2) . '/classes');

            $generator = new ClassMapGenerator($resolver, dirname(__DIR__, 2));
            $installer = new Installer($generator, $resolver);
            $writer = new InstallerCacheWriter($cacheConfig, $installer);

            $writer->writeServiceCache();
            $writer->writeJavascriptCache();

            // Erzeugte ServiceMap einbinden
            $factoryServiceMap = @include $serviceCacheFile;
            if ($factoryServiceMap === false) {
                throw new RuntimeException(sprintf(
                    'Cache-Datei "%s" konnte nicht erzeugt werden. Vermutlich fehlen Schreibrechte in %s',
                    $serviceCacheFile,
                    $cacheConfig->getUserDataTempDir()
                ));
            }
        }

        $this->addFactories($factoryServiceMap);
    }

    /**
     * @param string $name
     *
     * @throws ServiceNotFoundException
     *
     * @return object
     */
    public function get($name)
    {
        try {
            $instance = parent::get($name);

            // Injects the Logger via setLogger() if the class of $instance uses the LoggerAwareTrait.
            if (
                $this->has('Logger')
                && in_array(LoggerAwareTrait::class, class_uses($instance), true)
            ) {
                /** @var LoggerAwareTrait $instance */
                $instance->setLogger($this->get('Logger'));
            }

            return $instance;
        } catch (EntryNotFoundException $e) {
            throw new ServiceNotFoundException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function addFactories(array $factoryServiceMap): void
    {
        foreach ($factoryServiceMap as $name => $callable) {
            $this->addFactory($name, $callable);
        }
    }

    public function addFactory(string $name, callable $callable): void
    {
        $this->singleton(
            $name,
            function ($container) use ($callable) {
                return call_user_func($callable, $container);
            }
        );
    }

    /**
     * @return string
     */
    public function publicPath()
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'www';
    }

    public static function getVersion(): string
    {
        $versionFile = base_path() . '/version.php';

        include $versionFile;

        return $version_revision;
    }
}
