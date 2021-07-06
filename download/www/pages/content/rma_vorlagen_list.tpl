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
        <legend> </legend>
        [TAB1]
      </fieldset>
    </div>
    </div>
    <div class="col-xs-12 col-md-2 col-md-height">
    <div class="inside inside-full-height">
      <fieldset>
        <legend>{|Aktionen|}</legend>
        <input type="button" class="btnGreenNew" name="neuereintrag" value="&#10010; Neuer Eintrag" onclick="RMA_VorlagenEdit(0);">
      </fieldset>
    </div>
    </div>
    </div>
    </div>

    [TAB1NEXT]
  </div>

<!-- tab view schließen -->
</div>

<div id="editRMA_Vorlagen" style="display:none;" title="Bearbeiten">
  <form method="post">
    <input type="hidden" id="e_id">
    <fieldset>
  	  <legend>{|RMA Vorlage|}</legend>
  	  <table>
        <tr>
          <td width="150"><label for="e_bezeichnung">{|Bezeichnung|}:</label></td>
          <td><input type="text" name="e_bezeichnung" id="e_bezeichnung" size="40"></td>
        </tr>
        <tr>
          <td><label for="e_beschreibung">{|Beschreibung|}:</label></td>
          <td><textarea name="e_beschreibung" id="e_beschreibung"></textarea></td>
        </tr>
        <tr>
          <td><label for="e_sprache">{|Sprache|}:</label></td>
          <td><select name="e_sprache" id="e_sprache">
                                      [SPRACHEN]
                                    </select></td>
        </tr>
        <tr>
          <td><label for="e_projekt">{|Projekt|}:</label></td>
          <td><input type="text" name="e_projekt" id="e_projekt" size="40"></td>
        </tr>
        <tr>
          <td><label for="e_kategorie">{|Kategorie|}:</label></td>
          <td><input type="text" name="e_kategorie" id="e_kategorie" size="40"></td>
        </tr>
        <tr>
          <td><label for="e_storagelocation">{|Default Lagerplatz|}:</label></td>
          <td><input type="text" name="e_storagelocation" id="e_storagelocation" size="40"></td>
        </tr>
        <tr>
          <td><label for="e_ausblenden">{|ausblenden|}:</label></td><td><input type="checkbox" name="e_ausblenden" id="e_ausblenden" size="40"></td>
        </tr>
      </table>
    </fieldset>    
  </form>
</div>



<script type="text/javascript">

$(document).ready(function() {
  
  $('#e_bezeichnung').focus();

  $("#editRMA_Vorlagen").dialog({
    modal: true,
    bgiframe: true,
    closeOnEscape:false,
    minWidth:650,
    maxHeight:700,
    autoOpen: false,
    buttons: {
      ABBRECHEN: function() {
        RMA_VorlagenReset();
        $(this).dialog('close');
      },
      SPEICHERN: function() {
        RMA_VorlagenEditSave();
      }
    }
  });

    $("#editRMA_Vorlagen").dialog({

  close: function( event, ui ) { RMA_VorlagenReset();}
});

});


function RMA_VorlagenReset()
{
  $('#editRMA_Vorlagen').find('#e_id').val('');
  $('#editRMA_Vorlagen').find('#e_bezeichnung').val('');
  $('#editRMA_Vorlagen').find('#e_beschreibung').val('');
  $('#editRMA_Vorlagen').find('#e_sprache').val('DE');
  $('#editRMA_Vorlagen').find('#e_projekt').val('');
  $('#editRMA_Vorlagen').find('#e_kategorie').val('');
  $('#editRMA_Vorlagen').find('#e_storagelocation').val('');
  $('#editRMA_Vorlagen').find('#e_ausblenden').val(0);
}

function RMA_VorlagenEditSave(){
	$.ajax({
    url: 'index.php?module=rma_vorlagen&action=save',
    data: {
      //Alle Felder die fürs editieren vorhanden sind
      id: $('#e_id').val(),
      bezeichnung: $('#e_bezeichnung').val(),
      beschreibung: $('#e_beschreibung').val(),
      sprache: $('#e_sprache').val(),
      projekt: $('#e_projekt').val(),
      kategorie: $('#e_kategorie').val(),
      ausblenden: $('#e_ausblenden').prop("checked")?1:0,
      storagelocation: $('#e_storagelocation').val(),
    },
    method: 'post',
    dataType: 'json',
    beforeSend: function() {
      App.loading.open();
    },
    success: function(data) {
     	App.loading.close();
      if (data.status == 1) {
        RMA_VorlagenReset();
        updateLiveTable();
        $("#editRMA_Vorlagen").dialog('close');
      } else {
        alert(data.statusText);
      }
    }
  });


}

function RMA_VorlagenEdit(id){
  if(id > 0)
  { 
    $.ajax({
      url: 'index.php?module=rma_vorlagen&action=edit&cmd=get',
      data: {
        id: id
      },
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
      	App.loading.open();
      },
      success: function(data) {
        $('#editRMA_Vorlagen').find('#e_id').val(data.id);
        $('#editRMA_Vorlagen').find('#e_bezeichnung').val(data.bezeichnung);
        $('#editRMA_Vorlagen').find('#e_beschreibung').val(data.beschreibung);
        $('#editRMA_Vorlagen').find('#e_sprache').val(data.sprache);
        $('#editRMA_Vorlagen').find('#e_projekt').val(data.projekt);
        $('#editRMA_Vorlagen').find('#e_kategorie').val(data.rmakategorie);
        $('#editRMA_Vorlagen').find('#e_ausblenden').prop("checked", data.ausblenden==1?true:false);
        $('#editRMA_Vorlagen').find('#e_storagelocation').val(data.storagelocation);
        App.loading.close();
        $("#editRMA_Vorlagen").dialog('open');
      }
    });
  } else {
    RMA_VorlagenReset(); 
    $("#editRMA_Vorlagen").dialog('open');
  }

}

function updateLiveTable(i){
  var oTableL = $('#rma_vorlagen_list').dataTable();
  var tmp = $('.dataTables_filter input[type=search]').val();
  oTableL.fnFilter('%');
  //oTableL.fnFilter('');
  oTableL.fnFilter(tmp);   
}

function RMA_VorlagenDelete(id){
  var conf = confirm('Wirklich löschen?');
  if (conf) {
    $.ajax({
      url: 'index.php?module=rma_vorlagen&action=delete',
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
