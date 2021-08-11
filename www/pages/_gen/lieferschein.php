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

class GenLieferschein { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","LieferscheinCreate");
    $this->app->ActionHandler("edit","LieferscheinEdit");
    $this->app->ActionHandler("copy","LieferscheinCopy");
    $this->app->ActionHandler("list","LieferscheinList");
    $this->app->ActionHandler("delete","LieferscheinDelete");

    $this->app->Tpl->Set("HEADING","Lieferschein");    //$this->app->ActionHandlerListen($app);
  }

  function LieferscheinCreate(){
    $this->app->Tpl->Set("HEADING","Lieferschein (Anlegen)");
      $this->app->PageBuilder->CreateGen("lieferschein_create.tpl");
  }

  function LieferscheinEdit(){
    $this->app->Tpl->Set("HEADING","Lieferschein (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("lieferschein_edit.tpl");
  }

  function LieferscheinCopy(){
    $this->app->Tpl->Set("HEADING","Lieferschein (Kopieren)");
      $this->app->PageBuilder->CreateGen("lieferschein_copy.tpl");
  }

  function LieferscheinDelete(){
    $this->app->Tpl->Set("HEADING","Lieferschein (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("lieferschein_delete.tpl");
  }

  function LieferscheinList(){
    $this->app->Tpl->Set("HEADING","Lieferschein (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("lieferschein_list.tpl");
  }

} 
?>