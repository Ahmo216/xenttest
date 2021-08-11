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

class WidgetGenretoure
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

  public function retoureDelete()
  {
    
    $this->form->Execute("retoure","delete");

    $this->retoureList();
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
    $this->form = $this->app->FormHandler->CreateNew("retoure");
    $this->form->UseTable("retoure");
    $this->form->UseTemplate("retoure.tpl",$this->parsetarget);

    $field = new HTMLInput("lieferid","hidden","","","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("ansprechpartnerid","hidden","","","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("adresse","text","","20","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("lieferant","text","","10","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLCheckbox("lieferantenretoure","","","1","0","0");
    $this->form->NewField($field);

    $field = new HTMLInput("projekt","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("auftragid","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("lieferscheinid","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("gutschrift_id","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("replacementorder_id","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("ihrebestellnummer","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("internebezeichnung","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("datum","text","","","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("standardlager","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("kommissionskonsignationslager","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLCheckbox("schreibschutz","","","1","0","0");
    $this->form->NewField($field);

    $field = new HTMLCheckbox("abweichendebezeichnung","","","1","0","0");
    $this->form->NewField($field);

    $field = new HTMLSelect("fortschritt",0,"fortschritt","","","0");
    $field->AddOption('{|Angek&uuml;ndigt|}','angekuendigt');
    $field->AddOption('{|Eingegangen|}','eingegangen');
    $field->AddOption('{|Gepr&uuml;ft|}','geprueft');
    $field->AddOption('{|Erledigt|}','erledigt');
    $this->form->NewField($field);

    $field = new HTMLCheckbox("abweichendelieferadresse","","","1","0","0");
    $this->form->NewField($field);

    $field = new HTMLInput("liefername","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("liefertitel","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("lieferansprechpartner","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("lieferabteilung","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("lieferunterabteilung","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("lieferadresszusatz","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("lieferstrasse","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("lieferplz","text","","5","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("lieferort","text","","22","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("liefergln","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLSelect("typ",0,"typ","","","0");
    $field->AddOption('Firma','firma');
    $field->AddOption('Herr','herr');
    $field->AddOption('Frau','frau');
    $this->form->NewField($field);

    $field = new HTMLInput("name","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);
    $this->form->AddMandatory("name","notempty","Pflichfeld!","MSGNAME");

    $field = new HTMLInput("titel","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("ansprechpartner","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("abteilung","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("unterabteilung","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("adresszusatz","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("strasse","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("plz","text","","5","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("ort","text","","19","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("telefon","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("telefax","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("email","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("anschreiben","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLTextarea("freitext",5,110,"","","","","0");   
    $this->form->NewField($field);

    $field = new HTMLTextarea("bodyzusatz",5,110,"","","","","0");   
    $this->form->NewField($field);

    $field = new HTMLSelect("versandart",0,"versandart","","","0");
    $this->form->NewField($field);

    $field = new HTMLInput("lieferbedingung","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("vertrieb","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("bearbeiter","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLCheckbox("keinerechnung","","","1","0","0");
    $this->form->NewField($field);

    $field = new HTMLCheckbox("ohne_briefpapier","","","1","0","0");
    $this->form->NewField($field);

    $field = new HTMLCheckbox("ohne_artikeltext","","","1","0","0");
    $this->form->NewField($field);

    $field = new HTMLInput("gln","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLTextarea("internebemerkung",2,110,"","","","","0");   
    $this->form->NewField($field);

    $field = new HTMLInput("ustid","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLSelect("ust_befreit",0,"ust_befreit","","","0");
    $field->AddOption('{|Inland|}','0');
    $field->AddOption('{|EU-Lieferung|}','1');
    $field->AddOption('{|Export|}','2');
    $field->AddOption('{|Steuerfrei Inland|}','3');
    $this->form->NewField($field);

    $field = new HTMLInput("sprache","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);


  }

}

?>