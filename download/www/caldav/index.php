<?php

use Xentral\Components\Database\Database;
use Xentral\Core\DependencyInjection\ContainerInterface;

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Europe/Berlin');

/** @var ContainerInterface $container */
$container = include(__DIR__ . '/../../classes/bootstrap.php');

const DBNAME = 'wawision_19.3';
const DBHOST = 'serv';
const DBUSER = 'user';
const DBPASS = 'pass';

// If you want to run the SabreDAV server in a custom location (using mod_rewrite for instance)
// You can override the baseUri here.
// $baseUri = '/dakhno/caldav/no.php/';

/** @var Database $db */
$db = $container->get('Database');

// Backends
$authBackend = $container->get('WawisionAuthBackend');
$calendarBackend = $container->get('WawisionCalendarBackend');
$principalBackend = $container->get('WawisionPrincipalBackend');

// Directory structure
$tree = [
  new Sabre\CalDAV\Principal\Collection($principalBackend),
  new Sabre\CalDAV\CalendarRoot($principalBackend, $calendarBackend),
];

$server = new Sabre\DAV\Server($tree);

$baseUri = $db->fetchValue('SELECT k.wert FROM konfiguration AS k WHERE k.name = "caldav_baseuri"');
if(!empty($baseUri)){
  $server->setBaseUri($baseUri);
}

/* Server Plugins */
$authPlugin = new Sabre\DAV\Auth\Plugin($authBackend);
$server->addPlugin($authPlugin);

$aclPlugin = new Sabre\DAVACL\Plugin();
$server->addPlugin($aclPlugin);
/* CalDAV support */
$caldavPlugin = new Sabre\CalDAV\Plugin();
$server->addPlugin($caldavPlugin);

/* Calendar subscription support */
$server->addPlugin(
  new Sabre\CalDAV\Subscriptions\Plugin()
);

/* Calendar scheduling support */
$server->addPlugin(
  new Sabre\CalDAV\Schedule\Plugin()
);
/* WebDAV-Sync plugin */
$server->addPlugin(new Sabre\DAV\Sync\Plugin());

/* CalDAV Sharing support */
$server->addPlugin(new Sabre\DAV\Sharing\Plugin());
$server->addPlugin(new Sabre\CalDAV\SharingPlugin());

// Support for html frontend
$browser = new Sabre\DAV\Browser\Plugin();
$server->addPlugin($browser);

$server->exec();

function execute($targetFile, $output)
{
  $data = sprintf(
    "%s %s %s\n\nHTTP headers:\n",
    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI'],
    $_SERVER['SERVER_PROTOCOL']
  );
  foreach (getHeaderList() as $name => $value) {
    $data .= $name . ': ' . $value . "\n";
  }
  $data .= "\nRequest body:\n";
  file_put_contents(
    $targetFile,
    $data . file_get_contents('php://input') . "\n\n{$output}\n\n\n",
    FILE_APPEND
  );
}

function getHeaderList()
{
  $headerList = [];
  foreach ($_SERVER as $name => $value) {
    if(preg_match('/^HTTP_/', $name)){
      // convert HTTP_HEADER_NAME to Header-Name
      $name = strtr(substr($name, 5), '_', ' ');
      $name = ucwords(strtolower($name));
      $name = strtr($name, ' ', '-');
      // add to list
      $headerList[$name] = $value;
    }
  }

  return $headerList;
}
