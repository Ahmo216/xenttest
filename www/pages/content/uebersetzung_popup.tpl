<!-- gehort zu tabview -->
<!--<div id="tabs">
    <ul>
        <li><a href="#tabs-1">[TABTEXT]</a></li>
    </ul>-->
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<!--<form method="post">
<div id="tabs-1">
  [MESSAGE]
  <center><input style="width:20em" type="button" name="anlegen" value="Neuen Eintrag anlegen" onclick="Uebersetzung_Edit(0);"></center>
  [TAB1]
  [TAB1NEXT]
</div>-->

<!-- tab view schließen -->
<!--</div>-->

<div id="editUebersetzung" style="display:none;" title="Bearbeiten">
  <fieldset>
    <legend>Textbaustein</legend>
    <input type="hidden" id="editid">
    <table>
      <tr>
        <td>Variable:</td>
        <td><input type="text" name="editvar" id="editvar" size="40"></td>
      </tr>
      <tr>
        <td>Sprache:</td>
        <td><select name="editsprache" id="editsprache">
            [SPRACHE]
            </select>
        </td>        
      </tr>
      <tr>
        <td>&Uuml;bersetzung:</td>
        <td><textarea name="edituebersetzung" id="edituebersetzung"></textarea></td>
      </tr>
      <tr>
        <td width="180">Text aus Standardsprache:</td>
        <td><textarea name="editstandard" id="editstandard"></textarea></td>
      </tr>
    </table>
  </fieldset>      
</div>


</form>
<script type="text/javascript">

$(document).ready(function() {
    $('#editvar').trigger('focus');

    $("#editUebersetzung").dialog({
    modal: true,
    bgiframe: true,
    closeOnEscape:false,
    minWidth:650,
    autoOpen: false,
    buttons: {
      ABBRECHEN: function() {
        Uebersetzung_Reset();
        $(this).dialog('close');
      },
      SPEICHERN: function() {
        Uebersetzung_EditSave();
      }
    }
  });

  $("#editUebersetzung").dialog({
    close: function( event, ui ) { Uebersetzung_Reset();}
  });

});

function Uebersetzung_Reset(){
  $('#editUebersetzung').find('#editid').val('');
  $('#editUebersetzung').find('#editvar').val('');
  $('#editUebersetzung').find('#editsprache').val('');
  $('#editUebersetzung').find('#edituebersetzung').val('');
  $('#editUebersetzung').find('#editstandard').val('');
}

function Uebersetzung_EditSave() {

    $.ajax({
        url: 'index.php?module=uebersetzung&action=save',
        data: {
            //Alle Felder die fürs editieren vorhanden sind
            editid: $('#editid').val(),
            editvar: $('#editvar').val(),
            editsprache: $('#editsprache').val(),
            edituebersetzung: $('#edituebersetzung').val(),
            editstandard: $('#editstandard').val()            
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            App.loading.open();
        },
        success: function(data) {
            App.loading.close();
            if (data.status == 1) {
                $('#editUebersetzung').find('#editid').val('');
                $('#editUebersetzung').find('#editvar').val('');
                $('#editUebersetzung').find('#editsprache').val('');
                $('#editUebersetzung').find('#edituebersetzung').val('');
                $('#editUebersetzung').find('#editstandard').val('');
                Uebersetzung_Reset();
                updateLiveTable();
                $("#editUebersetzung").dialog('close');
            } else {
                alert(data.statusText);
            }
        }
    });

}

function Uebersetzung_Edit(id) {
  if(id > 0)
  { 
    $.ajax({
        url: 'index.php?module=uebersetzung&action=edit&cmd=get',
        data: {
            id: id
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            App.loading.open();
        },
        success: function(data) {
            $('#editUebersetzung').find('#editid').val(data.id);
            $('#editUebersetzung').find('#editvar').val(data.label);
            $('#editUebersetzung').find('#editsprache').val(data.sprache);
            $('#editUebersetzung').find('#edituebersetzung').val(data.beschriftung);
            $('#editUebersetzung').find('#editstandard').val(data.original);
            App.loading.close();
            $("#editUebersetzung").dialog('open');
        }
    });
  }else{
    Uebersetzung_Reset(); 
    $("#editUebersetzung").dialog('open');
  }

}

function updateLiveTable(i) {
    var oTableL = $('#uebersetzunglist').dataTable();

    var tmp  = $('.dataTables_filter input[type=search]').val(); 
    oTableL.fnFilter('%');
    oTableL.fnFilter(tmp);   
}

function Uebersetzung_Delete(id) {

    var conf = confirm('Wirklich löschen?');
    if (conf) {
        $.ajax({ 
            url: 'index.php?module=uebersetzung&action=delete',
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
