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

class GenVerbindlichkeit_Position { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","Verbindlichkeit_PositionCreate");
    $this->app->ActionHandler("edit","Verbindlichkeit_PositionEdit");
    $this->app->ActionHandler("copy","Verbindlichkeit_PositionCopy");
    $this->app->ActionHandler("list","Verbindlichkeit_PositionList");
    $this->app->ActionHandler("delete","Verbindlichkeit_PositionDelete");

    $this->app->Tpl->Set("HEADING","Verbindlichkeit_Position");    //$this->app->ActionHandlerListen($app);
  }

  function Verbindlichkeit_PositionCreate(){
    $this->app->Tpl->Set("HEADING","Verbindlichkeit_Position (Anlegen)");
      $this->app->PageBuilder->CreateGen("verbindlichkeit_position_create.tpl");
  }

  function Verbindlichkeit_PositionEdit(){
    $this->app->Tpl->Set("HEADING","Verbindlichkeit_Position (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("verbindlichkeit_position_edit.tpl");
  }

  function Verbindlichkeit_PositionCopy(){
    $this->app->Tpl->Set("HEADING","Verbindlichkeit_Position (Kopieren)");
      $this->app->PageBuilder->CreateGen("verbindlichkeit_position_copy.tpl");
  }

  function Verbindlichkeit_PositionDelete(){
    $this->app->Tpl->Set("HEADING","Verbindlichkeit_Position (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("verbindlichkeit_position_delete.tpl");
  }

  function Verbindlichkeit_PositionList(){
    $this->app->Tpl->Set("HEADING","Verbindlichkeit_Position (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("verbindlichkeit_position_list.tpl");
  }

} 
?>