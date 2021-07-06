<!-- gehort zu tabview -->
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">[TABTEXT]</a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
    <fieldset>
		<legend>{|Anlegen|}</legend>
		<form method="post">
			<table>
				<tr>
					<td width="50">{|Name|}:</td>
                    <td width="250"><input type="text" id="name" name="name" size="30" /></td>
                    <td width="65">{|Kennung|}:</td>
					<td width="250"><input type="text" id="kennung" name="kennung" size="30" /></td>
					<td width="35">{|Aktiv|}:</td>
					<td width="50"><input type="checkbox" name="aktiv" value="1" /></td>
					<td><input type="submit" name="speichern" value="Speichern" /></td>
				</tr>
			</table>
		</form>
	</fieldset>
[MESSAGE]

[TAB1]

[TAB1NEXT]


</div>
<!-- tab view schließen -->
</div>

<div id="editFormeln" style="display:none;" title="Bearbeiten">
  <fieldset>
    <legend>{|Formel|}</legend>
    <input type="hidden" id="id">
    <table>
      <tr>
        <td style="width:80px">{|Name|}:</td>
        <td><input type="text" id="editname" size="40"/></td>        
      </tr>
      <tr>
        <td>{|Kennung|}:</td>
        <td><input type="text" id="editkennung" size="40"/></td>        
      </tr>
      <tr>
        <td>{|ID|}:</td>
        <td><span id="idanzeigen"></span></td>
      </tr>
      <tr>
        <td>{|Aktiv|}:</td>
        <td><input type="checkbox" id="editaktiv" /></td>
      </tr>
      <tr>
        <td>{|Formel|}:</td>
        <td><textarea id="editformel" name="editformel" rows="10" columns="50" style="width:400px; min-width:400px;"></textarea></td>
      </tr>
    </table>
  </fieldset>  
</div>

<script type="text/javascript">

$(document).ready(function() {
    $('#editname').focus();

    $("#editFormeln").dialog({
    modal: true,
    bgiframe: true,
    closeOnEscape:false,
    minWidth:550,
    autoOpen: false,
    buttons: {
      ABBRECHEN: function() {
        $(this).dialog('close');
      },
      SPEICHERN: function() {
        FormelnEditSave();
      }
    }
  });

});

function FormelnEditSave() {

    $.ajax({
        url: 'index.php?module=formeln&action=save',
        data: {
            //Alle Felder die fürs editieren vorhanden sind
            editid: $('#id').val(),
            editname: $('#editname').val(),
            editkennung: $('#editkennung').val(),
            editaktiv: $('#editaktiv').prop("checked")?1:0,
            editformel: $('#editformel').val(),

        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            App.loading.open();
        },
        success: function(data) {
            App.loading.close();
            if (data.status == 1) {
                $('#editFormeln').find('#id').val('');
                $('#editFormeln').find('#editname').val('');
                $('#editFormeln').find('#editkennung').val('');
                $('#editFormeln').find('#editaktiv').val('');
                $('#editFormeln').find('#editformel').val(),
                updateLiveTable();
                $("#editFormeln").dialog('close');
            } else {
                alert(data.statusText);
            }
        }
    });

}

function FormelnEdit(id) {

    $.ajax({
        url: 'index.php?module=formeln&action=edit&cmd=get',
        data: {
            id: id
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            App.loading.open();
        },
        success: function(data) {
            $('#editFormeln').find('#id').val(data.id);
            $('#editFormeln').find('#editname').val(data.name);
            $('#editFormeln').find('#editkennung').val(data.kennung);
            $('#editFormeln').find('#editaktiv').prop("checked",data.aktiv==1?true:false);
            $('#editFormeln').find('#editformel').val(data.formel);
            $('#editFormeln').find('#idanzeigen').text(data.id);
            App.loading.close();
            $("#editFormeln").dialog('open');
        }
    });

}

function updateLiveTable(i) {
    var oTableL = $('#formeln_list').dataTable();
    var tmp = $('.dataTables_filter input[type=search]').val();
    oTableL.fnFilter('%');
    //oTableL.fnFilter('');
    oTableL.fnFilter(tmp);   

}

function FormelnDelete(id) {

    var conf = confirm('Wirklich löschen?');
    if (conf) {
        $.ajax({
            url: 'index.php?module=formeln&action=delete',
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
