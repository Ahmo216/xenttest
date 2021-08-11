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

class GenProjekt { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","ProjektCreate");
    $this->app->ActionHandler("edit","ProjektEdit");
    $this->app->ActionHandler("copy","ProjektCopy");
    $this->app->ActionHandler("list","ProjektList");
    $this->app->ActionHandler("delete","ProjektDelete");

    $this->app->Tpl->Set("HEADING","Projekt");    //$this->app->ActionHandlerListen($app);
  }

  function ProjektCreate(){
    $this->app->Tpl->Set("HEADING","Projekt (Anlegen)");
      $this->app->PageBuilder->CreateGen("projekt_create.tpl");
  }

  function ProjektEdit(){
    $this->app->Tpl->Set("HEADING","Projekt (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("projekt_edit.tpl");
  }

  function ProjektCopy(){
    $this->app->Tpl->Set("HEADING","Projekt (Kopieren)");
      $this->app->PageBuilder->CreateGen("projekt_copy.tpl");
  }

  function ProjektDelete(){
    $this->app->Tpl->Set("HEADING","Projekt (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("projekt_delete.tpl");
  }

  function ProjektList(){
    $this->app->Tpl->Set("HEADING","Projekt (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("projekt_list.tpl");
  }

} 
?>