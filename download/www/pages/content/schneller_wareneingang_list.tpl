<!-- gehort zu tabview -->
<style>
  table#wareneingang_lieferant_schnell > tbody > tr > td:nth-child(10)
  {
    white-space: nowrap;
  }
  </style>

<div id="tabs">
  <ul>
    <li><a href="#tabs-1">[TABTEXT]</a></li>
  </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
[MESSAGE]
<div id="ajaxmessage"></div>
[TAB1]

<form id="frmwareneingang" method="POST">
<table width="100%">
<tr><td style="text-align:right;"><input type="checkbox" value="1" name="alleetiketten" />&nbsp;Etiketten beim Abschließen in angegebener Menge drucken</td><td style="text-align:left;"><input type="submit" name="senden" onclick="$('#ajaxmessage').hide();" value="Paketinhalt ist jetzt komplett erfasst!" /></td></tr>
</table>

</form>

[TAB1NEXT]
</div>

<!-- tab view schließen -->
</div>

<script>

function addmenge(id, menge)
{
  menge = menge + '';
  menge = menge.replace(',','.');
  $('#menge_'+id).val(menge);
  chmenge(id);
  if(menge > 0 && menge < 1)menge = 1;
  menge = Math.floor(menge);
  $('#etikett_'+id).val(menge);
  chetikett(id);
}

function addet(id, menge)
{
/*
  $('#etikett_'+id).val(menge);
  chetikett(id);*/
}


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
        $(elnext).after('<img  onclick="clicklupe(this);" style="right:10px;top:5px;position:absolute;cursor:pointer;" src="images/icon_lupe_plus_transparent.png" class="autocomplete_lupe" />');
      } else {
        $(this).after('<img  onclick="clicklupe(this);" style="left:-23px;top:4px;margin-right:-22px;position:relative;cursor:pointer;max-height:12px;" src="images/icon_lupe_plus_transparent.png" class="autocomplete_lupe" />');
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
      chlager(elid[1]);
    }
  
  });
  

} ,500);

function clicklabel(el)
{
  if(el)
  {
    var anz = $('#etikett_'+el).val();
    var art_id = $('#art_'+el).val();
    if(anz && anz > 0 && art_id)
    {
      if(confirm('Etiketten wirklich drucken?')){
        $.ajax({
          url: 'index.php?module=schneller_wareneingang&action=printetikett',
          type: 'POST',
          dataType: 'json',
          data: { id: el, val:anz,lp :art_id },
          success: function(data) {
            if(data == null || typeof data.status == "undefined")
            {
              $('#ajaxmessage').html('<div class="error">Fehlende Rechte: Wert konnte nicht gesetzt werden!</div>');
            } else if(data.status != 1)
            {
              $('#ajaxmessage').html('<div class="error">Fehler: '+(typeof data.error != "undefined"?data.error:'unbekannt')+'</div>');
            }
          },
          error: function() {
            $('#ajaxmessage').html('<div class="error">Fehlende Rechte: Wert konnte nicht gesetzt werden!</div>');
            }
        });
      }
    }
  }
}

function chlager(el)
{
  if(el)
  {
    var anz = $('#lager_'+el).val();
    $.ajax({
      url: 'index.php?module=schneller_wareneingang&action=chlager',
      type: 'POST',
      dataType: 'json',
      data: { id: el, val:anz },
      success: function(data) {
        if(data == null || typeof data.status == "undefined" || data.status != 1)
        {
          $('#ajaxmessage').html('<div class="error">Fehlende Rechte: Wert konnte nicht gesetzt werden!</div>');
        } else if(typeof data.error != "undefined")
        {
          $('#ajaxmessage').html('<div class="error">'+data.error+'</div>');
        }
      },
      error: function() {
        $('#ajaxmessage').html('<div class="error">Fehlende Rechte: Wert konnte nicht gesetzt werden!</div>');
        }
    });
  }  
}

function chmenge(el)
{
  if(el)
  {
    var anz = $('#menge_'+el).val();
    $.ajax({
      url: 'index.php?module=schneller_wareneingang&action=chmenge',
      type: 'POST',
      dataType: 'json',
      data: { id: el, val:anz },
      success: function(data) {
        if(data == null || typeof data.status == "undefined" || data.status != 1)
        {
          $('#menge_'+el).css('border-color','red');
          $('#ajaxmessage').html('<div class="error">Fehlende Rechte: Wert konnte nicht gesetzt werden!</div>');
        }else if(typeof data.error != "undefined")
        {
          $('#menge_'+el).css('border-color','red');
          $('#ajaxmessage').html('<div class="error">'+data.error+'</div>');
        }else{
          if(typeof data.auswahlmenge != "undefined")
          {
            anz = anz + '';
            anz = anz.replace(',','.');
            anz = parseFloat(anz);
            if(anz != data.auswahlmenge)
            {
              $('#menge_'+el).val(data.auswahlmenge);
              $('#menge_'+el).css('border-color','red');
              setTimeout(function(){$('#menge_'+el).css('border-color','');
              },2000);
            }
          }
        }
      },
      error: function() {
        $('#ajaxmessage').html('<div class="error">Fehlende Rechte: Wert konnte nicht gesetzt werden!</div>');
        }
    });
  }
}

function chetikett(el)
{
  if(el)
  {
    var anz = $('#etikett_'+el).val();
    $.ajax({
      url: 'index.php?module=schneller_wareneingang&action=chetikett',
      type: 'POST',
      dataType: 'json',
      data: { id: el, val:anz },
      success: function(data) {
        if(data == null || typeof data.status == "undefined" || data.status != 1)
        {
          $('#ajaxmessage').html('<div class="error">Fehlende Rechte: Wert konnte nicht gesetzt werden!</div>');
        }else if(typeof data.error != "undefined")
        {
          $('#ajaxmessage').html('<div class="error">'+data.error+'</div>');
        }
      },
      error: function() {
        $('#ajaxmessage').html('<div class="error">Fehlende Rechte: Wert konnte nicht gesetzt werden!</div>');
        }
    });
  }
}

</script>
