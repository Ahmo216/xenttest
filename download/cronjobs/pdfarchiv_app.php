<?php
if(!class_exists('ApplicationCore') && is_file(dirname(__DIR__).'/xentral_autoloader.php')) {
  include_once dirname(__DIR__).'/xentral_autoloader.php';
}
set_time_limit(36000);


error_reporting(E_ERROR | E_WARNING | E_PARSE);
if(!class_exists('Conf')){
  include_once(dirname(__DIR__) . "/conf/main.conf.php");
}
if(!class_exists('DB')){
  include_once(dirname(__DIR__) . "/phpwf/plugins/class.mysql.php");
}
if(!class_exists('Secure')){
  include_once(dirname(__DIR__) . "/phpwf/plugins/class.secure.php");
}

if(!class_exists('FormHandler')){
  include_once(dirname(__DIR__) . "/phpwf/plugins/class.formhandler.php");
}

if(!class_exists('User')){
  include_once(dirname(__DIR__) . "/phpwf/plugins/class.user.php");
}

if(!class_exists('FPDFWAWISION')){
  if(file_exists(dirname(__DIR__).'/conf/user_defined.php')){
    include_once dirname(__DIR__) . '/conf/user_defined.php';
  }
  if(defined('USEFPDF3') && USEFPDF3 && file_exists(dirname(__DIR__) . '/www/lib/pdf/fpdf_3.php')){
    require_once dirname(__DIR__) . '/www/lib/pdf/fpdf_3.php';
  }else if(defined('USEFPDF2') && USEFPDF2 && file_exists(dirname(__DIR__) . '/www/lib/pdf/fpdf_2.php')){
    require_once dirname(__DIR__) . '/www/lib/pdf/fpdf_2.php';
  }else{
    require_once dirname(__DIR__) . '/www/lib/pdf/fpdf.php';
  }
}
if(!class_exists('IMAP')){
  include_once(dirname(__DIR__) . "/www/lib/imap.inc.php");
}
if(!class_exists('PDF_EPS')){
  include_once(dirname(__DIR__) . "/www/lib/pdf/fpdf_final.php");
}
if(!class_exists('SuperFPDF')){
  include_once(dirname(__DIR__) . "/www/lib/dokumente/class.superfpdf.php");
}
if(!class_exists('erpAPI')){
  include_once(dirname(__DIR__) . "/www/lib/class.erpapi.php");
}
if(file_exists(dirname(__DIR__)."/www/lib/class.erpapi_custom.php") &&
  !class_exists('erpAPICustom')){
  include_once(dirname(__DIR__) . "/www/lib/class.erpapi_custom.php");
}
if(!class_exists('Remote')){
  include_once(dirname(__DIR__) . "/www/lib/class.remote.php");
}

if(!class_exists('RemoteCustom') &&
  file_exists(dirname(__DIR__)."/www/lib/class.remote_custom.php"))
  include_once(dirname(__DIR__)."/www/lib/class.remote_custom.php");
if(!class_exists('HttpClient')){
  include_once(dirname(__DIR__) . "/www/lib/class.httpclient.php");
}

if(!class_exists('PHPMailer')){
  include_once(dirname(__DIR__) . "/www/plugins/phpmailer/class.phpmailer.php");
}
if(!class_exists('SMTP')){
  include_once(dirname(__DIR__) . "/www/plugins/phpmailer/class.smtp.php");
}
if(!class_exists('image'))
{
  include_once (dirname(__DIR__)."/www/lib/class.image.php");
}

$classes = [
  'briefpapier', 'lieferschein', 'auftrag', 'anfrage', 'gutschrift',
  'bestellung', 'rechnung', 'mahnwesen', 'angebot', 'retoure'
];
foreach($classes as $class)
{
  if(file_exists(dirname(__DIR__)."/www/lib/dokumente/class.".$class."_custom.php"))
  {
    include_once(dirname(__DIR__)."/www/lib/dokumente/class.".$class."_custom.php");
  }elseif(file_exists(dirname(__DIR__)."/www/lib/dokumente/class.".$class.".php"))
  {
    include_once(dirname(__DIR__)."/www/lib/dokumente/class.".$class.".php");
  }
}
if(!defined('FPDF_FONTPATH'))
{
  define('FPDF_FONTPATH',dirname(__DIR__).'/www/lib/pdf/font/');
}
/*
include_once(dirname(__FILE__)."/../www/lib/dokumente/class.briefpapier.php");
include_once(dirname(__FILE__)."/../www/lib/dokumente/class.lieferschein.php");
include_once(dirname(__FILE__)."/../www/lib/dokumente/class.auftrag.php");
include_once(dirname(__FILE__)."/../www/lib/dokumente/class.angebot.php");
if(file_exists(dirname(__FILE__)."/../www/lib/dokumente/class.anfrage.php"))include_once(dirname(__FILE__)."/../www/lib/dokumente/class.anfrage.php");
include_once(dirname(__FILE__)."/../www/lib/dokumente/class.gutschrift.php");
include_once(dirname(__FILE__)."/../www/lib/dokumente/class.bestellung.php");
include_once(dirname(__FILE__)."/../www/lib/dokumente/class.rechnung.php");
include_once(dirname(__FILE__)."/../www/lib/dokumente/class.mahnwesen.php");*/
if(!class_exists('WawiString')){
  include_once(dirname(__DIR__) . "/phpwf/plugins/class.string.php");
}
if(!class_exists('app_t2'))
{
  class app_t2 extends ApplicationCore {
    var $DB;
    var $erp;
    var $User;
    var $mail;
    var $remote;
    var $Secure;
    public function GetLandLang($isocode)
    {
      $flipped = array_flip($this->GetLaender());
      if(isset($flipped[$isocode])){
        $land = $flipped[$isocode];
      }
      else
      {
        $land = 'unkown';
      }
      return $land;
    }

    public function GetLaender()
    {
      $laender = array(
        'Afghanistan'  => 'AF',
        '&Auml;gypten'  => 'EG',
        'Albanien'  => 'AL',
        'Algerien'  => 'DZ',
        'Andorra'  => 'AD',
        'Angola'  => 'AO',
        'Anguilla'  => 'AI',
        'Antarktis'  => 'AQ',
        'Antigua und Barbuda'  => 'AG',
        '&Auml;quatorial Guinea'  => 'GQ',
        'Argentinien'  => 'AR',
        'Armenien'  => 'AM',
        'Aruba'  => 'AW',
        'Aserbaidschan'  => 'AZ',
        '&Auml;thiopien'  => 'ET',
        'Australien'  => 'AU',
        'Bahamas'  => 'BS',
        'Bahrain'  => 'BH',
        'Bangladesh'  => 'BD',
        'Barbados'  => 'BB',
        'Belgien'  => 'BE',
        'Belize'  => 'BZ',
        'Benin'  => 'BJ',
        'Bermudas'  => 'BM',
        'Bhutan'  => 'BT',
        'Birma'  => 'MM',
        'Bolivien'  => 'BO',
        'Bosnien-Herzegowina'  => 'BA',
        'Botswana'  => 'BW',
        'Bouvet Inseln'  => 'BV',
        'Brasilien'  => 'BR',
        'Britisch-Indischer Ozean'  => 'IO',
        'Brunei'  => 'BN',
        'Bulgarien'  => 'BG',
        'Burkina Faso'  => 'BF',
        'Burundi'  => 'BI',
        'Chile'  => 'CL',
        'China'  => 'CN',
        'Christmas Island'  => 'CX',
        'Cook Inseln'  => 'CK',
        'Costa Rica'  => 'CR',
        'D&auml;nemark'  => 'DK',
        'Deutschland'  => 'DE',
        'Djibuti'  => 'DJ',
        'Dominika'  => 'DM',
        'Dominikanische Republik'  => 'DO',
        'Ecuador'  => 'EC',
        'El Salvador'  => 'SV',
        'Elfenbeink&uuml;ste'  => 'CI',
        'Eritrea'  => 'ER',
        'Estland'  => 'EE',
        'Falkland Inseln'  => 'FK',
        'F&auml;r&ouml;er Inseln'  => 'FO',
        'Fidschi'  => 'FJ',
        'Finnland'  => 'FI',
        'Frankreich'  => 'FR',
        'Franz&ouml;sisch Guyana'  => 'GF',
        'Franz&ouml;sisch Polynesien'  => 'PF',
        'Franz&ouml;sisches S&uuml;d-Territorium'  => 'TF',
        'Gabun'  => 'GA',
        'Gambia'  => 'GM',
        'Georgien'  => 'GE',
        'Ghana'  => 'GH',
        'Gibraltar'  => 'GI',
        'Grenada'  => 'GD',
        'Griechenland'  => 'GR',
        'Gr&ouml;nland'  => 'GL',
        'Großbritannien'  => 'UK',
        'Großbritannien (UK)'  => 'GB',
        'Guadeloupe'  => 'GP',
        'Guam'  => 'GU',
        'Guatemala'  => 'GT',
        'Guinea'  => 'GN',
        'Guinea Bissau'  => 'GW',
        'Guyana'  => 'GY',
        'Haiti'  => 'HT',
        'Heard und McDonald Islands'  => 'HM',
        'Honduras'  => 'HN',
        'Hong Kong'  => 'HK',
        'Indien'  => 'IN',
        'Indonesien'  => 'ID',
        'Irak'  => 'IQ',
        'Iran'  => 'IR',
        'Irland'  => 'IE',
        'Island'  => 'IS',
        'Israel'  => 'IL',
        'Italien'  => 'IT',
        'Jamaika'  => 'JM',
        'Japan'  => 'JP',
        'Jemen'  => 'YE',
        'Jordanien'  => 'JO',
        'Jugoslawien'  => 'YU',
        'Kaiman Inseln'  => 'KY',
        'Kambodscha'  => 'KH',
        'Kamerun'  => 'CM',
        'Kanada'  => 'CA',
        'Kap Verde'  => 'CV',
        'Kasachstan'  => 'KZ',
        'Kenia'  => 'KE',
        'Kirgisistan'  => 'KG',
        'Kiribati'  => 'KI',
        'Kokosinseln'  => 'CC',
        'Kolumbien'  => 'CO',
        'Komoren'  => 'KM',
        'Kongo'  => 'CG',
        'Kongo, Demokratische Republik'  => 'CD',
        'Kosovo'  => 'KO',
        'Kroatien'  => 'HR',
        'Kuba'  => 'CU',
        'Kuwait'  => 'KW',
        'Laos'  => 'LA',
        'Lesotho'  => 'LS',
        'Lettland'  => 'LV',
        'Libanon'  => 'LB',
        'Liberia'  => 'LR',
        'Libyen'  => 'LY',
        'Liechtenstein'  => 'LI',
        'Litauen'  => 'LT',
        'Luxemburg'  => 'LU',
        'Macao'  => 'MO',
        'Madagaskar'  => 'MG',
        'Malawi'  => 'MW',
        'Malaysia'  => 'MY',
        'Malediven'  => 'MV',
        'Mali'  => 'ML',
        'Malta'  => 'MT',
        'Marianen'  => 'MP',
        'Marokko'  => 'MA',
        'Marshall Inseln'  => 'MH',
        'Martinique'  => 'MQ',
        'Mauretanien'  => 'MR',
        'Mauritius'  => 'MU',
        'Mayotte'  => 'YT',
        'Mazedonien'  => 'MK',
        'Mexiko'  => 'MX',
        'Mikronesien'  => 'FM',
        'Mocambique'  => 'MZ',
        'Moldavien'  => 'MD',
        'Monaco'  => 'MC',
        'Mongolei'  => 'MN',
        'Montenegro'  => 'ME',
        'Montserrat'  => 'MS',
        'Namibia'  => 'NA',
        'Nauru'  => 'NR',
        'Nepal'  => 'NP',
        'Neukaledonien'  => 'NC',
        'Neuseeland'  => 'NZ',
        'Nicaragua'  => 'NI',
        'Niederlande'  => 'NL',
        'Niederl&auml;ndische Antillen'  => 'AN',
        'Niger'  => 'NE',
        'Nigeria'  => 'NG',
        'Niue'  => 'NU',
        'Nord Korea'  => 'KP',
        'Norfolk Inseln'  => 'NF',
        'Norwegen'  => 'NO',
        'Oman'  => 'OM',
        '&Ouml;sterreich'  => 'AT',
        'Pakistan'  => 'PK',
        'Pal&auml;stina'  => 'PS',
        'Palau'  => 'PW',
        'Panama'  => 'PA',
        'Papua Neuguinea'  => 'PG',
        'Paraguay'  => 'PY',
        'Peru'  => 'PE',
        'Philippinen'  => 'PH',
        'Pitcairn'  => 'PN',
        'Polen'  => 'PL',
        'Portugal'  => 'PT',
        'Puerto Rico'  => 'PR',
        'Qatar'  => 'QA',
        'Reunion'  => 'RE',
        'Ruanda'  => 'RW',
        'Rum&auml;nien'  => 'RO',
        'Ru&szlig;land'  => 'RU',
        'Saint Lucia'  => 'LC',
        'Sambia'  => 'ZM',
        'Samoa'  => 'AS',
        'Samoa'  => 'WS',
        'San Marino'  => 'SM',
        'Sao Tome'  => 'ST',
        'Saudi Arabien'  => 'SA',
        'Schweden'  => 'SE',
        'Schweiz'  => 'CH',
        'Senegal'  => 'SN',
        'Serbien'  => 'RS',
        'Seychellen'  => 'SC',
        'Sierra Leone'  => 'SL',
        'Singapur'  => 'SG',
        'Slowakei -slowakische Republik-'  => 'SK',
        'Slowenien'  => 'SI',
        'Solomon Inseln'  => 'SB',
        'Somalia'  => 'SO',
        'South Georgia, South Sandwich Isl.'  => 'GS',
        'Spanien'  => 'ES',
        'Sri Lanka'  => 'LK',
        'St. Helena'  => 'SH',
        'St. Kitts Nevis Anguilla'  => 'KN',
        'St. Pierre und Miquelon'  => 'PM',
        'St. Vincent'  => 'VC',
        'S&uuml;d Korea'  => 'KR',
        'S&uuml;dafrika'  => 'ZA',
        'Sudan'  => 'SD',
        'Surinam'  => 'SR',
        'Svalbard und Jan Mayen Islands'  => 'SJ',
        'Swasiland'  => 'SZ',
        'Syrien'  => 'SY',
        'Tadschikistan'  => 'TJ',
        'Taiwan'  => 'TW',
        'Tansania'  => 'TZ',
        'Thailand'  => 'TH',
        'Timor'  => 'TP',
        'Togo'  => 'TG',
        'Tokelau'  => 'TK',
        'Tonga'  => 'TO',
        'Trinidad Tobago'  => 'TT',
        'Tschad'  => 'TD',
        'Tschechische Republik'  => 'CZ',
        'Tunesien'  => 'TN',
        'T&uuml;rkei'  => 'TR',
        'Turkmenistan'  => 'TM',
        'Turks und Kaikos Inseln'  => 'TC',
        'Tuvalu'  => 'TV',
        'Uganda'  => 'UG',
        'Ukraine'  => 'UA',
        'Ungarn'  => 'HU',
        'Uruguay'  => 'UY',
        'Usbekistan'  => 'UZ',
        'Vanuatu'  => 'VU',
        'Vatikan'  => 'VA',
        'Venezuela'  => 'VE',
        'Vereinigte Arabische Emirate'  => 'AE',
        'Vereinigte Staaten von Amerika'  => 'US',
        'Vietnam'  => 'VN',
        'Virgin Island (Brit.)'  => 'VG',
        'Virgin Island (USA)'  => 'VI',
        'Wallis et Futuna'  => 'WF',
        'Wei&szlig;ru&szlig;land'  => 'BY',
        'Westsahara'  => 'EH',
        'Zentralafrikanische Republik'  => 'CF',
        'Zimbabwe'  => 'ZW',
        'Zypern'  => 'CY'
      );
      return $laender;
    }
  }
}
//ENDE


if(empty($app) || !class_exists('ApplicationCore') || !($app instanceof ApplicationCore)) {
  $app = new app_t2();
}
if(empty($app->Conf)){
  $conf = new Config();
  $app->Conf = $conf;
}
if(empty($app->DB) || empty($app->DB->connection)){
  $app->DB = new DB($app->Conf->WFdbhost, $app->Conf->WFdbname, $app->Conf->WFdbuser, $app->Conf->WFdbpass, null, $app->Conf->WFdbport);
}
if(empty($app->erp)){
  if(class_exists('erpAPICustom')){
    $erp = new erpAPICustom($app);
  }
  else{
    $erp = new erpAPI($app);
  }
  $app->erp = $erp;
}
  if(class_exists('RemoteCustom'))
  {
    $app->remote = new RemoteCustom($app);
  }elseif(class_exists('Remote'))
  {
    $app->remote = new Remote($app);
  }

  $app->String  = new WawiString();

  $app->Secure = new Secure($app);
  $app->User = new User($app);
  if(class_exists('FormHandler')){
    $app->FormHandler = new FormHandler($app);
  }
  if(class_exists('TemplateParser'))
  {
    $app->TemplateParser = new TemplateParser($app);
  }
  if(!defined('FPDF_FONTPATH'))
  {
    define('FPDF_FONTPATH',dirname(__DIR__)."/www/lib/pdf/font/");
  }

$pdf = new pdfarchiv_app($app);
$pdf->run();
class pdfarchiv_app
{
  /** @var ApplicationCore $app */
  var $app;

  /** @var string $folder */
  var $folder;

  protected $hasZipExtension = false;

  protected CONST MAX_PDFS_PER_FILE = 1000;

  /**
   * pdfarchiv_app constructor.
   *
   * @param ApplicationCore $app
   */
  public function __construct($app)
  {
    $this->app = $app;
    $this->folder = $this->app->Conf->WFuserdata."/pdfarchiv/".$this->app->Conf->WFdbname;
    if(!function_exists('exec')) {
      return;
    }

    exec('whereis zip', $output);
    if(empty($output)) {
      return;
    }

    $zipExtension = explode(':', $output[0]);
    $this->hasZipExtension = count($zipExtension) > 1 && !empty(trim($zipExtension[1]));
  }

  /**
   * @return bool
   */
  public function run()
  {
    if(!empty($this->app->Conf->WFuserdata))
    {
      if(!file_exists($this->app->Conf->WFuserdata.'/pdfarchiv'))
      {
        if(!@mkdir($this->app->Conf->WFuserdata.'/pdfarchiv') && !is_dir($this->app->Conf->WFuserdata.'/pdfarchiv'))
        {
          echo $this->app->Conf->WFuserdata."/pdfarchiv"."konnte nicht erstellt werden\r\n";
        }
      }
      if(!file_exists($this->folder))
      {
        if(!@mkdir($this->folder) && !is_dir($this->folder))
        {
          echo $this->folder."konnte nicht erstellt werden\r\n";
        }
      }
    }
    if(!file_exists($this->folder))
    {
      return false;
    }
    $this->app->DB->Update(
      "UPDATE `prozessstarter` 
      SET `mutexcounter` = `mutexcounter` + 1 
      WHERE `parameter` = 'pdfarchiv_app' AND `aktiv` = 1 AND mutex = 1"
    );
    if(
      (int)$this->app->DB->Select(
        "SELECT COUNT(`id`) 
        FROM `prozessstarter` 
        WHERE `parameter` = 'pdfarchiv_app' AND `aktiv` = 1 AND `mutex` = 1 
        LIMIT 1"
      ) > 0
    ) {
      return false;
    }
    do {
      $this->setMutex();
      $run = $this->run_next();
      if(method_exists($this->app->erp, 'canRunCronjob') && !$this->app->erp->canRunCronjob(['pdfarchiv_app'])) {
        return true;
      }
    }
    while($run);
    $this->setMutex(false);

    return true;
  }

  /**
   * @param $job
   * @param $tabellen
   * @param $jahrvon
   * @param $jahrbis
   * @param $monatvon
   * @param $monatbis
   *
   * @return bool
   */
  protected function archiveNoneArchived($job, $tabellen, $jahrvon, $jahrbis, $monatvon, $monatbis)
  {
    $this->app->DB->Update("UPDATE pdfarchiv_jobs SET status = 'Archiviere...' WHERE id = '".$job['id']."' LIMIT 1");
    foreach($tabellen as $table)
    {
      $check = $this->app->DB->Query("SELECT t.* FROM $table t LEFT JOIN pdfarchiv p ON p.table_id = t.id AND p.table_name = '$table' AND if(t.schreibschutz = 1, p.schreibschutz = 1,1) AND CHAR_LENGTH(p.belegnummer) > 2 AND p.belegnummer <> 'SAB' WHERE isnull(p.id) AND t.belegnr <> '' AND t.status <> 'angelegt' AND t.status <> 'angelegta' AND t.status <> 'a' 
          AND year(t.datum) >= '".$jahrvon."' AND year(t.datum) <= '".$jahrbis."' AND month(t.datum) >= '".$monatvon."' AND month(t.datum) <= '".$monatbis."'
          ");
      $centries = 0;
      while($row = $this->app->DB->Fetch_Assoc($check))
      {
        echo "$table ".$row['id']."\r\n";
        $centries++;
        if($centries % 10 === 0 && function_exists('gc_enabled') && function_exists('gc_collect_cycles') && gc_enabled())
        {
          $cgc = gc_collect_cycles();
          if($cgc > 0)
          {
            $this->app->erp->LogFile($cgc.' cycles collected');
          }
        }
        if(empty($row['schreibschutz']))
        {
          $this->app->DB->Update("UPDATE $table SET schreibschutz = 1 WHERE id = '".$row['id']."' LIMIT 1");
          $protkollfunktion = ucfirst($table.'Protokoll');
          if(method_exists($this->app->erp,$protkollfunktion))
          {
            $this->app->erp->$protkollfunktion($row['id'], 'PDF-Archiv-App: Schreibschutz gesetzt');
          }
        }

        $this->app->DB->Update("UPDATE prozessstarter SET mutex = 1 , mutexcounter = 0, letzteausfuerhung = now() WHERE parameter = 'pdfarchiv_app' AND aktiv = 1");
        if($this->app->DB->Select("SELECT abbrechen FROM pdfarchiv_jobs WHERE id = '".$job['id']."'"))
        {
          $this->app->DB->Update("UPDATE pdfarchiv_jobs SET status = 'abgebrochen' WHERE id = '".$job['id']."' LIMIT 1");
          return true;
        }
        $name = ucfirst($table).'PDFCustom';
        if(!class_exists($name))
        {
          $name = ucfirst($table).'PDF';
        }
        $nameget = 'Get'.ucfirst($table);
        $this->app->erp->BriefpapierHintergrunddisable = !$this->app->erp->BriefpapierHintergrunddisable;
        $this->app->erp->BriefpapierHintergrunddisable = !$this->app->erp->BriefpapierHintergrunddisable;
        $Brief = new $name($this->app, $row['projekt']);

        $Brief->$nameget($row['id']);
        $tmpfile = $Brief->displayTMP();
        $Brief->ArchiviereDocument();
        unlink($tmpfile);
        $this->app->erp->BriefpapierHintergrunddisable = !$this->app->erp->BriefpapierHintergrunddisable;
        $Brief = new $name($this->app, $row['projekt']);

        $Brief->$nameget($row['id']);
        $tmpfile = $Brief->displayTMP();
        $Brief->ArchiviereDocument();
        unlink($tmpfile);
      }
    }

    return false;
  }

  protected function archiveNew($job, $tabellen, $jahrvon, $jahrbis, $monatvon, $monatbis): bool
  {
    $this->app->DB->Update("UPDATE pdfarchiv_jobs SET status = 'Archiviere...' WHERE id = '".$job['id']."' LIMIT 1");
    $centries = 0;
    foreach($tabellen as $table)
    {
      $centries++;
      if($centries % 10 === 0 && function_exists('gc_enabled') && function_exists('gc_collect_cycles') && gc_enabled())
      {
        $cgc = gc_collect_cycles();
        if($cgc > 0)
        {
          $this->app->erp->LogFile($cgc.' cycles collected');
        }
      }
      $check = $this->app->DB->Query(
        "SELECT t.id,t.projekt,t.schreibschutz 
            FROM `$table` AS `t` 
            LEFT JOIN `pdfarchiv` AS `p` ON p.table_id = t.id AND p.table_name = '$table' AND CHAR_LENGTH(p.belegnummer) > 2 AND p.belegnummer <> 'SAB' 
            WHERE t.belegnr <> '' AND t.status <> 'angelegt' AND t.status <> 'angelegta' AND t.status <> 'a'  
          AND year(t.datum) >= '".$jahrvon."' AND year(t.datum) <= '".$jahrbis."' AND month(t.datum) >= '".$monatvon."' 
          AND month(t.datum) <= '".$monatbis."'
          GROUP BY t.id,t.projekt");
      while($row = $this->app->DB->Fetch_Assoc($check))
      {
        echo "$table ".$row['id']."\r\n";

        if(empty($row['schreibschutz']))
        {
          $this->app->DB->Update("UPDATE `$table` SET `schreibschutz` = 1 WHERE `id` = '{$row['id']}' LIMIT 1");
          $protkollfunktion = ucfirst($table.'Protokoll');
          if(method_exists($this->app->erp,$protkollfunktion))
          {
            $this->app->erp->$protkollfunktion($row['id'], 'PDF-Archiv-App: Schreibschutz gesetzt');
          }
        }

        $this->app->DB->Update("UPDATE prozessstarter SET mutex = 1 , mutexcounter = 0, letzteausfuerhung = now() WHERE parameter = 'pdfarchiv_app' AND aktiv = 1");
        if($this->app->DB->Select("SELECT abbrechen FROM pdfarchiv_jobs WHERE id = '".$job['id']."'"))
        {
          $this->app->DB->Update("UPDATE `pdfarchiv_jobs` SET `status` = 'abgebrochen' WHERE `id` = '{$job['id']}' LIMIT 1");
          return true;
        }

        if($table === 'rechnung')
        {
          $mahnwesen = $this->app->DB->Select("SELECT mahnwesen FROM rechnung WHERE id='".$row['id']."' LIMIT 1");
          if($mahnwesen && class_exists('MahnwesenPDF'))
          {
            $this->app->erp->BriefpapierHintergrunddisable = !$this->app->erp->BriefpapierHintergrunddisable;
            if(class_exists('MahnwesenCustomPDF'))
            {
              $Brief = new MahnwesenCustomPDF($this->app, $row['projekt']);
            }else{
              $Brief = new MahnwesenPDF($this->app, $row['projekt']);
            }
            $Brief->GetRechnung($row['id'], $mahnwesen);
            $tmpfile = $Brief->displayTMP();
            $Brief->ArchiviereDocument();
            unlink($tmpfile);
            $this->app->erp->BriefpapierHintergrunddisable = !$this->app->erp->BriefpapierHintergrunddisable;
            if(class_exists('MahnwesenCustomPDF'))
            {
              $Brief = new MahnwesenCustomPDF($this->app, $row['projekt']);
            }else{
              $Brief = new MahnwesenPDF($this->app, $row['projekt']);
            }
            $Brief->GetRechnung($row['id'], $mahnwesen);
            $tmpfile = $Brief->displayTMP();
            $Brief->ArchiviereDocument();
            unlink($tmpfile);
          }
        }

        $name = ucfirst($table).'PDFCustom';
        if(!class_exists($name))
        {
          $name = ucfirst($table).'PDF';
        }
        $nameget = 'Get'.ucfirst($table);
        $this->app->erp->BriefpapierHintergrunddisable = !$this->app->erp->BriefpapierHintergrunddisable;
        $Brief = new $name($this->app, $row['projekt']);
        $Brief->$nameget($row['id']);
        $tmpfile = $Brief->displayTMP();
        $Brief->ArchiviereDocument();
        unlink($tmpfile);
        $this->app->erp->BriefpapierHintergrunddisable = !$this->app->erp->BriefpapierHintergrunddisable;

        if(class_exists($name)){
          $anzargs = 2;
          if(method_exists($name,'__construct'))
          {
            $r = new ReflectionMethod($name, '__construct');
            $params = $r->getParameters();
            $anzargs = count($params);
          }
          if($anzargs < 2)
          {
            $Brief = new $name($this->app);
          }else{
            $Brief = new $name($this->app, $row['projekt']);
          }
          $Brief->$nameget($row['id']);
          $tmpfile = $Brief->displayTMP();
          $Brief->ArchiviereDocument();
          unlink($tmpfile);
        }
      }
      if($check)
      {
        $this->app->DB->free($check);
      }
    }

    return false;
  }

  protected function setMutex(bool $mutex = true): void
  {
    $mutexInt = (int)$mutex;
    $this->app->DB->Update(
      "UPDATE `prozessstarter` SET `mutex` = {$mutexInt}, `mutexcounter` = 0 WHERE `parameter` = 'pdfarchiv_app' AND `aktiv` = 1"
    );
  }

  /**
   * @return bool
   */
  protected function run_next()
  {
    $job = $this->app->DB->SelectRow(
      "SELECT * FROM `pdfarchiv_jobs` 
      WHERE `status` = 'angelegt' and `aktiv` = 1 and `abbrechen` <> 1 
      ORDER BY `geaendert_am` 
      LIMIT 1"
    );
    if(empty($job)) {
      return false;
    }
    $this->setStatus((int)$job['id'], 'gestartet');

      $monatvon = (int)$job['monat_von'];
      $monatbis = (int)$job['monat_bis'];
      $jahrvon = (int)$job['jahr_von'];
      $jahrbis = (int)$job['jahr_bis'];
      if($jahrbis < $jahrvon)
      {
        $jahrbis = $jahrvon;
        $monatbis = $monatvon;
      }
      if($jahrbis == $jahrvon && $monatbis < $monatvon)
      {
        $monatbis = $monatvon;
      }
      $monatvon = ($monatvon < 10?'0':'').$monatvon;
      $monatbis = ($monatbis < 10?'0':'').$monatbis;
      $pdfneu = $job['pdfneu'];
      $generiere_nur_neue = $job['generiere_nur_neue'];
      $tabs = explode(',',$job['tabellen']);
      $splitInDayly = !empty($job['daily']);
      foreach($tabs as $k => $v)
      {
        $tabelle = strtolower(trim($v));
        if(!empty($tabelle))
        {
          $tabellen[] = $tabelle;
        }
      }

      if(empty($tabellen))
      {
        $this->setStatus((int)$job['id'], 'Fehler', 'Keine Tabellen angegeben');
        return true;
      }
      $this->setMutex();

      if($pdfneu) {
        if($this->archiveNew($job, $tabellen, $jahrvon, $jahrbis, $monatvon, $monatbis)) {
          return true;
        }
      }
      elseif($generiere_nur_neue)
      {
        if($this->archiveNoneArchived($job, $tabellen, $jahrvon, $jahrbis, $monatvon, $monatbis)) {
          return true;
        }
      }

      $this->setMutex();
      if($this->app->DB->Select("SELECT `abbrechen` FROM `pdfarchiv_jobs` WHERE `id` = '".$job['id']."'"))
      {
        $this->setStatus((int)$job['id'], 'abgebrochen');
        return true;
      }
      $allepdf = $job['allepdf'];
      $this->setStatus((int)$job['id'], 'Speichere...');

      $countPerDate = $this->getDaysByTables($tabellen, $jahrvon, $jahrbis, $monatvon, $monatbis);

      if(empty($countPerDate)) {
        $this->setStatus((int)$job['id'], 'abgeschlossen', 'Keine PDFs', '');
        return true;
      }
      $zip = false;
      $gz = false;
      if($job['format'] === 'zip'){
        $zip = true;
      }
      if($job['format'] === 'gz'){
        $gz = true;
      }
      $subjobDates = $this->getSubJobs($countPerDate, $splitInDayly, $zip || $gz);
      $subJobCount = count($subjobDates);


      //getSqlByTableAndDate
      foreach($subjobDates as $indexSubJob => $subjobDate) {
      //for($indexSubjob = 0; $indexSubjob < $subJobCount; $indexSubjob++) {
        if($splitInDayly) {
          $pfad_rel = str_replace('-', '', $subjobDate['date_from']) . '_' . $job['id'];
          $pfad = rtrim($pfad =$this->folder.'/').'/' . $pfad_rel;
          $i = 0;
          while (file_exists($pfad)) {
            $i++;
            $pfad_rel = str_replace('-', '', $subjobDate['date_from']) . '_' . $i . '_' . $job['id'];
            rtrim($pfad =$this->folder.'/').'/' . $pfad_rel;
          }
        }
        else{
          $pfad = $this->folder . '/' . $monatvon . $jahrvon . '-' . $monatbis . $jahrbis . '-' . $job['id'];
          $pfad_rel = $monatvon . $jahrvon . '-' . $monatbis . $jahrbis . '-' . $job['id'];
          $i = 0;
          while (file_exists($pfad)) {
            $i++;
            $pfad_rel = $monatvon . $jahrvon . '-' . $monatbis . $jahrbis . '_' . $i . '_' . $job['id'];
            $pfad = rtrim($pfad =$this->folder.'/').'/' .  $pfad_rel;
          }
        }

        if($zip && !$this->hasZipExtension){
          if(!class_exists('ZipArchive')){
            $this->setStatus((int)$job['id'], 'Fehler', 'Die Klasse ZipArchiv ist nicht installiert');
            return false;
          }
          $ziparchiv = new ZipArchive;
          if($ziparchiv->open($pfad . '.zip', ZipArchive::CREATE) !== true){
            $this->app->erp->LogFile($pfad . '.zip ' . (file_exists($pfad . '.zip') ? 'ex' : 'ex nicht'));
            $this->setStatus((int)$job['id'], 'Fehler', 'Es konnte kein Zip-Archiv erstellt werden');
            return false;
          }
        }elseif($zip){
          if(!mkdir($pfad, 0777, true) && !is_dir($pfad)){
            echo "$pfad konnte nicht erstellt werden\r\n";
          }
          if(!function_exists('system')){
            $this->setStatus((int)$job['id'], 'Fehler', 'Die Funktion system() ist deaktivert: Es ist nicht moeglich zip-Archive zu erzeugen');
            return false;
          }
        }elseif($gz){
          if(!mkdir($pfad, 0777, true) && !is_dir($pfad)){
            echo "$pfad konnte nicht erstellt werden\r\n";
          }
          if(!function_exists('system')){
            $this->setStatus((int)$job['id'], 'Fehler', 'Die Funktion system() ist deaktivert: Es ist nicht moeglich gz-Archive zu erzeugen');
            return false;
          }
        }
        foreach ($tabellen as $table) {
          if(!$gz && !$zip){
            continue;
          }
          if($allepdf){
            echo "allepdf\r\n";
            //$sql = $this->getAllSQLByTable($table, $jahrvon, $jahrbis, $monatvon, $monatbis);
            $sql = $this->getSqlByTableAndDate($table, $subjobDate['date_from'], $subjobDate['date_to']);
            $check = $this->app->DB->Query($sql);
            //echo $sql."\r\n";
            $centries = 0;
            while ($row = $this->app->DB->Fetch_Assoc($check)) {
              $centries++;
              if($centries % 10 === 0 && function_exists('gc_enabled') && function_exists('gc_collect_cycles') && gc_enabled()){
                $cgc = gc_collect_cycles();
                if($cgc > 0){
                  $this->app->erp->LogFile($cgc . ' cycles collected');
                }
              }
              echo $row['id'];
              $this->setMutex();
              $dateipfad = Briefpapier::getPDFfolder($this->folder . '/' . $table, $row['id'], $row['dateiname']);
              //if(file_exists($this->folder.'/'.$table.'/'.$row['dateiname']))
              if(file_exists($dateipfad)){

                echo " " . $row['dateiname'];
                $belegnr = $row['pbelegnr'];
                if(empty($belegnr)){
                  $belegnr = $row['belegnr'];
                }
                if(empty($belegnr)){
                  $belegnr = $table;
                }

                $ziel = $pfad . '/' . $table . '/' . $belegnr . '.pdf';
                $zielzip = $table . '/' . $belegnr . '.pdf';
                $i = 0;
                while (file_exists($ziel)) {
                  $i++;
                  $ziel = $pfad . '/' . $table . '/' . $belegnr . '_' . $i . '.pdf';
                  $zielzip = $table . '/' . $belegnr . '_' . $i . '.pdf';
                }
                if($zip && !$this->hasZipExtension){
                  //echo "addfile: ".$ziparchiv->addFile($this->folder.'/'.$table.'/'.$row['dateiname'], $zielzip);
                  echo "addfile: " . $ziparchiv->addFile($dateipfad, $zielzip);
                  echo "\r\n";
                  echo "numfiles: " . $ziparchiv->numFiles . "\n";
                  echo "status:" . $ziparchiv->status . "\n";
                  echo "status:" . $ziparchiv->getStatusString() . "\r\n";
                }else{
                  if(!file_exists($pfad . '/' . $table)){
                    if(!mkdir($pfad . '/' . $table, 0777, true) && !is_dir($pfad . '/' . $table)){
                      echo 'Es konnte der Pfad ' . $pfad . '/' . $table . ' nicht erstellt werden' . "\r\n";
                    }
                  }
                  //copy($this->folder.'/'.$table.'/'.$row['dateiname'], $ziel);
                  copy($dateipfad, $ziel);
                }
              }
              echo "\r\n";
            }
          }else{
            echo "nur neueste\r\n";
            $check = $this->app->DB->Query(
              $this->getSqlByTableAndDate($table, $subjobDate['date_from'], $subjobDate['date_to'])
              //$this->getAllSQLByTable($table, $jahrvon, $jahrbis, $monatvon, $monatbis)
            );
            $lasttable_id = false;
            $lastDate = null;
            $centries = 0;
            while ($row = $this->app->DB->Fetch_Assoc($check)) {
              $centries++;
              if($centries % 10 === 0 && function_exists('gc_enabled') && function_exists('gc_collect_cycles') && gc_enabled()){
                $cgc = gc_collect_cycles();
                if($cgc > 0){
                  $this->app->erp->LogFile($cgc . ' cycles collected');
                }
              }
              echo $row['id'];
              $this->setMutex();
              if($lasttable_id != $row['table_id']){
                $ok = false;
                $lasttable_id = $row['table_id'];
              }
              if(!$ok){

                $dateipfad = Briefpapier::getPDFfolder($this->folder . '/' . $table, $row['id'], $row['dateiname']);

                //if(file_exists($this->folder.'/'.$table.'/'.$row['dateiname']))
                if(file_exists($dateipfad)){
                  echo " " . $row['dateiname'];
                  $ok = true;
                  $belegnr = $row['pbelegnr'];
                  if(empty($belegnr)){
                    $belegnr = $row['belegnr'];
                  }
                  if(empty($belegnr)){
                    $belegnr = $table;
                  }

                  $ziel = $pfad . '/' . $table . '/' . $belegnr . '.pdf';
                  $zielzip = $table . '/' . $belegnr . '.pdf';
                  $i = 0;
                  while (file_exists($ziel)) {
                    $i++;
                    $zielzip = $table . '/' . $belegnr . '_' . $i . '.pdf';
                    $ziel = $pfad . '/' . $table . '/' . $belegnr . '_' . $i . '.pdf';
                  }
                  if($zip && !$this->hasZipExtension){
                    //$ziparchiv->addFile($this->folder.'/'.$table.'/'.$row['dateiname'], $zielzip);
                    $ziparchiv->addFile($dateipfad, $zielzip);
                    echo "numfiles: " . $ziparchiv->numFiles . "\n";
                    echo "status:" . $ziparchiv->status . "\n";
                    echo "status:" . $ziparchiv->getStatusString() . "\r\n";
                  }else{
                    if(!file_exists($pfad . '/' . $table)){
                      if(!mkdir($pfad . '/' . $table) && !is_dir($pfad . '/' . $table)){
                        echo $pfad . '/' . $table . " konnte nicht erstellt werden\r\n";
                      }
                    }
                    //copy($this->folder.'/'.$table.'/'.$row['dateiname'], $ziel);
                    copy($dateipfad, $ziel);
                  }
                }
              }
              echo "\r\n";
            }
          }
        }
        $this->setMutex();

        if($zip && !$this->hasZipExtension){
          if(TRUE !== $ziparchiv->close()){
            $this->setStatus((int)$job['id'], 'Fehler', 'Fehler beim Speichern des Zip-Archives');
            return false;
          }
          $this->app->erp->LogFile($pfad . '.zip ' . (file_exists($pfad . '.zip') ? 'ex' : 'ex nicht'));
          if($subJobCount > 1) {
            $this->setPdfJobFile((int)$job['id'], $pfad_rel. '.zip', $subjobDate['date_from'], $subjobDate['date_to']);
            if($indexSubJob === $subJobCount - 1) {
              $this->setStatus((int)$job['id'], 'abgeschlossen', '', '');
              return true;
            }
          }
          else{
            $this->setStatus((int)$job['id'], 'abgeschlossen', '', $pfad_rel . '.zip');
          }
        }
        elseif($zip){
          echo "cd " . $this->folder . "/{$pfad_rel} && zip -r " . $pfad . ".zip ./* \n";
          system("cd {$this->folder}/{$pfad_rel} && zip -r {$pfad}.zip ./* ");
          if($subJobCount > 1) {
            $this->setPdfJobFile((int)$job['id'], $pfad_rel. '.zip', $subjobDate['date_from'], $subjobDate['date_to']);
            if($indexSubJob === $subJobCount - 1) {
              $this->setStatus((int)$job['id'], 'abgeschlossen', '', '');
              return true;
            }
          }
          else{
            $this->setStatus((int)$job['id'], 'abgeschlossen', '', $pfad_rel . '.zip');
          }
        }
        elseif($gz){
          system("cd " . $this->folder . " && tar cfz " . $pfad . ".tar.gz $pfad_rel $1>/dev/null");
          if($subJobCount > 1) {
            $this->setPdfJobFile((int)$job['id'], $pfad_rel. '.tar.gz', $subjobDate['date_from'], $subjobDate['date_to']);
            if($indexSubJob === $subJobCount - 1) {
              $this->setStatus((int)$job['id'], 'abgeschlossen', '', '');
              return true;
            }
          }
          else{
            $this->setStatus((int)$job['id'], 'abgeschlossen', '', $pfad_rel . '.tar.gz');
          }
        }else{
          if($subJobCount > 1) {
            if($indexSubJob === $subJobCount - 1) {
              $this->setStatus((int)$job['id'], 'abgeschlossen', '', '');
              return true;
            }
          }
          else{
            $this->setStatus((int)$job['id'], 'abgeschlossen', '', '');
          }
        }

        $this->setMutex();
        //system("rm -R $pfad");
      }
    return true;
  }

  protected function setPdfJobFile(int $jobId, string $file, string $dateFrom, string $dateTo): void
  {
    $this->app->DB->Insert("INSERT INTO `pdfarchiv_file` (`pdfarchiv_job_id`, `filename`, `date_from`, `date_to`)
        VALUES ({$jobId}, '{$file}', '{$dateFrom}', '{$dateTo}') "
    );
  }

  protected function getSubJobs(array $countPerDate, bool $splitInDayly, bool $compress): array
  {
    if(empty($countPerDate)){
      return [];
    }
    $dates = array_keys($countPerDate);
    if(!$compress) {
      return [
        ['date_from' => $dates[0], 'date_to' => $dates[count($dates) - 1]]
      ];
    }
    if($splitInDayly) {
      return array_map(static function ($date) {
        return ['date_from' => $date, 'date_to' => $date];
      }, $dates);
    }
    $subJobs = [];

    $actCount = 0;
    $actJobIndex = null;
    foreach($countPerDate as $date => $count) {
      if($actJobIndex === null) {
        $actJobIndex = 0;
        $subJobs[] = ['date_from' => $date, 'date_to' => $date];
        $actCount = $count;
        continue;
      }
      if($count + $actCount <= self::MAX_PDFS_PER_FILE) {
        $actCount += $count;
        $subJobs[$actJobIndex]['date_to'] = $date;
        continue;
      }
      $actJobIndex++;
      $subJobs[] = ['date_from' => $date, 'date_to' => $date];
      $actCount = $count;
    }

    return $subJobs;
  }

  protected function setStatus(int $jobId, string $status, ?string $comment = null, ?string $filename = null): void
  {
    if($filename === null) {
      if($comment === null) {
        $this->app->DB->Update(
          "UPDATE `pdfarchiv_jobs` SET `status` = '{$status}' WHERE `id` = {$jobId} LIMIT 1"
        );
        return;
      }
      $this->app->DB->Update(
        "UPDATE `pdfarchiv_jobs` SET `status` = '{$status}', `kommentar` = '{$comment}' WHERE `id` = {$jobId} LIMIT 1"
      );
      return;
    }
    $this->app->DB->Update(
      "UPDATE `pdfarchiv_jobs` 
      SET `status` = '{$status}', `kommentar` = '{$comment}', `datei` = '{$filename}'
      WHERE `id` = {$jobId} LIMIT 1"
    );
  }

  /**
   * @param string $jahrvon
   * @param string $jahrbis
   * @param string $monatvon
   * @param string $monatbis
   *
   * @return string
   */
  protected function getYearMonthWhere($jahrvon, $jahrbis, $monatvon, $monatbis)
  {
    $sql = '';
    if($jahrvon === $jahrbis) {
      $sql .= " AND YEAR(t.datum) = '{$jahrvon}' ";
      if($monatvon === $monatbis) {
        $sql .= " AND MONTH(t.datum) = '{$monatvon}' ";
      }
      else {
        if($monatvon !== '01') {
          $sql .= " AND MONTH(t.datum) >= '{$monatvon}' ";
        }
        if($monatbis !== '12'){
          $sql .= " AND MONTH(t.datum) <= '{$monatbis}' ";
        }
      }
    }
    else {
      $sql .= " AND YEAR(t.datum) >= '{$jahrvon}' AND YEAR(t.datum) <= '{$jahrbis}' ";
      if($monatvon !== '01') {
        $sql .= "
          AND ((YEAR(t.datum) = '{$jahrvon}' AND MONTH(t.datum) >= '{$monatvon}') OR YEAR(t.datum) > '{$jahrvon}')
        ";
      }
      if($monatbis !== '12') {
        $sql .= "
          AND ((YEAR(t.datum) = '{$jahrbis}' AND MONTH(t.datum) <= '{$monatbis}') OR YEAR(t.datum) < '{$jahrbis}')
        ";
      }
    }

    return $sql;
  }

  /**
   * @param string $table
   * @param string $jahrvon
   * @param string $jahrbis
   * @param string $monatvon
   * @param string $monatbis
   *
   * @return string
   */
  protected function getAllSQLByTable($table, $jahrvon, $jahrbis, $monatvon, $monatbis): string
  {
    $sql = "SELECT t.id, t.status, t.belegnr, t.datum, p.dateiname, p.belegnummer as `pbelegnr` 
            FROM `{$table}` AS `t` 
            INNER JOIN `pdfarchiv` AS `p` ON p.table_id = t.id AND p.table_name = '$table' 
                                                AND CHAR_LENGTH(p.belegnummer) > 2 AND p.belegnummer <> 'SAB' 
            WHERE p.dateiname <> '' AND p.dateiname IS NOT NULL AND t.belegnr <> ''  
                  AND t.status NOT IN ('angelegt', 'angelegta', 'a', '') "
      . $this->getYearMonthWhere($jahrvon, $jahrbis, $monatvon, $monatbis)
      . " ORDER BY t.datum, p.table_id, p.id DESC"
    ;

    return $sql;
  }

  /**
   * @param array  $tables
   * @param string $jahrvon
   * @param string $jahrbis
   * @param string $monatvon
   * @param string $monatbis
   *
   * @return array
   */
  protected function getDaysByTables($tables, $jahrvon, $jahrbis, $monatvon, $monatbis): array
  {
    $dates = [];
    foreach($tables as $table) {
      $sql = $this->getCountPerDaySqlByTable($table, $jahrvon, $jahrbis, $monatvon, $monatbis);
      $tableDates = $this->app->DB->SelectPairs(
        $sql
      );
      foreach($tableDates as $date => $count) {
        if(isset($dates[$date])) {
          $dates[$date] += $count;
        }
        else {
          $dates[$date] = $count;
        }
      }
    }

    ksort($dates);

    return $dates;
  }

  /**
   * @param string $table
   * @param string $jahrvon
   * @param string $jahrbis
   * @param string $monatvon
   * @param string $monatbis
   *
   * @return string
   */
  protected function getCountPerDaySqlByTable($table, $jahrvon, $jahrbis, $monatvon, $monatbis): string
  {
    $sql = "SELECT t.datum, COUNT(p.id) AS `count` 
            FROM `{$table}` AS `t` 
            INNER JOIN `pdfarchiv` AS `p` ON p.table_id = t.id AND p.table_name = '$table' 
                                                AND CHAR_LENGTH(p.belegnummer) > 2 AND p.belegnummer <> 'SAB' 
            WHERE p.dateiname <> '' AND p.dateiname IS NOT NULL AND t.belegnr <> ''  
                  AND t.status NOT IN ('angelegt', 'angelegta', 'a', '') "
      . $this->getYearMonthWhere($jahrvon, $jahrbis, $monatvon, $monatbis)
      . " GROUP BY t.datum ORDER BY t.datum"
    ;

    return $sql;
  }

  /**
   * @param string $table
   * @param string $dateFrom
   * @param string $dateTo
   *
   * @return string
   */
  protected function getSqlByTableAndDate($table, $dateFrom, $dateTo): string
  {
    if($dateFrom === $dateTo) {
      $subWhere = " AND t.datum = '{$dateFrom}' ";
    }
    else {
      $subWhere = " AND t.datum >= '{$dateFrom}' AND t.datum <= '{$dateTo}' ";
    }
    return "SELECT t.id, t.status, t.belegnr,t.datum, p.table_id, p.dateiname, p.belegnummer AS `pbelegnr` 
            FROM `{$table}` AS `t` 
            INNER JOIN `pdfarchiv` AS `p` ON p.table_id = t.id AND p.table_name = '{$table}' 
                                         AND CHAR_LENGTH(p.belegnummer) > 2 AND p.belegnummer <> 'SAB' 
            WHERE p.dateiname <> '' AND p.dateiname IS NOT NULL AND t.belegnr <> '' 
              AND t.status NOT IN ('angelegt', 'angelegta', 'a', '')
              $subWhere
            ORDER BY p.table_id, p.id DESC
          ";
  }
}

