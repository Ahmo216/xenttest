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

class WidgetGengruppen_kategorien
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

  public function gruppen_kategorienDelete()
  {
    
    $this->form->Execute("gruppen_kategorien","delete");

    $this->gruppen_kategorienList();
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
    $this->form = $this->app->FormHandler->CreateNew("gruppen_kategorien");
    $this->form->UseTable("gruppen_kategorien");
    $this->form->UseTemplate("gruppen_kategorien.tpl",$this->parsetarget);

    $field = new HTMLInput("bezeichnung","text","","80","","","","","","","","0","","");
    $this->form->NewField($field);
    $this->form->AddMandatory("bezeichnung","notempty","Pflichfeld!","MSGBEZEICHNUNG");

    $field = new HTMLInput("projekt","text","","80","","","","","","","","0","","");
    $this->form->NewField($field);


  }

}

?>