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

class WidgetGenzeiterfassungvorlage
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

  public function zeiterfassungvorlageDelete()
  {
    
    $this->form->Execute("zeiterfassungvorlage","delete");

    $this->zeiterfassungvorlageList();
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
    $this->form = $this->app->FormHandler->CreateNew("zeiterfassungvorlage");
    $this->form->UseTable("zeiterfassungvorlage");
    $this->form->UseTemplate("zeiterfassungvorlage.tpl",$this->parsetarget);

    $field = new HTMLInput("vorlage","text","","40","","","","","","","","0","2","");
    $this->form->NewField($field);
    $this->form->AddMandatory("vorlage","notempty","Pflichfeld!","MSGVORLAGE");

    $field = new HTMLTextarea("vorlagedetail",5,39,"","","","","");
    $this->form->NewField($field);


    $field = new HTMLCheckbox("ausblenden","","","1","0","0");
    $this->form->NewField($field);


  }

}

?>