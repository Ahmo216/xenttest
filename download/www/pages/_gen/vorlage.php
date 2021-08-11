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

class GenVorlage { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","VorlageCreate");
    $this->app->ActionHandler("edit","VorlageEdit");
    $this->app->ActionHandler("copy","VorlageCopy");
    $this->app->ActionHandler("list","VorlageList");
    $this->app->ActionHandler("delete","VorlageDelete");

    $this->app->Tpl->Set("HEADING","Vorlage");    //$this->app->ActionHandlerListen($app);
  }

  function VorlageCreate(){
    $this->app->Tpl->Set("HEADING","Vorlage (Anlegen)");
      $this->app->PageBuilder->CreateGen("vorlage_create.tpl");
  }

  function VorlageEdit(){
    $this->app->Tpl->Set("HEADING","Vorlage (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("vorlage_edit.tpl");
  }

  function VorlageCopy(){
    $this->app->Tpl->Set("HEADING","Vorlage (Kopieren)");
      $this->app->PageBuilder->CreateGen("vorlage_copy.tpl");
  }

  function VorlageDelete(){
    $this->app->Tpl->Set("HEADING","Vorlage (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("vorlage_delete.tpl");
  }

  function VorlageList(){
    $this->app->Tpl->Set("HEADING","Vorlage (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("vorlage_list.tpl");
  }

} 
?>