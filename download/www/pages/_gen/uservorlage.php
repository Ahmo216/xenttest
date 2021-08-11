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

class GenUservorlage { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","UservorlageCreate");
    $this->app->ActionHandler("edit","UservorlageEdit");
    $this->app->ActionHandler("copy","UservorlageCopy");
    $this->app->ActionHandler("list","UservorlageList");
    $this->app->ActionHandler("delete","UservorlageDelete");

    $this->app->Tpl->Set("HEADING","Uservorlage");    //$this->app->ActionHandlerListen($app);
  }

  function UservorlageCreate(){
    $this->app->Tpl->Set("HEADING","Uservorlage (Anlegen)");
      $this->app->PageBuilder->CreateGen("uservorlage_create.tpl");
  }

  function UservorlageEdit(){
    $this->app->Tpl->Set("HEADING","Uservorlage (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("uservorlage_edit.tpl");
  }

  function UservorlageCopy(){
    $this->app->Tpl->Set("HEADING","Uservorlage (Kopieren)");
      $this->app->PageBuilder->CreateGen("uservorlage_copy.tpl");
  }

  function UservorlageDelete(){
    $this->app->Tpl->Set("HEADING","Uservorlage (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("uservorlage_delete.tpl");
  }

  function UservorlageList(){
    $this->app->Tpl->Set("HEADING","Uservorlage (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("uservorlage_list.tpl");
  }

} 
?>