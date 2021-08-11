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

class WidgetGenwebmail_mails
{

  private $app;            //application object  
  public $form;            //store form object  
  protected $parsetarget;    //target for content

  public function __construct($app,$parsetarget)
  {
    $this->app = $app;
    $this->parsetarget = $parsetarget;
    $this->Form();
  }

  public function webmail_mailsDelete()
  {
    
    $this->form->Execute("webmail_mails","delete");

    $this->webmail_mailsList();
  }

  function Edit()
  {
    $this->form->Edit();
  }

  function Copy()
  {
    $this->form->Copy();
  }

  public function Create()
  {
    $this->form->Create();
  }

  public function Search()
  {
    $this->app->Tpl->Set($this->parsetarget,"SUUUCHEEE");
  }

  public function Summary()
  {
    $this->app->Tpl->Set($this->parsetarget,"grosse Tabelle");
  }

  function Form()
  {
    $this->form = $this->app->FormHandler->CreateNew("webmail_mails");
    $this->form->UseTable("webmail_mails");
    $this->form->UseTemplate("webmail_mails.tpl",$this->parsetarget);


    $field = new HTMLInput("do","radio","print","","","","","","","","0");
    $this->form->NewField($field);

    $field = new HTMLInput("do","radio","print","","","","","","","","0");
    $this->form->NewField($field);

    $field = new HTMLInput("do","radio","print","","","","","","","","0");
    $this->form->NewField($field);

    $field = new HTMLInput("do","radio","spam","","","","","","","","0");
    $this->form->NewField($field);





  }

}

?>