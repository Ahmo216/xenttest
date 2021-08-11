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

class WidgetGenzolltarifnummer
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

  public function zolltarifnummerDelete()
  {
    
    $this->form->Execute("zolltarifnummer","delete");

    $this->zolltarifnummerList();
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
    $this->form = $this->app->FormHandler->CreateNew("zolltarifnummer");
    $this->form->UseTable("zolltarifnummer");
    $this->form->UseTemplate("zolltarifnummer.tpl",$this->parsetarget);

    $field = new HTMLInput("nummer","text","","40","","","","","","","0");
    $this->form->NewField($field);
    $this->form->AddMandatory("nummer","notempty","Pflichfeld!","MSGNUMMER");

    $field = new HTMLInput("beschreibung","text","","40","","","","","","","0");
    $this->form->NewField($field);
    $this->form->AddMandatory("beschreibung","notempty","Pflichfeld!","MSGBESCHREIBUNG");

    $field = new HTMLTextarea("internebemerkung",5,50);   
    $this->form->NewField($field);


  }

}

?>