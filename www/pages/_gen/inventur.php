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

class GenInventur { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","InventurCreate");
    $this->app->ActionHandler("edit","InventurEdit");
    $this->app->ActionHandler("copy","InventurCopy");
    $this->app->ActionHandler("list","InventurList");
    $this->app->ActionHandler("delete","InventurDelete");

    $this->app->Tpl->Set("HEADING","Inventur");    //$this->app->ActionHandlerListen($app);
  }

  function InventurCreate(){
    $this->app->Tpl->Set("HEADING","Inventur (Anlegen)");
      $this->app->PageBuilder->CreateGen("inventur_create.tpl");
  }

  function InventurEdit(){
    $this->app->Tpl->Set("HEADING","Inventur (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("inventur_edit.tpl");
  }

  function InventurCopy(){
    $this->app->Tpl->Set("HEADING","Inventur (Kopieren)");
      $this->app->PageBuilder->CreateGen("inventur_copy.tpl");
  }

  function InventurDelete(){
    $this->app->Tpl->Set("HEADING","Inventur (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("inventur_delete.tpl");
  }

  function InventurList(){
    $this->app->Tpl->Set("HEADING","Inventur (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("inventur_list.tpl");
  }

} 
?>