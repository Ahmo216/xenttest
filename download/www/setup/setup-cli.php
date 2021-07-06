<?php

$userdata ="";
$dbhost="";
$dbname="";
$dbuser="";
$dbpass="";

$longopts  = array(
    "dbname:",     // Required value
    "dbuser:",     // Required value
    "dbpass:",     // Required value
    "dbhost::",     // Optional value
    "userdata::",    // Optional value
    "dbreadonlyuser::",// Optional value
    "dbreadonlypass::",// Optional value
);

$options = getopt("n:u:p:h::u::rou::rop::",$longopts);

echo "\r\n";

$parameter_error=false;

if(!isset($options['dbname'])) { echo "--dbname ist ein Pflichtangabe\r\n"; $parameter_error=true; }
if(!isset($options['dbuser'])) { echo "--dbuser ist ein Pflichtangabe\r\n"; $parameter_error=true; }
if(!isset($options['dbpass'])) { echo "--dbpass ist ein Pflichtangabe\r\n";$parameter_error=true; }

echo "\r\n";


$wawision_pfad = str_replace('www/setup/setup-cli.php','',$_SERVER['PHP_SELF']);
$wawision_pfad = rtrim($wawision_pfad,'/');


$dbname = $options['dbname'];
$dbuser = $options['dbuser'];
$dbpass = $options['dbpass'];

if(!isset($options['dbhost'])) $dbhost = "localhost";  else $dbhost=$options['dbhost'];
if(!isset($options['userdata'])) $userdata = $wawision_pfad."/userdata"; else $userdata = $options['userdata'];

$dbReadOnlyUser = isset($options['dbreadonlyuser']) ? $options['dbreadonlyuser'] : null;
$dbReadOnlyPassword = isset($options['dbreadonlypass']) ? $options['dbreadonlypass'] : null;

// check userdata

if(!is_dir($userdata)) { 
  echo "--userdata Pfad existiert nicht! Bitte gueltigen angeben!\r\n";
  $parameter_error=true;
}

// check mysql

system("mysql  --default-character-set=utf8 -u$dbuser -p$dbpass -h$dbhost -D$dbname -e 'quit' ",$retval);

if($retval != 0){
  echo "Datenbank Verbindung fehlgeschlagen!\r\n";
  $parameter_error=true;
}

if($parameter_error) {
  exit;
}
if($dbReadOnlyUser !== null && $dbReadOnlyPassword !== null) {
  system("mysql  --default-character-set=utf8 -u$dbReadOnlyUser -p$dbReadOnlyPassword -h$dbhost -D$dbname -e 'quit' ",$retval);

  if($retval != 0){
    echo "Datenbank Verbindung fehlgeschlagen!\r\n";
    $parameter_error=true;
  }

  if($parameter_error) {
    exit;
  }
}


system("cp ".$wawision_pfad."/conf/main.conf.php.tpl ".$wawision_pfad."/conf/main.conf.php");

// user.inc.php.tpl auslesen, veraendern und unbennenen
$content = file_get_contents($wawision_pfad."/conf/user.inc.php.tpl");
$content = str_replace("WFdbhost='localhost'", "WFdbhost='$dbhost'", $content);
$content = str_replace("WFdbuser='root'", "WFdbuser='$dbuser'", $content);
$content = str_replace("WFdbpass='DBPASS'", "WFdbpass='$dbpass'", $content);
$content = str_replace("WFdbname='wawision'", "WFdbname='$dbname'", $content);
$content = str_replace("WFuserdata='/var/www/wawision/userdata/'","WFuserdata='$userdata'", $content);
if($dbReadOnlyUser !== null && $dbReadOnlyPassword !== null) {
  $dbReadOnlyUser = str_replace('\'', '\\\'', str_replace('\\', '\\\\', $dbReadOnlyUser));
  $dbReadOnlyPassword = str_replace('\'', '\\\'', str_replace('\\', '\\\\', $dbReadOnlyPassword));
  $content = str_replace("WFdbreadonlyuser=null", "WFdbreadonlyuser='{$dbReadOnlyUser}'", $content);
  $content = str_replace("WFdbreadonlypass=null", "WFdbreadonlypass='{$dbReadOnlyPassword}'", $content);
}
$file = fopen($wawision_pfad."/conf/user.inc.php","w");
fwrite($file, $content);
fclose($file);

system("mysql  --default-character-set=utf8 -u$dbuser -p$dbpass -h$dbhost -D$dbname < ".$wawision_pfad."/database/struktur.sql");
//system("mysql  --default-character-set=utf8 -u$dbuser -p$dbpass -h$dbhost -D$dbname < ".$wawision_pfad."/database/beispiel.sql");

//system("php ".$wawision_pfad."/upgradedbonly.php");

