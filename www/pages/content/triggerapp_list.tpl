<div id="edittrigger" style="display:none" title="Mailtrigger Einstellungen">
  <input type="hidden" id="editid" value="0">
  <table>
    <tr>
      <td>Bezeichnung:</td><td><input type="text" id="bezeichnung" value="" /></td>
    </tr>     
    <tr>
      <td>Beleg:</td><td><select id="belegart" value="" />[BELEGART]</select></td>
    </tr>    
    <tr>
      <td>Status:</td><td><select id="belegstatus" value="" />[BELEGSTATUS]</select></td>
    </tr> 
    <tr>
      <td>Vorlage:</td><td><input type="text" id="vorlage" value="" /></td>
    </tr> 
    <tr>
      <td>Tage nach Wechsel zu Status:</td><td><input type="text" id="zeit" value="0" /> <small><i>(0 = sofort)</i></small></td>
    </tr> 
    <tr>
      <td>aktiv</td><td><input type="checkbox" id="aktiv" checked="checked" /></td>
    </tr> 

  </table>
</div>


<div id="tabs">
    <ul>
        <li><a href="#tabs-1"></a></li>
    </ul>
<div id="tabs-1">

[MESSAGE]

[TAB1]
   
[TAB1NEXT]
</div>


</div>


<script>
$(document).ready(function() {
  $("#edittrigger").dialog({
  modal: true,
  bgiframe: true,
  closeOnEscape:false,
  minWidth:470,
  autoOpen: false,
  buttons: {
    ABBRECHEN: function() {
      $(this).dialog('close');
    },
    SPEICHERN: function() {
      triggersave();
    }
  }
  });

  $("#edittrigger").dialog({
    close: function( event, ui ){}
  });  

});

  function neuedit(nr)
  {
    if(nr == 0){
      document.getElementById("belegart").options[0].selected = true;
      document.getElementById("belegstatus").options[0].selected = true;
      $('#edittrigger').find('#editid').val('0');
      $('#edittrigger').find('#bezeichnung').val('');
      $('#edittrigger').find('#vorlage').val('');
      $('#edittrigger').find('#zeit').val('0');
      $('#aktiv').prop("checked",true);
      $("#edittrigger").dialog('open');     
    }else{
      $.ajax({
        url: 'index.php?module=triggerapp&action=ajax&cmd=get&id='+nr,
        type: 'POST',
        dataType: 'json',
        data: {},
        success: function(data) {
          $('#edittrigger').dialog('open');
          $('#bezeichnung').val(data.bezeichnung);
          $('#belegart').val(data.belegart);
          $('#belegstatus').val(data.belegstatus);
          $('#vorlage').val(data.vorlage);
          $('#zeit').val(data.zeit);
          $('#editid').val(data.id);
          if(data.aktiv == 1){
            $('#aktiv').prop("checked", true);
          }else{
            $('#aktiv').prop("checked", false);
          }

        },
        beforeSend: function() {

        }
      });
    } 
  }

  function deleteeintrag(nr)
  {
    if(!confirm("Soll der Trigger wirklich gelöscht werden?")) return false;
    $.ajax({
        url: 'index.php?module=triggerapp&action=ajax&cmd=delete&id='+nr,
        data: { 
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
        },
        success: function(data) {
          updateLiveTable();
        }
    });
  }
  function triggersave() {
    $.ajax({
        url: 'index.php?module=triggerapp&action=ajax&cmd=save',
        data: {
          id: $('#editid').val(),
          bezeichnung: $('#bezeichnung').val(),
          belegart: $('#belegart').val(),
          belegstatus: $('#belegstatus').val(),
          vorlage: $('#vorlage').val(),
          zeit: $('#zeit').val(),   
          aktiv: $('#aktiv').prop("checked")?1:0       
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
        },
        success: function(data) { 
          $('#edittrigger').find('#editid').val('0');       
          $("#edittrigger").dialog('close'); 
          updateLiveTable();
        }
    });

  }

function updateLiveTable() {
  var oTable = $('#triggerapp_list').DataTable( );
  oTable.ajax.reload();
}


</script>
