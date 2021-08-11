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

class GenArbeitsnachweis_Position { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","Arbeitsnachweis_PositionCreate");
    $this->app->ActionHandler("edit","Arbeitsnachweis_PositionEdit");
    $this->app->ActionHandler("copy","Arbeitsnachweis_PositionCopy");
    $this->app->ActionHandler("list","Arbeitsnachweis_PositionList");
    $this->app->ActionHandler("delete","Arbeitsnachweis_PositionDelete");

    $this->app->Tpl->Set('HEADING',"Arbeitsnachweis_Position");    $this->app->ActionHandlerListen($app);
  }

  function Arbeitsnachweis_PositionCreate(){
    $this->app->Tpl->Set('HEADING',"Arbeitsnachweis_Position (Anlegen)");
      $this->app->PageBuilder->CreateGen("arbeitsnachweis_position_create.tpl");
  }

  function Arbeitsnachweis_PositionEdit(){
    $this->app->Tpl->Set('HEADING',"Arbeitsnachweis_Position (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("arbeitsnachweis_position_edit.tpl");
  }

  function Arbeitsnachweis_PositionCopy(){
    $this->app->Tpl->Set('HEADING',"Arbeitsnachweis_Position (Kopieren)");
      $this->app->PageBuilder->CreateGen("arbeitsnachweis_position_copy.tpl");
  }

  function Arbeitsnachweis_PositionDelete(){
    $this->app->Tpl->Set('HEADING',"Arbeitsnachweis_Position (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("arbeitsnachweis_position_delete.tpl");
  }

  function Arbeitsnachweis_PositionList(){
    $this->app->Tpl->Set('HEADING',"Arbeitsnachweis_Position (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("arbeitsnachweis_position_list.tpl");
  }

} 
?>