<style>
  #tableuebernehmen > tr > td 
  {
    vertical-align: text-top;
  }
</style>
<div id="tabs">
  <ul>
    <li><a href="#tabs-1">{|Details|}</a></li>
    <li><a href="#tabs-2">{|Preisentwicklung|}</a></li>
    <li><a href="#tabs-3">{|Diagramm|}</a></li>
    <li><a href="#tabs-4">{|Einstellungen|}</a></li>
  </ul>
  <div id="tabs-1">
  [MESSAGE]
  <div id="messagejson"></div>
  <table width="100%">
    <tr>
      <td>
        <div class="row">
        <div class="row-height">
        <div class="col-xs-12 col-md-10 col-md-height">
        <div class="inside inside-full-height">

          <div class="filter-box filter-usersave">
            <div class="filter-block filter-inline">
              <div class="filter-title">{|Filter|}</div>
              <ul class="filter-list">
                <li class="filter-item">
                  <label for="f_gesperrt" class="switch">
                    <input type="checkbox" value="1" id="f_gesperrt" />
                    <span class="slider round"></span>
                  </label>
                  <label for="f_gesperrt">{|Archivierte Kosten anzeigen|}</label>
                </li>
                <li class="filter-item">
                  <label for="f_einkaufausblenden" class="switch">
                    <input type="checkbox" value="1" id="f_einkaufausblenden" />
                    <span class="slider round"></span>
                  </label>
                  <label for="f_einkaufausblenden">{|Einkauf ausblenden|}</label>
                </li>
                <li class="filter-item">
                  <label for="f_nureinkaufanzeigen" class="switch">
                    <input type="checkbox" value="1" id="f_nureinkaufanzeigen" />
                    <span class="slider round"></span>
                  </label>
                  <label for="f_nureinkaufanzeigen">{|nur Einkauf anzeigen|}</label>
                </li>
              </ul>
            </div>
          </div>

        </div>
        </div>
        <div class="col-xs-12 col-md-2 col-md-height">
        <div class="inside inside-full-height">
          <fieldset>
            <legend>{|Aktionen|}</legend>
            <input type="button" class="btnGreenNew" onclick="ArtikelkalkulationEdit(0);" value="&#10010; {|Neuen Eintrag anlegen|}" />
          </fieldset>
        </div>
        </div>
        </div>
        </div>
      </td>
    </tr>
  </table>
  <br>
  [TAB1]
  <fieldset>
    <legend>{|Neuen Kalkulierten-EK Preis ausrechnen und übernehmen|}</legend>
    <table id="tableuebernehmen">
      <tr>
        <td>{|Menge f&uuml;r Kalkulation|}:</td>
        <td><input type="text" id="menge" name="menge" value="[MENGE]" onchange="vorschau();" size="10" onkeyup="vorschau();" /></td>
        <td>{|Errechneter St&uuml;ckpreis|}:</td>
        <td><input type="text" id="gesamtkosten" name="gesamtkosten" value="" size="12" /><input type="hidden" id="ids" value="" /></td>
        <td>{|W&auml;hrung|}:</td>
        <td><select id="waehrung" name="waehrung" onchange="vorschau();">[SELWAEHRUNG]</select></td>
        <td><input type="button" onclick="uebernehmen();" value="{|&Uuml;bernehmen|}" /></td>
      </tr>
    </table>
  </fieldset>

  [TAB1NEXT]
</div>
<div id="tabs-2">

  <div class="row">
  <div class="row-height">
  <div class="col-xs-12 col-md-10 col-md-height">
  <div class="inside_white inside-full-height">

    <fieldset class="white">
      <legend>&nbsp;</legend>
      [TAB2]
    </fieldset>

  </div>
  </div>
  <div class="col-xs-12 col-md-2 col-md-height">
  <div class="inside inside-full-height">

    <fieldset>
      <legend>{|Aktionen|}</legend>
      <input class="btnGreenNew" type="button" value="&#10010; {|Neuer Eintrag|}" onclick="ArtikelkalkulationTagEdit(0);" />
    </fieldset>
  </div>
  </div>
  </div>
  </div>


[TAB2NEXT]
</div>

<div id="tabs-3">
[TAB3]
[TAB3NEXT]
</div>

<div id="tabs-4">
  [TAB4]
  <fieldset><legend>{|Einstellungen|}</legend>
  <table>
    <!--<tr>
      <td>
        <input type="checkbox" value="1" [ARTIKELAUTOKALKULATION]  id="f_artderkosten" /><label for="f_artderkosten">&nbsp;{|EK basierend auf "Art der Kosten: Einkauf" automatisch ermitteln|}</label><br />
      </td>
    </tr>-->
    <tr>
      <td>
        <input type="checkbox" value="1" [ARTIKELABSCHLIESSENKALKULATION]  id="f_abschliessen" /><label for="f_abschliessen">&nbsp;{|Einkaufspreis beim Abschlie&szlig;en der Bestellung in Kalkulation &uuml;bernehmen|}</label><br />
      </td>
    </tr>
    <tr>
      <td>
        <input type="checkbox" value="1" [ARTIKELFIFOKALKULATION]  id="f_fifo" /><label for="f_fifo">&nbsp;{|Kalkulierter EK Preis automatisch nach FIFO Prinzip der letzten Eingänge den Mittelwert neu berechnen|}</label>
      </td>
    </tr>
  </table>
  </fieldset>
  [TAB4NEXT]
</div>
<!-- tab view schließen -->
</div>


  
<div id="editArtikelkalkulation" style="display:none;" title="{|Bearbeiten|}">
  <fieldset>
    <legend>&nbsp;</legend>
  <input type="hidden" id="e_id">
  <input type="hidden" id="e_artikelidneu" value="[ID]">
  <table>
    <tr>
      <td>{|Bezeichnung|}:</td>
      <td><input type="text" name="e_bezeichnung" id="e_bezeichnung" size="35"></td>        
    </tr>
    <tr>
      <td>{|Datum|}:</td>
      <td><input type="text" id="e_datum"></td>
    </tr>
    <tr>
      <td>{|Art der Kosten|}:</td>
      <td><select name="e_kostenart" id="e_kostenart">
            <option value="einmal">{|Einmal|}</option>
            <option value="prostueck">{|Pro Stück|}</option>
            <option value="prolos">{|Pro Los|}</option>
            <option value="einkauf">{|Einkauf|}</option>
          </select>
          <input type="button" name="ekladen" id="ekladen" value="{|EK laden|}" onclick="EKladen('[ID]');" />
          <input type="button" name="kalkekladen" id="kalkekladen" value="{|Kalk. EK laden|}" onclick="KalkEKLaden('[ID]');" />
      </td>
    </tr>
    <tr>
      <td>{|Kosten|}:</td>
      <td><input type="text" id="e_kosten"></td>
    </tr>
    <tr>
      <td>{|W&auml;hrung|}:</td>
      <td><input type="text" id="e_waehrung"></td>
    </tr>
    <tr class="fuermenge">
      <td>{|Für Menge|}:</td>
      <td><input type="text" id="e_menge" value="1"></td>
    </tr>
    <tr>
      <td>{|Interner Kommentar|}:</td>
      <td><textarea rows="4" cols="35" name="e_kommentar" id="e_kommentar"></textarea></td>
    </tr>
    <tr class="tre_automatischneuberechnen">
      <td></td>
      <td>
        <input type="checkbox" id="e_automatischneuberechnen" name="e_automatischneuberechnen" value="1" />&nbsp;
        {|In EK-Berechnung einschlie&szlig;en|}
      </td>
    </tr>
  </table>
  </fieldset>  
</div>

<div id="editArtikelkalkulationTag" style="display:none;" title="{|Bearbeiten|}">
  <fieldset>
    <legend></legend>
    <input type="hidden" id="e_idtag">
    <input type="hidden" id="e_artikelidneutag" value="[ID]">
    <table>
      <tr>
        <td>{|Berechneter EK|}:</td>
        <td><input type="text" name="e_preistag" id="e_preistag"></td>
      </tr>
      <tr>
        <td>{|Datum|}:</td>
        <td><input type="text" name="e_datumtag" id="e_datumtag"></td>
      </tr>
    </table>
  </fieldset>
</div>
<script type="text/javascript">
var kurse = Array();
[KURSE]

$(document).ready(function() {
    $('#e_bezeichnung').focus();
    $("#editArtikelkalkulation").dialog({
      modal: true,
      bgiframe: true,
      closeOnEscape:false,
      minWidth:500,
      autoOpen: false,
      buttons: {
        '{|ABBRECHEN|}': function() {
          $(this).dialog('close');
        },
        '{|SPEICHERN|}': function() {
          artikelkalkulationEditSave();
        }
      }
    });

    $("#editArtikelkalkulationTag").dialog({
      modal: true,
      bgiframe: true,
      closeOnEscape:false,
      minWidth:300,
      autoOpen: false,
      buttons: {
        '{|ABBRECHEN|}': function(){
          $(this).dialog('close');
        },
        '{|SPEICHERN|}': function(){
          artikelkalkulationTagEditSave();
        }
      }
    });


  setTimeout(function(){vorschau();},1000);
});



function fnArtderKosten(){
	$.ajax({
		url: 'index.php?module=artikelkalkulation&action=list&cmd=setartikelautokalkulation&id=[ID]',
		method: 'POST',
		dataType: 'json',
    data: {wert: $('#f_artderkosten').prop('checked')?1:0},
		success: function(data) {
      var oTable = $('#artikelkalkulation_list').DataTable( );
        oTable.ajax.reload();
		}
	});
}
  
function fnAbschliessen(){
	$.ajax({
		url: 'index.php?module=artikelkalkulation&action=list&cmd=setabschliessen&id=[ID]',
		method: 'POST',
		dataType: 'json',
    data: {wert: $('#f_abschliessen').prop('checked')?1:0},
		success: function(data) {
      var oTable = $('#artikelkalkulation_list').DataTable( );
        oTable.ajax.reload();
		}
	});
}

function fnFifo(){
	$.ajax({
		url: 'index.php?module=artikelkalkulation&action=list&cmd=setfifo&id=[ID]',
		method: 'POST',
		dataType: 'json',
    data: {wert: $('#f_fifo').prop('checked')?1:0},
		success: function(data) {
      var oTable = $('#artikelkalkulation_list').DataTable( );
        oTable.ajax.reload();
		}
	});
}




function vorschau()
{
  var ergcol = 6;
  var kostenartcol = 2;
  var kostencol = 3;
  var mengecol = 4;
  var waehrungcol = 8;
  var menucol = 9;
  var menge = parseFloat(($('#menge').val()+'').replace(',','.'));
  var waehrung = $('#waehrung').val();
  if(waehrung == '')waehrung = 'EUR';
  var kurs = 1;
  if(typeof kurse[waehrung] != 'undefined')kurs = parseFloat(kurse[waehrung]);
  var gesamtkosten = 0;
  var ids='';
  var preiseeinkauf = 0;
  var mengeeinkauf = 0;
  var summeeinkauf = 0;
  var gesamtkostenprostueck = 0;
  if(isNaN(menge))menge = 0;
  $('#artikelkalkulation_list > tbody > tr').each(function() {
    var children = $(this).children('td');
    if(menge == 0)
    {
      $(children[ ergcol ]).html('');
    }else{
      var elerg = 0;
      var kostenart = $(children[ kostenartcol ]).html();
      var waehrungel = $(children[ waehrungcol ]).html();
      if(waehrungel == '')waehrungel = 'EUR';
      var mengeel = parseFloat(($(children[ mengecol ]).html()+'').replace('.','').replace('.','').replace('.','').replace(',','.'));
      if(isNaN(mengeel))mengeel = 0;
      var preis = parseFloat(($(children[ kostencol ]).html()+'').replace('.','').replace('.','').replace('.','').replace(',','.'));
      if(isNaN(preis))preis = 0;
      var kursel = 1;
      if(typeof kurse[waehrungel] != 'undefined')kursel = parseFloat(kurse[waehrungel]);
	    switch(kostenart){
		    case 'Einkauf':
			    preiseeinkauf +=  preis * kurs / kursel;
			    mengeeinkauf +=  mengeel;
				  summeeinkauf += (preis * kurs / kursel) / menge;
				  elerg += preis  / mengeel;
			    break;
		    case 'Pro Los':
		    case 'Einmal':
			    if(mengeel <= 0)
			    {
				    elerg = preis / menge;
			    }else{
				    elerg = preis / mengeel;
			    }
			    break;

		    default:
			    elerg = preis;
	    }



      elerg = elerg * kurs / kursel;
      if(isNaN(elerg))elerg = 0;
      var menuel = $(children[ menucol ]).html()+'';
      var iof = menuel.indexOf("id=");
      menuel = menuel.substring(iof+3);
      iof = menuel.indexOf('"');
      menuel = menuel.substring(0,iof);
      ids = ids+menuel+';';
      if(kostenart != 'Einkauf')
      {
        gesamtkosten += elerg;
      }

      if(($( children[ ergcol ] ).html()+'').indexOf('<s>') != 0) {
        $(children[ergcol]).html((elerg.toFixed(4) + '').replace('.', ','));
      }
      else {
        $(children[ergcol]).html('<s>'+(elerg.toFixed(4) + '').replace('.', ',')+'</s>');
      }
    }

	  if( $('#f_nureinkaufanzeigen').is(":checked") || $('#f_artderkosten').is(":checked") ) {
		  if($('#f_artderkosten').is(":checked")){
			  gesamtkostenprostueck = preiseeinkauf / mengeeinkauf;
      }else{
        //gesamtkostenprostueck = gesamtkosten - summeeinkauf + (preiseeinkauf / mengeeinkauf);
        gesamtkostenprostueck += elerg;
      }
	  }else{
	    gesamtkostenprostueck += elerg;
	  }
    if(isNaN(gesamtkosten))gesamtkosten = 0;
    if(isNaN(gesamtkostenprostueck))gesamtkostenprostueck = 0;
  });
  if(mengeeinkauf > 0) {
    window.console.log(preiseeinkauf / mengeeinkauf);

    gesamtkostenprostueck += (preiseeinkauf / mengeeinkauf);
    gesamtkosten += (preiseeinkauf / mengeeinkauf);
  }
  //$('#artikelkalkulation_list > tfoot > tr > th:nth-child(8)').first().html('<span style="color: red">&Sigma; '+((gesamtkostenprostueck.toFixed(2))+'').replace('.',',')+'</span>');
  //$('#artikelkalkulation_list > tfoot > tr > th:nth-child(7)').first().html('<span style="color: red">&Sigma; '+((gesamtkosten.toFixed(2))+'').replace('.',',')+'</span>');
  $('#gesamtkosten').val(((gesamtkostenprostueck.toFixed(4))+'').replace('.',','));

  $.ajax({
    url: 'index.php?module=artikelkalkulation&action=list&cmd=berechneek&id=[ID]',
    data: {
      kalkmenge:menge
    },
    method: 'post',
    dataType: 'json',
    beforeSend: function() {

    },
    success: function(data) {
      if(typeof data.ek != 'undefined' && data.ek > 0)
      {
        gesamtkostenprostueck = kurs * parseFloat(data.ek);
        $('#gesamtkosten').val(((gesamtkostenprostueck.toFixed(4))+'').replace('.',','));
        $('#artikelkalkulation_list > tfoot > tr > th:nth-child(7)').first().html('<span style="color: red">&Sigma; '+((gesamtkostenprostueck.toFixed(2))+'').replace('.',',')+'</span>');
      }
    }
  });


	if(menge > 0)
  {
    $.ajax({
      url: 'index.php?module=artikelkalkulation&action=list&cmd=setmenge&id=[ID]',
      data: {savemenge:menge},
      method: 'post',
      dataType: 'json'
    });
  }
  $('#ids').val(ids);
}

function uebernehmen()
{
  if($('#gesamtkosten').val() != '')
  {
    if(confirm('{|Preis wirklich übernehmen?|}'))
    {
      $.ajax({
        url: 'index.php?module=artikelkalkulation&action=list&cmd=getverwendeberechneterek&id=[ID]',
        data: {},
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
          
        },
        success: function(data) {
        
          var fragesetzen=data.verwendeberechneterek;
          if(data.verwendeberechneterek == 0)
          {
          
            if(confirm('{|Kalkulationspreis aktiv setzen?|}'))
            {
              fragesetzen=1;
            }else{
              fragesetzen=0;
            }
          }
          $.ajax({
            url: 'index.php?module=artikelkalkulation&action=list&cmd=uebernehmekalkulation&id=[ID]',
            data: {
              gesamtkosten: $('#gesamtkosten').val(),waehrung:$('#waehrung').val(),setzen: fragesetzen, ids:$('#ids').val()
            },
            method: 'post',
            dataType: 'json',
            beforeSend: function() {
              
            },
            success: function(data) {
              $('#messagejson').html(data.html);
              setTimeout(function(){$('#messagejson').html('')},5000);
              updateLiveTableuebernehmen();
            }
          });
        }
      });
    }
  }
}

function ArtikelkalkulationTagEdit(id)
{
  $.ajax({
    url: 'index.php?module=artikelkalkulation&action=edittag&cmd=get',
    data: {
      id: id
    },
    method: 'post',
    dataType: 'json',
    beforeSend: function() {
      App.loading.open();
    },
    success: function(data) {
      if(typeof data.id != 'undefined' && data.id > 0){
        $('#editArtikelkalkulationTag').find('#e_idtag').val(data.id);
        $('#editArtikelkalkulationTag').find('#e_preistag').val(data.preis);
        $('#editArtikelkalkulationTag').find('#e_datumtag').val(data.datum);
        $('#editArtikelkalkulationTag').find('#e_artikelidneutag').val([ID]);

      }else{
        $('#editArtikelkalkulationTag').find('#e_idtag').val('');
        $('#editArtikelkalkulationTag').find('#e_preistag').val('');
        $('#editArtikelkalkulationTag').find('#e_datumtag').val('');
        
      }
           
      App.loading.close();
      $("#editArtikelkalkulationTag").dialog('open');
    }
  });
}

function artikelkalkulationTagEditSave()
{
  $.ajax({
    url: 'index.php?module=artikelkalkulation&action=savetag',
    data: {
      //Alle Felder die fürs editieren vorhanden sind
      id: $('#e_idtag').val(),
      preis: $('#e_preistag').val(),
      datum: $('#e_datumtag').val(),
      artikelidneu: $('#e_artikelidneutag').val()
    },
    method: 'post',
    dataType: 'json',
    beforeSend: function() {
      App.loading.open();

    },
    success: function(data) {
      App.loading.close();
      if (data.status == 1) {
        $('#editArtikelkalkulationTag').find('#e_idtag').val('');
        $('#editArtikelkalkulationTag').find('#e_preistag').val('');
        $('#editArtikelkalkulationTag').find('#e_datumtag').val('');
        updateLiveTableuebernehmen();
        $("#editArtikelkalkulationTag").dialog('close');
      }else{
        alert(data.statusText);
      }
    }
  });
}

function ArtikelkalkulationTagDelete(id)
{
  if(confirm('{|Eintrag wirklich löschen?|}'))
  {
    $.ajax({
        url: 'index.php?module=artikelkalkulation&action=delete&cmd=deletetag',
        data: {
            
            tagid: id
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            
        },
        success: function(data) {
          var oTable = $('#artikelkalkulation_tag').DataTable( );
          oTable.ajax.reload();
          setTimeout(function(){vorschau();},500);
        }
    });
  }
}

function artikelkalkulationEditSave() {

    $.ajax({
        url: 'index.php?module=artikelkalkulation&action=save',
        data: {
            //Alle Felder die fürs editieren vorhanden sind
            id: $('#e_id').val(),
            bezeichnung: $('#e_bezeichnung').val(),
            datum: $('#e_datum').val(),
            kostenart: $('#e_kostenart').val(),
            kosten: $('#e_kosten').val(),
            menge: $('#e_menge').val(),
            kommentar: $('#e_kommentar').val(),
            waehrung: $('#e_waehrung').val(),
            artikelidneu: $('#e_artikelidneu').val(),
            automatischneuberechnen:($('#e_automatischneuberechnen').prop('checked')?1:0)
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            App.loading.open();

        },
        success: function(data) {
          App.loading.close();
          if (data.status == 1) {
            $('#editArtikelkalkulation').find('#e_id').val('');
            $('#editArtikelkalkulation').find('#e_bezeichnung').val('');
            $('#editArtikelkalkulation').find('#e_datum').val('');
            $('#editArtikelkalkulation').find('#e_kostenart').val('');
            $('#editArtikelkalkulation').find('#e_kosten').val('');
            $('#editArtikelkalkulation').find('#e_menge').val('');
            $('#editArtikelkalkulation').find('#e_kommentar').val('');
            $('#editArtikelkalkulation').find('#e_waehrung').val('');
            $('#editArtikelkalkulation').find('#e_automatischneuberechnen').prop('checked', false);
            updateLiveTable();
            $("#editArtikelkalkulation").dialog('close');
          }else{
            alert(data.statusText);
          }
        }
    });

}

function ArtikelkalkulationEdit(id) {

    $.ajax({
        url: 'index.php?module=artikelkalkulation&action=edit&cmd=get',
        data: {
            id: id
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            App.loading.open();
        },
        success: function(data) {
          if(data && typeof data.id != 'undefined' && data.id > 0){
            $('#editArtikelkalkulation').find('#e_id').val(data.id);
            $('#editArtikelkalkulation').find('#e_bezeichnung').val(data.bezeichnung);
            $('#editArtikelkalkulation').find('#e_datum').val(data.datum);
            //$('#editArtikelkalkulation').find('#e_kostenart').val(data.kostenart);
            $('#editArtikelkalkulation').find('#e_kosten').val(data.kosten);
            $('#editArtikelkalkulation').find('#e_menge').val(data.menge);
            $('#editArtikelkalkulation').find('#e_kommentar').val(data.kommentar);
            $('#editArtikelkalkulation').find('#e_artikelidneu').val([ID]);
            $('#editArtikelkalkulation').find('#e_automatischneuberechnen').prop('checked',data.automatischneuberechnen == '1'?true:false);
            if(typeof data.waehrung != 'undefined')$('#editArtikelkalkulation').find('#e_waehrung').val(data.waehrung);
            if(data.kostenart==""){
              $('#e_kostenart option[value=einmal]').attr('selected','selected');
            }else{ 
              $('#editArtikelkalkulation').find('#e_kostenart').val(data.kostenart);
            }
          }else{
            $('#e_kostenart option[value=einmal]').attr('selected','selected');
            $('#editArtikelkalkulation').find('#e_id').val('');
            $('#editArtikelkalkulation').find('#e_bezeichnung').val('');
            $('#editArtikelkalkulation').find('#e_datum').val('');
            $('#editArtikelkalkulation').find('#e_kosten').val('');
            $('#editArtikelkalkulation').find('#e_menge').val('');
            $('#editArtikelkalkulation').find('#e_kommentar').val('');
            $('#editArtikelkalkulation').find('#e_waehrung').val('EUR');
            $('#editArtikelkalkulation').find('#e_automatischneuberechnen').prop('checked', false);
          }
           
          App.loading.close();
          $("#editArtikelkalkulation").dialog('open');
          $('#e_kostenart').trigger('change');e_automatischneuberechnen
        }
    });

}

function updateLiveTable(i) {
  var oTable = $('#artikelkalkulation_list').DataTable( );
  oTable.ajax.reload();
  setTimeout(function(){vorschau();},500);
}

function updateLiveTableuebernehmen(i){
  var oTable = $('#artikelkalkulation_tag').DataTable( );
  oTable.ajax.reload();
  setTimeout(function(){vorschau();},500);
}

function ArtikelkalkulationDelete(id) {
    var conf = confirm('Wirklich löschen?');
    if (conf) {
        $.ajax({
            url: 'index.php?module=artikelkalkulation&action=delete',
            data: {
                id: id
            },
            method: 'post',
            dataType: 'json',
            beforeSend: function() {
                App.loading.open();
            },
            success: function(data) {
                if (data.status == 1) {
                    updateLiveTable();
                } else {
                    alert(data.statusText);
                }
                App.loading.close();
            }
        });
    }

    return false;

}

function EKladen(id){
  $.ajax({
    url: 'index.php?module=artikelkalkulation&action=ekladen',
    data: {
      id: $('#e_artikelidneutag').val(),
      menge: $('#e_menge').val()
    },
    method: 'post',
    dataType: 'json',
    beforeSend: function() {
      App.loading.open();

    },
    success: function(data) {
      if(data.status == 1){
        $('#editArtikelkalkulation').find('#e_kosten').val(data.ek);
      }else{
        alert('Kein EK hinterlegt');
      }
      App.loading.close();
    }
  });
}

function KalkEKLaden(id){
  $.ajax({
    url: 'index.php?module=artikelkalkulation&action=kalkekladen',
    data: {
      id: $('#e_artikelidneutag').val(),
      menge: $('#e_menge').val()
    },
    method: 'post',
    dataType: 'json',
    beforeSend: function() {
      App.loading.open();

    },
    success: function(data) {
      if(data.status == 1){
        $('#editArtikelkalkulation').find('#e_kosten').val(data.kalkek);
      }else{
        $('#editArtikelkalkulation').find('#e_kosten').val(0);
      }
      App.loading.close();
    }
  });
}

</script>

