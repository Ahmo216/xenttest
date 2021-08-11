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

class GenLieferadresse { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","LieferadresseCreate");
    $this->app->ActionHandler("edit","LieferadresseEdit");
    $this->app->ActionHandler("copy","LieferadresseCopy");
    $this->app->ActionHandler("list","LieferadresseList");
    $this->app->ActionHandler("delete","LieferadresseDelete");

    $this->app->Tpl->Set("HEADING","Lieferadresse");    //$this->app->ActionHandlerListen($app);
  }

  function LieferadresseCreate(){
    $this->app->Tpl->Set("HEADING","Lieferadresse (Anlegen)");
      $this->app->PageBuilder->CreateGen("lieferadresse_create.tpl");
  }

  function LieferadresseEdit(){
    $this->app->Tpl->Set("HEADING","Lieferadresse (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("lieferadresse_edit.tpl");
  }

  function LieferadresseCopy(){
    $this->app->Tpl->Set("HEADING","Lieferadresse (Kopieren)");
      $this->app->PageBuilder->CreateGen("lieferadresse_copy.tpl");
  }

  function LieferadresseDelete(){
    $this->app->Tpl->Set("HEADING","Lieferadresse (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("lieferadresse_delete.tpl");
  }

  function LieferadresseList(){
    $this->app->Tpl->Set("HEADING","Lieferadresse (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("lieferadresse_list.tpl");
  }

} 
?>