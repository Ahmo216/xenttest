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

class GenVerrechnungsart { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","VerrechnungsartCreate");
    $this->app->ActionHandler("edit","VerrechnungsartEdit");
    $this->app->ActionHandler("copy","VerrechnungsartCopy");
    $this->app->ActionHandler("list","VerrechnungsartList");
    $this->app->ActionHandler("delete","VerrechnungsartDelete");

    $this->app->Tpl->Set('HEADING',"Verrechnungsart");    $this->app->ActionHandlerListen($app);
  }

  function VerrechnungsartCreate(){
    $this->app->Tpl->Set('HEADING',"Verrechnungsart (Anlegen)");
      $this->app->PageBuilder->CreateGen("verrechnungsart_create.tpl");
  }

  function VerrechnungsartEdit(){
    $this->app->Tpl->Set('HEADING',"Verrechnungsart (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("verrechnungsart_edit.tpl");
  }

  function VerrechnungsartCopy(){
    $this->app->Tpl->Set('HEADING',"Verrechnungsart (Kopieren)");
      $this->app->PageBuilder->CreateGen("verrechnungsart_copy.tpl");
  }

  function VerrechnungsartDelete(){
    $this->app->Tpl->Set('HEADING',"Verrechnungsart (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("verrechnungsart_delete.tpl");
  }

  function VerrechnungsartList(){
    $this->app->Tpl->Set('HEADING',"Verrechnungsart (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("verrechnungsart_list.tpl");
  }

} 
?>