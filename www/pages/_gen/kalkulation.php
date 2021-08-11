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

class GenKalkulation { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","KalkulationCreate");
    $this->app->ActionHandler("edit","KalkulationEdit");
    $this->app->ActionHandler("copy","KalkulationCopy");
    $this->app->ActionHandler("list","KalkulationList");
    $this->app->ActionHandler("delete","KalkulationDelete");

    $this->app->Tpl->Set("HEADING","Kalkulation");    //$this->app->ActionHandlerListen($app);
  }

  function KalkulationCreate(){
    $this->app->Tpl->Set("HEADING","Kalkulation (Anlegen)");
      $this->app->PageBuilder->CreateGen("kalkulation_create.tpl");
  }

  function KalkulationEdit(){
    $this->app->Tpl->Set("HEADING","Kalkulation (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("kalkulation_edit.tpl");
  }

  function KalkulationCopy(){
    $this->app->Tpl->Set("HEADING","Kalkulation (Kopieren)");
      $this->app->PageBuilder->CreateGen("kalkulation_copy.tpl");
  }

  function KalkulationDelete(){
    $this->app->Tpl->Set("HEADING","Kalkulation (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("kalkulation_delete.tpl");
  }

  function KalkulationList(){
    $this->app->Tpl->Set("HEADING","Kalkulation (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("kalkulation_list.tpl");
  }

} 
?>