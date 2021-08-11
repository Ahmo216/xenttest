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

class GenEtiketten { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","EtikettenCreate");
    $this->app->ActionHandler("edit","EtikettenEdit");
    $this->app->ActionHandler("copy","EtikettenCopy");
    $this->app->ActionHandler("list","EtikettenList");
    $this->app->ActionHandler("delete","EtikettenDelete");

    $this->app->Tpl->Set("HEADING","Etiketten");    //$this->app->ActionHandlerListen($app);
  }

  function EtikettenCreate(){
    $this->app->Tpl->Set("HEADING","Etiketten (Anlegen)");
      $this->app->PageBuilder->CreateGen("etiketten_create.tpl");
  }

  function EtikettenEdit(){
    $this->app->Tpl->Set("HEADING","Etiketten (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("etiketten_edit.tpl");
  }

  function EtikettenCopy(){
    $this->app->Tpl->Set("HEADING","Etiketten (Kopieren)");
      $this->app->PageBuilder->CreateGen("etiketten_copy.tpl");
  }

  function EtikettenDelete(){
    $this->app->Tpl->Set("HEADING","Etiketten (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("etiketten_delete.tpl");
  }

  function EtikettenList(){
    $this->app->Tpl->Set("HEADING","Etiketten (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("etiketten_list.tpl");
  }

} 
?>