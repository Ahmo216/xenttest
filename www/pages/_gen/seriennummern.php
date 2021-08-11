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

class GenSeriennummern { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","SeriennummernCreate");
    $this->app->ActionHandler("edit","SeriennummernEdit");
    $this->app->ActionHandler("copy","SeriennummernCopy");
    $this->app->ActionHandler("list","SeriennummernList");
    $this->app->ActionHandler("delete","SeriennummernDelete");

    $this->app->Tpl->Set("HEADING","Seriennummern");    //$this->app->ActionHandlerListen($app);
  }

  function SeriennummernCreate(){
    $this->app->Tpl->Set("HEADING","Seriennummern (Anlegen)");
      $this->app->PageBuilder->CreateGen("seriennummern_create.tpl");
  }

  function SeriennummernEdit(){
    $this->app->Tpl->Set("HEADING","Seriennummern (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("seriennummern_edit.tpl");
  }

  function SeriennummernCopy(){
    $this->app->Tpl->Set("HEADING","Seriennummern (Kopieren)");
      $this->app->PageBuilder->CreateGen("seriennummern_copy.tpl");
  }

  function SeriennummernDelete(){
    $this->app->Tpl->Set("HEADING","Seriennummern (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("seriennummern_delete.tpl");
  }

  function SeriennummernList(){
    $this->app->Tpl->Set("HEADING","Seriennummern (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("seriennummern_list.tpl");
  }

} 
?>