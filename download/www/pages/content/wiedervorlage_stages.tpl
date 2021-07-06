
<div id="editStages" style="display:none;" title="Bearbeiten">
  <input type="hidden" id="editstageid" value="">
  <fieldset>
    <legend>{|Stages|}</legend>
    <table>
      <tr>
        <td><label for="editname">{|Name|}:</label></td>
        <td><input type="text" name="editname" id="editname" size="40"></td>        
      </tr>
      <tr>
        <td width="140"><label for="editkurzbezeichnung">{|Kurzbezeichnung (optional)|}:</label></td>
        <td><input type="text" name="editkurzbezeichnung" id="editkurzbezeichnung" size="40"></td>
      </tr>
      <tr>
        <td><label for="edithexcolor">{|Farbe|} ({|optional|}):</label></td>
        <td><input type="text" name="edithexcolor" id="edithexcolor" size="8"></td>
      </tr>
      <tr>
        <td><label for="editsort">{|Reihenfolge|}:</label></td>
        <td><input type="text" id="editsort" size="10"></td>
      </tr>
      <tr>
        <td><label for="editview">{|Board|}:</label></td>
        <td><input type="text" name="editview" id="editview" size="40"></td>
      </tr>
      <tr>
        <td><label for="editausblenden">{|Nicht sichtbar|}:</label></td>
        <td><input type="checkbox" name="editausblenden" id="editausblenden" value="1"></td>
      </tr>
      <tr>
        <td><label for="editstageausblenden">{|Als Pipe verwenden|}:</label></td>
        <td><input type="checkbox" name="editstageausblenden" id="editstageausblenden" value="1"></td>
      </tr>
      <tr>
        <td><label for="seleditchance">{|Chance|}:</label></td>
        <td>
          <select id="seleditchance" name="seleditchance">
            <option value=""></option>
            <option value="0">0%</option>
            <option value="10">10%</option>
            <option value="20">20%</option>
            <option value="30">30%</option>
            <option value="40">40%</option>
            <option value="50">50%</option>
            <option value="60">60%</option>
            <option value="70">70%</option>
            <option value="80">80%</option>
            <option value="90">90%</option>
            <option value="100">100%</option>
          </select>
        </td>
      </tr>
    </table>
  </fieldset>
</div>


<script type="text/javascript">

$(document).ready(function() {
    $('#editkurzbezeichnung').focus();

    $("#editStages").dialog({
    modal: true,
    bgiframe: true,
    closeOnEscape:false,
    minWidth:600,
    autoOpen: false,
    buttons: {
      ABBRECHEN: function() {
        $(this).dialog('close');
      },
      SPEICHERN: function() {
        WiedervorlageStagesEditSave();
      }
    }
  });

});

function WiedervorlageStagesEditSave(){

    $.ajax({
        url: 'index.php?module=wiedervorlage&action=stagessave',
        data: {
            //Alle Felder die fürs editieren vorhanden sind
            editid: $('#editstageid').val(),
            editkurzbezeichnung: $('#editkurzbezeichnung').val(),
            editname: $('#editname').val(),
            edithexcolor: $('#edithexcolor').val(),
            editsort: $('#editsort').val(),
            editview: $('#editview').val(),
            editchance: $('#seleditchance').val()+'',
            editausblenden: $('#editausblenden').prop("checked")?1:0,
	        editstageausblenden: $('#editstageausblenden').prop("checked")?1:0
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            App.loading.open();
        },
        success: function(data) {
            App.loading.close();
            if (data.status == 1) {
                $('#editStages').find('#editstageid').val('');
                $('#editStages').find('#editkurzbezeichnung').val('');
                $('#editStages').find('#editname').val('');
                $('#editStages').find('#edithexcolor').val(data.hexcolor);
                $('#editStages').find('#editsort').val('');
                $('#editStages').find('#editview').val('');
                $('#editStages').find('#seleditchance').val('');
                $('#editStages').find('#editausblenden').val('');
                $('#editStages').find('#editstageausblenden').val('');

                WiedervorlageStagesUpdateLiveTable();
                $("#editStages").dialog('close');
            } else {
                alert(data.statusText);
            }
        }
    });

}

function WiedervorlageStagesEdit(id) {
    $.ajax({
        url: 'index.php?module=wiedervorlage&action=stagesedit&cmd=get',
        data: {
            id: id
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            App.loading.open();
        },
        success: function(data) {
            $('#editStages').find('#editstageid').val(data.id);
            $('#editStages').find('#editkurzbezeichnung').val(data.kurzbezeichnung);
            $('#editStages').find('#editname').val(data.name);
            $('#editStages').find('#edithexcolor').val(data.hexcolor).change();
            $('#editStages').find('#editsort').val(data.sort);
            $('#editStages').find('#editview').val(data.view);
            $('#editStages').find('#seleditchance').val(data.chance);
            $('#editStages').find('#editausblenden').prop("checked",data.ausblenden==1?true:false);
            $('#editStages').find('#editstageausblenden').prop("checked",data.stageausblenden==1?true:false);

            $("#editStages").dialog('open');
        }
    });

}

function WiedervorlageStagesUpdateLiveTable(i) {
    var oTableL = $('#wiedervorlage_stages').dataTable();
    oTableL.fnFilter('%');
    oTableL.fnFilter('');   
}

function WiedervorlageStagesDelete(id) {

    var conf = confirm('Wirklich löschen?');
    if (conf) {
        $.ajax({ 
            url: 'index.php?module=wiedervorlage&action=stagesdelete',
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
                    WiedervorlageStagesUpdateLiveTable();
                } else {
                    alert(data.statusText);
                }
                App.loading.close();
            }
        });
    }

    return false;

}

function WiedervorlageStandardLaden(){
  var conf = confirm('Wirklich Standarddaten laden?');
  if(conf){
    $.ajax({
      url: 'index.php?module=wiedervorlage&action=stages&cmd=standard',
      data: {

      },
      method: 'post',
      dataType: 'json',
      beforeSend: function(){
        App.loading.open();
      },
      success: function(data){
        if(data.status == 1){
          WiedervorlageStagesUpdateLiveTable();
        }else{
          alert(data.statusText);
        }
        App.loading.close();
      }
    });
  }

  return false;

}


</script>
