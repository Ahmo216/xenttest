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

class WidgetGenbrief
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

  public function briefDelete()
  {
    
    $this->form->Execute("brief","delete");

    $this->briefList();
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
    $this->form = $this->app->FormHandler->CreateNew("brief");
    $this->form->UseTable("brief");
    $this->form->UseTemplate("brief.tpl",$this->parsetarget);

    $field = new HTMLInput("betreff","text","","40","","","","","","","0");
    $this->form->NewField($field);
    $this->form->AddMandatory("betreff","notempty","Pflichtfeld!",MSGBETREFF);

    $field = new HTMLTextarea("nachricht",30,90);   
    $this->form->NewField($field);
    $this->form->AddMandatory("nachricht","notempty","Pflichtfeld!",MSGNACHRICHT);



  }

}

?>