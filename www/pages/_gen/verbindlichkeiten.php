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

class GenVerbindlichkeiten { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","VerbindlichkeitenCreate");
    $this->app->ActionHandler("edit","VerbindlichkeitenEdit");
    $this->app->ActionHandler("copy","VerbindlichkeitenCopy");
    $this->app->ActionHandler("list","VerbindlichkeitenList");
    $this->app->ActionHandler("delete","VerbindlichkeitenDelete");

    $this->app->Tpl->Set("HEADING","Verbindlichkeiten");    //$this->app->ActionHandlerListen($app);
  }

  function VerbindlichkeitenCreate(){
    $this->app->Tpl->Set("HEADING","Verbindlichkeiten (Anlegen)");
      $this->app->PageBuilder->CreateGen("verbindlichkeiten_create.tpl");
  }

  function VerbindlichkeitenEdit(){
    $this->app->Tpl->Set("HEADING","Verbindlichkeiten (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("verbindlichkeiten_edit.tpl");
  }

  function VerbindlichkeitenCopy(){
    $this->app->Tpl->Set("HEADING","Verbindlichkeiten (Kopieren)");
      $this->app->PageBuilder->CreateGen("verbindlichkeiten_copy.tpl");
  }

  function VerbindlichkeitenDelete(){
    $this->app->Tpl->Set("HEADING","Verbindlichkeiten (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("verbindlichkeiten_delete.tpl");
  }

  function VerbindlichkeitenList(){
    $this->app->Tpl->Set("HEADING","Verbindlichkeiten (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("verbindlichkeiten_list.tpl");
  }

} 
?>