<?php

use Xentral\Components\Database\DatabaseConfig;
use Xentral\Core\Installer\ClassMapGenerator;
use Xentral\Core\Installer\Installer;
use Xentral\Core\Installer\InstallerCacheConfig;
use Xentral\Core\Installer\InstallerCacheWriter;
use Xentral\Core\Installer\Psr4ClassNameResolver;
use Xentral\Core\Installer\TableSchemaEnsurer;


// Nur einfache Fehler melden
error_reporting(E_ERROR | E_COMPILE_ERROR | E_CORE_ERROR | E_RECOVERABLE_ERROR | E_USER_ERROR | E_PARSE);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/xentral_autoloader.php';
/** @var \App\Core\Application $app */
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$config = new Config();

// Delete ServiceMap-CacheFile
$installConf = new InstallerCacheConfig($config->WFuserdata . '/tmp/' . $config->WFdbname);
$serviceCacheFile = $installConf->getServiceCacheFile();
@unlink($serviceCacheFile);

$app = new ApplicationCore();

$DEBUG = 0;

$app->Conf = $config;


echo "STARTE   Installer\r\n";

$resolver = new Psr4ClassNameResolver();
$resolver->addNamespace('Xentral\\', __DIR__ . '/classes');

$generator = new ClassMapGenerator($resolver, __DIR__);
$installer = new Installer($generator, $resolver);
$writer = new InstallerCacheWriter($installConf, $installer);

echo "SCHREIBE ServiceMap\r\n";
$writer->writeServiceCache();

echo "SCHREIBE JavascriptMap\r\n";
$writer->writeJavascriptCache();


$app->DB = new DB($app->Conf->WFdbhost, $app->Conf->WFdbname, $app->Conf->WFdbuser, $app->Conf->WFdbpass, $app, $app->Conf->WFdbport);

$erp = class_exists('erpAPICustom')
    ? new erpAPICustom($app)
    : new erpAPI($app);

echo "STARTE   DB Upgrade\r\n";
$erp->UpgradeDatabase();
echo "ENDE     DB Upgrade\r\n\r\n";


$dbConfig = new DatabaseConfig(
    $app->Conf->WFdbhost,
    $app->Conf->WFdbuser,
    $app->Conf->WFdbpass,
    $app->Conf->WFdbname,
    null,
    $app->Conf->WFdbport
);
$tableSchemaCreator = new TableSchemaEnsurer(
    $app->Container->get('SchemaCreator'),
    $installConf,
    $dbConfig
);

echo "ERZEUGE  Table Schemas\r\n";
$schemaCollection = $installer->getTableSchemas();
$tableSchemaCreator->ensureSchemas($schemaCollection);

echo "ENDE     Installer\r\n";
