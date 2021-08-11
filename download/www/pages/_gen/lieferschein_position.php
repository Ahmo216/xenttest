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

class GenLieferschein_Position { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","Lieferschein_PositionCreate");
    $this->app->ActionHandler("edit","Lieferschein_PositionEdit");
    $this->app->ActionHandler("copy","Lieferschein_PositionCopy");
    $this->app->ActionHandler("list","Lieferschein_PositionList");
    $this->app->ActionHandler("delete","Lieferschein_PositionDelete");

    $this->app->Tpl->Set("HEADING","Lieferschein_Position");    //$this->app->ActionHandlerListen($app);
  }

  function Lieferschein_PositionCreate(){
    $this->app->Tpl->Set("HEADING","Lieferschein_Position (Anlegen)");
      $this->app->PageBuilder->CreateGen("lieferschein_position_create.tpl");
  }

  function Lieferschein_PositionEdit(){
    $this->app->Tpl->Set("HEADING","Lieferschein_Position (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("lieferschein_position_edit.tpl");
  }

  function Lieferschein_PositionCopy(){
    $this->app->Tpl->Set("HEADING","Lieferschein_Position (Kopieren)");
      $this->app->PageBuilder->CreateGen("lieferschein_position_copy.tpl");
  }

  function Lieferschein_PositionDelete(){
    $this->app->Tpl->Set("HEADING","Lieferschein_Position (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("lieferschein_position_delete.tpl");
  }

  function Lieferschein_PositionList(){
    $this->app->Tpl->Set("HEADING","Lieferschein_Position (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("lieferschein_position_list.tpl");
  }

} 
?>