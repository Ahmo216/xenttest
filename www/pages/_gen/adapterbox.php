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

class GenAdapterbox { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","AdapterboxCreate");
    $this->app->ActionHandler("edit","AdapterboxEdit");
    $this->app->ActionHandler("copy","AdapterboxCopy");
    $this->app->ActionHandler("list","AdapterboxList");
    $this->app->ActionHandler("delete","AdapterboxDelete");

    $this->app->Tpl->Set("HEADING","Adapterbox");    //$this->app->ActionHandlerListen($app);
  }

  function AdapterboxCreate(){
    $this->app->Tpl->Set("HEADING","Adapterbox (Anlegen)");
      $this->app->PageBuilder->CreateGen("adapterbox_create.tpl");
  }

  function AdapterboxEdit(){
    $this->app->Tpl->Set("HEADING","Adapterbox (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("adapterbox_edit.tpl");
  }

  function AdapterboxCopy(){
    $this->app->Tpl->Set("HEADING","Adapterbox (Kopieren)");
      $this->app->PageBuilder->CreateGen("adapterbox_copy.tpl");
  }

  function AdapterboxDelete(){
    $this->app->Tpl->Set("HEADING","Adapterbox (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("adapterbox_delete.tpl");
  }

  function AdapterboxList(){
    $this->app->Tpl->Set("HEADING","Adapterbox (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("adapterbox_list.tpl");
  }

} 
?>