<img src="themes/new/images/settings.svg" style="display:none;" title="Ebay Einstellungen" onclick="openebayeinstellungen[NR]();"  class="onlinshopbutton[NR]" />

<div id="divebaykategoriesuche[NR]" style="display:none;">
<fieldset><legend>Kategorievorschlag</legend>
<table id="categorytable">
  [KATEGORIEVORSCHLAG]
</table>
</fieldset>
<fieldset><legend>Manuelle Kategoriesuche</legend>
<table><tr><td>Suche:<td/><td><input type="text" id="ebay[NR]_manuellesuchetext" name="ebay[NR]_manuellesuchetext"><td/><td><input type="submit" id="manuellesuche" name="manuellesuche" value="Suche starten" onclick="manuellesuche[NR]()"></td></tr></table>
</fieldset>
</div>


<div id="divebayeinstellungen[NR]" style="display:none;">
<table>
<!--<tr><td>Beschreibung:</td><td><textarea id="ebay[NR]_beschreibung" cols="50" rows="6"></textarea></td></tr>-->
</table>
  <fieldset><legend>Artikeldaten</legend>
    <table>
      <tr>
        <td width="120em;"> </td><td width="300em;"> </td>
        <td width="120em;"> </td><td width="300em;"> </td>
      </tr>
      <tr>
        <td>Primärkategorie:</td><td><input type="text" size="27" id="ebay[NR]_katprimaer" value="" /> <input type="button" onclick="openkategoriesuche[NR](1);" value="Suchen" /></td>
        <td>Primäre Storekategorie:</td><td><select id="ebay[NR]_storeprimcat" style="width: 23em">[STOREPRIMCAT]
          </select></td>
      </tr>
      <tr>
        <td>Sekundärkategorie:</td><td><input type="text" size="27" id="ebay[NR]_katsekundaer" value="" disabled="" /> <input type="button" onclick="openkategoriesuche[NR](2);" value="Suchen" disabled="disabled" /></td>
        <td>Sekundäre Storekategorie:</td><td><select id="ebay[NR]_storeseccat" style="width: 23em">[STORESECCAT]
          </select></td>
      </tr>
      <tr></tr>
      <tr>
        <td>Name überschreiben:</td><td><input type="text" id="ebay[NR]_ersatzname" size="27"><br /><small><i><div id="ebay[NR]_inputcounter">Maximal 80 Zeichen</div></i></small></td>
        <td>Frachtart:</td><td><select id="ebay[NR]_frachtart"><option value="ExtraLargePack">Paket</option><option value="PackageThickEnvelope">Gepolsterter Umschlag</option><option value="Letter">Brief</option></select></td>
      </tr>
      <tr>
        <td>Zustand:</td><td><select id="ebay[NR]_zustand" style="width: 23em">
          [ZUSTAND]
          </select></td>
        <td>Lieferzeit:</td><td><input type="text" id="ebay[NR]_lieferzeit" size="5" > Tage</td>
      </tr>
      <tr>
        <td>Zustandsbeschreibung: </td>
        <td colspan="3"><textarea id="ebay[NR]_zustandsbeschreibung" name="ebay[NR]_zustandsbeschreibung" cols="95"></textarea></td>
      </tr>
      <tr>
        <td>Sprache: </td><td><select id="ebay[NR]_sprache" name="ebay[NR]_sprache">[EBAYSPRACHE]</select></td>
      </tr>
    </table>
  </fieldset>

  <fieldset><legend>Kategoriespezifisch</legend>
      <table id="specificstable[NR]" style="border-collapse: collapse;">
        <tbody>[SPECIFICSPRIMARY]</tbody>
      </table>
  </fieldset>

  [VARIANTENAUSBLENDEN]
  <fieldset><legend>Varianten</legend>
    <table>
      <tbody>
      [VARIANTENEIGENSCHAFTEN]
      </tbody>
    </table>
  </fieldset>
  [VARIANTENAUSBLENDENENDE]

  <fieldset><legend>Zahlung</legend>
    <table>
      <tr>
        <td width="30em;"> </td><td width="370em;"> </td>
        <td width="30em;"> </td><td width="370em;"> </td>
      </tr>
      [ZAHLUNGSWEISEN]
    </table>  
  </fieldset>

  <fieldset><legend>Versand</legend>
    <table>
      <tr>
        <td width="30em;"> </td><td width="110em;"> </td><td width="260em;"> </td>
        <td width="30em;"> </td><td width="110em;"> </td><td width="260em;"> </td>
      </tr>
      [VERSANDOPTIONEN]
    </table> 
  </fieldset>

  <fieldset><legend>Rückgabe</legend>
    <table>
      <tr>
        <td width="30em;"> </td><td width="110em;"> </td><td width="300em;"> </td>
        <td width="30em;"> </td><td width="110em;"> </td><td width="300em;"> </td>
      </tr>
      [RUECKGABEOPTIONEN]
    </table> 
  </fieldset>

  <fieldset><legend>Auktionsdaten</legend>
    <table>
      <tr>
        <td width="120em;"> </td><td width="300em;"> </td>
        <td width="120em;"> </td><td width="300em;"> </td>
      </tr>
      <tr>
        <td>Auktionsart:</td><td><select id="ebay[NR]_auktionsart" style="width: 23em">
          <option value="festpreis" selected>Festpreis</option>
          <option value="festpreisstore" disabled="">Festpreis (Ebay-Store)</option>
          <option value="steigerungsauktion">Steigerungs-Auktion</option>
          </select></td>
        <td>Privatlisting:</td><td><input type="checkbox" id="ebay[NR]_privatlisting"/></td>
      </tr>
      <tr>
        [AUKTIONSDAUER]
        <td>Preisvorschlag:</td><td><input type="checkbox" id="ebay[NR]_preisvorschlag"/></td>
      </tr>

      <tr>
        <td>Galleriebilder:</td><td><select id="ebay[NR]_gallerie" style="width: 23em">
          <option value="Gallery" selected>Standard</option>
          <option value="Plus">Plus</option>
          <option value="Featured">Featured</option>
        </select></td>
        <td>eBay Plus:</td><td><input type="checkbox" id="ebay[NR]_ebayplus"/></td>
      </tr>
    </table>
  </fieldset>


  <fieldset><legend>Template</legend>
    <table>
      <tr>
        <td width="120em;"> </td><td width="300em;"> </td>
      </tr>
      <tr>
        <td>Vorlagen:</td><td><select id="ebay[NR]_template" style="width: 23em">
          [EBAYTEMPLATES]
        </select></td><td><input type="button" name="zeigetemplatevorschau" id="zeigetemplatevorschau" onclick="zeigevorschau[NR]();" value="Zeige Vorschau"></td>
      </tr>
    </table>
  </fieldset>

  <fieldset><legend>Bilder</legend>
    <input type="checkbox" id="ebay[NR]_bilderaktualisieren"><label for="ebay[NR]_bilderaktualisieren">Bilder aktualisieren</label><br /><br />
    <input type="checkbox" id="ebay[NR]_allebilder" onclick="allebilderauswaehlen[NR]();"><label for="ebay[NR]_allebilder">Alle Bilder auswählen</label><br /><br />
    <table>
      <tr>
        <td width="50em;">Senden</td><td width="50em"><center>Bild</center></td><td width="300em">Dateiname</td>
        <td width="50em">Senden</td><td width="50em"><center>Bild</center></td><td width="300em">Dateiname</td>
      </tr>
      [EBAYBILDER]
    </table>
  </fieldset>
</div>




<script>
  var kategorietyp = 0;

  $('#ebay[NR]_ersatzname').keyup(function() {
      console.log(this.value.length);
      let text = this.value.length + '/80 Zeichen';
      if(this.value.length > 80){
          text = '<b>'+text+'! Zeichenbegrenzung überschritten!</b>';
      }
      document.getElementById('ebay[NR]_inputcounter').innerHTML = text;
  });

  $(document).ready(function() {

    $('#divebaykategoriesuche[NR]').dialog({
      modal: true,
      autoOpen: false,
      minWidth: 940,
      title:'Kategorie Suche',
      buttons: {
        OK: function()
        {
          $('#divebaykategoriesuche[NR]').dialog('close');
        },
        ABBRECHEN: function() {
          $(this).dialog('close');
        }
      },
      close: function(event, ui){
        
      }
    });

    $('#divebayeinstellungen[NR]').dialog(
    {
      modal: true,
      autoOpen: false,
      minWidth: 940,
      title:'Ebay Einstellungen',
      buttons: {
        SPEICHERN: function()
        {
          $.ajax({
              url: 'index.php?module=shopimporter_ebay&action=list&cmd=ebayartikelsave&artikel=[ARTIKELID]&id=[SHOPID]',
              type: 'POST',
              dataType: 'json',
              data: {
                [DATASAVE]              
              },
              success: function(data) {
                $('#divebayeinstellungen[NR]').dialog('close');
              }
          });
        },
        ABBRECHEN: function() {
          $(this).dialog('close');
        }
      },
      close: function(event, ui){

      }
    });
  });
  
  function allebilderauswaehlen[NR]()
  {
    var wert = $('#ebay[NR]_allebilder').prop('checked');
    $('input[id^="ebay[NR]_bild"][id$="cb"]').prop('checked', wert);
  }
  function zeigevorschau[NR](){
    var templateid = $('#ebay[NR]_template').val();
    var sprache= $('#ebay[NR]_sprache').val();
    $.ajax({
        url: 'index.php?module=shopimporter_ebay&action=list&cmd=ebaygettemplate&sprache='+sprache+'&templateid='+templateid+'&artikel=[ARTIKELID]&shopid=[SHOPID]',
        type: 'POST',
        dataType: 'json',
        data: {

        },
        success: function(data) {
          var OpenWindow = window.open('','','');
          OpenWindow.document.write(data);
        }
    });

  }
  function openkategoriesuche[NR](nr)
  {
    kategorietyp = nr;
    $('#divebaykategoriesuche[NR]').dialog('open');
  }

  function manuellesuche[NR](){
    $("#categorytable > tbody").empty();
    $('#categorytable > tbody:last-child').append('<tr><td>Kategorien werden abgefragt...<br /><img width="200" height="150" src="../www/themes/new/images/loading.gif"/></td></tr>');


    $.ajax({
      url: 'index.php?module=shopimporter_ebay&action=list&cmd=categoryget&id=[SHOPID]&category='+document.getElementById('ebay[NR]_manuellesuchetext').value,
      type: 'POST',
      dataType: 'json',
      data: {   

      },
      success: function(data) {
        $("#categorytable > tbody").empty();
        if(data.length > 0){
          for (var i = 0; i < data.length; i++) {
            $('#categorytable > tbody:last-child').append('<tr><td><input style="min-width:20em;" onclick="kategorieuebertrag[NR](\''+data[i].buttontext+'\');" value="'+data[i].buttontext+'" type="submit"></td><td>'+data[i].parent+'</td></tr>');
          }

        }else{
          $('#categorytable > tbody:last-child').append('<tr><td>Es konnte leider keine passende Kategorie ermittelt werden</td></tr>');
        }

      }
    });

  }

  function kategorieuebertrag[NR](kategorienr)
  {
    $("#specificstable[NR] > tbody").empty();
    $('#specificstable[NR] > tbody:last-child').append('<tr><td>Kategoriedaten werden abgefragt...<br /><img width="200" height="150" src="../www/themes/new/images/loading.gif"/></td></tr>');
    $.ajax({
        url: 'index.php?module=shopimporter_ebay&action=list&cmd=ebaycategoryspecifics&artikel=[ARTIKELID]&id=[SHOPID]&category='.concat(kategorienr),
        type: 'POST',
        dataType: 'json',
        data: {
          kategorienr          
        },
        success: function(data) {
          $("#specificstable[NR] > tbody").empty();
          for (var i = 0; i < data.length; i++) {
            var fieldName = data[i].name;
            fieldName = fieldName + ':';
            if(data[i].mandatory === true){
              fieldName = fieldName + '<small><i><br/>Pflichtfeld</i></small>';
            }

            if(data[i].typ == 'SelectionOnly' || (data[i].typ === 'SELECTION_ONLY' && data[i].cardinality == 'SINGLE')){
              var options = '<option value="">Nicht übertragen</option>';
              for (var j = 0; j < data[i].options.length; j++) {
                options += "<option value=\"" + data[i].options[j] + "\">" + data[i].options[j] + "</option>";
              }
              $('#specificstable[NR] > tbody:last-child').append('<tr><td>' + fieldName + '<input type="hidden" id="ebay[NR]_specname' + i + '" value="' + data[i].name + ':" /></td><td><select id ="ebay[NR]_spec' + i + '">' + options + '</select></td><td> </td></tr>');

            }else if(data[i].typ === 'SELECTION_ONLY' && data[i].cardinality == 'MULTI') {
              var options = '';
              for (var j = 0; j < data[i].options.length; j++) {
                if (options.length == 0) {
                  options += data[i].options[j];
                } else {
                  options += ', ' + data[i].options[j];
                }
              }
              suffix = '<b>Erlaubte Werte (bei Mehrfachauswahl getrennt durch Semikolon):</b>';
              suffix += '<br />' + options;
              $('#specificstable[NR] > tbody:last-child').append('<tr><td>' + fieldName + '<input type="hidden" id="ebay[NR]_specname' + i + '" value="' + data[i].name + '" /></td><td><input type="text" size="27" id="ebay[NR]_spec' + i + '" value="" /></td><td><small>' + suffix + '</small></td></tr>');

            }else if(data[i].typ == 'Zustand'){
              var options = '';
              for (var j = 0; j < data[i].options.length; j++) {
                options += "<option value=\"" + data[i].options[j].wert + "\">" + data[i].options[j].name + "</option>";
              }
              document.getElementById("ebay[NR]_zustand").innerHTML = options;

            }else{
              var datalist = '<datalist id="list'+ i +'">';
              for (var j = 0; j < data[i].options.length; j++) {
                datalist += '<option value="' + data[i].options[j]+ '">'
              }
              datalist += "</datalist>";
              $('#specificstable[NR] > tbody:last-child').append('<tr><td>' + fieldName + '<input type="hidden" id="ebay[NR]_specname' + i + '" value="' + data[i].name + '" /></td><td><input type="text" size="27" id="ebay[NR]_spec' + i + '" value="" list="list'+ i +'"/>' + datalist + '</td><td> </td></tr>');
            }
          }
        }
    });

    if(kategorietyp == 1){
      document.getElementById('ebay[NR]_katprimaer').value = kategorienr;
    }else{
      document.getElementById('ebay[NR]_katsekundaer').value = kategorienr;
    }
    
    $('#divebaykategoriesuche[NR]').dialog('close');
  }
  function openebayeinstellungen[NR]()
  {
    $.ajax({
        url: 'index.php?module=shopimporter_ebay&action=list&cmd=ebayartikelget&artikel=[ARTIKELID]&id=[SHOPID]',
        type: 'POST',
        dataType: 'json',
        data: {},
        success: function(data) {
          $('#divebayeinstellungen[NR]').dialog('open');
          [DATAGET]



          if(data.auktionsart.length == 0){
            document.getElementById('ebay[NR]_auktionsart').value = 'festpreis';
          }
          if(data.gallerie.length == 0){
            document.getElementById('ebay[NR]_gallerie').value = 'Gallery';
          }  

          if(data.template.length == 0){
            document.getElementById('ebay[NR]_template').value = '0';
          }else{
            if(!$('#ebay[NR]_template option[value="'+data.template+'"]').prop("selected", true).length){
              document.getElementById('ebay[NR]_template').value = '0';
            }else{
              document.getElementById('ebay[NR]_template').value = data.template;
            }
          }
          if(data.auktionsdauer.length == 0){
            document.getElementById('ebay[NR]_auktionsdauer').value = '0';
          }else{
            if(!$('#ebay[NR]_auktionsdauer option[value="'+data.auktionsdauer+'"]').prop("selected", true).length){
              document.getElementById('ebay[NR]_auktionsdauer').value = '0';
            }else{
              document.getElementById('ebay[NR]_auktionsdauer').value = data.auktionsdauer;
            }
          }
          if(data.storeprimcat.length == 0){
            document.getElementById('ebay[NR]_storeprimcat').value = '0';
          }else{
            if(!$('#ebay[NR]_storeprimcat option[value="'+data.storeprimcat+'"]').prop("selected", true).length){
              document.getElementById('ebay[NR]_storeprimcat').value = '0';
            }else{
              document.getElementById('ebay[NR]_storeprimcat').value = data.storeprimcat;
            }
          } 
          if(data.storeseccat.length == 0){
            document.getElementById('ebay[NR]_storeseccat').value = '0';
          }else{
            if(!$('#ebay[NR]_storeseccat option[value="'+data.storeseccat+'"]').prop("selected", true).length){
              document.getElementById('ebay[NR]_storeseccat').value = '0';
            }else{
              document.getElementById('ebay[NR]_storeseccat').value = data.storeseccat;
            }
          }


          if(data.zahlungsweisenbupo.length == 0){
            var tmpval = $('#ebay[NR]_zahlungsweisenbupo option:first-child').val();
            if(tmpval != undefined){
              document.getElementById('ebay[NR]_zahlungsweisenbupo').value = tmpval;
            }
          }else{
            if(!$('#ebay[NR]_zahlungsweisenbupo option[value="'+data.zahlungsweisenbupo+'"]').prop("selected", true).length){
              var tmpval = $('#ebay[NR]_zahlungsweisenbupo option:first-child').val();
              document.getElementById('ebay[NR]_zahlungsweisenbupo').value = tmpval;
            }else{
              document.getElementById('ebay[NR]_zahlungsweisenbupo').value = data.zahlungsweisenbupo;
            }
          } 
          if(data.versandbupo.length == 0){
            var tmpval = $('#ebay[NR]_versandbupo option:first-child').val();
            if(tmpval != undefined){
              document.getElementById('ebay[NR]_versandbupo').value = tmpval;
            }
          }else{
            if(!$('#ebay[NR]_versandbupo option[value="'+data.versandbupo+'"]').prop("selected", true).length){
              var tmpval = $('#ebay[NR]_versandbupo option:first-child').val();
              document.getElementById('ebay[NR]_versandbupo').value = tmpval;
            }else{
              document.getElementById('ebay[NR]_versandbupo').value = data.versandbupo;
            }
          }
          if(data.frachtart.length == 0){
            var tmpval = $('#ebay[NR]_frachtart option:first-child').val();
            if(tmpval != undefined){
              document.getElementById('ebay[NR]_frachtart').value = tmpval;
            }
          }
          if(data.rueckgabebupo.length == 0){
            var tmpval = $('#ebay[NR]_rueckgabebupo option:first-child').val();
            if(tmpval != undefined){
              document.getElementById('ebay[NR]_rueckgabebupo').value = tmpval;
            }
          }else{
            if(!$('#ebay[NR]_rueckgabebupo option[value="'+data.rueckgabebupo+'"]').prop("selected", true).length){
              var tmpval = $('#ebay[NR]_rueckgabebupo option:first-child').val();
              document.getElementById('ebay[NR]_rueckgabebupo').value = tmpval;
            }else{
              document.getElementById('ebay[NR]_rueckgabebupo').value = data.rueckgabebupo;
            }
          } 
        },
        beforeSend: function() {

        }
    });
  
    $('#divebayeinstellungen[NR]').dialog('open');
  }
</script>
