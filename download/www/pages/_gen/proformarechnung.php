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

class GenProformarechnung { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","ProformarechnungCreate");
    $this->app->ActionHandler("edit","ProformarechnungEdit");
    $this->app->ActionHandler("copy","ProformarechnungCopy");
    $this->app->ActionHandler("list","ProformarechnungList");
    $this->app->ActionHandler("delete","ProformarechnungDelete");

    $this->app->Tpl->Set("HEADING","Proformarechnung");    //$this->app->ActionHandlerListen($app);
  }

  function ProformarechnungCreate(){
    $this->app->Tpl->Set("HEADING","Proformarechnung (Anlegen)");
      $this->app->PageBuilder->CreateGen("proformarechnung_create.tpl");
  }

  function ProformarechnungEdit(){
    $this->app->Tpl->Set("HEADING","Proformarechnung (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("proformarechnung_edit.tpl");
  }

  function ProformarechnungCopy(){
    $this->app->Tpl->Set("HEADING","Proformarechnung (Kopieren)");
      $this->app->PageBuilder->CreateGen("proformarechnung_copy.tpl");
  }

  function ProformarechnungDelete(){
    $this->app->Tpl->Set("HEADING","Proformarechnung (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("proformarechnung_delete.tpl");
  }

  function ProformarechnungList(){
    $this->app->Tpl->Set("HEADING","Proformarechnung (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("proformarechnung_list.tpl");
  }

} 
?>