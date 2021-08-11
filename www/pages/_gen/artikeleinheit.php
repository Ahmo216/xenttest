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

class GenArtikeleinheit { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","ArtikeleinheitCreate");
    $this->app->ActionHandler("edit","ArtikeleinheitEdit");
    $this->app->ActionHandler("copy","ArtikeleinheitCopy");
    $this->app->ActionHandler("list","ArtikeleinheitList");
    $this->app->ActionHandler("delete","ArtikeleinheitDelete");

    $this->app->Tpl->Set("HEADING","Artikeleinheit");    //$this->app->ActionHandlerListen($app);
  }

  function ArtikeleinheitCreate(){
    $this->app->Tpl->Set("HEADING","Artikeleinheit (Anlegen)");
      $this->app->PageBuilder->CreateGen("artikeleinheit_create.tpl");
  }

  function ArtikeleinheitEdit(){
    $this->app->Tpl->Set("HEADING","Artikeleinheit (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("artikeleinheit_edit.tpl");
  }

  function ArtikeleinheitCopy(){
    $this->app->Tpl->Set("HEADING","Artikeleinheit (Kopieren)");
      $this->app->PageBuilder->CreateGen("artikeleinheit_copy.tpl");
  }

  function ArtikeleinheitDelete(){
    $this->app->Tpl->Set("HEADING","Artikeleinheit (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("artikeleinheit_delete.tpl");
  }

  function ArtikeleinheitList(){
    $this->app->Tpl->Set("HEADING","Artikeleinheit (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("artikeleinheit_list.tpl");
  }

} 
?>