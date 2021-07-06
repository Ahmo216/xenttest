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
<div class="col-xs-12 col-md-3 col-md-height">
<div class="inside inside-full-height">


	<div class="filter-box filter-usersave">
		<div class="filter-block filter-inline">
			<div class="filter-title">{|Filter|}</div>
			<ul class="filter-list">
				<li class="filter-item">
					<label for="inaktiv" class="switch">
						<input type="checkbox" id="inaktiv">
						<span class="slider round"></span>
					</label>
					<label for="inaktiv">&nbsp;{|Auch inaktive|}</label>
				</li>
			</ul>
		</div>
	</div>

</div>
</div>
<div class="col-xs-12 col-md-9 col-md-height">
<div class="inside inside-full-height">

<form method="post">
<fieldset>
	<legend>{|Anlegen|}</legend>
	<table>
		<tr>
			<td width="80">{|Bezeichnung|}:</td>
			<td width="170"><input type="text" id="bezeichnungfremdnr" name="bezeichnungfremdnr" /></td>
			<td width="90">{|Fremdnummer|}:</td>
			<td width="170"><input type="text" id="fremdnummer" name="fremdnummer" /></td>
			<td width="40">{|Shop|}:</td>
			<td width="200"><input type="text" id="shopfremdnr" name="shopfremdnr" /></td>
			<td>{|Barcodescanner|}:</td>
			<td width="50"><input type="checkbox" name="scannablefremdnr" value="1" /></td>
			<td>{|Aktiv|}:</td>
			<td width="50"><input type="checkbox" name="aktivfremdnr" checked="checked" value="1" /></td>
			<td><input type="submit" name="speichernfremdnr" value="Speichern" /></td>
		</tr>
	</table>
</fieldset>


</div>
</div>
</div>
</div>

[TAB1]
[TAB1NEXT]
</div>
<!-- tab view schließen -->
</div>


<div id="editFremdnummern" style="display:none;" title="Bearbeiten">
  <input type="hidden" id="id" name="id" value="" />
  <table>
    <tr>
      <td>Bezeichnung:</td>
      <td><input type="text" id="editbezeichnung" name="editbezeichnung" value="" size="35"/></td>        
    </tr>
    <tr>
      <td width="90">Fremdnummer:</td>
      <td><input type="text" id="editfremdnummer" name="editfremdnummer" value="" size="35"/></td>
    </tr>
    <tr>
    	<td>Shop:</td>
    	<td><input type="text" id="editshop" name="editshop" value="" size="35"/></td>
    </tr>
    <tr>
      <td>Barcodescanner:</td>
      <td><input type="checkbox" id="editscannable" name="editscannable" value="1" /></td>
     </tr>
 
    <tr>
      <td>Aktiv:</td>
      <td><input type="checkbox" id="editaktiv" name="editaktiv" value="1" /></td>
     </tr>
  </table>
</div>
</form>

<script type="text/javascript">

$(document).ready(function() {
    $('#editbezeichnung').focus();

    $("#editFremdnummern").dialog({
    modal: true,
    bgiframe: true,
    closeOnEscape:false,
    minWidth:420,
    autoOpen: false,
    buttons: {
      ABBRECHEN: function() {
        $(this).dialog('close');
      },
      SPEICHERN: function() {
        ArtikelFremdnummernEditSave();
      }
    }
  });

});

function ArtikelFremdnummernEditSave() {

    $.ajax({
        url: 'index.php?module=artikel&action=fremdnummernsave',
        data: {
            //Alle Felder die fürs editieren vorhanden sind
            editid: $('#id').val(),
            editbezeichnung: $('#editbezeichnung').val(),
            editfremdnummer: $('#editfremdnummer').val(),
            editshop: $('#editshop').val(),
            editaktiv: $('#editaktiv').prop("checked")?1:0,
            editscannable: $('#editscannable').prop("checked")?1:0,
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            App.loading.open();
        },
        success: function(data) {
            App.loading.close();
            if (data.status == 1) {
                $('#editFremdnummern').find('#id').val('');
                $('#editFremdnummern').find('#editbezeichnung').val('');
                $('#editFremdnummern').find('#editfremdnummer').val('');
                $('#editFremdnummern').find('#editshop').val('');
                $('#editFremdnummern').find('#editaktiv').val('');
                $('#editFremdnummern').find('#editscannable').val('');
                updateLiveTable();
                $("#editFremdnummern").dialog('close');
            } else {
                alert(data.statusText);
            }
        }
    });

}

function ArtikelFremdnummernEdit(id) {

    $.ajax({
        url: 'index.php?module=artikel&action=fremdnummernedit&cmd=get',
        data: {
            id: id
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            App.loading.open();
        },
        success: function(data) {
            $('#editFremdnummern').find('#id').val(data.id);
            $('#editFremdnummern').find('#editbezeichnung').val(data.bezeichnung);
            $('#editFremdnummern').find('#editfremdnummer').val(data.fremdnummer);
            $('#editFremdnummern').find('#editshop').val(data.shop);
            $('#editFremdnummern').find('#editaktiv').prop("checked",data.aktiv==1?true:false);
            $('#editFremdnummern').find('#editscannable').prop("checked",data.scannable==1?true:false);
            App.loading.close();
            $("#editFremdnummern").dialog('open');
        }
    });

}

function updateLiveTable(i) {
    var oTableL = $('#artikel_fremdnummern').dataTable();
    oTableL.fnFilter('%');
    oTableL.fnFilter('');   
}

function ArtikelFremdnummernDelete(id) {

    var conf = confirm('Wirklich löschen?');
    if (conf) {
        $.ajax({
            url: 'index.php?module=artikel&action=fremdnummerndelete',
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
