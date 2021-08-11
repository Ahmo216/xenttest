<?php
/*
**** COPYRIGHT & LICENSE NOTICE *** DO NOT REMOVE ****
*
* Xentral (c) Xentral ERP Software GmbH GmbH, Fuggerstrasse 11, D-86150 Augsburg, * Germany 2019 
*
**** END OF COPYRIGHT & LICENSE NOTICE *** DO NOT REMOVE ****
*/
?>
<?php

class ObjGenKalkulation_Position
{

  private  $id;
  private  $kalkulation;
  private  $kalkulationart;
  private  $artikel;
  private  $projekt;
  private  $bezeichnung;
  private  $beschreibung;
  private  $internerkommentar;
  private  $nummer;
  private  $verrechnungsart;
  private  $menge;
  private  $datum;
  private  $von;
  private  $bis;
  private  $sort;
  private  $status;
  private  $bemerkung;
  private  $betrag;
  private  $abrechnen;
  private  $abgerechnet;
  private  $abgerechnet_objekt;
  private  $abgerechnet_parameter;
  private  $logdatei;

  public $app;            //application object 

  public function __construct($app)
  {
    $this->app = $app;
  }

  public function Select($id)
  {
    if(is_numeric($id))
      $result = $this->app->DB->SelectArr("SELECT * FROM kalkulation_position WHERE (id = '$id')");
    else
      return -1;

$result = $result[0];

    $this->id=$result['id'];
    $this->kalkulation=$result['kalkulation'];
    $this->kalkulationart=$result['kalkulationart'];
    $this->artikel=$result['artikel'];
    $this->projekt=$result['projekt'];
    $this->bezeichnung=$result['bezeichnung'];
    $this->beschreibung=$result['beschreibung'];
    $this->internerkommentar=$result['internerkommentar'];
    $this->nummer=$result['nummer'];
    $this->verrechnungsart=$result['verrechnungsart'];
    $this->menge=$result['menge'];
    $this->datum=$result['datum'];
    $this->von=$result['von'];
    $this->bis=$result['bis'];
    $this->sort=$result['sort'];
    $this->status=$result['status'];
    $this->bemerkung=$result['bemerkung'];
    $this->betrag=$result['betrag'];
    $this->abrechnen=$result['abrechnen'];
    $this->abgerechnet=$result['abgerechnet'];
    $this->abgerechnet_objekt=$result['abgerechnet_objekt'];
    $this->abgerechnet_parameter=$result['abgerechnet_parameter'];
    $this->logdatei=$result['logdatei'];
  }

  public function Create()
  {
    $sql = "INSERT INTO kalkulation_position (id,kalkulation,kalkulationart,artikel,projekt,bezeichnung,beschreibung,internerkommentar,nummer,verrechnungsart,menge,datum,von,bis,sort,status,bemerkung,betrag,abrechnen,abgerechnet,abgerechnet_objekt,abgerechnet_parameter,logdatei)
      VALUES('','{$this->kalkulation}','{$this->kalkulationart}','{$this->artikel}','{$this->projekt}','{$this->bezeichnung}','{$this->beschreibung}','{$this->internerkommentar}','{$this->nummer}','{$this->verrechnungsart}','{$this->menge}','{$this->datum}','{$this->von}','{$this->bis}','{$this->sort}','{$this->status}','{$this->bemerkung}','{$this->betrag}','{$this->abrechnen}','{$this->abgerechnet}','{$this->abgerechnet_objekt}','{$this->abgerechnet_parameter}','{$this->logdatei}')"; 

    $this->app->DB->Insert($sql);
    $this->id = $this->app->DB->GetInsertID();
  }

  public function Update()
  {
    if(!is_numeric($this->id))
      return -1;

    $sql = "UPDATE kalkulation_position SET
      kalkulation='{$this->kalkulation}',
      kalkulationart='{$this->kalkulationart}',
      artikel='{$this->artikel}',
      projekt='{$this->projekt}',
      bezeichnung='{$this->bezeichnung}',
      beschreibung='{$this->beschreibung}',
      internerkommentar='{$this->internerkommentar}',
      nummer='{$this->nummer}',
      verrechnungsart='{$this->verrechnungsart}',
      menge='{$this->menge}',
      datum='{$this->datum}',
      von='{$this->von}',
      bis='{$this->bis}',
      sort='{$this->sort}',
      status='{$this->status}',
      bemerkung='{$this->bemerkung}',
      betrag='{$this->betrag}',
      abrechnen='{$this->abrechnen}',
      abgerechnet='{$this->abgerechnet}',
      abgerechnet_objekt='{$this->abgerechnet_objekt}',
      abgerechnet_parameter='{$this->abgerechnet_parameter}',
      logdatei='{$this->logdatei}'
      WHERE (id='{$this->id}')";

    $this->app->DB->Update($sql);
  }

  public function Delete($id="")
  {
    if(is_numeric($id))
    {
      $this->id=$id;
    }
    else
      return -1;

    $sql = "DELETE FROM kalkulation_position WHERE (id='{$this->id}')";
    $this->app->DB->Delete($sql);

    $this->id="";
    $this->kalkulation="";
    $this->kalkulationart="";
    $this->artikel="";
    $this->projekt="";
    $this->bezeichnung="";
    $this->beschreibung="";
    $this->internerkommentar="";
    $this->nummer="";
    $this->verrechnungsart="";
    $this->menge="";
    $this->datum="";
    $this->von="";
    $this->bis="";
    $this->sort="";
    $this->status="";
    $this->bemerkung="";
    $this->betrag="";
    $this->abrechnen="";
    $this->abgerechnet="";
    $this->abgerechnet_objekt="";
    $this->abgerechnet_parameter="";
    $this->logdatei="";
  }

  public function Copy()
  {
    $this->id = "";
    $this->Create();
  }

 /** 
   Mit dieser Funktion kann man einen Datensatz suchen 
   dafuer muss man die Attribute setzen nach denen gesucht werden soll
   dann kriegt man als ergebnis den ersten Datensatz der auf die Suche uebereinstimmt
   zurueck. Mit Next() kann man sich alle weiteren Ergebnisse abholen
   **/ 

  public function Find()
  {
    //TODO Suche mit den werten machen
  }

  public function FindNext()
  {
    //TODO Suche mit den alten werten fortsetzen machen
  }

 /** Funktionen um durch die Tabelle iterieren zu koennen */ 

  public function Next()
  {
    //TODO: SQL Statement passt nach meiner Meinung nach noch nicht immer
  }

  public function First()
  {
    //TODO: SQL Statement passt nach meiner Meinung nach noch nicht immer
  }

 /** dank dieser funktionen kann man die tatsaechlichen werte einfach 
  ueberladen (in einem Objekt das mit seiner klasse ueber dieser steht)**/ 

  function SetId($value) { $this->id=$value; }
  function GetId() { return $this->id; }
  function SetKalkulation($value) { $this->kalkulation=$value; }
  function GetKalkulation() { return $this->kalkulation; }
  function SetKalkulationart($value) { $this->kalkulationart=$value; }
  function GetKalkulationart() { return $this->kalkulationart; }
  function SetArtikel($value) { $this->artikel=$value; }
  function GetArtikel() { return $this->artikel; }
  function SetProjekt($value) { $this->projekt=$value; }
  function GetProjekt() { return $this->projekt; }
  function SetBezeichnung($value) { $this->bezeichnung=$value; }
  function GetBezeichnung() { return $this->bezeichnung; }
  function SetBeschreibung($value) { $this->beschreibung=$value; }
  function GetBeschreibung() { return $this->beschreibung; }
  function SetInternerkommentar($value) { $this->internerkommentar=$value; }
  function GetInternerkommentar() { return $this->internerkommentar; }
  function SetNummer($value) { $this->nummer=$value; }
  function GetNummer() { return $this->nummer; }
  function SetVerrechnungsart($value) { $this->verrechnungsart=$value; }
  function GetVerrechnungsart() { return $this->verrechnungsart; }
  function SetMenge($value) { $this->menge=$value; }
  function GetMenge() { return $this->menge; }
  function SetDatum($value) { $this->datum=$value; }
  function GetDatum() { return $this->datum; }
  function SetVon($value) { $this->von=$value; }
  function GetVon() { return $this->von; }
  function SetBis($value) { $this->bis=$value; }
  function GetBis() { return $this->bis; }
  function SetSort($value) { $this->sort=$value; }
  function GetSort() { return $this->sort; }
  function SetStatus($value) { $this->status=$value; }
  function GetStatus() { return $this->status; }
  function SetBemerkung($value) { $this->bemerkung=$value; }
  function GetBemerkung() { return $this->bemerkung; }
  function SetBetrag($value) { $this->betrag=$value; }
  function GetBetrag() { return $this->betrag; }
  function SetAbrechnen($value) { $this->abrechnen=$value; }
  function GetAbrechnen() { return $this->abrechnen; }
  function SetAbgerechnet($value) { $this->abgerechnet=$value; }
  function GetAbgerechnet() { return $this->abgerechnet; }
  function SetAbgerechnet_Objekt($value) { $this->abgerechnet_objekt=$value; }
  function GetAbgerechnet_Objekt() { return $this->abgerechnet_objekt; }
  function SetAbgerechnet_Parameter($value) { $this->abgerechnet_parameter=$value; }
  function GetAbgerechnet_Parameter() { return $this->abgerechnet_parameter; }
  function SetLogdatei($value) { $this->logdatei=$value; }
  function GetLogdatei() { return $this->logdatei; }

}

?>