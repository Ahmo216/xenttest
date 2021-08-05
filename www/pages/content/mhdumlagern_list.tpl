<!-- gehort zu tabview -->
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">[TABTEXT]</a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
<form method="post">
  [MESSAGE]

  <fieldset>
  	<legend>{|Anlegen|}</legend>
    <table>
  	  <tr>
  	    <td nowrap>
  	      {|Artikel|}:
  	    </td>
  	    <td width="170">	
  	      <input type="text" name="artikel_neu" id="artikel_neu">
  	    </td>
  	    <td nowrap>
  	      {|Lagerplatz von|}:
  	    </td>
  	    <td width="170">
  	      <input type="text" name="lagerplatz_neu" id="lagerplatz_neu">
  	    </td>
  	    <td nowrap>
  	      {|Lagerplatz Ziel|}:
  	    </td>
  	    <td width="170">
  	      <input type="text" name="lagerplatz_ziel_neu" id="lagerplatz_ziel_neu">
  	    </td>
  	    <td nowrap>
  	      {|Anzahl Tage|}:
  	    </td>
  	    <td width="170">
  	      <input type="text" name="anzahl_tage_neu" id="anzahl_tage_neu">
  	    </td>
  	    <td nowrap>
          {|Aktiv|}:
        </td>
        <td width="40">
          <input type="checkbox" name="aktiv_neu" id="aktiv_neu" checked="checked" value="1">
        </td>
  	    <td nowrap>
          {|MHD Verl&auml;ngerung zulassen (heutiges Datum als Basis)|}:
        </td>
        <td width="40">
          <input type="checkbox" name="verlaengern_zulassen_neu" id="verlaengern_zulassen_neu" value="1">
        </td>
        <td>
  	      <input type="submit" name="anlegen" value="Anlegen">
  	    </td>
      </tr>
  	</table>
  </fieldset>

  [TAB1]
  [TAB1NEXT]
</div>

<!-- tab view schließen -->
</div>


  
<div id="editMhdumlagern" style="display:none;" title="Bearbeiten">
  <input type="hidden" id="e_id">
  <table>
    <tr>
      <td nowrap>{|Artikel|}: </td>
      <td><input type="text" name="e_artikel" id="e_artikel"></td>        
    </tr>
    <tr>
      <td nowrap>{|Lagerplatz von|}:</td>
      <td><input type="text" id="e_lagerplatz"></td>
    </tr>
    <tr>
      <td nowrap>{|Lagerplatz Ziel|}:</td>
      <td><input type="text" id="e_lagerplatz_ziel"></td>
    </tr>
    <tr>
      <td nowrap>{|Anzahl Tage|}:</td>
      <td><input type="text" id="e_anzahl_tage"></td>
    </tr>
    <tr>
      <td nowrap>{|Aktiv|}:</td>
      <td><input type="checkbox" id="e_aktiv" value="1"></td>
    </tr>
    <tr>
      <td nowrap>{|Verl&auml;ngern zulassen|}:</td>
      <td><input type="checkbox" id="e_verlaengern_zulassen" value="1"></td>
    </tr>
  </table>
  
  
</div>


</form>
<script type="text/javascript">

$(document).ready(function() {
    $('#e_nummer').focus();

    $("#editMhdumlagern").dialog({
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
        mhdumlagernEditSave();
      }
    }
  });

});

function mhdumlagernEditSave() {

    $.ajax({
        url: 'index.php?module=mhdumlagern&action=save',
        data: {
            //Alle Felder die fürs editieren vorhanden sind
            id: $('#e_id').val(),
            artikel: $('#e_artikel').val(),
            lagerplatz: $('#e_lagerplatz').val(),
            lagerplatz_ziel: $('#e_lagerplatz_ziel').val(),
            anzahl_tage: $('#e_anzahl_tage').val(),
            aktiv: $('#e_aktiv').prop("checked")?1:0,
            verlaengern_zulassen:$('#e_verlaengern_zulassen').prop("checked")?1:0,
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            App.loading.open();
        },
        success: function(data) {

            App.loading.close();
            if (data.status == 1) {
                $('#editMhdumlagern').find('#e_id').val('');
                $('#editMhdumlagern').find('#e_artikel').val('');
                $('#editMhdumlagern').find('#e_lagerplatz').val('');
                $('#editMhdumlagern').find('#e_lagerplatz_ziel').val('');
                $('#editMhdumlagern').find('#e_anzahl_tage').val();
                $('#editMhdumlagern').find('#e_aktiv').prop('checked',true);
                $('#editMhdumlagern').find('#e_verlaengern_zulassen').prop('checked',false);
                updateLiveTable();
                $("#editMhdumlagern").dialog('close');
            } else {
                alert(data.statusText);
            }
        }
    });

}

function MhdumlagernEdit(id) {

    $.ajax({
        url: 'index.php?module=mhdumlagern&action=edit&cmd=get',
        data: {
            id: id
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            App.loading.open();
        },
        success: function(data) {
            $('#editMhdumlagern').find('#e_id').val(data.id);
            $('#editMhdumlagern').find('#e_artikel').val(data.artikel);
            $('#editMhdumlagern').find('#e_lagerplatz').val(data.lagerplatz);
            $('#editMhdumlagern').find('#e_lagerplatz_ziel').val(data.lagerplatz_ziel);
            $('#editMhdumlagern').find('#e_anzahl_tage').val(data.anzahl_tage);
            $('#editMhdumlagern').find('#e_aktiv').prop("checked",data.aktiv==1?true:false);
            $('#editMhdumlagern').find('#e_verlaengern_zulassen').prop("checked",data.verlaengern_zulassen==1?true:false);
            
            App.loading.close();
            $("#editMhdumlagern").dialog('open');
        }
    });

}

function updateLiveTable(i) {
    var oTableL = $('#mhdumlagern_list').dataTable();
    var tmp = $('.dataTables_filter input[type=search]').val();
    oTableL.fnFilter('%');
    //oTableL.fnFilter('');
    oTableL.fnFilter(tmp);    
}

function MhdumlagernDelete(id) {

    var conf = confirm('Wirklich löschen?');
    if (conf) {
        $.ajax({
            url: 'index.php?module=mhdumlagern&action=delete',
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


