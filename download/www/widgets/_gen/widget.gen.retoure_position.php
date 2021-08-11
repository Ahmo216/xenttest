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

class WidgetGenretoure_position
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

  public function retoure_positionDelete()
  {
    
    $this->form->Execute("retoure_position","delete");

    $this->retoure_positionList();
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
    $this->form = $this->app->FormHandler->CreateNew("retoure_position");
    $this->form->UseTable("retoure_position");
    $this->form->UseTemplate("retoure_position.tpl",$this->parsetarget);

    $field = new HTMLInput("nummer","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("bezeichnung","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);
    $this->form->AddMandatory("bezeichnung","notempty","Pflichtfeld!","MSGBEZEICHNUNG");

    $field = new HTMLTextarea("beschreibung",8,48,"","","","","0");   
    $this->form->NewField($field);

    $field = new HTMLInput("menge","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);
    $this->form->AddMandatory("menge","notempty","Pflichtfeld!","MSGMENGE");

    $field = new HTMLInput("einheit","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("vpe","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("lieferdatum","text","","15","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLCheckbox("lieferdatumkw","","","1","0","0");
    $this->form->NewField($field);

    $field = new HTMLInput("artikelnummerkunde","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("zolltarifnummer","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("herkunftsland","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLCheckbox("ausblenden_im_pdf","","","1","0","0");
    $this->form->NewField($field);

    $field = new HTMLInput("seriennummer","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("grund","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld1","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld2","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld3","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld4","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld5","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld6","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld7","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld8","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld9","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld10","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld11","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld12","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld13","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld14","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld15","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld16","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld17","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld18","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld19","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld20","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld21","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld22","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld23","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld24","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld25","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld26","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld27","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld28","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld29","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld30","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld31","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld32","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld33","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld34","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld35","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld36","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld37","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld38","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld39","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("freifeld40","text","","50","","","","","","","","0","","");
    $this->form->NewField($field);


    $field = new HTMLTextarea("bemerkung",3,30,"","","","","0");   
    $this->form->NewField($field);

    $field = new HTMLInput("input2","hidden","","","","","","","","","","0","","");
    $this->form->NewField($field);

  }

}

?>