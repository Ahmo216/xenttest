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

class GenPreisanfrage { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","PreisanfrageCreate");
    $this->app->ActionHandler("edit","PreisanfrageEdit");
    $this->app->ActionHandler("copy","PreisanfrageCopy");
    $this->app->ActionHandler("list","PreisanfrageList");
    $this->app->ActionHandler("delete","PreisanfrageDelete");

    $this->app->Tpl->Set("HEADING","Preisanfrage");    //$this->app->ActionHandlerListen($app);
  }

  function PreisanfrageCreate(){
    $this->app->Tpl->Set("HEADING","Preisanfrage (Anlegen)");
      $this->app->PageBuilder->CreateGen("preisanfrage_create.tpl");
  }

  function PreisanfrageEdit(){
    $this->app->Tpl->Set("HEADING","Preisanfrage (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("preisanfrage_edit.tpl");
  }

  function PreisanfrageCopy(){
    $this->app->Tpl->Set("HEADING","Preisanfrage (Kopieren)");
      $this->app->PageBuilder->CreateGen("preisanfrage_copy.tpl");
  }

  function PreisanfrageDelete(){
    $this->app->Tpl->Set("HEADING","Preisanfrage (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("preisanfrage_delete.tpl");
  }

  function PreisanfrageList(){
    $this->app->Tpl->Set("HEADING","Preisanfrage (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("preisanfrage_list.tpl");
  }

} 
?>