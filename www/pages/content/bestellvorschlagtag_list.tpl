<script>
function openerzeugen()
{
  $.ajax({
    url: 'index.php?module=bestellvorschlagtag&action=list&cmd=openerzeugen',
    type: 'POST',
    dataType: 'json',
    data: { },
    success: function(data) {
      $('#anzpos').html(data.anzpos);
      $('#popuperzeugen').dialog('open');
      var oTable = $('#bestellvorschlagtag_vorschau').DataTable( );
      oTable.ajax.reload();
    }
  });
}
</script>
<div id="tabs">
    <ul>
        <li><a href="#tabs-1"></a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
<form action="" method="post">
<table width="100%"><tr><td>
  <fieldset style="min-height:80px;"><legend>{|Filter|}</legend>
  <table><tr><td><input type="checkbox" id="markierte" name="markierte" value="1" [MARKIERTE] />&nbsp;nur markierte Artikel anzeigen</td>
  <td><input type="checkbox" id="alle" name="alle" value="1" [ALLE] />&nbsp;alle Artikel anzeigen</td>
  </tr>
  </table>
  </fieldset>
</td><td>
  <fieldset style="min-height:80px;"><legend>{|Tagesbestellung Vorschlag|}</legend>
  <table align="center">
  <tr><td><input type="hidden" name="basis" value="0" />Lieferant:</td><td nowrap>[LIEFERANTAUTOSTART]<input type="text" name="adresse" value="[LIEFERANT]" id="adresse" size="30">[LIEFERANTAUTOEND]
  &nbsp;Tag: <input type="text" id="tagvon" name="tagvon" value="[TAGVON]" size="10" /></td>
  <td nowrap style="vertical-align: top;"><input type="submit" value="starten" name="starten"></td></tr>
  </table>
  </fieldset>
</td><td>
  <fieldset style="min-height:80px;"><legend>zur&uuml;cksetzen</legend>
  <table width="" align="center"><tr>
  <td><input type="submit" name="reset" value="Bestellvorschlag zur&uuml;cksetzen"></td></tr>
  </table>
  </fieldset>
</td></tr></table>
<br>
[MESSAGE]
<table width="100%>">
<tr><td>Anz Artikel</td><td>Lieferanten</td><td>Summe</td></tr>
<tr>
	<td class="greybox" width="25%" id="statistikartikel">[STATISTIKARTIKEL]</td>
	<td class="greybox" width="25%" id="statistiklieferanten">[STATISTIKLIEFERANTEN]</td>
  <td class="greybox" width="25%" id="statistiksumme">[STATISTIKSUMME]</td>
</tr>
</table>
[TAB1]
<input type="checkbox" value="1" id="allemarkieren" onchange="markierealle()" /> Alle markieren
<center><input type="button"  value="Bestellung(en) erzeugen" onclick="openerzeugen();" /><!--<input type="submit" name="erzeugen" value="Bestellung(en) erzeugen" />--></center>
</form>
</div>

<!-- tab view schließen -->
</div>
<form method="post" id="frmbestellungerzeugen"><input type="hidden" value="1" name="erzeugen" /><input type="submit" value="erzeugen" style="display:none;" />
<div id="popuperzeugen" style="display:none">
<div class="warning">Es werden <span id="anzpos"></span> Position(en) erzeugt</div>
[TABVORSCHAU]
</form>
</div>
<script type="text/javascript">
function aendere(el)
{
  var menge = $('#menge_'+el).val();
  $.ajax({
    url: 'index.php?module=bestellvorschlagtag&action=list&cmd=changemenge',
    type: 'POST',
    dataType: 'json',
    data: { bid: el, wert : menge},
    success: function(data) {
      $('#ek_'+el).html(data.ek);
    }
  });
}

function aendereek(el)
{
  var menge = $('#ekmenge_'+el).val();
  $.ajax({
    url: 'index.php?module=bestellvorschlagtag&action=list&cmd=changeekmenge',
    type: 'POST',
    dataType: 'json',
    data: { bid: el, wert : menge},
    success: function(data) {
      $('#ek_'+el).html(data.ek);
    }
  });
}


function markierealle()
{
  var anznichtmarkiert = 0;
  
  $('#bestellvorschlagtag_list input:checkbox').each(function(){
    if(!$(this).prop('checked'))anznichtmarkiert++;
  });

  if(anznichtmarkiert == 0)
  {
    var bida = '';
    $('#bestellvorschlagtag_list input:checked').each(function(){
      if(bida != '')bida = bida+'_';
      bida = bida+''+ (this.id).replace('auswahl_','');
      $(this).prop('checked', false);
    });
    $.ajax({
      url: 'index.php?module=bestellvorschlagtag&action=list&cmd=changeauswahl',
      type: 'POST',
      dataType: 'json',
      data: { arts: bida, wert : 0},
      success: function(data) {
        
      }
    });
  }else{
    var bida = '';
    $('#bestellvorschlagtag_list input:checkbox').each(function(){
      if(!$(this).prop('checked'))
      {
        if(bida != '')bida = bida+'_';
        bida = bida+''+ (this.id).replace('auswahl_','');
        $(this).prop('checked', true);
      }
    });
    if(bida != '')
    {
      $.ajax({
        url: 'index.php?module=bestellvorschlagtag&action=list&cmd=changeauswahl',
        type: 'POST',
        dataType: 'json',
        data: { arts: bida, wert : 1},
        success: function(data) {
          
        }
      });
    }
  }
  
}

function changeauswahl(el)
{
  var menge = $('#auswahl_'+el).prop('checked');
  $.ajax({
    url: 'index.php?module=bestellvorschlagtag&action=list&cmd=changeauswahl',
    type: 'POST',
    dataType: 'json',
    data: { bid: el, wert : menge?1:0},
    success: function(data) {
      
    }
  });
}

function changelieferant(el)
{
  var lieferant = $('#lieferant_'+el).val();
  $.ajax({
    url: 'index.php?module=bestellvorschlagtag&action=list&cmd=changelieferant',
    type: 'POST',
    dataType: 'json',
    data: { bid: el, wert : lieferant},
    success: function(data) {
      $('#ek_'+el).html(data.ek);
    }
  });
}

$(document).ready(function() {
$( "#tagbis" ).datepicker({ dateFormat: 'dd.mm.yy',dayNamesMin: ['SO', 'MO', 'DI', 'MI', 'DO', 'FR', 'SA'], firstDay:1,
          showWeek: true, monthNames: ['Januar', 'Februar', 'März', 'April', 'Mai', 
          'Juni', 'Juli', 'August', 'September', 'Oktober',  'November', 'Dezember'], });
$( "#tagvon" ).datepicker({ dateFormat: 'dd.mm.yy',dayNamesMin: ['SO', 'MO', 'DI', 'MI', 'DO', 'FR', 'SA'], firstDay:1,
          showWeek: true, monthNames: ['Januar', 'Februar', 'März', 'April', 'Mai', 
          'Juni', 'Juli', 'August', 'September', 'Oktober',  'November', 'Dezember'], });
         
    $('#popuperzeugen').dialog(
    {
      modal: true,
      autoOpen: false,
      minWidth: 940,
      title:'Bestellung(en) erzeugen',
      buttons: {
        'BESTELLUNG ERZEUGEN': function()
        {
          $('#frmbestellungerzeugen').submit();
        },
        ABBRECHEN: function() {
          $(this).dialog('close');
        }
      },
      close: function(event, ui){
        
      }
    });
         
          
          
});
</script>
<style>
table.staffel
{
width:100%;
}
table.staffel td 
{
background-color: rgb(255,255,204);
font-size:6pt;
}
table.staffel td:nth-child(1)
{
min-width:40px;
}
table.staffel td:nth-child(2)
{
min-width:70px;
}
table.staffel td:nth-child(3)
{
min-width:130px;
}
table.staffel td:nth-child(4)
{
text-align:right;
}
</style>
