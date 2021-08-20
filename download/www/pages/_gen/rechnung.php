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

include_once __DIR__.'/appgeneric.php';

class GenRechnung extends AppGeneric {

  function __construct(&$app) {

    parent::__construct($app);

    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","RechnungCreate");
    $this->app->ActionHandler("edit","RechnungEdit");
    $this->app->ActionHandler("copy","RechnungCopy");
    $this->app->ActionHandler("list","RechnungList");
    $this->app->ActionHandler("delete","RechnungDelete");

    $this->app->Tpl->Set("HEADING","Rechnung");    //$this->app->ActionHandlerListen($app);
  }

  function RechnungCreate(){
    $this->app->Tpl->Set("HEADING","Rechnung (Anlegen)");
      $this->app->PageBuilder->CreateGen("rechnung_create.tpl");
  }

  function RechnungEdit(){
    $this->app->Tpl->Set("HEADING","Rechnung (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("rechnung_edit.tpl");
  }

  function RechnungCopy(){
    $this->app->Tpl->Set("HEADING","Rechnung (Kopieren)");
      $this->app->PageBuilder->CreateGen("rechnung_copy.tpl");
  }

  function RechnungDelete(){
    $this->app->Tpl->Set("HEADING","Rechnung (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("rechnung_delete.tpl");
  }

  function RechnungList(){
    $this->app->Tpl->Set("HEADING","Rechnung (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("rechnung_list.tpl");
  }

} 
?>