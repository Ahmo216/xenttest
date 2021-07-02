<!-- gehort zu tabview -->
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">[TABTEXT]</a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
  [MESSAGE]
  <form method="post">
	  <fieldset>
	  	<legend>Auftrag</legend>
	  	<table>
	  		<tr>
	  			<td width="85">Bezeichnung:</td>
	  			<td width="305"><input type="text" name="bezeichnung" id="bezeichnung" size="40" value="[BEZEICHNUNG]"></td>
	  			<td width="110">geh&ouml;rt zu Auftrag:</td>
	  			<td width="170"><input type="text" name="auftrag" id="auftrag" size="20" value="[AUFTRAG]"></td>
	  			<td><input type="submit" name="anlegen" id="anlegen" value="Speichern"></td>
	  		</tr>
	  	</table>
	  </fieldset>
	</form>
  [TAB1]
  [TAB1NEXT]
</div>

<!-- tab view schließen -->
</div>

<!--<div id="editEintrag" style="display:none;" title="Bearbeiten">
  <input type="hidden" id="editid">
  <table>
    <tr>
      <td>Bezeichnung:</td>
      <td><input type="text" id="editbezeichnung" size="40"></td>
    </tr>
    <tr>
      <td>Auftrag:</td>
      <td><input type="text" id="editauftrag"></td>
    </tr>    
    
  </table>
      
</div>


</form>
<script type="text/javascript">

$(document).ready(function() {
    $('#editbezeichnung').focus();

    $("#editEintrag").dialog({
    modal: true,
    bgiframe: true,
    closeOnEscape:false,
    minWidth:400,
    autoOpen: false,
    buttons: {
      ABBRECHEN: function() {
        $(this).dialog('close');
      },
      SPEICHERN: function() {
        AbschlagsrechnungEditSave();
      }
    }
  });

});

function AbschlagsrechnungEditSave() {

    $.ajax({
        url: 'index.php?module=abschlagsrechnung&action=save',
        data: {
            //Alle Felder die fürs editieren vorhanden sind
            editid: $('#editid').val(),
            editbezeichnung: $('#editbezeichnung').val(),
            editauftrag: $('#editauftrag').val()
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            App.loading.open();
        },
        success: function(data) {
            App.loading.close();
            if (data.status == 1) {
                $('#editEintrag').find('#editid').val('');
                $('#editEintrag').find('#editbezeichnung').val('');
                $('#editEintrag').find('#editauftrag').val('');
                
                updateLiveTable();
                $("#editEintrag").dialog('close');
            } else {
                alert(data.statusText);
            }
        }
    });

}

function AbschlagsrechnungEdit(id) {

    $.ajax({
        url: 'index.php?module=abschlagsrechnung&action=edit&cmd=get',
        data: {
            id: id
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            App.loading.open();
        },
        success: function(data) {
            $('#editEintrag').find('#editid').val(data.id);
            $('#editEintrag').find('#editbezeichnung').val(data.bezeichnung);
            $('#editEintrag').find('#editauftrag').val(data.auftrag);
            
            App.loading.close();
            $("#editEintrag").dialog('open');
        }
    });

}

function updateLiveTable(i) {
    var oTableL = $('#abschlagsrechnung_list').dataTable();
    oTableL.fnFilter('%');
    oTableL.fnFilter('');   
}

</script>-->
