<div id="tabs">
    <ul>
        <li><a href="#tabs-1">[TABTEXT]</a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
<div id="ajaxmessage"></div>
[MESSAGE]
  <form method="POST" id="">
    <table border="0" width="100%" cellpadding="0" cellspacing="0">
      <tr>
        <td>
          <table width="100%" align="center" style="background-color:#cfcfd1;">
            <tr>
              <td width="33%"></td>
              <td align="center" nowrap><b style="font-size: 14pt">{|Adresse|} <font color="blue">[KUNDE]</font></b> {|Kunde|}: [KUNDENNUMMER]</td>
              <td width="33%" align="right">&nbsp;</td>
            </tr>
          </table>
          <div style="height:10px"></div>

        </td>
      </tr>
    </table>
[TAB1]

    <fieldset>
      <legend>{|Stapelverarbeitung|}</legend>
      <input type="hidden" id="adressid" name="adressid" value="[ADRESSID]">
      <table width="100%">
        <tr>
          <td>
            <table align="left">
              <tr>
                <td><input type="checkbox" id="auswahlalle" onchange="alleauswaehlen();" />&nbsp;{|alle markieren|}</td>
              </tr>
            </table>
          </td>
          <td>
            <table align="left">
              <tr>
                <td width="20"><input type="radio" name="ld" checked="checked" value="ls" /></td><td>{|Lieferdatum aus Lieferschein|}</td>
              </tr>
              <tr>
                <td><input type="radio" name="ld" value="af" /></td><td>{|Lieferdatum aus Lieferscheinpositionen|}</td>
              </tr>
            </table>
          </td>
          <td>
            <table align="left">
              <tr>
                <td></td><td><input type="submit" name="speichern" class="btnBlue" value="markierte Positionen in Sammelrechnung nehmen"></td>
                <td><input type="checkbox" name="ihrebestellnummer" id="ihrebestellnummer" value="1" [IHREBESTELLNUMMER]>&nbsp;{|inkl. Ihre Bestellnummer|}</td>  
                <td><input type="checkbox" name="auftragsnummer" id="auftragsnummer" value="1" [AUFTRAGSNUMMER]>&nbsp;{|inkl. Auftragsnummer|}</td>
                <td><input type="checkbox" name="mitversandkosten" id="mitversandkosten" value="1" [MITVERSANDKOSTEN]>&nbsp;<span id="sammelrechnung_portoartikel">{|inkl. aller offenen Portoartikel aus Auftrag|}</span></td>
              </tr>
            </table>
  </form>
          </td>
          <td>
            <form method="POST" id="zuruecksetzen">
              <table>
                <tr>
                  <td width="20"></td><td><input type="submit" name="kostenlossetzen" class="btnBlue" value="markierte Positionen auf nicht abrechnen setzen" /></td>
                </tr>
              </table>
            </form>

          </td>
        </tr>
      </table>
    </fieldset>

[TAB1NEXT]




<script>
$( "form#zuruecksetzen" ).submit(function( event ) {
  if ( confirm('Wollen Sie die Positionen wirklich auf kostenlos setzen?')) {
    return;
  }
  event.preventDefault();
});
function chcb(id)
{
  setTimeout(function() {
  var v = $('#cb_'+id).is(':checked');
  var p = $('#auswahl_'+id).val();
  if(v == false)v = 0;
  $.ajax({
    url: 'index.php?module=sammelrechnung&action=edit&cmd=chcb',
    type: 'POST',
    dataType: 'json',
    data: { lid: id, wert:v, preis:p, adresse:[ID] },
    success: function(data) {
      if(data == null)
      {
        $('#ajaxmessage').html('<div class="error">&Uuml;bertragungsfehler: Wert konnte nicht gesetzt werden!</div>');
      } else
      {
        if(typeof data.status == null)
        {
          $('#ajaxmessage').html('<div class="error">Fehler beim Setzen!</div>');
        } else {
          if(data.status != 1)$('#ajaxmessage').html('<div class="error">Fehler beim Setzen</div>');
        }
      }
    },
    error: function() {
      $('#ajaxmessage').html('<div class="error">&Uuml;bertragungsfehler: Wert konnte nicht gesetzt werden!</div>');
      }
  });  
  },500);
}
function chmenge(id)
{
  var v = $('#auswahl_'+id).val();
  if(v == '')v = 0;
  var v2 = parseFloat(v.replace(',','.') );
  if(isNaN(v2))
  {
    v2 = 0;
    v = 0;
  }
  var mnge = parseFloat($('#auswahl_'+id).parent('td').next().next().html());
  $('#auswahl_'+id).parent('td').next().html((v2*mnge).toFixed(2) );
  
  
  $.ajax({
    url: 'index.php?module=sammelrechnung&action=edit&cmd=chmenge',
    type: 'POST',
    dataType: 'json',
    data: { lid: id, wert:v,adresse:[ID] },
    success: function(data) {
      if(data == null)
      {
        $('#ajaxmessage').html('<div class="error">&Uuml;bertragungsfehler: Wert konnte nicht gesetzt werden!</div>');
      } else{
        if(typeof data.status == null)
        {
          $('#ajaxmessage').html('<div class="error">Fehler beim Setzen!</div>');
        } else {
          if(data.status != 1)$('#ajaxmessage').html('<div class="error">Fehler beim Setzen</div>');
        }
      }
    },
    error: function() {
      $('#ajaxmessage').html('<div class="error">&Uuml;bertragungsfehler: Wert konnte nicht gesetzt werden!</div>');
      }
  }); 
}



function alleauswaehlen()
{
  var cbs = [];
  $("#sammelrechnung_edit").find("input:checkbox").each(function(){ cbs.push(this.id); });
      
  var wert = $('#auswahlalle').prop('checked');
  $('#sammelrechnung_edit').find(':checkbox').prop('checked',wert);
  $('#tabs-1').loadingOverlay('show');
  $.ajax({
    url: 'index.php?module=sammelrechnung&action=edit&cmd=allemarkieren',
    type: 'POST',
    dataType: 'json',
    data: {markiert : wert,
           cbs : cbs,
           adressid : $('#adressid').val(),
          },
    success: function(data){
      //if(data == null)
      //{
        //$('#ajaxmessage').html('<div class="error">Fehlende Rechte: Wert konnte nicht gesetzt werden!</div>');
      //}else{
        if(typeof data.status == null)
        {
          $('#ajaxmessage').html('<div class="error">Fehler beim Setzen!</div>');
        }else{
          if(data.status != 1)$('#ajaxmessage').html('<div class="error">Fehler beim Setzen</div>');
        }
      $('#tabs-1').loadingOverlay('remove');
    },
    beforeSend: function(){

    }
  });

}



</script>
</div>

<!-- tab view schließen -->
</div>

