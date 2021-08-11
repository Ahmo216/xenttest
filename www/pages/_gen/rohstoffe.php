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

class GenRohstoffe { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","RohstoffeCreate");
    $this->app->ActionHandler("edit","RohstoffeEdit");
    $this->app->ActionHandler("copy","RohstoffeCopy");
    $this->app->ActionHandler("list","RohstoffeList");
    $this->app->ActionHandler("delete","RohstoffeDelete");

    $this->app->Tpl->Set("HEADING","Rohstoffe");    //$this->app->ActionHandlerListen($app);
  }

  function RohstoffeCreate(){
    $this->app->Tpl->Set("HEADING","Rohstoffe (Anlegen)");
      $this->app->PageBuilder->CreateGen("rohstoffe_create.tpl");
  }

  function RohstoffeEdit(){
    $this->app->Tpl->Set("HEADING","Rohstoffe (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("rohstoffe_edit.tpl");
  }

  function RohstoffeCopy(){
    $this->app->Tpl->Set("HEADING","Rohstoffe (Kopieren)");
      $this->app->PageBuilder->CreateGen("rohstoffe_copy.tpl");
  }

  function RohstoffeDelete(){
    $this->app->Tpl->Set("HEADING","Rohstoffe (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("rohstoffe_delete.tpl");
  }

  function RohstoffeList(){
    $this->app->Tpl->Set("HEADING","Rohstoffe (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("rohstoffe_list.tpl");
  }

} 
?>