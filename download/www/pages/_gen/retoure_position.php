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

class GenRetoure_Position { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","Retoure_PositionCreate");
    $this->app->ActionHandler("edit","Retoure_PositionEdit");
    $this->app->ActionHandler("copy","Retoure_PositionCopy");
    $this->app->ActionHandler("list","Retoure_PositionList");
    $this->app->ActionHandler("delete","Retoure_PositionDelete");

    $this->app->Tpl->Set("HEADING","Retoure_Position");    //$this->app->ActionHandlerListen($app);
  }

  function Retoure_PositionCreate(){
    $this->app->Tpl->Set("HEADING","Retoure_Position (Anlegen)");
      $this->app->PageBuilder->CreateGen("retoure_position_create.tpl");
  }

  function Retoure_PositionEdit(){
    $this->app->Tpl->Set("HEADING","Retoure_Position (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("retoure_position_edit.tpl");
  }

  function Retoure_PositionCopy(){
    $this->app->Tpl->Set("HEADING","Retoure_Position (Kopieren)");
      $this->app->PageBuilder->CreateGen("retoure_position_copy.tpl");
  }

  function Retoure_PositionDelete(){
    $this->app->Tpl->Set("HEADING","Retoure_Position (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("retoure_position_delete.tpl");
  }

  function Retoure_PositionList(){
    $this->app->Tpl->Set("HEADING","Retoure_Position (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("retoure_position_list.tpl");
  }

} 
?>