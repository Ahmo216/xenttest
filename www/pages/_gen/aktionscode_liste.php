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

class GenAktionscode_Liste { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","Aktionscode_ListeCreate");
    $this->app->ActionHandler("edit","Aktionscode_ListeEdit");
    $this->app->ActionHandler("copy","Aktionscode_ListeCopy");
    $this->app->ActionHandler("list","Aktionscode_ListeList");
    $this->app->ActionHandler("delete","Aktionscode_ListeDelete");

    $this->app->Tpl->Set('HEADING',"Aktionscode_Liste");    $this->app->ActionHandlerListen($app);
  }

  function Aktionscode_ListeCreate(){
    $this->app->Tpl->Set('HEADING',"Aktionscode_Liste (Anlegen)");
      $this->app->PageBuilder->CreateGen("aktionscode_liste_create.tpl");
  }

  function Aktionscode_ListeEdit(){
    $this->app->Tpl->Set('HEADING',"Aktionscode_Liste (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("aktionscode_liste_edit.tpl");
  }

  function Aktionscode_ListeCopy(){
    $this->app->Tpl->Set('HEADING',"Aktionscode_Liste (Kopieren)");
      $this->app->PageBuilder->CreateGen("aktionscode_liste_copy.tpl");
  }

  function Aktionscode_ListeDelete(){
    $this->app->Tpl->Set('HEADING',"Aktionscode_Liste (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("aktionscode_liste_delete.tpl");
  }

  function Aktionscode_ListeList(){
    $this->app->Tpl->Set('HEADING',"Aktionscode_Liste (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("aktionscode_liste_list.tpl");
  }

} 
?>