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

class GenStueckliste { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","StuecklisteCreate");
    $this->app->ActionHandler("edit","StuecklisteEdit");
    $this->app->ActionHandler("copy","StuecklisteCopy");
    $this->app->ActionHandler("list","StuecklisteList");
    $this->app->ActionHandler("delete","StuecklisteDelete");

    $this->app->Tpl->Set("HEADING","Stueckliste");    //$this->app->ActionHandlerListen($app);
  }

  function StuecklisteCreate(){
    $this->app->Tpl->Set("HEADING","Stueckliste (Anlegen)");
      $this->app->PageBuilder->CreateGen("stueckliste_create.tpl");
  }

  function StuecklisteEdit(){
    $this->app->Tpl->Set("HEADING","Stueckliste (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("stueckliste_edit.tpl");
  }

  function StuecklisteCopy(){
    $this->app->Tpl->Set("HEADING","Stueckliste (Kopieren)");
      $this->app->PageBuilder->CreateGen("stueckliste_copy.tpl");
  }

  function StuecklisteDelete(){
    $this->app->Tpl->Set("HEADING","Stueckliste (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("stueckliste_delete.tpl");
  }

  function StuecklisteList(){
    $this->app->Tpl->Set("HEADING","Stueckliste (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("stueckliste_list.tpl");
  }

} 
?>