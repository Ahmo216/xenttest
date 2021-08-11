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

class GenSpedition { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","SpeditionCreate");
    $this->app->ActionHandler("edit","SpeditionEdit");
    $this->app->ActionHandler("copy","SpeditionCopy");
    $this->app->ActionHandler("list","SpeditionList");
    $this->app->ActionHandler("delete","SpeditionDelete");

    $this->app->Tpl->Set("HEADING","Spedition");    //$this->app->ActionHandlerListen($app);
  }

  function SpeditionCreate(){
    $this->app->Tpl->Set("HEADING","Spedition (Anlegen)");
      $this->app->PageBuilder->CreateGen("spedition_create.tpl");
  }

  function SpeditionEdit(){
    $this->app->Tpl->Set("HEADING","Spedition (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("spedition_edit.tpl");
  }

  function SpeditionCopy(){
    $this->app->Tpl->Set("HEADING","Spedition (Kopieren)");
      $this->app->PageBuilder->CreateGen("spedition_copy.tpl");
  }

  function SpeditionDelete(){
    $this->app->Tpl->Set("HEADING","Spedition (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("spedition_delete.tpl");
  }

  function SpeditionList(){
    $this->app->Tpl->Set("HEADING","Spedition (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("spedition_list.tpl");
  }

} 
?>