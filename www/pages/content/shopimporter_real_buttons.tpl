<img src="themes/new/images/settings.svg" style="display:none;" title="Real Einstellungen" onclick="openrealeinstellungen[NR]();"  class="onlinshopbutton[NR]" />


<div id="divrealeinstellungen[NR]" style="display:none;">
<table>

</table>
  <fieldset><legend>Artikeldaten</legend>
    <table style="float:left;">
      <tr>
        <td width="200em;"> </td><td width="300em;"> </td>
      </tr>
      <tr>
        <td>Kategorie</td><td><input type="text" size="27" id="real[NR]_kategorie" value="[KATEGORIE]" disabled="disabled" /> <input type="button" onclick="openkategoriesuche[NR](0);" value="Suchen" /></td>
      </tr>
      <tr></tr>
      <tr>
        <td>Versandgruppe:</td><td><select id="real[NR]_versandgruppe" style="width: 23em">
            [VERSANDGRUPPEN]
          </select></td>
      </tr>
      <tr>
        <td>Zustand:</td><td><select id="real[NR]_zustand" style="width: 23em">
          [ZUSTAND]
          </select></td>
      </tr>
      <tr>
        <td>Zustandsbeschreibung:<br /><small><i>(nur für gebrauchter Ware)</i></small></td><td><input type="text" name="real[NR]_zustandsbeschreibung" id="real[NR]_zustandsbeschreibung" value="[ZUSTANDSBESCHREIBUNG]"></td>
      </tr>
      <tr>
        <td>Minimale Lieferzeit:<br/><small><i>(in Tagen)</i></small></td><td><input type="text" name="real[NR]_minlieferzeit" id="real[NR]_minlieferzeit" value="[MINLIEFERZEIT]" size="5"></td>
      </tr>
      <tr>
        <td>Maximale Lieferzeit:<br/><small><i>(in Tagen)</i></small></td><td><input type="text" name="real[NR]_maxlieferzeit" id="real[NR]_maxlieferzeit" value="[MAXLIEFERZEIT]" size="5"></td>
      </tr>
    </table>
  </fieldset>

  <fieldset><legend>Attribute</legend>
    <div>
      <table id="attributetablemandatorisch" style="float: left;">
        <tbody>
          [ATTRIBUTEMANDATORISCH]
        </tbody>
      </table>
      <table id="attributetableoptional" style="float: left;">
        <tbody>
          [ATTRIBUTEOPTIONAL]
        </tbody>
      </table>
      <table style="clear: both;">
        <tr>
          <td><b>Platzhalter:</b></td>
        </tr>
        <tr>  
          <td><small><i>(Platzhalter werden mit den entsprechenden Werten aus den Artikeldaten befüllt)</i></small></td>
        </tr>
        <tr>  
          <td colspan="2">[WILDCARDS]</td>
        </tr>
      </table>
    </div>
  </fieldset>

  <fieldset><legend>Produktdaten</legend>
    <input type="button" style="min-width:150px;" onclick="produktdatenstatus[NR]();" value="Status Produktdaten abfragen" />
    <table id="produktdatenstatus">
     <tbody></tbody>
    </table>
  </fieldset>


</div>

<div id="divrealkategoriesuche[NR]" style="display:none;">
<fieldset><legend>Optionen</legend>
  <input type="button" style="min-width:150px;" onclick="openkategoriesuche[NR](1);" value="{|Kategorievorschlag neu ermitteln|}" />
</fieldset>  
<fieldset><legend>Kategorievorschlag</legend>
<table id ="kategorievorschlagtable">
  <tbody>
  [KATEGORIEVORSCHLAG]
  </tbody>
</table>
<small><i>Beim auswählen einer Kategorie werden die bisher eingestellten Attribute gelöscht, bzw. ersetzt.</i></small>
</fieldset>
<fieldset><legend>Manuelle Kategoriesuche</legend>
<input type="text" name="real[NR]_manuellesuchetext" id="real[NR]_manuellesuchetext"><input type="button" id="manuellesuche" name="manuellesuche" value="Suche starten" onclick="manuellesuche[NR]()">  
</fieldset>
</div>



<script>
  $(document).ready(function() {

    $('#divrealkategoriesuche[NR]').dialog({
      modal: true,
      autoOpen: false,
      minWidth: 940,
      title:'Kategorie Suche',
      buttons: {
        ABBRECHEN: function() {
          $(this).dialog('close');
        }
      },
      close: function(event, ui){
        
      }
    });
    
    
    $('#divrealeinstellungen[NR]').dialog(
    {
      modal: true,
      autoOpen: false,
      minWidth: 940,
      title:'Real Einstellungen',
      buttons: {
        SPEICHERN: function()
        {
          $.ajax({
              url: 'index.php?module=shopimporter_real&action=ajax&cmd=realartikelsave&artikel=[ARTIKELID]&id=[SHOPID]',
              type: 'POST',
              dataType: 'json',
              data: {
                [DATASAVE]              
              },
              success: function(data) {

                $('#divrealeinstellungen[NR]').dialog('close');
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


  function produktdatenstatus[NR]() {
    $("#produktdatenstatus > tbody").empty();
    $('#produktdatenstatus > tbody:last-child').append('<tr><td><img width="120" height="90" src="../www/themes/new/images/loading.gif"/></td></tr>');
    $.ajax({
      url: 'index.php?module=shopimporter_real&action=ajax&cmd=realholeproduktdatenstatus&artikel=[ARTIKELID]&id=[SHOPID]',
      type: 'POST',
      dataType: 'json',
      data: {},
      success: function(data) {
        $("#produktdatenstatus > tbody").empty();
        if(data.status == 'fail'){
          $('#produktdatenstatus > tbody:last-child').append('<tr><td>'+data.value+'</td></tr>');
        }else{
          if(data.value.attribute.length > 0){
            $('#produktdatenstatus > tbody:last-child').append('<tr><td colspan="4"><b>Attribute:</b></td></tr>');
            $('#produktdatenstatus > tbody:last-child').append('<tr><td>Attribut</td><td>Wert</td><td>Status</td><td>Begründung</td></tr>');

            for (var i = 0; i < data.value.attribute.length; i++) {
              $('#produktdatenstatus > tbody:last-child').append('<tr><td>'+data.value.attribute[i].attribute+'</td><td>'+data.value.attribute[i].value+'</td><td>'+data.value.attribute[i].state+'</td><td>'+data.value.attribute[i].message+'</td></tr>');
            }
          }
          if(data.value.missing > 0){
            $('#produktdatenstatus > tbody:last-child').append('<tr><td colspan="4"><b>Fehlende Attribute:</b></td></tr>');
            $('#produktdatenstatus > tbody:last-child').append('<tr><td colspan="4"><b>Es fehlen'+data.value.missing+' Attribute.</b></td></tr>');
          }
        }

      },
      beforeSend: function() {

      }
    });
  }

  function openkategoriesuche[NR](neuholen)
  {
    $("#kategorievorschlagtable > tbody").empty();
    $('#kategorievorschlagtable > tbody:last-child').append('<tr><td>Kategoriedaten werden abgefragt...<br /><img width="200" height="150" src="../www/themes/new/images/loading.gif"/></td></tr>');
    $.ajax({
      url: 'index.php?module=shopimporter_real&action=ajax&cmd=realholekategorievorschlag&artikel=[ARTIKELID]&id=[SHOPID]&neuholen='+neuholen,
      type: 'POST',
      dataType: 'json',
      data: {},
      success: function(data) {
        $("#kategorievorschlagtable > tbody").empty();
        for (var i = 0; i < data.length; i++) {
          $('#kategorievorschlagtable > tbody:last-child').append('<tr><td><input type="button" style="min-width:20em;" value="'+data[i].vorschlagbezeichnung+'" onclick="kategorieuebertrag[NR]('+data[i].vorschlagid+',\''+data[i].vorschlagbezeichnung+'\');"/></td></tr>'); 
        }
      },
      beforeSend: function() {

      }
    });
    $('#divrealkategoriesuche[NR]').dialog('open');
  }


  function manuellesuche[NR](){
    $("#kategorievorschlagtable > tbody").empty();
    $('#kategorievorschlagtable > tbody:last-child').append('<tr><td>Kategorien werden abgefragt...<br /><img width="200" height="150" src="../www/themes/new/images/loading.gif"/></td></tr>');


    $.ajax({
      url: 'index.php?module=shopimporter_real&action=list&cmd=categoryget&id=[SHOPID]&category='+document.getElementById('real[NR]_manuellesuchetext').value,
      type: 'POST',
      dataType: 'json',
      data: {
        
      },
      success: function(data) {
        $("#kategorievorschlagtable > tbody").empty();
        if(data.length > 0){
          for (var i = 0; i < data.length; i++) {
            $('#kategorievorschlagtable > tbody:last-child').append('<tr><td><input style="min-width:20em;" onclick="kategorieuebertrag[NR]('+data[i].categoryid+',\''+data[i].buttontext+'\');" value="'+data[i].buttontext+'" type="button"></td><td>'+data[i].parent+'</td></tr>');
          }
        }else{
          $('#kategorievorschlagtable > tbody:last-child').append('<tr><td>Es konnte leieder keine passende Kategorie ermittelt werden</td></tr>');
        }

      }
    });

  }



  function kategorieuebertrag[NR](vorschlagid, vorschlagbezeichnung)
  {
    $('#divrealeinstellungen[NR]').find('#real[NR]_kategorie').val(vorschlagid+' : '+vorschlagbezeichnung);

    $("#attributetablemandatorisch > tbody").empty();
    $("#attributetableoptional > tbody").empty(); 

    $('#attributetablemandatorisch > tbody:last-child').append('<tr><td>Kategoriedaten werden abgefragt...<br /><img width="200" height="150" src="../www/themes/new/images/loading.gif"/></td></tr>');
    $.ajax({
      url: 'index.php?module=shopimporter_real&action=ajax&cmd=realholekategoriespezifisch&artikel=[ARTIKELID]&id=[SHOPID]&cat='+vorschlagid,
      type: 'POST',
      dataType: 'json',
      data: {},
      success: function(data) {
        $("#attributetablemandatorisch > tbody").empty(); 
        $('#attributetablemandatorisch > tbody:last-child').append('<tr><td><b>Mandatorische Attribute:</b></td></tr>'); 
        counter = 0;
        for (var i = 0; i < data.mandatory.length; i++) {
          counter = i;
          $('#attributetablemandatorisch > tbody:last-child').append('<tr><td>' + data.mandatory[i].title + ':</td><input type="hidden" id="real[NR]_specbezeichnung' + counter + '" value="' + data.mandatory[i].title + '" /><td><input type="text" size="27" id="real[NR]_specwert' + i + '" value="' + data.mandatory[i].value + '" /></td></tr>');
           
        }
        $('#attributetableoptional > tbody:last-child').append('<tr><td><b>Optionale Attribute:</b></td></tr>');  
        for (var i = 0; i < data.optional.length; i++) {
          counter = counter +1;
          $('#attributetableoptional > tbody:last-child').append('<tr><td>' + data.optional[i].title + ':</td><td><input type="hidden" id="real[NR]_specbezeichnung' + counter + '" value="' + data.optional[i].title + '" /><input type="text" size="27" id="real[NR]_specwert' + counter + '" value="' + data.optional[i].value + '"/></td></tr>');
        }

      },
      beforeSend: function() {

      }
    });
       $('#divrealkategoriesuche[NR]').dialog('close');
  }
  function openrealeinstellungen[NR]()
  {
    $.ajax({
        url: 'index.php?module=shopimporter_real&action=ajax&cmd=realartikelget&artikel=[ARTIKELID]&id=[SHOPID]',
        type: 'POST',
        dataType: 'json',
        data: {},
        success: function(data) {
          $("#attributetablemandatorisch > tbody").empty(); 
          $("#attributetableoptional > tbody").empty(); 

          $('#attributetablemandatorisch > tbody:last-child').append('<tr><td><b>Mandatorische Attribute:</b></td></tr>'); 
          counter = 0;
          for (var i = 0; i < data.mandatory.length; i++) {
            counter = i;
            if(data.mandatory[i].fields.length > 0){
              var fields = '<select id="real[NR]_fields' + i + '">';
              for (var j = data.mandatory[i].fields.length - 1; j >= 0; j--){
                var optionselected = "";
                if(data.mandatory[i].fieldvalue == data.mandatory[i].fields[j].englisch){
                  optionselected = "selected";
                }
                fields += '<option value="'+data.mandatory[i].fields[j].englisch+'" '+optionselected+'>'+data.mandatory[i].fields[j].deutsch+'</option>';
                data.mandatory[i].fields[i]

              }
              fields += '</select>';

              $('#attributetablemandatorisch > tbody:last-child').append('<tr><td>' + data.mandatory[i].title + ':</td><input type="hidden" id="real[NR]_specbezeichnung' + counter + '" value="' + data.mandatory[i].title + '" /><td><input type="text" size="20" id="real[NR]_specwert' + i + '" value="' + data.mandatory[i].value +'" /> '+fields+'</td></tr>');
            }else{
              $('#attributetablemandatorisch > tbody:last-child').append('<tr><td>' + data.mandatory[i].title + ':</td><input type="hidden" id="real[NR]_specbezeichnung' + counter + '" value="' + data.mandatory[i].title + '" /><td><input type="text" size="34" id="real[NR]_specwert' + i + '" value="' + data.mandatory[i].value +'" /></td><input type="hidden" id="real[NR]_fields" value=""></tr>');
            }
          }
          $('#attributetableoptional > tbody:last-child').append('<tr><td><b>Optionale Attribute:</b></td></tr>');  
          for (var i = 0; i < data.optional.length; i++) {
            counter = counter +1;
            if(data.optional[i].fields.length > 0){
              var fields = '<select id="real[NR]_fields' + counter + '">';
              for (var j = data.optional[i].fields.length - 1; j >= 0; j--){
                var optionselected = "";
                if(data.optional[i].fieldvalue == data.optional[i].fields[j].englisch){
                  optionselected = "selected";
                }
                fields += '<option value="'+data.optional[i].fields[j].englisch+'" '+optionselected+'>'+data.optional[i].fields[j].deutsch+'</option>';
                data.optional[i].fields[i]
              }
              fields += '</select>';

              $('#attributetableoptional > tbody:last-child').append('<tr><td>' + data.optional[i].title + ':</td><td><input type="hidden" id="real[NR]_specbezeichnung' + counter + '" value="' + data.optional[i].title + '" /><input type="text" size="20" id="real[NR]_specwert' + counter + '" value="' + data.optional[i].value + '"/> '+fields+'</td></tr>');
            }else{  
              $('#attributetableoptional > tbody:last-child').append('<tr><td>' + data.optional[i].title + ':</td><td><input type="hidden" id="real[NR]_specbezeichnung' + counter + '" value="' + data.optional[i].title + '" /><input type="text" size="34" id="real[NR]_specwert' + counter + '" value="' + data.optional[i].value + '"/></td><input type="hidden" id="real[NR]_fields" value=""></tr>');
            }
          }
        },
        beforeSend: function() {

        }
    });
  
    $('#divrealeinstellungen[NR]').dialog('open');
  }
</script>