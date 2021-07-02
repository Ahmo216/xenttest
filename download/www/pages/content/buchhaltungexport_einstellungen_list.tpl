<!-- gehort zu tabview -->

<div id="tabs">
  <ul>
    <li><a href="#tabs-1">[TABTEXT]</a></li>
  </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
  [MESSAGE]
  	
  <div class="row">
  <div class="row-height">
  <div class="col-xs-12 col-md-10 col-md-height">
  <div class="inside_white inside-full-height">

    <fieldset class="white">
      <legend>&nbsp;</legend>
      [TAB1]
    </fieldset>
    
  </div>
  </div>
  <div class="col-xs-12 col-md-2 col-md-height">
  <div class="inside inside-full-height">
  
    <fieldset>
      <legend>{|Aktionen|}</legend>
      <input class="btnGreenNew" type="button" name="anlegen" value="&#10010; {|Neuen Eintrag anlegen|}" onclick="BuchhaltungexportEinstellungenEdit(0);">
    </fieldset>

  </div>
  </div>
  </div>
  </div>

  [TAB1NEXT]
</div>

<!-- tab view schließen -->
</div>

<div id="editEinstellungen" style="display:none;" title="Bearbeiten">
<form method="post">
  <div class="row">
    <div class="row-height">
      <div class="col-xs-12 col-md-12 col-md-height">
        <div class="inside inside-full-height">
          <input type="hidden" id="e_id">
          <fieldset>
            <legend>{|Einstellungen|}</legend>
            <table>
              <tr>
                <td>{|Beraternummer|}:</td>
                <td><input type="text" name="e_beraternummer" id="e_beraternummer" size="40"></td>
              </tr>
              <tr>
                <td>{|Mandantennummer|}:</td>
                <td><input type="text" name="e_mandantennummer" id="e_mandantennummer" size="40"></td>
              </tr>
              <tr>
                <td>{|Projekt|}:</td>
                <td><input type="text" name="e_projekt" id="e_projekt" size="40"></td>
              </tr>
              <tr>
                <td width="160">{|Zahlungsweise in Datev|}:</td>
                <td><input type="text" name="e_zahlungsweise" id="e_zahlungsweise" size="40"></td>
              </tr>
              <tr>
                <td>{|Wirtschaftsjahr|}:</td>
                <td><input type="text" name="e_wirtschaftsjahr" id="e_wirtschaftsjahr" size="5"></td>
              </tr>
              <tr>
                <td>{|Sachkontenl&auml;nge|}:</td>
                <td><input type="text" name="e_sachkontenlaenge" id="e_sachkontenlaenge" size="5"></td>
              </tr>
            </table>
          </fieldset>
        </div>
      </div>
    </div>
  </div>
  
</div>


</form>
<script type="text/javascript">

$(document).ready(function() {
    $('#e_beraternummer').focus();

    $("#editEinstellungen").dialog({
    modal: true,
    bgiframe: true,
    closeOnEscape:false,
    minWidth:600,
    maxHeight:700,
    autoOpen: false,
    buttons: {
      ABBRECHEN: function() {
        EinstellungenReset();
        $(this).dialog('close');
      },
      SPEICHERN: function() {
        BuchhaltungExportEinstellungenEditSave();
      }
    }
  });

    $("#editEinstellungen").dialog({

  close: function( event, ui ) { EinstellungenReset();}
});

});


function EinstellungenReset()
{
  $('#editEinstellungen').find('#e_id').val('');
  $('#editEinstellungen').find('#e_beraternummer').val('');
  $('#editEinstellungen').find('#e_mandantennummer').val('');
  $('#editEinstellungen').find('#e_projekt').val('');
  $('#editEinstellungen').find('#e_zahlungsweise').val('');
  $('#editEinstellungen').find('#e_wirtschaftsjahr').val('');
  $('#editEinstellungen').find('#e_sachkontenlaenge').val('');
  
}

function BuchhaltungExportEinstellungenEditSave() {
	$.ajax({
    url: 'index.php?module=buchhaltungexport&action=einstellungen_save',
    data: {
      //Alle Felder die fürs editieren vorhanden sind
      id: $('#e_id').val(),
      beraternummer: $('#e_beraternummer').val(),
      mandantennummer: $('#e_mandantennummer').val(),
      projekt: $('#e_projekt').val(),
      zahlungsweise: $('#e_zahlungsweise').val(),
      wirtschaftsjahr: $('#e_wirtschaftsjahr').val(),
      sachkontenlaenge: $('#e_sachkontenlaenge').val()            
    },
    method: 'post',
    dataType: 'json',
    beforeSend: function() {
      App.loading.open();
    },
    success: function(data) {
     	App.loading.close();
      if (data.status == 1) {
        EinstellungenReset();
        updateLiveTable();
        $("#editEinstellungen").dialog('close');
      } else {
        alert(data.statusText);
      }
    }
  });


}

function BuchhaltungexportEinstellungenEdit(id) {
  if(id > 0)
  { 
    $.ajax({
      url: 'index.php?module=buchhaltungexport&action=einstellungen_edit&cmd=get',
      data: {
          id: id
      },
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
      	App.loading.open();
      },
      success: function(data) {
        if(data.id > 0){
          $('#editEinstellungen').find('#e_id').val(data.id);
          $('#editEinstellungen').find('#e_beraternummer').val(data.beraternummer);
          $('#editEinstellungen').find('#e_mandantennummer').val(data.mandantennummer);
          $('#editEinstellungen').find('#e_projekt').val(data.projekt);
          $('#editEinstellungen').find('#e_zahlungsweise').val(data.zahlungsweise);
          $('#editEinstellungen').find('#e_wirtschaftsjahr').val(data.wirtschaftsjahr);
          $('#editEinstellungen').find('#e_sachkontenlaenge').val(data.sachkontenlaenge);
                          
        }
        App.loading.close();
        $("#editEinstellungen").dialog('open');
      }
    });
  } else {
    EinstellungenReset(); 
    $("#editEinstellungen").dialog('open');
  }

}

function updateLiveTable(i) {
  var oTableL = $('#buchhaltungexport_einstellugen').dataTable();
  oTableL.fnFilter('%');
  oTableL.fnFilter('');   
}

function BuchhaltungexportEinstellungenDelete(id) {

  var conf = confirm('Wirklich löschen?');
  if (conf) {
    $.ajax({
      url: 'index.php?module=buchhaltungexport&action=einstellungen_delete',
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

</script>
