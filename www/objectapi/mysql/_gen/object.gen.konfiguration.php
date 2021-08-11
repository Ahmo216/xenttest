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

class ObjGenKonfiguration
{

  private  $name;
  private  $wert;
  private  $adresse;
  private  $firma;

  public $app;            //application object 

  public function __construct($app)
  {
    $this->app = $app;
  }

  public function Select($name)
  {
    $result = $this->app->DB->SelectArr("SELECT * FROM konfiguration WHERE (name = '$name')");

$result = $result[0];

    $this->name=$result[name];
    $this->wert=$result[wert];
    $this->adresse=$result[adresse];
    $this->firma=$result[firma];
  }

  public function Create()
  {
    $sql = "INSERT INTO konfiguration (name,wert,adresse,firma)
      VALUES('{$this->name}','{$this->wert}','{$this->adresse}','{$this->firma}')"; 

    $this->app->DB->Insert($sql);
    $this->id = $this->app->DB->GetInsertID();
  }

  public function Update()
  {
    $sql = "UPDATE konfiguration SET
      wert='{$this->wert}',
      adresse='{$this->adresse}',
      firma='{$this->firma}'
      WHERE (name='{$this->name}')";

    $this->app->DB->Update($sql);
  }

  public function Delete($name="")
  {
    $sql = "DELETE FROM konfiguration WHERE (name='{$this->name}')";
    $this->app->DB->Delete($sql);

    $this->name="";
    $this->wert="";
    $this->adresse="";
    $this->firma="";
  }

  public function Copy()
  {
    $this->name = "";
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

  function SetName($value) { $this->name=$value; }
  function GetName() { return $this->name; }
  function SetWert($value) { $this->wert=$value; }
  function GetWert() { return $this->wert; }
  function SetAdresse($value) { $this->adresse=$value; }
  function GetAdresse() { return $this->adresse; }
  function SetFirma($value) { $this->firma=$value; }
  function GetFirma() { return $this->firma; }

}

?>