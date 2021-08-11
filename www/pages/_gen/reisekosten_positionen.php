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

class GenReisekosten_Positionen { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","Reisekosten_PositionenCreate");
    $this->app->ActionHandler("edit","Reisekosten_PositionenEdit");
    $this->app->ActionHandler("copy","Reisekosten_PositionenCopy");
    $this->app->ActionHandler("list","Reisekosten_PositionenList");
    $this->app->ActionHandler("delete","Reisekosten_PositionenDelete");

    $this->app->Tpl->Set('HEADING',"Reisekosten_Positionen");    $this->app->ActionHandlerListen($app);
  }

  function Reisekosten_PositionenCreate(){
    $this->app->Tpl->Set('HEADING',"Reisekosten_Positionen (Anlegen)");
      $this->app->PageBuilder->CreateGen("reisekosten_positionen_create.tpl");
  }

  function Reisekosten_PositionenEdit(){
    $this->app->Tpl->Set('HEADING',"Reisekosten_Positionen (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("reisekosten_positionen_edit.tpl");
  }

  function Reisekosten_PositionenCopy(){
    $this->app->Tpl->Set('HEADING',"Reisekosten_Positionen (Kopieren)");
      $this->app->PageBuilder->CreateGen("reisekosten_positionen_copy.tpl");
  }

  function Reisekosten_PositionenDelete(){
    $this->app->Tpl->Set('HEADING',"Reisekosten_Positionen (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("reisekosten_positionen_delete.tpl");
  }

  function Reisekosten_PositionenList(){
    $this->app->Tpl->Set('HEADING',"Reisekosten_Positionen (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("reisekosten_positionen_list.tpl");
  }

} 
?>