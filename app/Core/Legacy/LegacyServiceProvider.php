<?php

namespace App\Core\Legacy;

use App\Core\Exceptions\Handler;
use Config;
use erpooSystem;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Factory;

class LegacyServiceProvider extends ServiceProvider
{
    /**
     * @param Config                   $legacyConfig
     * @param Repository               $config
     * @param ExceptionHandler|Handler $exceptionHandler
     * @param Factory                  $view
     */
    public function boot(Config $legacyConfig, Repository $config, ExceptionHandler $exceptionHandler, Factory $view): void
    {
        $exceptionHandler->register();
        $legacyApp = new erpooSystem($legacyConfig);
        $this->app->instance(erpooSystem::class, $legacyApp);

        $config->set('database.connections.mysql.host', $legacyConfig->WFdbhost);
        $config->set('database.connections.mysql.database', $legacyConfig->WFdbname);
        $config->set('database.connections.mysql.username', $legacyConfig->WFdbuser);
        $config->set('database.connections.mysql.port', $legacyConfig->WFdbport);
        $config->set('database.connections.mysql.password', $legacyConfig->WFdbpass);

        $view->share('legacyConfig', $legacyConfig);
        $view->share('theme', 'new');
        $view->share('popupWidth', '1200');
        $view->share('popupHeight', '800');
    }
}
