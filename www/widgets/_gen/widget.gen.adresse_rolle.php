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

class WidgetGenadresse_rolle
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

  public function adresse_rolleDelete()
  {
    
    $this->form->Execute("adresse_rolle","delete");

    $this->adresse_rolleList();
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
    $this->form = $this->app->FormHandler->CreateNew("adresse_rolle");
    $this->form->UseTable("adresse_rolle");
    $this->form->UseTemplate("adresse_rolle.tpl",$this->parsetarget);

    $field = new HTMLSelect("subjekt",0,"subjekt");
    $this->form->NewField($field);

    $field = new HTMLSelect("objekt",0,"objekt");
    $field->AddOption('Projekt','Projekt');
    $field->AddOption('Gruppe','Gruppe');
    $this->form->NewField($field);

    $field = new HTMLInput("parameter","text","","20","","","","","","","0");
    $this->form->NewField($field);

    $field = new HTMLInput("gruppe","text","","20","","","","","","","0");
    $this->form->NewField($field);


  }

}

?>