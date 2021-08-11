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

class WidgetGensupportapp
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

  public function supportappDelete()
  {
    
    $this->form->Execute("supportapp","delete");

    $this->supportappList();
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
    $this->form = $this->app->FormHandler->CreateNew("supportapp");
    $this->form->UseTable("supportapp");
    $this->form->UseTemplate("supportapp.tpl",$this->parsetarget);

    $field = new HTMLInput("adresse","text","","40","","","","","","","","0","1","");
    $this->form->NewField($field);
    $this->form->AddMandatory("adresse","notempty","Pflichfeld!","MSGADRESSE");

    $field = new HTMLInput("mitarbeiter","text","","40","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("version","text","","40","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLSelect("status",0,"status","","","0");
    $field->AddOption('geplant','geplant');
    $field->AddOption('gestartet','gestartet');
    $field->AddOption('abgeschlossen','abgeschlossen');
    $this->form->NewField($field);

    $field = new HTMLInput("intervall","text","","5","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("startdatum","text","","10","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("zeitgeplant","text","","5","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLTextarea("bemerkung",5,50,"","","","","0");   
    $this->form->NewField($field);

    $field = new HTMLSelect("phase",0,"phase","","","0");
    $field->AddOption('Beginn','beginn');
    $field->AddOption('Mitte','mitte');
    $field->AddOption('kurz vor Abschluss','kurzdavor');
    $field->AddOption('&Uuml;bergabe','uebergabe');
    $this->form->NewField($field);


  }

}

?>