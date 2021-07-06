<script>
function openerzeugen()
{
  $.ajax({
    url: 'index.php?module=bestellvorschlagapp&action=list&cmd=openerzeugen',
    type: 'POST',
    dataType: 'json',
    data: { },
    success: function(data) {
      $('#anzpos').html(data.anzpos);
      $('#popuperzeugen').dialog('open');
      var oTable = $('#bestellvorschlagapp_vorschau').DataTable( );
      oTable.ajax.reload();
    }
  });
}
function openproduktionerzeugen()
{
  $.ajax({
    url: 'index.php?module=bestellvorschlagapp&action=list&cmd=openproduktionerzeugen',
    type: 'POST',
    dataType: 'json',
    data: { },
    success: function(data) {
      $('#anzproduktionpos').html(data.anzpos);
      $('#popupproduktionerzeugen').dialog('open');
      var oTable = $('#bestellvorschlagapp_produktionvorschau').DataTable( );
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
    <div class="row">
      <div class="row-height">
        <div class="col-xs-12 col-md-1 col-md-height">
          <div class="inside inside-full-height">
            <fieldset><legend>{|Filter|}</legend>
              <table>
                <tr>
                  <td><input type="radio" id="markierte" name="markierte" value="markierte" [MARKIERTE] />&nbsp;<label for="markierte">nur markierte Artikel</label></td>
                </tr>
                <tr>
                  <td><input type="radio" id="alle" name="markierte" value="alle" [ALLE] />&nbsp;<label for="alle">alle Artikel</label></td>
                </tr>
              </table>
            </fieldset>
          </div>
        </div>
        <div class="col-xs-12 col-md-6 col-md-height">
          <div class="inside inside-full-height">
            <fieldset><legend>{|Bestellvorschlag|}</legend>
              <table align="center"><tr>
                <td nowrap>{|Basis|}:</td><td nowrap><select onchange="changebasis();" id="basis" name="basis">
                [BASIS]
                </select>&nbsp;<input class="filterauftrag" type="text" value="[AUFTRAG]" name="auftrag" id="auftrag" name="produktion" style="display:none;" /><input type="text" style="display:none;" value="[PRODUKTION]" id="produktion" name="produktion" /></td></tr>
                <tr><td>{|Lieferant|}:</td><td nowrap>[LIEFERANTAUTOSTART]<input type="text" name="adresse" value="[LIEFERANT]" id="adresse" size="30">[LIEFERANTAUTOEND]
              &nbsp;  {|Tage einbeziehen von|} <input type="text" id="tagvon" name="tagvon" value="[TAGVON]" size="10" /> bis <input id="tagbis" type="text" name="tagbis" value="[TAGBIS]" size="10" /></td>
                <td nowrap style="vertical-align: top;"><input type="submit" value="starten" name="starten"></td></tr>
              </table>
            </fieldset>
          </div>
        </div>
        <div class="col-xs-12 col-md-2 col-md-height">
          <div class="inside inside-full-height">
            <fieldset><legend>{|Filter|}</legend>
              <table>
                <tr><td><input type="checkbox" [NURPRODUKTIONSARTIKEL] name="nurproduktionsartikel" id="nurproduktionsartikel" value="1" /></td><td><label for="nurproduktionsartikel">{|nur Produktionsartikel|}</label></td></tr>
                <tr><td><input type="checkbox" [KEINEPRODUKTIONSARTIKEL] name="keineproduktionsartikel" id="keineproduktionsartikel" value="1" /></td><td><label for="keineproduktionsartikel">{|nur Material / ohne Produktionsartikel|}</label></td></tr>
                <tr><td><input type="checkbox" [OHNEBESTELLAUFTRAG] name="ohnebestellauftrag" id="ohnebestellauftrag" value="1" /></td><td><label for="ohnebestellauftrag">{|ohne Auftr&auml;ge mit Bestellung|}</label></td></tr>
                <!--<tr><td><input type="checkbox" [NURLAGERARTIKEL] name="lagerartikel" id="lagerartikel" value="1" /></td><td>{|nur Lagerartikel|}</td></tr>-->
              </table>
            </fieldset>
          </div>
        </div>
        <div class="col-xs-12 col-md-2 col-md-height">
          <div class="inside inside-full-height">
            <fieldset><legend>{|zur&uuml;cksetzen|}</legend>
              <input type="submit" class="btnGreenNew" name="reset" value="{|Bestellvorschlag zur&uuml;cksetzen|}">
            </fieldset>
          </div>
        </div>
      </div>
    </div>
    <br />
    [MESSAGE]
    <table width="100%>">
      <tr><td>{|Anzahl Artikel|}</td><td>{|Lieferanten|}</td><td>{|Summe|}</td></tr>
      <tr>
        <td class="greybox" width="25%" id="statistikartikel">[STATISTIKARTIKEL]</td>
        <td class="greybox" width="25%" id="statistiklieferanten">[STATISTIKLIEFERANTEN]</td>
        <td class="greybox" width="25%" id="statistiksumme">[STATISTIKSUMME]</td>
      </tr>
    </table>
    [TAB1]
    <input type="checkbox" value="1" id="allemarkieren" onchange="markierealle()" /> <label for="allemarkieren">{|Alle markieren|}</label>
    <center>
      <input type="button"  value="{|Bestellung(en) erzeugen|}" onclick="openerzeugen();" /><!--<input type="submit" name="erzeugen" value="Bestellung(en) erzeugen" />-->
      [BEFOREPRODUCTION]<input type="button" value="{|Produktion erzeugen|}" onclick="openproduktionerzeugen();" />[AFTERPRODUCTION]
    </center>
  </form>
</div>

<!-- tab view schließen -->
</div>
<div id="popuperzeugen" style="display:none">
<form method="post" id="frmbestellungerzeugen">
  <input type="hidden" value="1" name="erzeugen" />
  <input type="submit" value="erzeugen" style="display:none;">

    <div class="warning">{|Es werden|} <span id="anzpos"></span> {|Position(en) erzeugt|}</div>
    [TABVORSCHAU]
</form>
</div>
<div id="popupproduktionerzeugen" style="display:none">
<form method="post" id="frmproduktionerzeugen">
  <input type="hidden" value="1" name="produktionerzeugen" />
  <input type="submit" value="erzeugen" style="display:none;">

    <div class="warning">{|Es werden|} <span id="anzproduktionpos"></span> {|Position(en) erzeugt|}</div>
    <table>
      <tr>
        <td><label for="produktionadresse">{|Adresse|}:</label></td>
        <td><input size="30" id="produktionadresse" name="produktionadresse" /></td>
      </tr>
    </table>
    [TABVORSCHAUPRODUKTION]

</form>
</div>
<script type="text/javascript">
function aendere(el)
{
  var menge = $('#menge_'+el).val();
  $.ajax({
    url: 'index.php?module=bestellvorschlagapp&action=list&cmd=changemenge',
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
    url: 'index.php?module=bestellvorschlagapp&action=list&cmd=changeekmenge',
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
  
  $('#bestellvorschlagapp_list input:checkbox').each(function(){
    if(!$(this).prop('checked'))anznichtmarkiert++;
  });

  if(anznichtmarkiert == 0)
  {
    var bida = '';
    $('#bestellvorschlagapp_list input:checked').each(function(){
      if(bida != '')bida = bida+'_';
      bida = bida+''+ (this.id).replace('auswahl_','');
      $(this).prop('checked', false);
    });
    $.ajax({
      url: 'index.php?module=bestellvorschlagapp&action=list&cmd=changeauswahl',
      type: 'POST',
      dataType: 'json',
      data: { arts: bida, wert : 0},
      success: function(data) {
        
      }
    });
  }else{
    var bida = '';
    $('#bestellvorschlagapp_list input:checkbox').each(function(){
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
        url: 'index.php?module=bestellvorschlagapp&action=list&cmd=changeauswahl',
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
    url: 'index.php?module=bestellvorschlagapp&action=list&cmd=changeauswahl',
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
    url: 'index.php?module=bestellvorschlagapp&action=list&cmd=changelieferant',
    type: 'POST',
    dataType: 'json',
    data: { bid: el, wert : lieferant},
    success: function(data) {
      $('#ek_'+el).html(data.ek);
    }
  });
}

function changebasis()
{
  var wert = $('#basis').val();
  if(wert == '0' || wert === 'w' || wert === 'a')
  {
    $('#auftrag').show();
    $('#auftrag + img').show();
  }else{
    $('#auftrag').hide();
    $('#auftrag + img').hide();
  }
  if(wert === 'p')
  {
    $('#produktion').show();
    $('#produktion + img').show();
  }else{
    $('#produktion').hide();
    $('#produktion + img').hide();
  }
}
  
$(document).ready(function() {
  $('#alle').on('change',function () {
    if($(this).prop('checked'))
    {
      $('#markierte').trigger('change');
    }
  });
  $('#markierte').on('change',function () {
    if($(this).prop('checked'))
    {
      $('#alle').trigger('change');
    }
  });

  changebasis();
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
      title:'{|Bestellung(en) erzeugen|}',
      buttons: {
        '{|BESTELLUNG ERZEUGEN|}': function()
        {
          $('#frmbestellungerzeugen').trigger('submit');
        },
        ABBRECHEN: function() {
          $(this).dialog('close');
        }
      },
      close: function(event, ui){
        
      }
    });

  $('#popupproduktionerzeugen').dialog(
          {
            modal: true,
            autoOpen: false,
            minWidth: 940,
            title:'{|Produktion erzeugen|}',
            buttons: {
              '{|PRODUKTION ERZEUGEN|}': function()
              {
                $('#frmproduktionerzeugen').trigger('submit');
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
