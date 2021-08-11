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

class GenUser { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","UserCreate");
    $this->app->ActionHandler("edit","UserEdit");
    $this->app->ActionHandler("copy","UserCopy");
    $this->app->ActionHandler("list","UserList");
    $this->app->ActionHandler("delete","UserDelete");

    $this->app->Tpl->Set('HEADING',"User");    $this->app->ActionHandlerListen($app);
  }

  function UserCreate(){
    $this->app->Tpl->Set('HEADING',"User (Anlegen)");
      $this->app->PageBuilder->CreateGen("user_create.tpl");
  }

  function UserEdit(){
    $this->app->Tpl->Set('HEADING',"User (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("user_edit.tpl");
  }

  function UserCopy(){
    $this->app->Tpl->Set('HEADING',"User (Kopieren)");
      $this->app->PageBuilder->CreateGen("user_copy.tpl");
  }

  function UserDelete(){
    $this->app->Tpl->Set('HEADING',"User (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("user_delete.tpl");
  }

  function UserList(){
    $this->app->Tpl->Set('HEADING',"User (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("user_list.tpl");
  }

} 
?>