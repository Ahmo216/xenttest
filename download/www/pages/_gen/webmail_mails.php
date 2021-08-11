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

class GenWebmail_Mails { 

  function __construct(&$app) { 

    $this->app=&$app;
    $this->app->ActionHandlerInit($this);

    $this->app->ActionHandler("create","Webmail_MailsCreate");
    $this->app->ActionHandler("edit","Webmail_MailsEdit");
    $this->app->ActionHandler("copy","Webmail_MailsCopy");
    $this->app->ActionHandler("list","Webmail_MailsList");
    $this->app->ActionHandler("delete","Webmail_MailsDelete");

    $this->app->Tpl->Set('HEADING',"Webmail_Mails");    $this->app->ActionHandlerListen($app);
  }

  function Webmail_MailsCreate(){
    $this->app->Tpl->Set('HEADING',"Webmail_Mails (Anlegen)");
      $this->app->PageBuilder->CreateGen("webmail_mails_create.tpl");
  }

  function Webmail_MailsEdit(){
    $this->app->Tpl->Set('HEADING',"Webmail_Mails (Bearbeiten)");
      $this->app->PageBuilder->CreateGen("webmail_mails_edit.tpl");
  }

  function Webmail_MailsCopy(){
    $this->app->Tpl->Set('HEADING',"Webmail_Mails (Kopieren)");
      $this->app->PageBuilder->CreateGen("webmail_mails_copy.tpl");
  }

  function Webmail_MailsDelete(){
    $this->app->Tpl->Set('HEADING',"Webmail_Mails (L&ouml;schen)");
      $this->app->PageBuilder->CreateGen("webmail_mails_delete.tpl");
  }

  function Webmail_MailsList(){
    $this->app->Tpl->Set('HEADING',"Webmail_Mails (&Uuml;bersicht)");
      $this->app->PageBuilder->CreateGen("webmail_mails_list.tpl");
  }

} 
?>