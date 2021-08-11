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

class GenGruppen_Kategorien { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","Gruppen_KategorienCreate");
    $this->app->ActionHandler("edit","Gruppen_KategorienEdit");
    $this->app->ActionHandler("copy","Gruppen_KategorienCopy");
    $this->app->ActionHandler("list","Gruppen_KategorienList");
    $this->app->ActionHandler("delete","Gruppen_KategorienDelete");

    $this->app->Tpl->Set("HEADING","Gruppen_Kategorien");    //$this->app->ActionHandlerListen($app);
  }

  function Gruppen_KategorienCreate(){
    $this->app->Tpl->Set("HEADING","Gruppen_Kategorien (Anlegen)");
      $this->app->PageBuilder->CreateGen("gruppen_kategorien_create.tpl");
  }

  function Gruppen_KategorienEdit(){
    $this->app->Tpl->Set("HEADING","Gruppen_Kategorien (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("gruppen_kategorien_edit.tpl");
  }

  function Gruppen_KategorienCopy(){
    $this->app->Tpl->Set("HEADING","Gruppen_Kategorien (Kopieren)");
      $this->app->PageBuilder->CreateGen("gruppen_kategorien_copy.tpl");
  }

  function Gruppen_KategorienDelete(){
    $this->app->Tpl->Set("HEADING","Gruppen_Kategorien (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("gruppen_kategorien_delete.tpl");
  }

  function Gruppen_KategorienList(){
    $this->app->Tpl->Set("HEADING","Gruppen_Kategorien (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("gruppen_kategorien_list.tpl");
  }

} 
?>