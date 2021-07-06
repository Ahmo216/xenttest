<style>
  .filterimg{
    border:none;
    cursor:pointer;
  }
  table.filtertable fieldset
  {
    padding:8px 12px 2px;
    margin:0;
    min-height:70px;
  }
  .usersave-box .filter-item
  {
    float: left;
    padding: 0 10px 10px 0;
  }
  
  tr.infoboxen td
  {
    background-color:lightgrey;
    color:white;
    font-size: 2em;
    padding:10px;
  }
  
  #buchenfieldset input {
    padding:5px;
  }
  
  #aktionbuttons input {
    min-width:100px;
  }
  #tabs-1 img.autocomplete_lupe
  {
    max-height:15px !important;
  }
  .invisible 
  {
    display:none;
  }
  
  #zahlungseingang_kontenblatt > thead > tr > th:nth-child(11),
  #zahlungseingang_kontenblatt > tbody > tr > td:nth-child(11),
  #zahlungseingang_kontenblatt > tfoot > tr > th:nth-child(11)
  {
    display:none;
  }
</style>
<script type="text/javascript">

  $(document).ready(function() {
    $('#filterinfo').dialog(
    {
      modal: true,
      autoOpen: false,
      minWidth: 640,
      title: '',
      buttons: {
        '{|FILTER ANPASSEN|}': function () {

          $('#zahlungseingang_kontenblatt_gegenkonto').val($('#filterinfo_konto').val());
          $('#zahlungseingang_kontenblatt_von').val($('#filterinfo_von').val());
          $('#zahlungseingang_kontenblatt_bis').val($('#filterinfo_bis').val());
          $('#zahlungseingang_kontenblatt_gegenkonto').trigger('change');
          $('#zahlungseingang_kontenblatt_von').trigger('change');
          $('#zahlungseingang_kontenblatt_bis').trigger('change');
          $('#filterinfo').dialog('close');
        },
        '{|ABBRECHEN|}': function () {
          $(this).dialog('close');
        }
      },
      close: function (event, ui) {

      }
    });
  });


  function  filterleft()
  {
     $.ajax({
        url: 'index.php?module=zahlungseingang&action=kontenblatt&cmd=filterleft',
        data: {
          gegenkonto: $('#zahlungseingang_kontenblatt_gegenkonto').val(),
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            App.loading.open();
        },
        success: function(data) {
            App.loading.close();
            if (data.status == 1) {
                $('#zahlungseingang_kontenblatt_gegenkonto').val(data.gegenkonto);
                $('#zahlungseingang_kontenblatt_gegenkonto').trigger('change');
                updateLiveTable();
            } else {
              alert(data.statusText);
            }
        }
    });
  }
  function filterright()
  {
     $.ajax({
        url: 'index.php?module=zahlungseingang&action=kontenblatt&cmd=filterright',
        data: {
          gegenkonto: $('#zahlungseingang_kontenblatt_gegenkonto').val(),
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            App.loading.open();
        },
        success: function(data) {
            App.loading.close();
            if (data.status == 1) {
                $('#zahlungseingang_kontenblatt_gegenkonto').val(data.gegenkonto);
                $('#zahlungseingang_kontenblatt_gegenkonto').trigger('change');
                updateLiveTable();
            } else {
              alert(data.statusText);
            }
        }
    });
  }
  function updateLiveTable()
  {
    var oTable = $('#zahlungseingang_kontenblatt').DataTable( );
        oTable.ajax.reload();
  }
  
  function kontenblattedit(el)
  {
    var tr = $(el).parents('tr')[ 1 ];
    var kid = $(tr).find('.kid').first().text();
    if(kid)
    {
      LoadKontoauszug(tr);
    }
  }

  function kontenblattdelete(el)
  {
    var tr = $(el).parents('tr')[ 1 ];
    var kid = $(tr).find('.kid').first().text();
    if(kid)
    {
      DeleteKontoauszug(tr);
    }
  }


  function kontenblattreload()
  {
    infoboxladen();
  }
  
  function infoboxladen()
  {
     $.ajax({
        url: 'index.php?module=zahlungseingang&action=kontenblatt&cmd=laden',
        data: {
          
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            App.loading.open();
        },
        success: function(data) {
          App.loading.close();
          $('#haben').html(data.haben);
          $('#soll').html(data.soll);
          $('#saldo').html(data.saldo);
          $('#anz').html(data.anz);
        }
    });
  }
  
  function buchen()
  {
     $.ajax({
        url: 'index.php?module=zahlungseingang&action=kontenblatt&cmd=buchen',
        data: {
          kid:$('#buchungkid').val(),
          waehrung:$('#buchungwaehrung').val(),
          datum:$('#buchungdatum').val(),
          gegenkonto:$('#buchunggegenkonto').val(),
          kostenstelle:$('#buchungkostenstelle').val(),
          belegfeld1:$('#buchungbelegfeld1').val(),
          soll:$('#buchungsoll').val(),
          haben:$('#buchunghaben').val(),
          konto:$('#buchungkonto').val(),
          buchungtext:$('#buchungtext').val(),
          skonto:$('#buchungskonto').val(),
          filtergegenkonto: $('#zahlungseingang_kontenblatt_gegenkonto').val(),
          filter_von: $('#zahlungseingang_kontenblatt_von').val(),
          filter_bis: $('#zahlungseingang_kontenblatt_bis').val()
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            App.loading.open();
        },
        success: function(data) {
          App.loading.close();
          updateLiveTable();
          abbrechen();
          if(typeof data.popup != 'undefined')
          {
            $('#filterinfo_von').val(data.von);
            $('#filterinfo_bis').val(data.bis);
            $('#filterinfo_konto').val(data.konto);
            $('#filterinfo').dialog('open');
          }
        }
    });
  }
  
  function LoadKontoauszug(row)
  {
    var children = $(row).children();
    var gegenkonto = $(children[ 2 ]).html();
    var iof = gegenkonto.indexOf('<span class="manuell">(M)</span>');
    if(iof > -1)
    {
      gegenkonto = gegenkonto.substring(0,iof-1);
      $('#buchungkid').val($(row).find('.kid').first().text());
      $('#buchungwaehrung').val($(children[ 8 ]).text());
      $('#buchungdatum').val($(children[ 0 ]).text());
      $('#buchunggegenkonto').val(gegenkonto);
      $('#buchungkostenstelle').val($(children[ 3 ]).text());
      $('#buchungbelegfeld1').val($(children[ 5 ]).text());
      $('#buchungsoll').val($(children[ 6 ]).text());
      $('#buchunghaben').val($(children[ 7 ]).text());
      $('#buchungtext').val($(row).find('.buchungstext').first().text());
    }
  }

  function DeleteKontoauszug(row)
  {
    var kid = $(row).find('.kid').first().text();
    if(kid && confirm('{|Eintrag wirklich löschen?|}'))
    {
      $.ajax({
        url: 'index.php?module=zahlungseingang&action=kontenblatt&cmd=delete',
        data: {
          id : kid
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {

        },
        success: function(data) {
          var oTable = $('#zahlungseingang_kontenblatt').DataTable( );
          oTable.ajax.reload();
        }
      });
    }
  }
  
  function abbrechen()
  {
    $('#buchungkid').val('');
    $('#buchungwaehrung').val('EUR');
    $('#buchunghaben').val('');
    $('#buchungsoll').val('');
    
    $('#buchungbelegfeld1').val('');
    $('#buchungdatum').val('');
    $('#buchungkonto').val('');
    $('#buchungskonto').val('');
    $('#buchungtext').val('');
    
  }
</script>
<div id="tabs">
  <ul>
    <li><a href="#tabs-1"><!--[TABTEXT]--></a></li>
  </ul>
  <div id="tabs-1">
    <div class="warning">Hinweis: Dies ist ein Beta-Feature. Bitte pr&uuml;fen Sie die Daten und melden uns per Ticket-System, wenn etwas bei Ihnen nicht stimmt.</div>
    <table height="80" width="100%" class="filtertable">
      <tr>
        <td>
          <div class="usersave-box clearfix">
            <fieldset>
              <legend>{|Filter|}</legend>
              
              <div class="filter-item"><label for="zahlungseingang_kontenblatt_gegenkonto">{|Gegenkonto|}:</label>&nbsp;<img src="themes/new/images/left.png" class="filterimg" onclick="filterleft();" />&nbsp;<input size="50" value="[GEGENKONTO]" type="text" name="zahlungseingang_kontenblatt_gegenkonto" id="zahlungseingang_kontenblatt_gegenkonto">&nbsp;<img src="themes/new/images/right-calendar.png"  onclick="filterright();" class="filterimg" /></div>
              <div class="filter-item"><label for="zahlungseingang_kontenblatt_von">{|Von|}:</label> <input type="text" name="zahlungseingang_kontenblatt_von" value="[VON]" id="zahlungseingang_kontenblatt_von" size="12" /></div>
              <div class="filter-item"><label for="zahlungseingang_kontenblatt_bis">{|Bis|}:</label> <input type="text" name="zahlungseingang_kontenblatt_bis" value="[BIS]" id="zahlungseingang_kontenblatt_bis" size="12" /></div>
              <div class="clear"></div>
            </fieldset>
          </div>
        </td>
      </tr>
    </table>
    <table width="100%">
      <tr>
        <td width="25%">{|Haben|}</td>
        <td width="25%">{|Soll|}</td>
        <td width="25%">{|Saldo|}</td>
        <td width="25%">{|Anzahl Buchungen|}</td>
      </tr>
      <tr class="infoboxen">
        <td width="25%" id="haben">0,00</td>
        <td width="25%" id="soll">0,00</td>
        <td width="25%" id="saldo">0,00</td>
        <td width="25%" id="anz">0</td>
      </tr>
    </table>
    [MESSAGE]
    [TAB1]
    <table height="80" width="100%" class="filtertable">
      <tr>
        <td>
          <div class="usersave-box clearfix">
            <fieldset id="buchenfieldset"><legend>{|Buchung|}</legend>
              <input type="hidden" id="buchungkid" /><div class="filter-item"><label for="buchungwaehrung">{|W&auml;hrung|}:</label><br /><input type="text" name="buchungwaehrung" value="EUR" id="buchungwaehrung" size="5" /></div>
              <div class="filter-item"><label for="buchunghaben">{|Soll (S)*|}:</label><br /><input type="text" name="buchunghaben" id="buchunghaben" size="10" /></div>
              <div class="filter-item"><label for="buchungsoll">{|Haben (H)**|}:</label><br /><input type="text" name="buchungsoll" id="buchungsoll" size="10" /></div>
              <div class="filter-item"><label for="buchunggegenkonto">{|Gegenkonto|}:</label><br /><input type="text" name="buchunggegenkonto" id="buchunggegenkonto" size="10" /></div>
              <div class="filter-item"><label for="buchungkostenstelle">{|Kostenstelle|}:</label><br /><input type="text" name="buchungkostenstelle" id="buchungkostenstelle" size="10" /></div>
              <div class="filter-item"><label for="buchungbelegfeld1">{|Belegfeld1|}:</label><br /><input type="text" name="buchungbelegfeld1" id="buchungbelegfeld1" size="20" /></div>
              <div class="filter-item"><label for="buchungdatum">{|Datum|}:</label><br /><input type="text" name="buchungdatum" id="buchungdatum" size="12" /></div>
              <div class="filter-item"><label for="buchungkonto">{|Konto|}:</label><br /><input type="text" name="buchungkonto" id="buchungkonto" size="10" /></div>
              <div class="filter-item invisible"><label for="buchungskonto">{|Skonto|}:</label><br /><input type="text" name="buchungskonto" id="buchungskonto" size="10" /></div>
              <div class="filter-item"><label for="buchungtext">{|Buchungstext|}:</label><br /><input type="text" name="buchungtext" id="buchungtext" size="20" /></div>
            </fieldset>
          </div>
<i style="color:#999">* Soll (S) &rarr; Habenbuchung auf dem Kontoauszug</i><br>
<i style="color:#999">** Haben (H) &rarr; Sollbuchung auf dem Kontoauszug</i>
        </td>
        <td>
          <fieldset id="aktionbuttons"><legend>{|Aktion|}</legend>
            <div><input type="button" class="btnGreen" value="{|Buchen|}" onclick="buchen();" /></div>
            <div><input type="button" value="{|Abbrechen|}" onclick="abbrechen();" /></div>
          </fieldset>
        </td>
      </tr>
    </table>
  </div>
</div>

<div id="filterinfo">
  <fieldset>
    <legend>{|Filterinformation|}</legend><input type="hidden" id="filterinfo_von" /><input type="hidden" id="filterinfo_bis" /><input type="hidden" id="filterinfo_konto" />
    {|Achtung die hinzugef&uuml;gte Buchung befindet sich au&szlig;erhalb des Filterbereichs|}
  </fieldset>
</div>
