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

class GenBestellung_Position { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","Bestellung_PositionCreate");
    $this->app->ActionHandler("edit","Bestellung_PositionEdit");
    $this->app->ActionHandler("copy","Bestellung_PositionCopy");
    $this->app->ActionHandler("list","Bestellung_PositionList");
    $this->app->ActionHandler("delete","Bestellung_PositionDelete");

    $this->app->Tpl->Set("HEADING","Bestellung_Position");    //$this->app->ActionHandlerListen($app);
  }

  function Bestellung_PositionCreate(){
    $this->app->Tpl->Set("HEADING","Bestellung_Position (Anlegen)");
      $this->app->PageBuilder->CreateGen("bestellung_position_create.tpl");
  }

  function Bestellung_PositionEdit(){
    $this->app->Tpl->Set("HEADING","Bestellung_Position (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("bestellung_position_edit.tpl");
  }

  function Bestellung_PositionCopy(){
    $this->app->Tpl->Set("HEADING","Bestellung_Position (Kopieren)");
      $this->app->PageBuilder->CreateGen("bestellung_position_copy.tpl");
  }

  function Bestellung_PositionDelete(){
    $this->app->Tpl->Set("HEADING","Bestellung_Position (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("bestellung_position_delete.tpl");
  }

  function Bestellung_PositionList(){
    $this->app->Tpl->Set("HEADING","Bestellung_Position (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("bestellung_position_list.tpl");
  }

} 
?>