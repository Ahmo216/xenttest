
<style>

.cke_chrome {
    display: block;
    border: 0px solid #b6b6b6 !important;
    padding: 0;
    box-shadow: 0 0 0 rgba(0,0,0,0) !important;
}

/*
  Hide radio button (the round disc)
  we will use just the label to create pushbutton effect
*/
input[type=radio] {
    display:none; 
    margin:10px;
}

</style>

<div id="tabs">
    <ul>
        <li><a href="#tabs-1"></a></li>
    </ul>
<!-- ende gehort zu tabview -->
<div id="tabs-1">


[JAVASCRIPTCODE]

<form action=""
      data-ticket-message-id="[ID]"
      data-ticket-id="[TICKET_ID]"
      data-addressid="[ADDRESSID]"
      method="post" name="eproosubmitform"
      id="ticketassistent">
<input type="hidden" name="abschicken" value="Senden / Abschliessen" style="height: 40px;width:200px; display:none;">


<div class="row">
  <div class="row-height">
    <div class="col-xs-12 col-sm-height">
      <div class="inside_dark inside-full-height">
        <table width="100%" align="center">
          <tr>
            <td width=""><img src="./themes/new/images/status_[STATUSBILD].png"></td>
            <td align=""><b style="font-size: 14pt"><!--Support--->Ticket <font color="blue">#[TICKETNUMMER]</font></b>&nbsp;[BETREFF]</td>
            <td width="" align="right">{|Einreichen als|}: <select name="status">[STATUS]</select>&nbsp;<input type="radio" name="antwort" value="zuordnen">
              <label class="button button-secondary label-send-form" data-send-form="zuordnen">{|speichern|}</label>
              <input type="radio" name="antwort" value="forward">
              <label class="button button-secondary label-send-form" data-send-form="forward">{|weiterleiten|}</label>
              <input type="radio" name="antwort" value="sofort">
              <label class="button button-primary label-send-form" data-send-form="sofort">{|beantworten|}</label>
            </td>
          </tr>
        </table>

      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="row-height">
    <div class="col-xs-12 col-sm-height">
      <div class="inside inside-full-height">
<fieldset>
  <legend>{|Ticket Zuordnung|}</legend>

    <table width="100%" border="0">
      <tr>
        <td width="70">{|Adresse|}: </td>
        <td width="">[KUNDEAUTOSTART]
          <input type="text" size="28" name="adresse" id="adresse" value="[VORSCHLAG]">&nbsp;<input type="submit" class="button button-secondary" name="abschicken" value="&uuml;bernehmen">
          [KUNDEAUTOEND]&nbsp;<select name="changeticket" id="changeticket">
            <option value="">bitte wählen ...</option>
            [ONCHANGESELECT]
          </select>
        </td>
        <td width="90">{|Zuordnung|}: </td>
        <td><select name="warteschlange">[WARTESCHLANGE]</select>&nbsp;{|Projekt|}:&nbsp;<input type="text" name="projekt" value="[PROJEKT]" id="projekt"><br><br>
          {|als persönlich markiert|}:&nbsp;<input type="checkbox" value="1" name="privat" [CHECKBOX_PRIVAT]>
        &nbsp;{|Prio|}:&nbsp;<input type="checkbox" value="1" name="prio" [CHECKBOX_PRIO]>
        &nbsp;{|DSGVO|}:&nbsp;<input type="checkbox" value="1" name="dsgvo" [CHECKBOX_DSGVO]>
        </td>
        <!--<td>Prio: </td>
        <td><b><select name="prio">[PRIO]</select></b></td>--><!--        <td align="right"></td>
        <td align="center"><input type="radio" name="antwort" value="zuordnen" ><label onclick="SendForm('zuordnen')">speichern</label></td>-->
      </tr>
</table>
</fieldset>
      </div>
    </div>
  </div>
</div>


</form>

<div class="row">
  <div class="row-height">
    <div class="col-xs-12 col-sm-6 col-sm-height">
      <div class="inside inside-full-height">

<fieldset style="margin-right:10px;"><legend>{|Interner Kommentar|}</legend>
<table width="100%">
      <tr>
        <td colspan="6">
          <textarea name="kommentar" rows="4" style="width:100%" class="jskommentar">[KOMMENTAR]</textarea>
        </td>
      </tr>
     <tr>
       <td colspan="5" style="width:100%;>
         <div style="margin-bottom: 3px">{|Tags|}:</div>
         <input type="text" style="width:100%;" class="jstags" value="[TAGS]" id="tags" name="tags" />
       </td>
       <td>
         <input type="button" id="savecommentcrm" class="button button-secondary" value="Kommentar als CRM speichern" />
       </td>
     </tr>
    </table>
</fieldset>

      </div>
    </div>

    <div class="col-xs-12 col-sm-6 col-sm-height">
      <div class="inside inside-full-height">

<fieldset style=""><legend>{|Service & Support|}</legend>
<table>
      <tr valign="top">
        <td width="130">{|Zugw. Service Ticket|}:</td>
        <td colspan="5" width="725">
          <input type="text" size="55" name="service" form="ticketassistent" id="service" value="[SERVICE]" >&nbsp;[SERVICEPOPUP]
        </td>
      </tr>
</table>
</fieldset>
</div>
</div>
</div>
</div>

<div class="row">
  <div class="row-height">
    <div class="col-xs-12 col-sm-height">
      <div class="inside inside-full-height">
<fieldset><legend>{|Nachrichten Verlauf|}</legend>
[TEXT]
</fieldset>
</div>
</div>
</div>
</div>


<!--<fieldset><legend>Gespr&auml;chsverlauf:</legend>
[TABLE]
</fieldset>-->


<!-- kunde -->
<!--<fieldset><legend>{|Kunde|}</legend>
<table width="100%" height="140">
<tr><td>Kunde: </td><td><b>[NAME]</b></td></tr>
<tr><td>Kontakt: </td><td>[EMAIL]</td></tr>
<tr><td>Zeit: </td><td>[ZEIT]</td></tr>
<tr><td>Wartezeit: </td><td><font color="red"><b>[WARTEZEIT]</b></font></td></tr>
<tr><td>Quelle: </td><td>[QUELLE]</td></tr>
</table>
</fieldset>-->
<!--<fieldset style="float: right;" valign="top"><legend>{|Zuordnung|}</legend>
<table height="140">
<tr><td>1.</td><td>Projekt: </td><td>[SELECT_PROJEKT]</td></tr>
<tr><td>2.</td><td>Warteschlange: </td><td><select name="warteschlange">[WARTESCHLANGE]</select></td></tr>
<tr><td>3.</td><td>Prio: </td><td><b><select name="prio">[PRIO]</select></b></td></tr>
</table>
</fieldset>-->




<!--
    <tr valign="" height="" bgcolor="" align="" bordercolor="" class="klein" classname="klein">
    <td width="" valign="" height="" bgcolor="" align="right"  bordercolor="" classname="orange2" class="orange2">
<table width="100%" border="0"><tr><td>
</td><td align="center">
</td><td align="right"><input type="button" value="Abbrechen" onclick="window.location.href='index.php?module=ticket&action=freigabe&id=[ID]'">
    </td></tr></table>

</td>
    </tr>
-->  

</div>




<!-- tab view schließen -->
</div>

<div id="attachment-assign-dialog" class="hide">
  <div class="row">
    <div class="col-md-12">
      <div id="assign-dialog-content">[ATTACHEMENTDIALOGCONTENT]</div><br>
    </div>
  </div>
</div>

