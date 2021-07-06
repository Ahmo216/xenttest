
<div id="serienbriefDialog" style="display:none;" title="Serienbrief">    
    <input type="hidden" id="b_id">
    <input type="hidden" id="b_rechnung" value="[ID]">
    <table>
        <tr>
            <td>{|Layoutvorlagen|}:</td>
            <td>[LAYOUTS]</td>
        </tr>
        <tr>
            <td>{|Drucker|}:</td>
            <td><select name="b_drucker" id="b_drucker">[DRUCKER]</select></td>
        </tr>
        <tr>
            <td>{|Sammel-PDF|}:</td>
            <td><input type="checkbox" value="1" name="b_sammelpdf" id="b_sammelpdf" /></td>
        </tr>
    </table>
</div>

<script>
function selvorlage()
{
  var vorlage = $('#vorlage').val();
  if(vorlage)
  {
    $.ajax({
      url: 'index.php?module=adressefilter&action=list&cmd=getvorlage&id=[ID]',
      type: 'POST',
      dataType: 'json',
      data: { vorlageid: vorlage},
      success: function(data) {
        if(data)
        {
          $('#geladenvorlage').val(vorlage);
          if(typeof data.html != 'undefined')$('#filterdiv').html(data.html);
          autocompleteandbind();
        }
      }
    });
  }
}

function sendaktstatus(aktion, parameter)
{
  if(typeof aktion == 'undefined')aktion = false;
  $.ajax({
    url: 'index.php?module=adressefilter&action=list&cmd=sendaktstatus&id=[ID]',
    type: 'POST',
    dataType: 'json',
    data: $('#frmfilterdiv').serialize(),
    success: function(data) {
      if(data)
      {
        if(aktion == 'savevorlage')
        {
          if(typeof parameter != 'undefined')
          {
            $.ajax({
              url: 'index.php?module=adressefilter&action=list&cmd=savevorlage&id=[ID]',
              type: 'POST',
              dataType: 'json',
              data: {vorlage: parameter},
              success: function(data) {
                if(data)
                {
                  var urla = window.location.href.split('#');
                  window.location.href=urla[ 0 ];
                }
              }
            });
          }
          return;
        }
        
        if(aktion == 'saveneuevorlage')
        {
          if(typeof parameter != 'undefined')
          {          
            $.ajax({
              url: 'index.php?module=adressefilter&action=list&cmd=savevorlage&id=[ID]',
              type: 'POST',
              dataType: 'json',
              data: {vorlage:0, name: parameter},
              success: function(data) {
                if(data)
                {
                  var urla = window.location.href.split('#');
                  window.location.href=urla[ 0 ];
                }
              }
            });
          }
          return;
        }
      
        if(aktion == 'addfilter')
        {
          var addtypval = $('#addtyp').val();
          var addidval = $('#addid').val();
          var filtertypval = $('#filterpopup').val();
          $.ajax({
            url: 'index.php?module=adressefilter&action=list&cmd=addfilter&id=[ID]',
            type: 'POST',
            dataType: 'json',
            data: {addtyp: addtypval, addid: addidval, filtertyp:filtertypval},
            success: function(data) {
              if(data)
              {
                if(typeof data.html != 'undefined')
                {
                  $('#filterdiv').html(data.html);
                  $('#addposition').dialog('close');
                  autocompleteandbind();
                }
              }
            }
          });
          return;
        }
        if(aktion == 'delposition')
        {
          if(typeof parameter != 'undefined')
          {
            $.ajax({
              url: 'index.php?module=adressefilter&action=list&cmd=delposition&id=[ID]',
              type: 'POST',
              dataType: 'json',
              data: {pos:parameter},
              success: function(data) {
                if(data)
                {
                  if(typeof data.html != 'undefined')
                  {
                    $('#filterdiv').html(data.html);
                    autocompleteandbind();
                  }
                }
              }
            });
          }
          return;
        }

        if(typeof data.html != 'undefined')
        {
          $('#filterdiv').html(data.html);
          autocompleteandbind();
        }
      }
    }
  });
  
}

function delposition(id)
{
  sendaktstatus('delposition', id);
}

function autocompleteandbind()
{
  $('#filterdiv').find('input').off('keypress');
  $('#filterdiv').find('input').on('keypress', function(event){
    if (event.which == 13) {
      $(this).trigger('change');
      return false;
    }
  });
  $('.position_delete').off('click');
  $('.position_delete').on('click',function(){
    var ida = this.id.split('_');
    $.ajax({
      url: 'index.php?module=adressefilter&action=list&cmd=delposition&id=[ID]',
      type: 'POST',
      dataType: 'json',
      data: { element: this.id},
      success: function(data) {
        if(data)
        {
          if(typeof data.html != 'undefined')$('#filterdiv').html(data.html);
          autocompleteandbind();
        }
      }
    });
  });
  
  $('.projektname').each(function(){  
      $( this ).autocomplete({
      source: "index.php?module=ajax&action=filter&rmodule=adressefilter&raction=list&rid=&filtername=projektname",
      select: function( event, ui ) {
      var i = ui.item.value;
      var zahl = i.indexOf(" ");
      var text = i.slice(0, zahl);
      $( this).val( text );
      return false;
      }
    });
    

  
  });

    $('.gruppekennziffer').each(function(){

      $( this ).autocomplete({
      source: "index.php?module=ajax&action=filter&rmodule=adressefilter&raction=list&rid=&filtername=gruppekennziffer",
      select: function( event, ui ) {
      var i = ui.item.value;
      var zahl = i.indexOf(" ");
      var text = i.slice(0, zahl);
      $( this).val( text );
      return false;
      }
    });
   });
  
  $('#filterdiv').find('input').each(function(){
    $(this).on('change',function(){
      sendaktstatus();
    });
    
  });
  

  $('#filterdiv').find('.positionoder').each(function(){
    $(this).on('click',function(){
      $('#addtyp').val('positionoder');
      $('#addid').val(this.id);
      $('#addposition').dialog('open');
    });  
  });

  $('#filterdiv').find('.positionund').each(function(){
    $(this).on('click',function(){
      $('#addtyp').val('positionund');
      $('#addid').val(this.id);
      $('#addposition').dialog('open');
    });
  });
  
  
  $('#filterdiv').find('.parameter2sel').each(function(){
    $(this).on('change',function(){
      var wert = $(this).val();
      var ida = this.id.split('_');
      var pid = ida[ 2 ];
      switch(wert)
      {
        case 'marketingsperre':
          $('#position_typ2_'+pid).hide();
          $('#position_parameter1_'+pid).hide();
        break;
        default:
          $('#position_typ2_'+pid).show();
          $('#position_parameter1_'+pid).show();
        break;
      }
    });
    $(this).trigger('change');
  });


  var oTable = $('#adresse_filter').DataTable( );
  oTable.ajax.reload();
  updatelupe();
}

function chaktion()
{
  var aktion = $('#aktion').val();
  var vorlage = $('#vorlage').val();
  switch(aktion)
  {
    case 'savevorlage':
      if(vorlage)
      {
        var wert = '';
        if(wert = confirm('Vorlage überschreiben?'))
        {
          sendaktstatus('savevorlage', vorlage);
        }
      }else{
        var name = prompt("Name der Vorlage?");
        if(name)
        {
          sendaktstatus('saveneuevorlage', name);
        }else{
          alert('{|Nicht gespeichert|}');
        }
      }
    break;
    case 'savevorlageneu':
      var name = prompt("Name der Vorlage?");
      if(name)
      {
        sendaktstatus('saveneuevorlage', name);
      }else{
        alert('Nicht gespeichert');
      }
    break;
    case 'delvorlage':
      if(vorlage)
      {
        if(confirm('{|Vorlage wirklich löschen?|}'))
        {
          $.ajax({
            url: 'index.php?module=adressefilter&action=list&cmd=delvorlage&id=[ID]',
            type: 'POST',
            dataType: 'json',
            data: { vorlageid: vorlage},
            success: function(data) {
              if(data)
              {
                if(typeof data.status != 'undefined' && data.status == 1)
                {
                  var urla = window.location.href.split('#');
                  window.location.href=urla[ 0 ];
                }else alert('Fehler beim Löschen der Vorlage');
              }
            }
         });
        }
      }else{
        alert('Keine Vorlage ausgewählt');
      }
    
    break;
    case 'exportadressen':
      window.location.href='index.php?module=adressefilter&action=list&cmd=csv';
      $('#aktion').val('');
    break;
    case 'exportadressenansprechpartner':
      window.location.href='index.php?module=adressefilter&action=list&cmd=csvansprechpartner';
      $('#aktion').val('');
    break;
    case 'exportemails':
      window.location.href='index.php?module=adressefilter&action=list&cmd=csvemail';
      $('#aktion').val('');
    break;
    case 'exportemailsansprechpartner':
      window.location.href='index.php?module=adressefilter&action=list&cmd=csvemailanprechpartner';
      $('#aktion').val('');
    break;
    case 'exportadressenutf8':
      window.location.href='index.php?module=adressefilter&action=list&cmd=csvutf8';
      $('#aktion').val('');
      break;
    case 'exportadressenansprechpartnerutf8':
      window.location.href='index.php?module=adressefilter&action=list&cmd=csvansprechpartnerutf8';
      $('#aktion').val('');
      break;
    case 'exportemailsutf8':
      window.location.href='index.php?module=adressefilter&action=list&cmd=csvemailutf8';
      $('#aktion').val('');
      break;
    case 'exportemailsansprechpartnerutf8':
      window.location.href='index.php?module=adressefilter&action=list&cmd=csvemailanprechpartnerutf8';
      $('#aktion').val('');
      break;

    case 'serienbrief':
      $("#serienbriefDialog").dialog({
        modal: true,
        bgiframe: true,
        closeOnEscape:false,
        autoOpen: false,
        minWidth:400,
        buttons: {
          VORSCHAU: function() {
            window.location.href='index.php?module=adressefilter&action=list&cmd=serienbriefvorschau&layout=' + $('#b_layout').val();
          },
          ABBRECHEN: function() {
            $(this).dialog('close');
          },
          DRUCKEN: function() {
        $.ajax({
        url: 'index.php?module=adressefilter&action=list&cmd=serienbrief',
        data: {
            layout: $('#b_layout').val(),
            drucker: $('#b_drucker').val(),
            sammelpdf:$('#b_sammelpdf').prop('checked')?1:0
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
            if (data.status == 1) {
                $("#serienbriefDialog").dialog('close');
                alert(data.statusText);
            } else {
                alert(data.statusText);
            }
        }
        });
          }
        }
      });
      $('#serienbriefDialog').dialog('open');
      $('#aktion').val('');
    break;
  }
}

function addfilter()
{
  sendaktstatus('addfilter');
}

function findgruppe(typ)
{
  var lastid = false;
  $('#filterdiv').find('.'+typ).last().each(function(){
  
    var ret = false;
    return $(this).parent().each(function(){if(typeof this.id != 'undefined')ret = this.id});
    return ret;
  });
  return false;
}

function getnewpositionid()
{
  var k = 1;
  var found = false;
  while(true)
  {
    found = false;
    $('#vorlageposition_-'+k).each(function(){found = true;});
    if(!found)return k;
    k++;
  }

}

function getnewvorlageid()
{
  var k = 1;
  var found = false;
  while(true)
  {
    found = false;
    $('#vorlagegruppen_-'+k).each(function(){found = true;});
    if(!found)return k;
    k++;
  }
}

function addgruppe(typ)
{
  var getvorlageid_neu = getnewvorlageid();
  $('#filterdiv').append('<fieldset class="vorlagegruppe" id="vorlagegruppe_-'+getvorlageid_neu+'"></fieldset>');
  return 'vorlagegruppe_-'+getvorlageid_neu;
}

$(document).ready(function() {
autocompleteandbind();
});
function updatelupe()
{
  $('.ui-autocomplete-input').each(function(){
    var elnext = $(this).next();
    if($(elnext).is('a') && $(elnext).html() === 'X')
    {
      $(elnext).after('<img  onclick="clicklupe(this);" style="right:10px;top:5px;position:absolute;cursor:pointer;" src="images/icon_lupe_plus_transparent.png" class="autocomplete_lupe" />');
    } else {
      $(this).after('<img  onclick="clicklupe(this);" style="left:-23px;top:4px;margin-right:-22px;position:relative;cursor:pointer;max-heigth:12px;" src="images/icon_lupe_plus_transparent.png" class="autocomplete_lupe" />');
    }


  });


  $('.ui-autocomplete-input').each(function(){
    if($(this).css('display') === 'none')$(this).next('.autocomplete_lupe').hide();
  });
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
        var found = false;
        $(el).prev('.ui-autocomplete-input').each(function(){
          //var v = $(this).val();
          found = true;
          aktlupe = this;
          $(this).val(' ');
          $(this).trigger('keydown');
        });
        if(!found)
        {
          $(el).prev('a').prev('.ui-autocomplete-input').each(function(){
            found = true;
            aktlupe = this;
            $(this).val(' ');
            $(this).trigger('keydown');
          });
        }
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

</script>


<!-- gehort zu tabview -->
<div id="tabs">
    <ul>
        <li><a href="#tabs-1"><!--Alle Tickets--></a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">

<!--[MESSAGE]-->
<div class="row">
<div class="row-height">

<div class="col-xs-12 col-sm-4 col-sm-height">
<div class="inside inside-full-height">
  <fieldset><legend>&nbsp;Vorlage</legend>
    <table width="100%" cellspacing="5">
      <tr>
        <td>
          <label for="vorlage">{|Bezeichnung|}:</label>&nbsp;
          <select name="vorlage" id="vorlage" onchange="vorlagech()">
            <option value="">{|Bitte w&auml;hlen ...|}</option>
            [FILTERVORLAGEN]
          </select>
          <input type="button" value="{|laden|}" onclick="selvorlage();"><input type="hidden" id="geladenvorlage" />
        </td>
      </tr>
    </table>
  </fieldset>
</div>
</div>


<div class="col-xs-12 col-sm-4 col-sm-height">
<div class="inside inside-full-height">
<fieldset><legend>&nbsp;{|Aktion|}</legend>
  <label for="aktion">{|Bitte wählen|}</label>:&nbsp;
  <select name="aktion" id="aktion" onchange="chaktion()">
    <option value="">{|Bitte w&auml;hlen ...|}</option>
    <option value="savevorlageneu">{|als neue Vorlage speichern|}</option>
    <option value="savevorlage" class="bestehendopt">{|bestehende Vorlage &uuml;berschreiben|}</option>
    <option value="delvorlage">{|Vorlage l&ouml;schen|}</option>
    [ADRESSFILTERMODULE]
<!--    <option value="kampange">zur Kampange hinzuf&uuml;gen</option>
    <option value="auftrag">Auftrag anlegen</option>-->
    <option value="exportemails">{|Export E-Mails f&uuml;r Newsletter|}</option>
    <option value="exportemailsutf8">{|Export E-Mails f&uuml;r Newsletter (UTF-8)|}</option>
    <option value="exportemailsansprechpartner">{|Export E-Mails inkl. aller Ansprechpartner f&uuml;r Newsletter|}</option>
    <option value="exportemailsansprechpartnerutf8">{|Export E-Mails inkl. aller Ansprechpartner f&uuml;r Newsletter (UTF-8)|}</option>
    <option value="exportadressen">{|Export Adressen als CSV|}</option>
    <option value="exportadressenutf8">{|Export Adressen als CSV (UTF-8)|}</option>
    <option value="exportadressenansprechpartner">{|Export Adressen inkl. aller Ansprechpartner als CSV|}</option>
    <option value="exportadressenansprechpartnerutf8">{|Export Adressen inkl. aller Ansprechpartner als CSV (UTF-8)|}</option>
  </select>
  <input type="button" value="{|jetzt anlegen|}" />
</fieldset>


</div>
</div>
</div>
</div>

  <form id="frmfilterdiv" method="post" action="index.php?module=adressefilter&action=list">
    <div id="filterdiv">
      [FILTER]
    </div>
  </form>
  [TAB1]
</div>



<!-- tab view schließen -->
</div>
<div id="addposition">
  <table width="100%" cellspacing="5">
    <tr>
      <td>
        <label for="filterpopup">{|Bedingung f&uuml;r|}:</label>&nbsp;
        <select name="filterpopup" id="filterpopup">
          <option value="gruppe">{|Gruppe|}</option>
          <option value="gruppeansprechpartner">{|Gruppe f&uuml;r Ansprechpartner|}</option>
          <option value="projekt">{|Projekt|}</option>
          <option value="adressfeld">{|Adressfeld|}</option>
          <option value="artikelinbeleg">{|Artikel in Belege|}</option>
        </select>
        <input type="hidden" id="addtyp" /><input type="hidden" id="addid" />
        <input type="button" value="hinzuf&uuml;gen" onclick="addfilter()" />
      </td>
    </tr>
  </table>
</div>

<script>
  function vorlagech()
  {
    if($('#vorlage').val())
    {
      $('.bestehendopt').show();
    }else{
      $('.bestehendopt').hide();
    }
  }
  vorlagech();
  $('#addposition').dialog(
  {
    modal: true,
    autoOpen: false,
    minWidth: 940,
    title:'{|Filter hinzufügen|}',
    
    close: function(event, ui){
      
    }
  });
</script>
