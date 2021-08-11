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

class GenArtikeleigenschaften { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","ArtikeleigenschaftenCreate");
    $this->app->ActionHandler("edit","ArtikeleigenschaftenEdit");
    $this->app->ActionHandler("copy","ArtikeleigenschaftenCopy");
    $this->app->ActionHandler("list","ArtikeleigenschaftenList");
    $this->app->ActionHandler("delete","ArtikeleigenschaftenDelete");

    $this->app->Tpl->Set("HEADING","Artikeleigenschaften");    //$this->app->ActionHandlerListen($app);
  }

  function ArtikeleigenschaftenCreate(){
    $this->app->Tpl->Set("HEADING","Artikeleigenschaften (Anlegen)");
      $this->app->PageBuilder->CreateGen("artikeleigenschaften_create.tpl");
  }

  function ArtikeleigenschaftenEdit(){
    $this->app->Tpl->Set("HEADING","Artikeleigenschaften (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("artikeleigenschaften_edit.tpl");
  }

  function ArtikeleigenschaftenCopy(){
    $this->app->Tpl->Set("HEADING","Artikeleigenschaften (Kopieren)");
      $this->app->PageBuilder->CreateGen("artikeleigenschaften_copy.tpl");
  }

  function ArtikeleigenschaftenDelete(){
    $this->app->Tpl->Set("HEADING","Artikeleigenschaften (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("artikeleigenschaften_delete.tpl");
  }

  function ArtikeleigenschaftenList(){
    $this->app->Tpl->Set("HEADING","Artikeleigenschaften (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("artikeleigenschaften_list.tpl");
  }

} 
?>