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

class GenVerbindlichkeit_Positionen { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","Verbindlichkeit_PositionenCreate");
    $this->app->ActionHandler("edit","Verbindlichkeit_PositionenEdit");
    $this->app->ActionHandler("copy","Verbindlichkeit_PositionenCopy");
    $this->app->ActionHandler("list","Verbindlichkeit_PositionenList");
    $this->app->ActionHandler("delete","Verbindlichkeit_PositionenDelete");

    $this->app->Tpl->Set("HEADING","Verbindlichkeit_Positionen");    //$this->app->ActionHandlerListen($app);
  }

  function Verbindlichkeit_PositionenCreate(){
    $this->app->Tpl->Set("HEADING","Verbindlichkeit_Positionen (Anlegen)");
      $this->app->PageBuilder->CreateGen("verbindlichkeit_positionen_create.tpl");
  }

  function Verbindlichkeit_PositionenEdit(){
    $this->app->Tpl->Set("HEADING","Verbindlichkeit_Positionen (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("verbindlichkeit_positionen_edit.tpl");
  }

  function Verbindlichkeit_PositionenCopy(){
    $this->app->Tpl->Set("HEADING","Verbindlichkeit_Positionen (Kopieren)");
      $this->app->PageBuilder->CreateGen("verbindlichkeit_positionen_copy.tpl");
  }

  function Verbindlichkeit_PositionenDelete(){
    $this->app->Tpl->Set("HEADING","Verbindlichkeit_Positionen (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("verbindlichkeit_positionen_delete.tpl");
  }

  function Verbindlichkeit_PositionenList(){
    $this->app->Tpl->Set("HEADING","Verbindlichkeit_Positionen (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("verbindlichkeit_positionen_list.tpl");
  }

} 
?>