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

class GenKostenstellen { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","KostenstellenCreate");
    $this->app->ActionHandler("edit","KostenstellenEdit");
    $this->app->ActionHandler("copy","KostenstellenCopy");
    $this->app->ActionHandler("list","KostenstellenList");
    $this->app->ActionHandler("delete","KostenstellenDelete");

    $this->app->Tpl->Set('HEADING',"Kostenstellen");    $this->app->ActionHandlerListen($app);
  }

  function KostenstellenCreate(){
    $this->app->Tpl->Set('HEADING',"Kostenstellen (Anlegen)");
      $this->app->PageBuilder->CreateGen("kostenstellen_create.tpl");
  }

  function KostenstellenEdit(){
    $this->app->Tpl->Set('HEADING',"Kostenstellen (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("kostenstellen_edit.tpl");
  }

  function KostenstellenCopy(){
    $this->app->Tpl->Set('HEADING',"Kostenstellen (Kopieren)");
      $this->app->PageBuilder->CreateGen("kostenstellen_copy.tpl");
  }

  function KostenstellenDelete(){
    $this->app->Tpl->Set('HEADING',"Kostenstellen (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("kostenstellen_delete.tpl");
  }

  function KostenstellenList(){
    $this->app->Tpl->Set('HEADING',"Kostenstellen (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("kostenstellen_list.tpl");
  }

} 
?>