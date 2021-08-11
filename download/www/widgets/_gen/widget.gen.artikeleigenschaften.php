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

class WidgetGenartikeleigenschaften
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

  public function artikeleigenschaftenDelete()
  {
    
    $this->form->Execute("artikeleigenschaften","delete");

    $this->artikeleigenschaftenList();
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
    $this->form = $this->app->FormHandler->CreateNew("artikeleigenschaften");
    $this->form->UseTable("artikeleigenschaften");
    $this->form->UseTemplate("artikeleigenschaften.tpl",$this->parsetarget);

    $field = new HTMLInput("name","text","","40","","","","","","","","0","2","");
    $this->form->NewField($field);
    $this->form->AddMandatory("name","notempty","Pflichtfeld!","MSGNAME");

    $field = new HTMLInput("projekt","text","","40","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLSelect("typ",0,"typ","","","0");
    $field->AddOption('Einzeilig','einzeilig');
    $field->AddOption('Selectfeld','select');
    $this->form->NewField($field);


  }

}

?>