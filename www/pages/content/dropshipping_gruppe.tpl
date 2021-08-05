<!-- gehort zu tabview -->
<script type='text/javascript'>

$(document).ready(function() {

  $("#editGruppe").dialog({
    modal: true,
    bgiframe: true,
    closeOnEscape:false,
    minWidth:750,
    maxHeight:700,
    autoOpen: false,
    buttons: {
      'ARTIKELLISTE HOCHLADEN':function(){
        DropshippingUploadArticles();
      },
      'ALLE ARTIKELVERKNÜPFUNGEN LÖSCHEN': function(){
        DropshippingDeleteArticles();
      },
      'ABBRECHEN': function() {
        DropshippingGruppeReset();
        $(this).dialog('close');
      },
      'SPEICHERN': function() {
        DropshippingGruppeEditSave();
      }
    }
  });

  $("#editGruppe").dialog({
    close: function( event, ui ) { DropshippingGruppeReset();}
  });

  $('#uploadarticles').dialog({
    modal: true,
    bgiframe: true,
    closeOnEscape:false,
    minWidth:750,
    maxHeight:700,
    minHeight:550,
    autoOpen: false,
    buttons: {
      'ABBRECHEN':function()
      {
        $(this).dialog('close');

      }

    }

  });

});


function DropshippingGruppeReset()
{
  $('#editGruppe').find('#editid').val('');
  $('#editGruppe').find('#editbezeichnung').val('');
  $('#editGruppe').find('#editadresse').val('');
  $('#editGruppe').find('#editprojekt').val('');
  //$('#editGruppe').find('#edittype').val(0);

  $('#editGruppe').find('#editautoversand').val('0');
  $('#editGruppe').find('#editzahlungok').prop("checked",false);
  $('#editGruppe').find('#editlieferdatum').prop("checked", false);
  $('#editGruppe').find('#editbestellunganlegen').prop("checked",false);
  $('#editGruppe').find('#editabweichendelieferadresse').prop("checked",false);
  $('#editGruppe').find('#editlieferscheinanhaengen').prop("checked",false);
  $('#editGruppe').find('#editrechnunganhaengen').prop("checked",false);
  $('#editGruppe').find('#editauftraganhaengen').prop("checked",false);
  $('#editGruppe').find('#editbestellungmail').prop("checked",false);
  $('#editGruppe').find('#editlieferscheinmail').prop("checked",false);
  $('#editGruppe').find('#editrechnungmail').prop("checked",false);
  $('#editGruppe').find('#editrueckmeldungshop').prop("checked",false);
  $('#editGruppe').find('#editauftragcsv').prop("checked",false);
  $('#editGruppe').find('#editbestellungabschliessen').prop("checked",true);
  $('#editGruppe').find('#editlieferscheincsv').prop("checked",false);
  $('#editGruppe').find('#editbelegeautoversandkunde').prop("checked",false);
  $('#editGruppe').find('#editbestellungdrucken').val(0);
  $('#editGruppe').find('#editlieferscheindrucken').val(0);
  $('#editGruppe').find('#editrechnungdrucken').val(0);
  $('#editGruppe').find('#editbelegeautoversand').val('standardauftrag');
  $('#editGruppe').find('#editbelegeautoversand').trigger('change');
}

function DropshippingUploadArticles()
{
  if(parseInt($('#editid').val()) > 0) {
    $('#uploadcontainer').attr('src','index.php?module=dropshipping&action=gruppe&iframe=1&id='+$('#editid').val());
    $("#editGruppe").dialog('close');
    $('#uploadarticles').dialog('open');
  }
}

function DropshippingDeleteArticles()
{
  if(parseInt($('#editid').val()) > 0 && confirm('Wollen Sie wirklich alle Verknüfungen löschen?'))
  {
    $.ajax({
      url: 'index.php?module=dropshipping&action=gruppe&cmd=delarticles',
      data: {
        id: $('#editid').val()
      },
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
        App.loading.open();
      },
      success: function(data) {
        App.loading.close();
        updateLiveTable();
        $("#editGruppe").dialog('close');
        DropshippingGruppeReset();
      }
      });
  }
}

function DropshippingGruppeEditSave() {
  $.ajax({
      url: 'index.php?module=dropshipping&action=gruppesave',
      data: {
          //Alle Felder die fürs editieren vorhanden sind
          id: $('#editid').val(),
          bezeichnung: $('#editbezeichnung').val(),
          adresse: $('#editadresse').val(),
          projekt: $('#editprojekt').val(),

          autoversand: $('#editautoversand').val(),
          zahlungok: $('#editzahlungok').prop("checked")?1:0,
          lieferdatum: $('#editlieferdatum').prop("checked")?1:0,
          bestellunganlegen: $('#editbestellunganlegen').prop("checked")?1:0,
          abweichendelieferadresse:  $('#editabweichendelieferadresse').prop("checked")?1:0,
          lieferscheinanhaengen:  $('#editlieferscheinanhaengen').prop("checked")?1:0,
          rechnunganhaengen:  $('#editrechnunganhaengen').prop("checked")?1:0,
          auftraganhaengen:  $('#editauftraganhaengen').prop("checked")?1:0,
          bestellungmail:  $('#editbestellungmail').prop("checked")?1:0,
          lieferscheinmail:  $('#editlieferscheinmail').prop("checked")?1:0,
          rechnungmail:  $('#editrechnungmail').prop("checked")?1:0,
          rueckmeldungshop:  $('#editrueckmeldungshop').prop("checked")?1:0,
          auftragcsv:  $('#editauftragcsv').prop("checked")?1:0,
          bestellungabschliessen: $('#editbestellungabschliessen').prop("checked")?1:0,
          lieferscheincsv:  $('#editlieferscheincsv').prop("checked")?1:0,
          bestellungdrucken:$('#editbestellungdrucken').val(),
          lieferscheindrucken:$('#editlieferscheindrucken').val(),
          rechnungdrucken:$('#editrechnungdrucken').val(),
          belegeautoversandkunde:$('#editbelegeautoversandkunde').prop("checked")?1:0,
          belegeautoversand:$('#editbelegeautoversand').val()
      },
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
          App.loading.open();
      },
      success: function(data) {

          App.loading.close();
          if (data.status == 1) {
              updateLiveTable();
              $("#editGruppe").dialog('close');
              DropshippingGruppeReset();
          } else {
              alert(data.statusText);
          }
      }
  });
}

function DropshippingGruppeEdit(id) {

  if(id > 0)
  {
    $.ajax({
        url: 'index.php?module=dropshipping&action=gruppeedit&cmd=get',
        data: {
            id: id
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            App.loading.open();
        },
        success: function(data) {
            $('#editGruppe').find('#editid').val(data.id);
            $('#editGruppe').find('#editbezeichnung').val(data.bezeichnung);
            $('#editGruppe').find('#editadresse').val(data.adresse);
            $('#editGruppe').find('#editprojekt').val(data.projekt);


            $('#editGruppe').find('#editautoversand').val(data.autoversand);
            $('#editGruppe').find('#editzahlungok').prop("checked",data.zahlungok==1?true:false);
            $('#editGruppe').find('#editlieferdatum').prop("checked", data.lieferdatumberechnen==1?true:false);
            $('#editGruppe').find('#editbestellunganlegen').prop("checked",data.bestellunganlegen==1?true:false);
            $('#editGruppe').find('#editabweichendelieferadresse').prop("checked",data.abweichendelieferadresse==1?true:false);
            $('#editGruppe').find('#editlieferscheinanhaengen').prop("checked",data.lieferscheinanhaengen==1?true:false);
            $('#editGruppe').find('#editrechnunganhaengen').prop("checked",data.rechnunganhaengen==1?true:false);
            $('#editGruppe').find('#editauftraganhaengen').prop("checked",data.auftraganhaengen==1?true:false);
            $('#editGruppe').find('#editbestellungmail').prop("checked",data.bestellungmail==1?true:false);
            $('#editGruppe').find('#editlieferscheinmail').prop("checked",data.lieferscheinmail==1?true:false);
            $('#editGruppe').find('#editrechnungmail').prop("checked",data.rechnungmail==1?true:false);
            $('#editGruppe').find('#editrueckmeldungshop').prop("checked",data.rueckmeldungshop==1?true:false);
            $('#editGruppe').find('#editauftragcsv').prop("checked",data.auftragcsv==1?true:false);
            $('#editGruppe').find('#editbestellungabschliessen').prop("checked",data.bestellungabschliessen==1?true:false);
            $('#editGruppe').find('#editlieferscheincsv').prop("checked",data.lieferscheincsv==1?true:false);
            $('#editGruppe').find('#editbelegeautoversandkunde').prop("checked",data.belegeautoversandkunde==1?true:false);
            if(data.belegeautoversand +'' !== '')
            {
              $('#editGruppe').find('#editbelegeautoversand').val(data.belegeautoversand);
            }else{
              $('#editGruppe').find('#editbelegeautoversand').val('standardauftrag');
            }

            if(data.bestellungdrucken=="" || data.bestellungdrucken <=0 ) {
              $('#editGruppe').find('#editbestellungdrucken').val('0');
            }
            else {
              $('#editGruppe').find('#editbestellungdrucken').val(data.bestellungdrucken);
            }

            if(data.rechnungdrucken=="" || data.rechnungdrucken <=0 ) {
              $('#editGruppe').find('#editrechnungdrucken').val('0');
            }
            else{
              $('#editGruppe').find('#editrechnungdrucken').val(data.rechnungdrucken);
            }

            if(data.lieferscheindrucken=="" || data.lieferscheindrucken<=0 ) {
              $('#editGruppe').find('#editlieferscheindrucken').val('0');
            }
            else{
              $('#editGruppe').find('#editlieferscheindrucken').val(data.lieferscheindrucken);
            }

            App.loading.close();
            $('#editautoversand').trigger('change');
            $("#editGruppe").dialog('open');
        }
    });

  }else{
    DropshippingGruppeReset();
    $('#editautoversand').trigger('change');
    $("#editGruppe").dialog('open');
  }
}

function DropshippingGruppeDelete(id) {

  var conf = confirm('Wirklich diese Gruppe löschen?\nZusätzlich werden alle Artikel aus dieser Gruppe entfernt.');
  if (conf) {
    $.ajax({
        url: 'index.php?module=dropshipping&action=gruppedelete',
        data: {
            id: id
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            App.loading.open();
        },
        success: function(data) {
            if(data.status == 1){
                updateLiveTable();
            }else{
                alert(data.statusText);
            }
            App.loading.close();
        }
    });
  }

  return false;

}

function updateLiveTable(i) {
    var oTableL = $('#dropshipping_gruppe').dataTable();
    oTableL.fnFilter('%');
    oTableL.fnFilter('');
}




</script>

<div id="tabs">
    <ul>
        <li><a href="#tabs-1"></a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
[MESSAGE]
<div id="uploadarticles" style="display:none;">
  <style>
    #uploadcontainer
    {
      min-width:88%;
      min-height:500px;
    }
  </style>
  <iframe id="uploadcontainer" style="border:0;" src="" border="0" width="700">
  </iframe>
</div>
<div id="editGruppe" style="display:none;" title="Bearbeiten">
  <input type="hidden" id="editid">
<fieldset><legend>{|Allgemein|}</legend>
  <table class="mkTableFormular">
    <tr>
      <td>{|Bezeichnung|}:</td>
      <td><input type="text" name="editbezeichnung" id="editbezeichnung" size="40"></td>
    </tr>
    <tr>
      <td>{|Lieferant|}:</td>
      <td><input type="text" name="editadresse" id="editadresse" size="40"></td>
    </tr>
    <tr>
      <td>{|Ziel-Projekt (Optional)|}:</td>
      <td><input type="text" name="editprojekt" id="editprojekt" size="40"></td>
    </tr>

  </table>
</fieldset>
  <fieldset><legend>{|Auftrags-Einstellungen|}</legend>
    <table class="mkTableFormular">
      <tr>
        <td>{|Nur falls Zahlung OK|}:</td>
        <td><input type="checkbox" name="editzahlungok" id="editzahlungok" value="1"></td>
      </tr>
      <tr>
        <td>{|Lieferdatum aus Artikel berechnen|}:</td>
        <td><input type="checkbox" name="editlieferdatum" id="editlieferdatum" value="1"></td>
      </tr>
      <tr>
        <td><label for="editautoversand">{|Auto-Versand|}:</label></td>
        <td>
          <select id="editautoversand" name="editautoversand">
            <option value="0">{|Autoversandfreigabe deaktivieren|}</option>
            <option value="1">{|Autoversand durchf&uuml;hren|}</option>
            <option value="2">{|Autoversandfreigabe unverändert lassen|}</option>
          </select>
          &nbsp;&nbsp;<i>{|Direkt mit neuem Teilauftrag durchführen.|}</i></td>
      </tr>
      <tr class="trautoversand">
        <td>&nbsp;</td>
        <td>
          <b><label for="editbelegeautoversand">{|Belege im Autoversand erstellen|}</label></b><br />
          <select id="editbelegeautoversand" name="editbelegeautoversand">
            <option value="standardauftrag">{|Rechnung und Lieferschein erstellen|}</option>
            <option value="lieferung">{|nur Lieferschein erstellen|}</option>
            <option value="rechnung">{|nur Rechnung erstellen|}</option>
          </select>
        </td>
      </tr>
      <tr class="trautoversand">
        <td>&nbsp;</td>
        <td><input type="checkbox" name="editbelegeautoversandkunde" id="editbelegeautoversandkunde" value="1">&nbsp;&nbsp;
          <i><label for="editbelegeautoversandkunde">{|Einstellung aus Kundenadresse verwenden.|}</label></i></td>
      </tr>
    </table>
  </fieldset>
<fieldset><legend>{|Bestellung|}</legend>
  <table class="mkTableFormular">
    <tr>
      <td>{|automatisch anlegen|}:</td>
      <td><input type="checkbox" name="editbestellunganlegen" id="editbestellunganlegen" value="1"></td>
    </tr>
    <tr>
      <td>{|abweichende Lieferadresse|}:</td>
      <td><input type="checkbox" name="editabweichendelieferadresse" id="editabweichendelieferadresse" value="1">&nbsp;&nbsp;<i>{|Kundenadresse als abweichende Lieferadresse.|}</i></td>
    </tr>
    <tr class="lieferung">
      <td>{|als Anhang Lieferschein|}:</td>
      <td><input type="checkbox" name="editlieferscheinanhaengen" id="editlieferscheinanhaengen" value="1">&nbsp;&nbsp;<i>{|Als Anhang in Bestellung.|}</i></td>
    </tr>
    <tr class="lieferung">
      <td>{|inkl. Lieferschein als CSV|}:</td>
      <td><input type="checkbox" name="editlieferscheincsv" id="editlieferscheincsv" value="1">&nbsp;&nbsp;<i>{|Als Anhang in Bestellung.|}</i></td>
    </tr>
    <tr class="rechnung">
      <td>{|als Anhang Rechnung|}:</td>
      <td><input type="checkbox" name="editrechnunganhaengen" id="editrechnunganhaengen" value="1">&nbsp;&nbsp;<i>{|Als Anhang in Bestellung.|}</i></td>
    </tr>
    <tr>
      <td>{|als Anhang Auftrag|}:</td>
      <td><input type="checkbox" name="editauftraganhaengen" id="editauftraganhaengen" value="1">&nbsp;&nbsp;<i>{|Als Anhang in Bestellung.|}</i></td>
    </tr>
    <tr>
      <td>{|inkl. Auftrag als CSV|}:</td>
      <td><input type="checkbox" name="editauftragcsv" id="editauftragcsv" value="1">&nbsp;&nbsp;<i>{|Als Anhang in Bestellung.|}</i></td>
    </tr>
    <tr>
      <td>{|Bestellung abschlie&szlig;en|}:</td>
      <td><input type="checkbox" name="editbestellungabschliessen" id="editbestellungabschliessen" value="1"></td>
    </tr>
  </table>
</fieldset>



<fieldset><legend>{|Aktionen|}</legend>
  <table class="mkTableFormular">
    <tr>
      <td>{|Bestellung Mail an Lieferant|}:</td>
      <td><input type="checkbox" name="editbestellungmail" id="editbestellungmail" value="1">&nbsp;&nbsp;<i>{|Inkl. Anhang.|}</i></td>
    </tr>
    <tr>
      <td>{|Bestellung direkt drucken|}:</td>
      <td><select name="editbestellungdrucken" id="editbestellungdrucken">[SELECTBESTELLUNG]</select></td>
    </tr>

    <tr class="lieferung">
      <td>{|Lieferschein Mail an Lieferant|}:</td>
      <td><input type="checkbox" name="editlieferscheinmail" id="editlieferscheinmail" value="1"></td>
    </tr>
    <tr class="lieferung">
      <td>{|Lieferschein direkt drucken|}:</td>
      <td><select name="editlieferscheindrucken" id="editlieferscheindrucken">[SELECTLIEFERSCHEIN]</select></td>
    </tr>


    <tr class="rechnung">
      <td>{|Rechnung Mail an Kunden|}:</td>
      <td><input type="checkbox" name="editrechnungmail" id="editrechnungmail" value="1"></td>
    </tr>
    <tr class="rechnung">
      <td>{|Rechnung direkt drucken|}:</td>
      <td><select name="editrechnungdrucken" id="editrechnungdrucken">[SELECTRECHNUNG]</select></td>
    </tr>

    <tr>
      <td>{|R&uuml;ckmeldung an Shop|}:</td>
      <td><input type="checkbox" name="editrueckmeldungshop" id="editrueckmeldungshop" value="1"></td>
    </tr>
    <tr><td colspan="2">&nbsp;<!-- Leerzeilen wegen Tooltip --></td></tr>
    <tr><td colspan="2">&nbsp;</td></tr>
  </table>
</fieldset>






</div>

<div class="row">
<div class="row-height">
<div class="col-xs-12 col-md-10 col-md-height">
<div class="inside-white inside-full-height">
  [TAB1]
</div>
</div>
<div class="col-xs-12 col-md-2 col-md-height">
<div class="inside inside-full-height">
  <fieldset>
    <legend>{|Aktionen|}</legend>
    <input type="button" name="anlegen" class="btnGreenNew" value="{|&#10010; Neuen Eintrag anlegen|}" onclick="DropshippingGruppeEdit(0);">
  </fieldset>
</div>
</div>
</div>
</div>

</div>



<!-- tab view schließen -->
</div>
