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

class GenBestellung { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","BestellungCreate");
    $this->app->ActionHandler("edit","BestellungEdit");
    $this->app->ActionHandler("copy","BestellungCopy");
    $this->app->ActionHandler("list","BestellungList");
    $this->app->ActionHandler("delete","BestellungDelete");

    $this->app->Tpl->Set("HEADING","Bestellung");    //$this->app->ActionHandlerListen($app);
  }

  function BestellungCreate(){
    $this->app->Tpl->Set("HEADING","Bestellung (Anlegen)");
      $this->app->PageBuilder->CreateGen("bestellung_create.tpl");
  }

  function BestellungEdit(){
    $this->app->Tpl->Set("HEADING","Bestellung (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("bestellung_edit.tpl");
  }

  function BestellungCopy(){
    $this->app->Tpl->Set("HEADING","Bestellung (Kopieren)");
      $this->app->PageBuilder->CreateGen("bestellung_copy.tpl");
  }

  function BestellungDelete(){
    $this->app->Tpl->Set("HEADING","Bestellung (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("bestellung_delete.tpl");
  }

  function BestellungList(){
    $this->app->Tpl->Set("HEADING","Bestellung (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("bestellung_list.tpl");
  }

} 
?>