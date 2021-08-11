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

class GenUebersetzung { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","UebersetzungCreate");
    $this->app->ActionHandler("edit","UebersetzungEdit");
    $this->app->ActionHandler("copy","UebersetzungCopy");
    $this->app->ActionHandler("list","UebersetzungList");
    $this->app->ActionHandler("delete","UebersetzungDelete");

    $this->app->Tpl->Set("HEADING","Uebersetzung");    $this->app->ActionHandlerListen($app);
  }

  function UebersetzungCreate(){
    $this->app->Tpl->Set("HEADING","Uebersetzung (Anlegen)");
      $this->app->PageBuilder->CreateGen("uebersetzung_create.tpl");
  }

  function UebersetzungEdit(){
    $this->app->Tpl->Set("HEADING","Uebersetzung (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("uebersetzung_edit.tpl");
  }

  function UebersetzungCopy(){
    $this->app->Tpl->Set("HEADING","Uebersetzung (Kopieren)");
      $this->app->PageBuilder->CreateGen("uebersetzung_copy.tpl");
  }

  function UebersetzungDelete(){
    $this->app->Tpl->Set("HEADING","Uebersetzung (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("uebersetzung_delete.tpl");
  }

  function UebersetzungList(){
    $this->app->Tpl->Set("HEADING","Uebersetzung (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("uebersetzung_list.tpl");
  }

} 
?>