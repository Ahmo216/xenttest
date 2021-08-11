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

class WidgetGenbestellung
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

  public function bestellungDelete()
  {
    
    $this->form->Execute("bestellung","delete");

    $this->bestellungList();
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
    $this->form = $this->app->FormHandler->CreateNew("bestellung");
    $this->form->UseTable("bestellung");
    $this->form->UseTemplate("bestellung.tpl",$this->parsetarget);

    $field = new HTMLInput("adresse","text","","20","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("projekt","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("preisanfrageid","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("angebot","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("internebezeichnung","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("datum","text","","10","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("gewuenschteslieferdatum","text","","10","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLCheckbox("bestellung_bestaetigt","","","1","0","0");
    $this->form->NewField($field);

    $field = new HTMLInput("bestaetigteslieferdatum","text","","10","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLSelect("bestellungbestaetigtper",0,"bestellungbestaetigtper","","","0");
    $field->AddOption('Internet','internet');
    $field->AddOption('E-Mail','email');
    $field->AddOption('Telefon','telefon');
    $field->AddOption('Telefax','telefax');
    $field->AddOption('Brief','brief');
    $field->AddOption('Sonstige','sonstige');
    $this->form->NewField($field);

    $field = new HTMLInput("bestellungbestaetigtabnummer","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLCheckbox("schreibschutz","","","1","0","0");
    $this->form->NewField($field);

    $field = new HTMLCheckbox("abweichendebezeichnung","","","1","0","0");
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

    $field = new HTMLInput("kundennummer","text","","20","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLSelect("zahlungsweise",0,"zahlungsweise","","","0");
    $field->AddOption('Rechnung','rechnung');
    $field->AddOption('Vorkasse','vorkasse');
    $field->AddOption('Nachnahme','nachnahme');
    $field->AddOption('Kreditkarte','kreditkarte');
    $field->AddOption('Einzugsermaechtigung','einzugsermaechtigung');
    $field->AddOption('Bar','bar');
    $field->AddOption('PayPal','paypal');
    $this->form->NewField($field);

    $field = new HTMLInput("bearbeiter","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("lieferbedingung","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLCheckbox("bestellbestaetigung","","","1","0","0");
    $this->form->NewField($field);

    $field = new HTMLCheckbox("keineartikelnummern","","","1","0","0");
    $this->form->NewField($field);

    $field = new HTMLCheckbox("bestellungohnepreis","","","1","0","0");
    $this->form->NewField($field);

    $field = new HTMLCheckbox("artikelnummerninfotext","","","1","0","0");
    $this->form->NewField($field);

    $field = new HTMLCheckbox("langeartikelnummern","","","1","0","0");
    $this->form->NewField($field);

    $field = new HTMLCheckbox("ohne_briefpapier","","","1","0","0");
    $this->form->NewField($field);

    $field = new HTMLCheckbox("ohne_artikeltext","","","1","0","0");
    $this->form->NewField($field);

    $field = new HTMLInput("zahlungszieltage","text","","20","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("zahlungszieltageskonto","text","","20","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("zahlungszielskonto","text","","20","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("bank_inhaber","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("bank_institut","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("bank_blz","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("bank_konto","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("bank_inhaber","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("bank_institut","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("bank_blz","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("bank_konto","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("paypalaccount","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLSelect("kreditkarte_typ",0,"kreditkarte_typ","","","0");
    $this->form->NewField($field);

    $field = new HTMLInput("kreditkarte_inhaber","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("kreditkarte_nummer","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("kreditkarte_pruefnummer","text","","5","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLSelect("kreditkarte_monat",0,"kreditkarte_monat","","","0");
    $this->form->NewField($field);

    $field = new HTMLSelect("kreditkarte_jahr",0,"kreditkarte_jahr","","","0");
    $this->form->NewField($field);

    $field = new HTMLTextarea("internebemerkung",2,110,"","","","","0");   
    $this->form->NewField($field);

    $field = new HTMLInput("ustid","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLSelect("ust_befreit",0,"ust_befreit","","","0");
    $field->AddOption('{|Inland|}','0');
    $field->AddOption('{|EU-Lieferung|}','1');
    $field->AddOption('{|Import|}','2');
    $this->form->NewField($field);

    $field = new HTMLSelect("anzeigesteuer",0,"anzeigesteuer","","","0");
    $field->AddOption('{|automatisch|}','0');
    $field->AddOption('{|netto|}','3');
    $field->AddOption('{|brutto|}','4');
    $this->form->NewField($field);

    $field = new HTMLInput("waehrung","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("sprache","text","","30","","","","","","","","0","","");
    $this->form->NewField($field);

    $field = new HTMLInput("kostenstelle","text","","15","","","","","","","","0","","");
    $this->form->NewField($field);


  }

}

?>