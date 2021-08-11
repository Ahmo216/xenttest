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

class GenZolltarifnummer { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","ZolltarifnummerCreate");
    $this->app->ActionHandler("edit","ZolltarifnummerEdit");
    $this->app->ActionHandler("copy","ZolltarifnummerCopy");
    $this->app->ActionHandler("list","ZolltarifnummerList");
    $this->app->ActionHandler("delete","ZolltarifnummerDelete");

    $this->app->Tpl->Set("HEADING","Zolltarifnummer");    //$this->app->ActionHandlerListen($app);
  }

  function ZolltarifnummerCreate(){
    $this->app->Tpl->Set("HEADING","Zolltarifnummer (Anlegen)");
      $this->app->PageBuilder->CreateGen("zolltarifnummer_create.tpl");
  }

  function ZolltarifnummerEdit(){
    $this->app->Tpl->Set("HEADING","Zolltarifnummer (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("zolltarifnummer_edit.tpl");
  }

  function ZolltarifnummerCopy(){
    $this->app->Tpl->Set("HEADING","Zolltarifnummer (Kopieren)");
      $this->app->PageBuilder->CreateGen("zolltarifnummer_copy.tpl");
  }

  function ZolltarifnummerDelete(){
    $this->app->Tpl->Set("HEADING","Zolltarifnummer (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("zolltarifnummer_delete.tpl");
  }

  function ZolltarifnummerList(){
    $this->app->Tpl->Set("HEADING","Zolltarifnummer (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("zolltarifnummer_list.tpl");
  }

} 
?>