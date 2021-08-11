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

class WidgetGenuebersetzung
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

  public function uebersetzungDelete()
  {
    
    $this->form->Execute("uebersetzung","delete");

    $this->uebersetzungList();
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
    $this->form = $this->app->FormHandler->CreateNew("uebersetzung");
    $this->form->UseTable("uebersetzung");
    $this->form->UseTemplate("uebersetzung.tpl",$this->parsetarget);

    $field = new HTMLInput("label","text","","","","","","","","","0");
    $this->form->NewField($field);

    $field = new HTMLInput("sprache","text","","","","","","","","","0");
    $this->form->NewField($field);

    $field = new HTMLTextarea("beschriftung",10,100);   
    $this->form->NewField($field);

    $field = new HTMLTextarea("original",10,100);   
    $this->form->NewField($field);


  }

}

?>