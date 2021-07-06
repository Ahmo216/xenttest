<!-- gehort zu tabview -->
<div id="tabs">
  <ul>
    <li><a href="#tabs-1">[TABTEXT]</a></li>
  </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
	<div id="tabs-1">

		[MESSAGE]

		<div class="filter-box filter-usersave">
			<div class="filter-block filter-inline">
				<div class="filter-title">{|Filter|}</div>
				<ul class="filter-list">
					<li class="filter-item">
						<label for="archiviert" class="switch">
							<input type="checkbox" id="archiviert">
							<span class="slider round"></span>
						</label>
						<label for="archiviert">{|Archiv|}</label>
					</li>
				</ul>
			</div>
		</div>

[TAB1]
[TAB1NEXT]
</div>

<!-- tab view schließen -->
</div>


[DATEIENPOPUP]
<div id="editWissensdatenbank" style="display:none;" title="Bearbeiten">
<form method="post">
  <input type="hidden" id="e_id">
  <fieldset>
  	<legend>{|Wissensdatenbank|}</legend>
  	<table>
      <tr>
        <td width="150">{|&Uuml;berschrift|}:</td>
        <td><input type="text" name="e_ueberschrift" id="e_ueberschrift" size="90"></td>
      </tr>
      <tr>
        <td>{|Hinweis f&uuml;r &Uuml;berschrift|}:</td>
        <td><input type="text" name="e_hinweis" id="e_hinweis" size="90"></td>
      </tr>
      <tr>
        <td>{|Tags|}:</td>
        <td><input type="text" name="e_tags" id="e_tags" size="90"></td>
      </tr>
      <tr>
        <td>{|archiviert|}:</td>
        <td><input type="checkbox" name="e_archiviert" id="e_archiviert"></td>
      </tr>
      <tr>
      	<td>{|Text|}:</td>
      	<td><textarea name="e_text" id="e_text"></textarea></td>
      </tr>
      <tr>
      	<td>{|Interne Bemerkung|}:</td>
      	<td><textarea name="e_internebemerkung" id="e_internebemerkung"></textarea>
      </tr>
    </table>
  </fieldset>    
</div>
</form>


<script type="text/javascript">

  $(document).ready(function() {
  $('#e_ueberschrift').focus();
  

  $("#editWissensdatenbank").dialog({
    modal: true,
    bgiframe: true,
    closeOnEscape:false,
    minWidth:1000,
    maxHeight:1000,
    autoOpen: false,
    buttons: {
      [DATEIBUTTON]

      ABBRECHEN: function() {
        WissensdatenbankReset();
        $(this).dialog('close');
      },
      SPEICHERN: function() {
        WissensdatenbankEditSave();
      }
    },
    open: function(event, ui){
      if(auxid > 0){
        WissensdatenbankEdit(auxid);
        auxid = 0;
      }
    }
  });

  $("#editWissensdatenbank").dialog({
    close: function( event, ui ) { WissensdatenbankReset();}
  });

});


function WissensdatenbankReset()
{
  $('#editWissensdatenbank').find('#e_id').val('');
  $('#editWissensdatenbank').find('#e_ueberschrift').val('');
  $('#editWissensdatenbank').find('#e_hinweis').val('');
  $('#editWissensdatenbank').find('#e_tags').val('');
  $('#editWissensdatenbank').find('#e_archiviert').prop("checked", false);
  $('#editWissensdatenbank').find('#e_text').val('');
  $('#editWissensdatenbank').find('#e_internebemerkung').val(''); 
}

function WissensdatenbankEditSave() {
  $.ajax({
    url: 'index.php?module=wissensdatenbank&action=edit&cmd=save',
    data: {
      //Alle Felder die fürs editieren vorhanden sind
      id: $('#e_id').val(),
      ueberschrift: $('#e_ueberschrift').val(),
      hinweis: $('#e_hinweis').val(),
      tags: $('#e_tags').val(),
      archiviert: $('#e_archiviert').prop("checked")?1:0,
      text: $('#e_text').val(),
      internebemerkung: $('#e_internebemerkung').val()                      
    },
    method: 'post',
    dataType: 'json',
    beforeSend: function() {
      App.loading.open();
    },
    success: function(data) {
      App.loading.close();
      if (data.status == 1) {
        WissensdatenbankReset();
        updateLiveTable();
        $("#editWissensdatenbank").dialog('close');
      } else {
        alert(data.statusText);
      }
    }
  });
}

function WissensdatenbankEdit(id) {
  if(id > 0)
  { 
    auxid = 0;
    $.ajax({
      url: 'index.php?module=wissensdatenbank&action=edit&cmd=get',
      data: {
        id: id
      },
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
        App.loading.open();
      },
      success: function(data) {
        $('#editWissensdatenbank').find('#e_id').val(data.id);
        $('#editWissensdatenbank').find('#e_ueberschrift').val(data.ueberschrift);
        $('#editWissensdatenbank').find('#e_hinweis').val(data.hinweis);
        $('#editWissensdatenbank').find('#e_tags').val(data.tags);
        $('#editWissensdatenbank').find('#e_archiviert').prop("checked",data.archiviert==1?true:false);
        $('#editWissensdatenbank').find('#e_text').val(data.text);
        $('#editWissensdatenbank').find('#e_internebemerkung').val(data.bemerkung);
                        
        App.loading.close();
        [AFTERPOPUPOPEN]
        $("#editWissensdatenbank").dialog('open');
      }
    });
  } else {
    var button = $('#[FROMPOPUP]').parent().find('div.ui-dialog-buttonpane > div.ui-dialog-buttonset > button > span').first();
    var wert = $(button).html();
    if(wert.indexOf('(') > -1)wert = trim(wert.substr(0, wert.indexOf('(')));
    $(button).html(wert+' (0)');
    WissensdatenbankReset(); 
    $("#editWissensdatenbank").dialog('open');
  }

}

function updateLiveTable(i) {
  var oTableL = $('#wissensdatenbank_list').dataTable();
  var tmp = $('.dataTables_filter input[type=search]').val();
  oTableL.fnFilter('%');
  //oTableL.fnFilter('');
  oTableL.fnFilter(tmp);   
}


</script>
