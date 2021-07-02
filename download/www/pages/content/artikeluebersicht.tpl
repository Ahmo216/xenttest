<!-- gehort zu tabview -->
<div id="tabs">
  <ul>
    <li><a href="#tabs-1"></a></li>
  </ul>
  <style>
    #artikeltabellebilder > tbody > tr > td:nth-child(2) 
    {
      min-height:54px;
      display:inline-block;
    }
  </style>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
<!--
<table height="80" width="100%"><tr><td>
<fieldset><legend>&nbsp;Filter</legend>
<center>
<table width="100%" cellspacing="5">
<tr>
  <td><input type="checkbox" id="angeboteoffen">&nbsp;Fehlende Artikel</td>
  <td><input type="checkbox" id="angeboteoffen">&nbsp;Artikel im Zulauf</td>
  <td><input type="checkbox" id="angeboteheute">&nbsp;Gersperrte Artikel</td>
  <td>Artikelgruppen: <select><option>alle</option><option>Waren 700000</option></select></td>
</tr></table>
</center>
</fieldset>
</td></tr></table>
-->
[SCHNELLSUCHE]
[MESSAGE]
[ARTIKELBAUM]
[TAB1]
<fieldset><legend>{|Stapelverarbeitung|}</legend>
<input type="checkbox" id="auswahlalle" onchange="alleauswaehlen();" />&nbsp;{|alle markieren|}
&nbsp; <select id="sel_aktion" name="sel_aktion">
    <option value="">{|bitte w&auml;hlen|} ...</option>
    <option value="massenbearbeitung">{|Massenbearbeitung|}</option>
    <option value="shopuebertragung">{|Shop&uuml;bertragung|}</option>
    <option value="delete">{|Artikel l&ouml;schen|}</option>
  </select>&nbsp;<input type="button" class="btnBlue" onclick="aktionausfuehren();" name="ausfuehren" value="{|ausf&uuml;hren|}" />
</fieldset>

  <div id="massenwidget">
    <div id="massenwidgetcontent"></div>
  </div>
  
</div>


<!-- tab view schließen -->
</div>

<form method="post">

<div id="copyArtikeldaten" style="display:none;" title="Eintrag kopieren">
  <fieldset>
    <legend>{|Auswahl|}</legend>
    <input type="hidden" id="id">
    <table>
      <tr>
        <td width="150px">{|Einkaufspreise|}:</td>
        <td><input type="checkbox" id="editeinkaufspreise" name="editeinkaufspreise"></td>        
      </tr>
      <tr>
        <td>{|Verkaufspreise|}:</td>
        <td><input type="checkbox" id="editverkaufspreise" name="editverkaufspreise"></td>
      </tr>
      <tr>
        <td>{|Dateien|}:</td>
        <td><input type="checkbox" id="editdateien" name="editdateien"></td>
      </tr>
      <tr>
        <td>{|Eigenschaften|}:</td>
        <td><input type="checkbox" id="editeigenschaften" name="editeigenschaften"></td>
      </tr>
      <tr>
        <td>{|Arbeitsanweisungen|}:</td>
        <td><input type="checkbox" id="editanweisungen" name="editanweisungen"></td>
      </tr>
      <tr>
        <td>{|St&uuml;cklisten|}:</td>
        <td><input type="checkbox" id="editstuecklisten" name="editstuecklisten"></td>
      </tr>
      <tr>
        <td>{|Freifelder &Uuml;bersetzung|}:</td>
        <td><input type="checkbox" id="editfreifelderuebersetzung" name="editfreifelderuebersetzung"></td>
      </tr>
    </table>
  </fieldset>
  
</div>

</form>

<div id="shopuebertragung">
  <div class="content"></div>
</div>

<script type="text/javascript">
  function aktionausfuehren()
  {
    var wert = $('#sel_aktion').val();
    if(wert != '')
    {
      var elemente = $('input.auswahlcb:checked');
      if(elemente.length > 0)
      {
        var elementestr = '';
        $(elemente).each(function(k,v){
          if(elementestr != '')elementestr += ';';
          elementestr += $(v).val();
        });
        
        switch(wert)
        {
          case 'shopuebertragung':
            $.ajax({
              url: 'index.php?module=artikel&action=list&cmd=getshopuebertragung',
              data: {
                list:elementestr
              },
              method: 'post',
              dataType: 'json',
              beforeSend: function() {
                //App.loading.open();
              },
              success: function(data) {
                if(typeof data.success != 'undefined' && data.success == 1)
                {
                  $('#shopuebertragung').dialog('open');
                  $('#shopuebertragung .content').html(data.html);
                  $('#shopuebertragung #articlelist').val(data.articlelist);
                }else if(typeof data.error != 'undefined' && data.error != ''){
                  alert(data.error);
                }
              }
            });

          break;
          case 'delete':
            if(confirm('Sollen die Artikel wirklich gelöscht werden?')) {
              $.ajax({
                url: 'index.php?module=artikel&action=list&cmd=delete',
                data: {
                  list:elementestr
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                  if(typeof data.success != 'undefined' && data.success == 1)
                  {
                    var oTable = $('#artikeltabelle');
                    if(oTable.length == 0) {
                       oTable = $('#artikeltabellebilder');
                    }
                    oTable = $(oTable).DataTable( );
                    oTable.ajax.reload();
                  }else if(typeof data.error != 'undefined' && data.error != ''){
                    alert(data.error);
                  }
                }
              });
            }
            break;
          case 'massenbearbeitung':
            massenedit_open(elementestr);
          break;

        }
      }else {
        alert('{|Bitte Artikel auswählen|}');
      }
    }
  }
  function alleauswaehlen()
  {
    var wert = $('#auswahlalle').prop('checked');
    $('input.auswahlcb').prop('checked', wert);
  }
  
  $(document).ready(function() {
    $("#massenwidget").dialog({
      modal: true,
      bgiframe: true,
      closeOnEscape:false,
      minWidth:300,
      autoOpen: false,
      buttons: {
        '{|ABBRECHEN|}': function() {
          $(this).dialog('close');
        },
        '{|SPEICHERN|}': function() {
          $(this).dialog('close');
        }
      }
    });
    $("#shopuebertragung").dialog({
      modal: true,
      bgiframe: true,
      closeOnEscape:false,
      minWidth:400,
      autoOpen: false,
      buttons: {
        '{|ABBRECHEN|}': function() {
          $(this).dialog('close');
        },
        '{|ÜBERTRAGEN|}': function() {
          $('#frmshops').submit();
        }
      }
    });
  });
  
</script>

<script type="text/javascript">  
$(document).ready(function() {
    $('#editeinkaufspreise').focus();

    $("#copyArtikeldaten").dialog({
    modal: true,
    bgiframe: true,
    closeOnEscape:false,
    minWidth:300,
    autoOpen: false,
    buttons: {
      '{|ABBRECHEN|}': function() {
        $(this).dialog('close');
      },
      '{|SPEICHERN|}': function() {
        ArtikelCopyEditSave();
      }
    }
  });

});


function ArtikelCopyEditSave() {
  $('body').loadingOverlay();
    $.ajax({
        url: 'index.php?module=artikel&action=copysave',
        data: {
            //Alle Felder die fürs editieren vorhanden sind
            editid: $('#id').val(),
            editeinkaufspreise: $('#editeinkaufspreise').prop("checked")?1:0,
            editverkaufspreise: $('#editverkaufspreise').prop("checked")?1:0,
            editdateien: $('#editdateien').prop("checked")?1:0,
            editeigenschaften: $('#editeigenschaften').prop("checked")?1:0,
            editanweisungen: $('#editanweisungen').prop("checked")?1:0,
            editstuecklisten: $('#editstuecklisten').prop("checked")?1:0,
            editfreifelderuebersetzung: $('#editfreifelderuebersetzung').prop("checked")?1:0
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            //App.loading.open();
        },
        success: function(data) {
        	//alert(data);
            //App.loading.close();
            if (data.status == 1) {
            	if(typeof data.url != 'undefined')window.location = data.url;
                $('#copyArtikeldaten').find('#id').val('');
                $('#copyArtikeldaten').find('#editeinkaufspreise').prop('checked', false);
                $('#copyArtikeldaten').find('#editverkaufspreise').prop('checked', false);
                $('#copyArtikeldaten').find('#editdateien').prop('checked', false);
                $('#copyArtikeldaten').find('#editeigenschaften').prop('checked', false);
                $('#copyArtikeldaten').find('#editanweisungen').prop('checked', false);
                $('#copyArtikeldaten').find('#editstuecklisten').prop('checked', false);
                $('#copyArtikeldaten').find('#editfreifelderuebersetzung').prop('checked', false);
                //updateLiveTable();
                $("#copyArtikeldaten").dialog('close');
            } else {
                alert(data.statusText);
            }
        },
        complete: function () {
          $('body').loadingOverlay('remove');
        }
    });

}

function ArtikelCopyEdit(id) {  
    $.ajax({
        url: 'index.php?module=artikel&action=copyedit&cmd=get',
        data: {
            id: id
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            App.loading.open();
        },
        success: function(data) {

            if(typeof data.id != "undefined") $('#copyArtikeldaten').find('#id').val(data.id);
            $('#copyArtikeldaten').find('#editeinkaufspreise').prop('checked', true);
            $('#copyArtikeldaten').find('#editverkaufspreise').prop('checked', true);
            $('#copyArtikeldaten').find('#editdateien').prop('checked', true);
            $('#copyArtikeldaten').find('#editeigenschaften').prop('checked', true);
            $('#copyArtikeldaten').find('#editanweisungen').prop('checked', true);
            $('#copyArtikeldaten').find('#editstuecklisten').prop('checked', true);
            $('#copyArtikeldaten').find('#editfreifelderuebersetzung').prop('checked', true);
            App.loading.close();
            $("#copyArtikeldaten").dialog('open');
        }
    });

}



</script>

