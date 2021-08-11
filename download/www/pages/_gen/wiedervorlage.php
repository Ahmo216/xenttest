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

class GenWiedervorlage { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","WiedervorlageCreate");
    $this->app->ActionHandler("edit","WiedervorlageEdit");
    $this->app->ActionHandler("copy","WiedervorlageCopy");
    $this->app->ActionHandler("list","WiedervorlageList");
    $this->app->ActionHandler("delete","WiedervorlageDelete");

    $this->app->Tpl->Set("HEADING","Wiedervorlage");    //$this->app->ActionHandlerListen($app);
  }

  function WiedervorlageCreate(){
    $this->app->Tpl->Set("HEADING","Wiedervorlage (Anlegen)");
      $this->app->PageBuilder->CreateGen("wiedervorlage_create.tpl");
  }

  function WiedervorlageEdit(){
    $this->app->Tpl->Set("HEADING","Wiedervorlage (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("wiedervorlage_edit.tpl");
  }

  function WiedervorlageCopy(){
    $this->app->Tpl->Set("HEADING","Wiedervorlage (Kopieren)");
      $this->app->PageBuilder->CreateGen("wiedervorlage_copy.tpl");
  }

  function WiedervorlageDelete(){
    $this->app->Tpl->Set("HEADING","Wiedervorlage (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("wiedervorlage_delete.tpl");
  }

  function WiedervorlageList(){
    $this->app->Tpl->Set("HEADING","Wiedervorlage (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("wiedervorlage_list.tpl");
  }

} 
?>