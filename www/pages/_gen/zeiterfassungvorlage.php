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

class GenZeiterfassungvorlage { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","ZeiterfassungvorlageCreate");
    $this->app->ActionHandler("edit","ZeiterfassungvorlageEdit");
    $this->app->ActionHandler("copy","ZeiterfassungvorlageCopy");
    $this->app->ActionHandler("list","ZeiterfassungvorlageList");
    $this->app->ActionHandler("delete","ZeiterfassungvorlageDelete");

    $this->app->Tpl->Set("HEADING","Zeiterfassungvorlage");    //$this->app->ActionHandlerListen($app);
  }

  function ZeiterfassungvorlageCreate(){
    $this->app->Tpl->Set("HEADING","Zeiterfassungvorlage (Anlegen)");
      $this->app->PageBuilder->CreateGen("zeiterfassungvorlage_create.tpl");
  }

  function ZeiterfassungvorlageEdit(){
    $this->app->Tpl->Set("HEADING","Zeiterfassungvorlage (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("zeiterfassungvorlage_edit.tpl");
  }

  function ZeiterfassungvorlageCopy(){
    $this->app->Tpl->Set("HEADING","Zeiterfassungvorlage (Kopieren)");
      $this->app->PageBuilder->CreateGen("zeiterfassungvorlage_copy.tpl");
  }

  function ZeiterfassungvorlageDelete(){
    $this->app->Tpl->Set("HEADING","Zeiterfassungvorlage (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("zeiterfassungvorlage_delete.tpl");
  }

  function ZeiterfassungvorlageList(){
    $this->app->Tpl->Set("HEADING","Zeiterfassungvorlage (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("zeiterfassungvorlage_list.tpl");
  }

} 
?>