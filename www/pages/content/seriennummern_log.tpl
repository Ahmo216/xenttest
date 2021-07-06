<div id="tabs">
  <ul>
    <li><a href="#tabs-1"><!--[TABTEXT]--></a></li>
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
        <input type="button" class="btnGreenNew" name="neuereintrag" value="&#10010; Neuer Eintrag" onclick="neuereintrag(0);">
      </fieldset>
    </div>
    </div>
    </div>
    </div>
   
    [TAB1NEXT]
  </div>

<!-- tab view schließen -->
</div>
<div id="divneuereintrag" style="display:none">
  <fieldset><legend>{|Neuer Eintrag|}</legend>
  <table>
    <tr><td>{|Artikel|}:</td><td><input type="text" id="artikel" size="40" /></td></tr>
    <tr><td>{|Seriennummer|}:</td><td><input type="text" id="seriennummer" size="40" /></td></tr>
    <tr><td>{|Lieferschein|}:</td><td><input type="text" id="lieferschein" size="40" /></td></tr>
    <tr><td>{|Kunde|}:</td><td><input type="text" id="adresse" size="40" /></td></tr>
    <tr><td>{|Lager|}:</td><td><input type="text" id="lager_platz" size="40" /></td></tr>
    <tr><td>{|Bemerkung|}:</td><td><input type="text" id="grund" size="40" /></td></tr>
  </table>
    </fieldset>
</div>

<div id="editHistorieBemerkung" style="display:none;" title="Bearbeiten">
  <form method="post">
    <input type="hidden" id="e_id">
    <fieldset>
      <legend>{|Bearbeiten|}</legend>
      <table>
        <tr>
          <td width="200">{|Bemerkung|}:</td>
          <td><input type="text" name="e_bemerkung" id="e_bemerkung" size="40"></td> 
        </tr>
      </table>
    </fieldset>
  </form>
</div>

<script type="text/javascript">
  
  $(document).ready(function() {
  
    $('#divneuereintrag').dialog(
    {
      modal: true,
      autoOpen: false,
      minWidth: 460,
      title:'',
      buttons: {

        '{|ABBRECHEN|}': function() {
          $(this).dialog('close');
        },
        
        '{|SPEICHERN|}': function()
        {
          if($('#artikel').val()+'' != '' && $('#seriennummer').val()+'' != '')
          {
            $.ajax({
                url: 'index.php?module=seriennummern&action=log&cmd=new',
                type: 'POST',
                dataType: 'json',
                data: {  artikel:$('#artikel').val(),
                  seriennummer:$('#seriennummer').val(),
                  lieferschein:$('#lieferschein').val(),
                  adresse:$('#adresse').val(),
                  lager_platz:$('#lager_platz').val(),
                  grund:$('#grund').val()
                },
                success: function(data) {
                  $('#divneuereintrag').dialog('close');
                  var oTable = $('#seriennummern_log').DataTable( );
                  oTable.ajax.reload();
                },
                beforeSend: function() {

                }
            });
          }else{
            alert('{|Bitte Artikel und Seriennummer ausfüllen!|}');
          }
        }        
      },
      close: function(event, ui){
        
      }
    });


    $("#editHistorieBemerkung").dialog({
      modal: true,
      bgiframe: true,
      closeOnEscape:false,
      minWidth:430,
      maxHeight:700,
      autoOpen: false,
      buttons: {
        ABBRECHEN: function() {
          HistorieBemerkungReset();
          $(this).dialog('close');
        },
        SPEICHERN: function() {
          HistorieBemerkungEditSave();
        }
      }
    });

    $('#editHistorieBemerkung').submit(function(event){
      event.preventDefault();
      HistorieBemerkungEditSave();
    });


  });

  function HistorieBemerkungReset()
  {
    $('#editHistorieBemerkung').find('#e_id').val('');
    $('#editHistorieBemerkung').find('#e_bemerkung').val('');
  }

  function HistorieBemerkungEditSave() {
  $.ajax({
    url: 'index.php?module=seriennummern&action=log&cmd=bemerkungsave',
    data: {
      //Alle Felder die fürs editieren vorhanden sind
      id: $('#e_id').val(),
      bemerkung: $('#e_bemerkung').val()                      
    },
    method: 'post',
    dataType: 'json',
    beforeSend: function() {
      App.loading.open();
    },
    success: function(data) {
      App.loading.close();
      if (data.status == 1) {
        HistorieBemerkungReset();
        updateLiveTable();
        $("#editHistorieBemerkung").dialog('close');
      } else {
        alert(data.statusText);
      }
    }
  });


}

function HistorieBemerkungEdit(id) {
  if(id > 0)
  { 
    $.ajax({
      url: 'index.php?module=seriennummern&action=log&cmd=bemerkungedit',
      data: {
        id: id
      },
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
        App.loading.open();
      },
      success: function(data) {
        $('#editHistorieBemerkung').find('#e_id').val(data.id);
        $('#editHistorieBemerkung').find('#e_bemerkung').val(data.internebemerkung);
                
        App.loading.close();
        $("#editHistorieBemerkung").dialog('open');
      }
    });
  } else {
    HistorieBemerkungReset(); 
    $("#editHistorieBemerkung").dialog('open');
  }
}

function updateLiveTable(i) {
  var oTableL = $('#seriennummern_log').dataTable();
  var tmp = $('.dataTables_filter input[type=search]').val();
  oTableL.fnFilter('%');
  //oTableL.fnFilter('');
  oTableL.fnFilter(tmp);   
}

  
  function neuereintrag()
  {
    $('#artikel').val('');
    $('#seriennummer').val('');
    $('#lieferschein').val('');
    $('#adresse').val('');
    $('#grund').val('');
    $('#lager_platz').val('');
    $('#divneuereintrag').dialog('open');
  }
  
</script>