<?php
/*
**** COPYRIGHT & LICENSE NOTICE *** DO NOT REMOVE ****
* 
* Xentral (c) Xentral ERP Sorftware GmbH, Fuggerstrasse 11, D-86150 Augsburg, * Germany 2019
*
* This file is licensed under the Embedded Projects General Public License *Version 3.1. 
*
* You should have received a copy of this license from your vendor and/or *along with this file; If not, please visit www.wawision.de/Lizenzhinweis 
* to obtain the text of the corresponding license version.  
*
**** END OF COPYRIGHT & LICENSE NOTICE *** DO NOT REMOVE ****
*/
?>
<?php

class ObjGenZeiterfassungvorlage
{

  private  $id;
  private  $vorlage;
  private  $vorlagedetail;
  private  $ausblenden;

  public $app;            //application object 

  public function __construct($app)
  {
    $this->app = $app;
  }

  public function Select($id)
  {
    if(is_numeric($id))
      $result = $this->app->DB->SelectArr("SELECT * FROM zeiterfassungvorlage WHERE (id = '$id')");
    else
      return -1;

$result = $result[0];

    $this->id=$result['id'];
    $this->vorlage=$result['vorlage'];
    $this->vorlagedetail=$result['vorlagedetail'];
    $this->ausblenden=$result['ausblenden'];
  }

  public function Create()
  {
    $sql = "INSERT INTO zeiterfassungvorlage (id,vorlage,vorlagedetail,ausblenden)
      VALUES('','{$this->vorlage}','{$this->vorlagedetail}','{$this->ausblenden}')";

    $this->app->DB->Insert($sql);
    $this->id = $this->app->DB->GetInsertID();
  }

  public function Update()
  {
    if(!is_numeric($this->id))
      return -1;

    $sql = "UPDATE zeiterfassungvorlage SET
      vorlage='{$this->vorlage}',
      vorlagedetail='{$this->vorlagedetail}',
      ausblenden='{$this->ausblenden}'
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

    $sql = "DELETE FROM zeiterfassungvorlage WHERE (id='{$this->id}')";
    $this->app->DB->Delete($sql);

    $this->id="";
    $this->vorlage="";
    $this->ausblenden="";
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
  function SetVorlage($value) { $this->vorlage=$value; }
  function GetVorlage() { return $this->vorlage; }
  function SetVorlagedetail($value) { $this->vorlagedetail=$value; }
  function GetVorlagedetail() { return $this->vorlagedetail; }
  function SetAusblenden($value) { $this->ausblenden=$value; }
  function GetAusblenden() { return $this->ausblenden; }

}

?>