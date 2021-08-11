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

class GenArbeitsfreietage { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","ArbeitsfreietageCreate");
    $this->app->ActionHandler("edit","ArbeitsfreietageEdit");
    $this->app->ActionHandler("copy","ArbeitsfreietageCopy");
    $this->app->ActionHandler("list","ArbeitsfreietageList");
    $this->app->ActionHandler("delete","ArbeitsfreietageDelete");

    $this->app->Tpl->Set("HEADING","Arbeitsfreietage");    //$this->app->ActionHandlerListen($app);
  }

  function ArbeitsfreietageCreate(){
    $this->app->Tpl->Set("HEADING","Arbeitsfreietage (Anlegen)");
      $this->app->PageBuilder->CreateGen("arbeitsfreietage_create.tpl");
  }

  function ArbeitsfreietageEdit(){
    $this->app->Tpl->Set("HEADING","Arbeitsfreietage (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("arbeitsfreietage_edit.tpl");
  }

  function ArbeitsfreietageCopy(){
    $this->app->Tpl->Set("HEADING","Arbeitsfreietage (Kopieren)");
      $this->app->PageBuilder->CreateGen("arbeitsfreietage_copy.tpl");
  }

  function ArbeitsfreietageDelete(){
    $this->app->Tpl->Set("HEADING","Arbeitsfreietage (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("arbeitsfreietage_delete.tpl");
  }

  function ArbeitsfreietageList(){
    $this->app->Tpl->Set("HEADING","Arbeitsfreietage (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("arbeitsfreietage_list.tpl");
  }

} 
?>