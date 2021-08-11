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

class GenZahlungsweisen { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","ZahlungsweisenCreate");
    $this->app->ActionHandler("edit","ZahlungsweisenEdit");
    $this->app->ActionHandler("copy","ZahlungsweisenCopy");
    $this->app->ActionHandler("list","ZahlungsweisenList");
    $this->app->ActionHandler("delete","ZahlungsweisenDelete");

    $this->app->Tpl->Set("HEADING","Zahlungsweisen");    //$this->app->ActionHandlerListen($app);
  }

  function ZahlungsweisenCreate(){
    $this->app->Tpl->Set("HEADING","Zahlungsweisen (Anlegen)");
      $this->app->PageBuilder->CreateGen("zahlungsweisen_create.tpl");
  }

  function ZahlungsweisenEdit(){
    $this->app->Tpl->Set("HEADING","Zahlungsweisen (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("zahlungsweisen_edit.tpl");
  }

  function ZahlungsweisenCopy(){
    $this->app->Tpl->Set("HEADING","Zahlungsweisen (Kopieren)");
      $this->app->PageBuilder->CreateGen("zahlungsweisen_copy.tpl");
  }

  function ZahlungsweisenDelete(){
    $this->app->Tpl->Set("HEADING","Zahlungsweisen (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("zahlungsweisen_delete.tpl");
  }

  function ZahlungsweisenList(){
    $this->app->Tpl->Set("HEADING","Zahlungsweisen (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("zahlungsweisen_list.tpl");
  }

} 
?>