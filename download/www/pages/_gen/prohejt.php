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

class GenProhejt { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","ProhejtCreate");
    $this->app->ActionHandler("edit","ProhejtEdit");
    $this->app->ActionHandler("copy","ProhejtCopy");
    $this->app->ActionHandler("list","ProhejtList");
    $this->app->ActionHandler("delete","ProhejtDelete");

    $this->app->Tpl->Set("HEADING","Prohejt");    //$this->app->ActionHandlerListen($app);
  }

  function ProhejtCreate(){
    $this->app->Tpl->Set("HEADING","Prohejt (Anlegen)");
      $this->app->PageBuilder->CreateGen("prohejt_create.tpl");
  }

  function ProhejtEdit(){
    $this->app->Tpl->Set("HEADING","Prohejt (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("prohejt_edit.tpl");
  }

  function ProhejtCopy(){
    $this->app->Tpl->Set("HEADING","Prohejt (Kopieren)");
      $this->app->PageBuilder->CreateGen("prohejt_copy.tpl");
  }

  function ProhejtDelete(){
    $this->app->Tpl->Set("HEADING","Prohejt (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("prohejt_delete.tpl");
  }

  function ProhejtList(){
    $this->app->Tpl->Set("HEADING","Prohejt (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("prohejt_list.tpl");
  }

} 
?>