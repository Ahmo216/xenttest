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

class GenDatei_Stichwortvorlagen { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","Datei_StichwortvorlagenCreate");
    $this->app->ActionHandler("edit","Datei_StichwortvorlagenEdit");
    $this->app->ActionHandler("copy","Datei_StichwortvorlagenCopy");
    $this->app->ActionHandler("list","Datei_StichwortvorlagenList");
    $this->app->ActionHandler("delete","Datei_StichwortvorlagenDelete");

    $this->app->Tpl->Set("HEADING","Datei_Stichwortvorlagen");    //$this->app->ActionHandlerListen($app);
  }

  function Datei_StichwortvorlagenCreate(){
    $this->app->Tpl->Set("HEADING","Datei_Stichwortvorlagen (Anlegen)");
      $this->app->PageBuilder->CreateGen("datei_stichwortvorlagen_create.tpl");
  }

  function Datei_StichwortvorlagenEdit(){
    $this->app->Tpl->Set("HEADING","Datei_Stichwortvorlagen (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("datei_stichwortvorlagen_edit.tpl");
  }

  function Datei_StichwortvorlagenCopy(){
    $this->app->Tpl->Set("HEADING","Datei_Stichwortvorlagen (Kopieren)");
      $this->app->PageBuilder->CreateGen("datei_stichwortvorlagen_copy.tpl");
  }

  function Datei_StichwortvorlagenDelete(){
    $this->app->Tpl->Set("HEADING","Datei_Stichwortvorlagen (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("datei_stichwortvorlagen_delete.tpl");
  }

  function Datei_StichwortvorlagenList(){
    $this->app->Tpl->Set("HEADING","Datei_Stichwortvorlagen (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("datei_stichwortvorlagen_list.tpl");
  }

} 
?>