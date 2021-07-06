<?php

namespace Xentral\Components\Database;

use Xentral\Components\Database\Adapter\MysqliAdapter;
use Xentral\Components\Database\Exception\ConfigException;
use Xentral\Components\Database\Profiler\Profiler;
use Xentral\Components\Database\SqlQuery\QueryFactory;
use Xentral\Components\Logger\Context\ContextHelper;
use Xentral\Components\Logger\MemoryLogger;
use Xentral\Core\DependencyInjection\ContainerInterface;
use Xentral\Core\LegacyConfig\ConfigLoader;

final class Bootstrap
{
    public static function registerServices(): array
    {
        return [
            'Database'                     => 'onInitDatabase',
            'ReadOnlyDatabase'             => 'onInitReadOnlyDatabase',
            'DatabaseProfiler'             => 'onGetDatabaseProfiler',
            'MysqliAdapter'                => 'onInitMysqliAdapter',
            'MysqliReadOnlyAdapter'        => 'onInitMysqliReadOnlyAdapter',
            'QueryFactory'                 => 'onInitQueryFactory',
            'ReadOnlyDbQueryKiller'        => 'onInitReadOnlyDbQueryKiller',
            'DatabaseProcessListService'   => 'onInitDatabaseProcessListService',
            'DatabaseProcessKillService'   => 'onInitDatabaseProcessKillService',
            'ReadOnlyDataBaseCheckService' => 'onInitReadOnlyDataBaseCheckService',
        ];
    }

    public static function onInitDatabase(ContainerInterface $container): Database
    {
        return new Database($container->get('MysqliAdapter'), $container->get('QueryFactory'));
    }

    public static function onInitReadOnlyDatabase(ContainerInterface $container): Database
    {
        $systemConfigModule = $container->get('SystemConfigModule');
        $isReadonlyActive = $systemConfigModule->tryGetValue('database', 'readonly_active', '0') === '1';
        if (!$isReadonlyActive) {
            return new Database($container->get('MysqliAdapter'), $container->get('QueryFactory'));
        }

        return new Database($container->get('MysqliReadOnlyAdapter'), $container->get('QueryFactory'));
    }

    public static function onInitQueryFactory(): QueryFactory
    {
        return new QueryFactory('mysql');
    }

    public static function onGetDatabaseProfiler(ContainerInterface $container): Profiler
    {
        $request = $container->get('Request');

        return new Profiler(new MemoryLogger(new ContextHelper($request)));
    }

    public static function onInitMysqliAdapter(ContainerInterface $container): MysqliAdapter
    {
        $config = self::getDatabaseConfig($container);

        // Profiler aktivieren
        // Kann mit $container->get('DatabaseProfiler')->getContexts() abgefragt werden
        $profiler = $container->get('DatabaseProfiler');
        if (defined('DEVELOPMENT_MODE') && DEVELOPMENT_MODE === true) {
            $profiler->setActive(true);
        }

        return new MysqliAdapter($config, $profiler);
    }

    public static function onInitMysqliReadOnlyAdapter(ContainerInterface $container): MysqliAdapter
    {
        $config = self::getReadOnlyDatabaseConfig($container);
        // Profiler aktivieren
        // Kann mit $container->get('DatabaseProfiler')->getContexts() abgefragt werden
        $profiler = $container->get('DatabaseProfiler');
        if (defined('DEVELOPMENT_MODE') && DEVELOPMENT_MODE === true) {
            $profiler->setActive(true);
        }

        return new MysqliAdapter($config, $profiler);
    }

    public static function onInitReadOnlyDbQueryKiller(ContainerInterface $container): ReadOnlyDbQueryKiller
    {
        $app = $container->get('LegacyApplication');
        $databaseConfig = self::getDatabaseConfig($container);

        return new ReadOnlyDbQueryKiller(
            $container->get('DatabaseProcessListService'),
            $container->get('DatabaseProcessKillService'),
            $databaseConfig->getHostname(),
            $databaseConfig->getDatabase(),
            $databaseConfig->getUsername(),
            self::getReadOnlyDatabaseConfig($container)->getUsername(),
            (int)$app->erp->Firmendaten('read_only_kill')
        );
    }

    public static function onInitDatabaseProcessListService(ContainerInterface $container): DatabaseProcessListService
    {
        return new DatabaseProcessListService($container->get('Database'));
    }

    public static function onInitDatabaseProcessKillService(ContainerInterface $container): DatabaseProcessKillService
    {
        return new DatabaseProcessKillService($container->get('Database'));
    }

    public static function onInitReadOnlyDataBaseCheckService(ContainerInterface $container): ReadOnlyDataBaseCheckService
    {
        $dbConfig = self::getDatabaseConfig($container);
        $readOnlyDbConfig = self::getReadOnlyDatabaseConfig($container);

        return new ReadOnlyDataBaseCheckService(
            'konfiguration', 'name', 'wert', 'string', 'string', $container->get('Database'),
            $dbConfig->getUsername() === $readOnlyDbConfig->getUsername() ? null :
                new Database($container->get('MysqliReadOnlyAdapter'), $container->get('QueryFactory'))
        );
    }

    private static function getDatabaseConfig(ContainerInterface $container): DatabaseConfig
    {
        $conf = ConfigLoader::load();

        $dbHost = property_exists($conf, 'WFdbhost') ? $conf->WFdbhost : 'localhost';
        $dbPort = property_exists($conf, 'WFdbport') ? $conf->WFdbport : 3306;
        $dbName = property_exists($conf, 'WFdbname') ? $conf->WFdbname : null;
        $dbUser = property_exists($conf, 'WFdbuser') ? $conf->WFdbuser : null;
        $dbPass = property_exists($conf, 'WFdbpass') ? $conf->WFdbpass : null;

        if (empty($dbName)) {
            throw new ConfigException('Could not connect to database. Database name is missing or empty.');
        }
        if (empty($dbUser)) {
            throw new ConfigException('Could not connect to database. Database user is missing or empty.');
        }
        if (empty($dbPass)) {
            throw new ConfigException('Could not connect to database. Database password is missing or empty.');
        }

        $startupQueries = [
            "SET NAMES 'utf8', " .
            "CHARACTER SET 'utf8', " .
            "lc_time_names = 'de_DE', " .
            "SESSION sql_mode = '', " .
            "SESSION sql_big_selects = 1;",
        ];

        return new DatabaseConfig($dbHost, $dbUser, $dbPass, $dbName, 'utf8', $dbPort, $startupQueries);
    }

    private static function getReadOnlyDatabaseConfig(ContainerInterface $container): DatabaseConfig
    {
        $conf = ConfigLoader::load();
        if (!property_exists($conf, 'WFdbreadonlyuser') && !property_exists($conf, 'WFdbreadonlypass')) {
            return self::getDatabaseConfig($container);
        }
        $dbHost = property_exists($conf, 'WFdbhost') ? $conf->WFdbhost : 'localhost';
        $dbPort = property_exists($conf, 'WFdbport') ? $conf->WFdbport : 3306;
        $dbName = property_exists($conf, 'WFdbname') ? $conf->WFdbname : null;
        $dbUser = $conf->WFdbreadonlyuser;
        $dbPass = $conf->WFdbreadonlypass;

        if (empty($dbName)) {
            throw new ConfigException('Could not connect to database. Database name is missing or empty.');
        }
        if (empty($dbUser)) {
            throw new ConfigException('Could not connect to database. Database user is missing or empty.');
        }
        if (empty($dbPass)) {
            throw new ConfigException('Could not connect to database. Database password is missing or empty.');
        }

        $startupQueries = [
            "SET NAMES 'utf8', " .
            "CHARACTER SET 'utf8', " .
            "lc_time_names = 'de_DE', " .
            "SESSION sql_mode = '', " .
            "SESSION sql_big_selects = 1;",
        ];

        return new DatabaseConfig($dbHost, $dbUser, $dbPass, $dbName, 'utf8', $dbPort, $startupQueries);
    }
}
