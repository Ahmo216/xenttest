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

class GenAdresse_Typ { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","Adresse_TypCreate");
    $this->app->ActionHandler("edit","Adresse_TypEdit");
    $this->app->ActionHandler("copy","Adresse_TypCopy");
    $this->app->ActionHandler("list","Adresse_TypList");
    $this->app->ActionHandler("delete","Adresse_TypDelete");

    $this->app->Tpl->Set("HEADING","Adresse_Typ");    //$this->app->ActionHandlerListen($app);
  }

  function Adresse_TypCreate(){
    $this->app->Tpl->Set("HEADING","Adresse_Typ (Anlegen)");
      $this->app->PageBuilder->CreateGen("adresse_typ_create.tpl");
  }

  function Adresse_TypEdit(){
    $this->app->Tpl->Set("HEADING","Adresse_Typ (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("adresse_typ_edit.tpl");
  }

  function Adresse_TypCopy(){
    $this->app->Tpl->Set("HEADING","Adresse_Typ (Kopieren)");
      $this->app->PageBuilder->CreateGen("adresse_typ_copy.tpl");
  }

  function Adresse_TypDelete(){
    $this->app->Tpl->Set("HEADING","Adresse_Typ (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("adresse_typ_delete.tpl");
  }

  function Adresse_TypList(){
    $this->app->Tpl->Set("HEADING","Adresse_Typ (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("adresse_typ_list.tpl");
  }

} 
?>