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

class GenLieferadressen { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","LieferadressenCreate");
    $this->app->ActionHandler("edit","LieferadressenEdit");
    $this->app->ActionHandler("copy","LieferadressenCopy");
    $this->app->ActionHandler("list","LieferadressenList");
    $this->app->ActionHandler("delete","LieferadressenDelete");

    $this->app->Tpl->Set("HEADING","Lieferadressen");    //$this->app->ActionHandlerListen($app);
  }

  function LieferadressenCreate(){
    $this->app->Tpl->Set("HEADING","Lieferadressen (Anlegen)");
      $this->app->PageBuilder->CreateGen("lieferadressen_create.tpl");
  }

  function LieferadressenEdit(){
    $this->app->Tpl->Set("HEADING","Lieferadressen (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("lieferadressen_edit.tpl");
  }

  function LieferadressenCopy(){
    $this->app->Tpl->Set("HEADING","Lieferadressen (Kopieren)");
      $this->app->PageBuilder->CreateGen("lieferadressen_copy.tpl");
  }

  function LieferadressenDelete(){
    $this->app->Tpl->Set("HEADING","Lieferadressen (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("lieferadressen_delete.tpl");
  }

  function LieferadressenList(){
    $this->app->Tpl->Set("HEADING","Lieferadressen (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("lieferadressen_list.tpl");
  }

} 
?>