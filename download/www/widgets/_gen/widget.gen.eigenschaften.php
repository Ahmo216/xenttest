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

class WidgetGeneigenschaften
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

  public function eigenschaftenDelete()
  {
    
    $this->form->Execute("eigenschaften","delete");

    $this->eigenschaftenList();
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
    $this->form = $this->app->FormHandler->CreateNew("eigenschaften");
    $this->form->UseTable("eigenschaften");
    $this->form->UseTemplate("eigenschaften.tpl",$this->parsetarget);

    $field = new HTMLInput("hauptkategorie","text","","50","","","","","","","0");
    $this->form->NewField($field);
    $this->form->AddMandatory("hauptkategorie","notempty","Pflichtfeld!",MSGHAUPTKATEGORIE);

    $field = new HTMLInput("unterkategorie","text","","50","","","","","","","0");
    $this->form->NewField($field);

    $field = new HTMLInput("wert","text","","50","","","","","","","0");
    $this->form->NewField($field);

    $field = new HTMLInput("einheit","text","","50","","","","","","","0");
    $this->form->NewField($field);


  }

}

?>