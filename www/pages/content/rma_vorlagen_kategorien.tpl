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
        <input type="button" class="btnGreenNew" name="neuereintrag" value="&#10010; Neuer Eintrag" onclick="RMA_VorlagenKategorienEdit(0);">
      </fieldset>
    </div>
    </div>
    </div>
    </div>

    [TAB1NEXT]
  </div>

<!-- tab view schließen -->
</div>

<div id="editRMA_Vorlagen_Kategorien" style="display:none;" title="Bearbeiten">
  <form method="post">
    <input type="hidden" id="e_id">
    <fieldset>
  	  <legend>{|RMA Kategorie|}</legend>
  	  <table>
        <tr>
          <td width="90">{|Bezeichnung|}:</td><td><input type="text" name="e_bezeichnungk" id="e_bezeichnungk" size="40"></td>
        </tr>
        <tr>
          <td>{|Beschreibung|}:</td><td><textarea name="e_beschreibungk" id="e_beschreibungk"></textarea></td>
        </tr>
        <tr>
          <td>{|Aktion|}:</td><td><select name="e_aktion" id="e_aktion">
                                      [GRUENDE]
                                    </select></td>
        </tr>
      </table>
    </fieldset>    
  </form>
</div>



<script type="text/javascript">

$(document).ready(function() {
  
  $('#e_bezeichnung').focus();

  $("#editRMA_Vorlagen_Kategorien").dialog({
    modal: true,
    bgiframe: true,
    closeOnEscape:false,
    minWidth:650,
    maxHeight:700,
    autoOpen: false,
    buttons: {
      ABBRECHEN: function() {
        RMA_VorlagenKategorienReset();
        $(this).dialog('close');
      },
      SPEICHERN: function() {
        RMA_VorlagenKategorienEditSave();
      }
    }
  });

    $("#editRMA_Vorlagen_Kategorien").dialog({

  close: function( event, ui ) { RMA_VorlagenKategorienReset();}
});

});


function RMA_VorlagenKategorienReset()
{
  $('#editRMA_Vorlagen_Kategorien').find('#e_id').val('');
  $('#editRMA_Vorlagen_Kategorien').find('#e_bezeichnungk').val('');
  $('#editRMA_Vorlagen_Kategorien').find('#e_beschreibungk').val('');
  $('#editRMA_Vorlagen_Kategorien').find('#e_aktion').val('');
}

function RMA_VorlagenKategorienEditSave(){
	$.ajax({
    url: 'index.php?module=rma_vorlagen&action=kategorien_save',
    data: {
      //Alle Felder die fürs editieren vorhanden sind
      id: $('#e_id').val(),
      bezeichnung: $('#e_bezeichnungk').val(),
      beschreibung: $('#e_beschreibungk').val(),
      aktion: $('#e_aktion').val()                     
    },
    method: 'post',
    dataType: 'json',
    beforeSend: function() {
      App.loading.open();
    },
    success: function(data) {
     	App.loading.close();
      if (data.status == 1) {
        RMA_VorlagenKategorienReset();
        updateLiveTable();
        $("#editRMA_Vorlagen_Kategorien").dialog('close');
      } else {
        alert(data.statusText);
      }
    }
  });


}

function RMA_VorlagenKategorienEdit(id){
  if(id > 0)
  { 
    $.ajax({
      url: 'index.php?module=rma_vorlagen&action=kategorien_edit&cmd=get',
      data: {
        id: id
      },
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
      	App.loading.open();
      },
      success: function(data) {
        $('#editRMA_Vorlagen_Kategorien').find('#e_id').val(data.id);
        $('#editRMA_Vorlagen_Kategorien').find('#e_bezeichnungk').val(data.bezeichnung);
        $('#editRMA_Vorlagen_Kategorien').find('#e_beschreibungk').val(data.beschreibung);
        $('#editRMA_Vorlagen_Kategorien').find('#e_aktion').val(data.aktion);
                                        
        App.loading.close();
        $("#editRMA_Vorlagen_Kategorien").dialog('open');
      }
    });
  } else {
    RMA_VorlagenKategorienReset(); 
    $("#editRMA_Vorlagen_Kategorien").dialog('open');
  }

}

function updateLiveTable(i){
  var oTableL = $('#rma_vorlagen_kategorien').dataTable();
  var tmp = $('.dataTables_filter input[type=search]').val();
  oTableL.fnFilter('%');
  //oTableL.fnFilter('');
  oTableL.fnFilter(tmp);   
}

function RMA_VorlagenKategorienDelete(id){
  var conf = confirm('Wirklich löschen?');
  if (conf) {
    $.ajax({
      url: 'index.php?module=rma_vorlagen&action=kategorien_delete',
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
