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

class GenShopexport { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","ShopexportCreate");
    $this->app->ActionHandler("edit","ShopexportEdit");
    $this->app->ActionHandler("copy","ShopexportCopy");
    $this->app->ActionHandler("list","ShopexportList");
    $this->app->ActionHandler("delete","ShopexportDelete");

    $this->app->Tpl->Set("HEADING","Shopexport");    //$this->app->ActionHandlerListen($app);
  }

  function ShopexportCreate(){
    $this->app->Tpl->Set("HEADING","Shopexport (Anlegen)");
      $this->app->PageBuilder->CreateGen("shopexport_create.tpl");
  }

  function ShopexportEdit(){
    $this->app->Tpl->Set("HEADING","Shopexport (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("shopexport_edit.tpl");
  }

  function ShopexportCopy(){
    $this->app->Tpl->Set("HEADING","Shopexport (Kopieren)");
      $this->app->PageBuilder->CreateGen("shopexport_copy.tpl");
  }

  function ShopexportDelete(){
    $this->app->Tpl->Set("HEADING","Shopexport (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("shopexport_delete.tpl");
  }

  function ShopexportList(){
    $this->app->Tpl->Set("HEADING","Shopexport (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("shopexport_list.tpl");
  }

} 
?>