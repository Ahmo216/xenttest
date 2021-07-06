<style>
  #aktionsdialog div.dt-buttons
  {
    display:none !important;
  }
  
</style>
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">[TABTEXT]</a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">


  <table class="tableborder" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody>

      <tr valign="top" cellpadding="0" cellspacing="0">
        <td>
          <table width="100%" align="center" style="background-color:#cfcfd1;">
            <tr>
            <td width="33%"></td>
            <td align="center" nowrap><b style="font-size: 14pt">Kunde <font color="blue">[NAME]</font></b> [KUNDENNUMMER]</td>
            <td width="33%"></td></tr>
          </table>
          <div id="jqueryinfo"></div>
<br>
          [PAKETMARKEDIVS]

          <div class="usersave-box clearfix">
            <fieldset class="usersave">
              <legend>{|Filter|}</legend>
              <div class="clear"></div>
              <div class="filter-item"><input type="checkbox" name="auchabgeschlossenedetail" id="auchabgeschlossenedetail"><label for="auchabgeschlossenedetail">&nbsp;{|auch abgeschlossene Retouren anzeigen|}</label></div>
            </fieldset>
          </div>

          [TABELLE]
          <table width="100%">
            <tr>
              <td width="33%">
                <input type="checkbox" id="allemarkieren" style="margin-left:40px;" onchange="allemarkieren(this);" />{|Alle markieren|}
              </td>
              <td width="33%">
              
              </td>
              <td width="33%">
              <select id="aktionauswahl">
                <option value="gutschrift">{|Gutschrift zu ausgew&auml;hlten Artikeln|}</option>
                <option value="ersatzlieferung">{|Kostenlose Ersatzlieferung veranlassen|}</option>
                <option value="ruecksendung">{|R&uuml;cksendung der ausgew&auml;hlten Artikel|}</option>
                <option value="internreparatur">{|ausgew&auml;hlte zur internen Reparatur geben|}</option>
                <option value="externreparatur">{|ausgew&auml;hlte f&uuml;r Reparatur zur&uuml;ckschicken|}</option>
                <option value="abgeschlossen">{|ausgew&auml;hlte Artikel auf abgeschlossen setzen|}</option>
                <option value="offen">{|ausgew&auml;hlte Artikel auf offen setzen|}</option>
              </select><input type="button" value="{|Ausf&uuml;hren|}" onclick="btnauswahl();" />
              </td>
            </tr>
          </table>
          <!--
          <input type="button" value="Gutschrift erstellen">
          <input type="button" value="Artikel zur&uuml;ckschicken">
          <input type="button" value="Artikel an Lieferant senden">-->
        </td>
      </tr>
    </tbody>
  </table>
</div>
</div>
<div id="kommentardialog" style="display:none;"><table><tr><td colspan="2" id="kommentarinfo"></td></tr><tr><td>{|Kommentar|}:</td><td><input type="hidden" id="kommentarid" /><input size="40" type="input" id="neuerkommentar" /></td><td><input type="button" value="{|&Auml;ndern|}" onclick="sendkommentar();" /></td></tr></table></div>
<div id="aktionsdialog" style="display:none;">
  <form action="index.php?module=rma&action=sendaktion&adresse=[ID]" id="frmaction" method="POST">
    <table>
      <tr>
        <td valign="top">
          <table>
            <tr><th valign="top" colspan="2" id="ueberschrift"></th></tr>
            <tr><td valign="top">{|interner Kommentar|}:</td><td valign="top"><input type="hidden" name="saktion" id="saktion" /><input type="hidden" id="hids" name="hids" /><textarea rows="5" cols="60" name="internerkommentar" id="internerkommentar" ></textarea></td></tr>
            <tr><td valign="top">{|f&uuml;r Kunde (Freitext)|}</td><td valign="top"><textarea rows="5" cols="60" id="externerkommentar" name="externerkommentar"></textarea></td></tr>
            <tr><td valign="top" colspan="2" id="aktionwarnung"></td></tr>
            <tr><td valign="top">{|Hinweis|}</td><td  valign="top" id="aktionshinweis"></td></tr>
            <tr><td></td><td valign="top"><input type="submit" onclick="ocsendaktion();" name="sendaktion" value="Ausf&uuml;hren" /></td></tr>
          </table>
        </td>
        <td valign="top">
          <fieldset><legend>{|Einlagern|}</legend>
          <input type="checkbox" id="alle2" onclick="clickalle2();" /> Alle
          </fieldset>
          [RMAARTIKELTAB]

          <fieldset><legend>{|Aus Auftrag &Uuml;bernehmen|}</legend>
          <input type="checkbox" id="allenichtlagerartikel" onclick="clickallenichtlagerartikel();"> Alle
          </fieldset>
          [RMAARTIKELTAB2]

          <div id="chargenmhdhinweis" style="display:none;font-style:bold;">
          <div class="info">{|In der Tabelle befinden sich Artikel (mit * gekennzeichnet) mit Charge, MHD oder Seriennummer. Sie k&ouml;nnen die Artikel &uuml;ber Lager -&gt; Ein- und Auslagern einbuchen.|}</div>
          </div>
        </td>
      </tr>
    </table>
  </form>
</div>

<script type="text/javascript">
var jqueryinfotimeout = false;

function showsnmhdchargeinfo()
{
  if($('#rma_detail_artikel span.snmhdcharge').length > 0)
  {
    $('#chargenmhdhinweis').show();
  }else{
    $('#chargenmhdhinweis').hide();
  }
}
  
function ocsendaktion()
{
  var saktion = $('#saktion');
  $('#aktionsdialog').dialog('close');
  if(saktion == 'gutschrift' || saktion == 'ruecksendung' || saktion == 'ersatzlieferung')
  {
    setTimeout(function(){window.location = 'index.php?module=rma&action=detail&id=[ID]';},2000);
  }
}

function clickalle2()
{
  var wert = $('#alle2').prop('checked');
  $('#rma_detail_artikel').find('[type="checkbox"]').prop('checked',wert);
}

function clickallenichtlagerartikel()
{
  var wert = $('#allenichtlagerartikel').prop('checked');
  $('#rma_detail_keinelagerartikel').find('[type="checkbox"]').prop('checked',wert);
}

function allemarkieren(el)
{
  $('#rma_detail').find(':checkbox').each(function(){
    $(this).prop('checked',$(el).prop('checked'));
  });
}

  
  $(document).ready(function() {
      $('#aktionsdialog').dialog({modal: true,
        minWidth: 940,
          autoOpen:false,
          title:'Aktion ausführen'});
  });
function btnauswahl()
{
  var markiert = false;
  var ids = '';
  $('#rma_detail').find(':checkbox').each(function(){
    if($(this).prop('checked'))
    {
      var ida = this.id.split('_');
      if(typeof ida[1] != 'undefined')
      {
        if(ids != '')ids = ids + ',';
        ids = ids + ida[1];
      }
      markiert = true;
    }
  });
  if(markiert)
  {
    $.ajax({
        url: 'index.php?module=rma&action=getaktionbox',
        type: 'POST',
        dataType: 'json',
        data: { posids: ids ,auswahl: $('#aktionauswahl').val() },
        success: function(data) {
          $('#frmaction').prop('target', '');
          $('#internerkommentar').val('');
          $('#externerkommentar').val('');
          $('#aktionshinweis').html('');
          $('#ueberschrift').html('');
          $('#aktionwarnung').html('');
          $('#saktion').val($('#aktionauswahl').val());
          $('#hids').val(ids);
          if(typeof data.status != 'undefined' && data.status == 1)
          {
            if(typeof data.target != 'undefined')$('#frmaction').prop('target', data.target);
            if(typeof data.ueberschrift != 'undefined')$('#ueberschrift').html(data.ueberschrift);
            if(typeof data.hinweis != 'undefined')$('#aktionshinweis').html(data.hinweis);
            if(typeof data.warnung != 'undefined')$('#aktionwarnung').html(data.warnung);

            $('#aktionsdialog').dialog('open');
            $('#hids').trigger('change');
          } else {
            if(jqueryinfotimeout)clearTimeout(jqueryinfotimeout);
            $('#jqueryinfo').html('<div class="error">Dialog konnte nicht geladen werden</div>');
            jqueryinfotimeout = setTimeout(function(){$('#jqueryinfo').html('');},2000);
          }
        },
        fail: function(err) {
          if(jqueryinfotimeout)clearTimeout(jqueryinfotimeout);
          $('#jqueryinfo').html('<div class="error">Dialog konnte nicht geladen werden</div>');
          jqueryinfotimeout = setTimeout(function(){$('#jqueryinfo').html('');},2000);
        }
    });
  } else {
    $('#jqueryinfo').html('<div class="error">Keine Artikel ausgew&auml;hlt!</div>');
    jqueryinfotimeout = setTimeout(function(){$('#jqueryinfo').html('');},2000);
  }
}

function sendkommentar()
{
    $.ajax({
        url: 'index.php?module=rma&action=neuerkommentar',
        type: 'POST',
        dataType: 'json',
        data: { id: $('#kommentarid').val() ,neuerkommentar: $('#neuerkommentar').val() },
        success: function(data) {
        refreshTab();
            $('#kommentardialog').dialog('close');
        },
        beforeSend: function() {
            
        },
        fail: function(err) {
          $('#kommentarinfo').html('{|Fehler beim Erstellen des Kommentars|}');
        }
    });

}
function refreshTab()
{
  $('#rma_detail_filter').find('input').each(function(){
    var old = $(this).val();
    $(this).val(old+' ');
    $(this).trigger('keyup');
    $(this).val(old);
  });
}

function editkommentar(id)
{
  $('#neuerkommentar').val('');
  $('#kommentarinfo').html('');
  $('#kommentarid').val(id);
  $('#kommentardialog').dialog({modal: true,
        minWidth: 940,
        title:'Kommentar hinzufügen'});
}
$(document).ready(function() {
setInterval(function() {
  var countchanged = 0;

  $('input.lagerplatz').each(function(){
    if(!$(this).hasClass('ui-autocomplete-input'))
    {
      countchanged++;
      $( this).autocomplete({
      source: "index.php?module=ajax&action=filter&filtername=lagerplatz",
      });
      var elnext = $(this).next();
      if($(elnext).is('a') && $(elnext).html() == 'X')
      {
        $(elnext).after('<img  onclick="clicklupe(this);" style="right:-25px;top:5px;position:absolute;" src="images/icon_lupe_plus.png" class="autocomplete_lupe" />');
      } else {
        $(this).after('<img  onclick="clicklupe(this);" style="left:-23px;top:6px;margin-right:-22px;position:relative;" src="images/icon_lupe_plus.png" class="autocomplete_lupe" />');
      }
    }  
  });
  if(countchanged > 0)
  {
  
    $('*').each(function(){
      $(this).on('click',function(){
        if($(this).hasClass('autocomplete_lupe'))
        {
          
          $('.ui-autocomplete-input').each(function(){
            if($(this).val() === ' ')
            {
              $(this).val('');
              $(this).trigger('keydown');
            }
          });
          blockclick = true;
          lastlupe = this;
          var el = this;
          //var height = $(window).scrollTop();
          $(el).prev('.ui-autocomplete-input').each(function(){
          //var v = $(this).val();
            aktlupe = this;
            $(this).val(' ');
            $(this).trigger('keydown');
            //if(v !== '')setTimeout(trimel, 1500,this);
            //setTimeout(function(){$(window).scrollTop(height);},100);
          });
          setTimeout(function(){blockclick = false;},200);
        } else {
          if(this !== lastlupe)
          {
            if(!blockclick)
            {
              $('.ui-autocomplete-input').each(function(){
                if($(this).val() === ' ')
                {
                  $(this).val('');
                  $(this).trigger('keydown');
                }
              });
            }
          }
        }
      });
    });
  }
  $('input.lagerplatz').each(function(){
    var elid = this.id.split('_');
    if($(this).val() != $('#lagerold_'+elid[1]).val())
    {
      $('#lagerold_'+elid[1]).val($(this).val());
    }
  
  });
  

} ,500);
});
</script>
