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

class GenVersandarten { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","VersandartenCreate");
    $this->app->ActionHandler("edit","VersandartenEdit");
    $this->app->ActionHandler("copy","VersandartenCopy");
    $this->app->ActionHandler("list","VersandartenList");
    $this->app->ActionHandler("delete","VersandartenDelete");

    $this->app->Tpl->Set("HEADING","Versandarten");    //$this->app->ActionHandlerListen($app);
  }

  function VersandartenCreate(){
    $this->app->Tpl->Set("HEADING","Versandarten (Anlegen)");
      $this->app->PageBuilder->CreateGen("versandarten_create.tpl");
  }

  function VersandartenEdit(){
    $this->app->Tpl->Set("HEADING","Versandarten (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("versandarten_edit.tpl");
  }

  function VersandartenCopy(){
    $this->app->Tpl->Set("HEADING","Versandarten (Kopieren)");
      $this->app->PageBuilder->CreateGen("versandarten_copy.tpl");
  }

  function VersandartenDelete(){
    $this->app->Tpl->Set("HEADING","Versandarten (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("versandarten_delete.tpl");
  }

  function VersandartenList(){
    $this->app->Tpl->Set("HEADING","Versandarten (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("versandarten_list.tpl");
  }

} 
?>