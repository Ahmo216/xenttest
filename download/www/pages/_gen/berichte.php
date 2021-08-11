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

class GenBerichte { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","BerichteCreate");
    $this->app->ActionHandler("edit","BerichteEdit");
    $this->app->ActionHandler("copy","BerichteCopy");
    $this->app->ActionHandler("list","BerichteList");
    $this->app->ActionHandler("delete","BerichteDelete");

    $this->app->Tpl->Set("HEADING","Berichte");    //$this->app->ActionHandlerListen($app);
  }

  function BerichteCreate(){
    $this->app->Tpl->Set("HEADING","Berichte (Anlegen)");
      $this->app->PageBuilder->CreateGen("berichte_create.tpl");
  }

  function BerichteEdit(){
    $this->app->Tpl->Set("HEADING","Berichte (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("berichte_edit.tpl");
  }

  function BerichteCopy(){
    $this->app->Tpl->Set("HEADING","Berichte (Kopieren)");
      $this->app->PageBuilder->CreateGen("berichte_copy.tpl");
  }

  function BerichteDelete(){
    $this->app->Tpl->Set("HEADING","Berichte (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("berichte_delete.tpl");
  }

  function BerichteList(){
    $this->app->Tpl->Set("HEADING","Berichte (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("berichte_list.tpl");
  }

} 
?>