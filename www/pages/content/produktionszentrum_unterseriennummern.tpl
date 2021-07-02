<div id="divunterseriennummern" style="display:none;">
<form id="frmuseriennummern" method="post"><input type="hidden" name="saveusn" value="1" />
  <table>
    <tr>
      <td>
        [MESSAGEUNTERSERIENNUMMERN]
        [UNTERSERIENNUMMERN]
        <br/>
        <!-- <input type="button" value="Schlie&szlig;en" onclick="$('#divunterseriennummern').dialog('close');" /> -->
      </td>
    </tr>
  </table>
</form>
</div>
<script type="text/javascript">
function changeunterseriennummer(el)
{
  /*
  var seriennummer = $(el).val();
  var ela = el.id.split('_');
  var eid = ela[1];
    $.ajax({
        url: 'index.php?module=produktionszentrum&action=changeuseriennr',
        type: 'POST',
        dataType: 'json',
        data: { id: eid, unterseriennummer: seriennummer},
        success: function(data) {
   

        },
        beforeSend: function() {

        }
    });
  */
}

function editunterseriennummer(el)
{
  var ela = el.id.split('_');
  var inp = $('#iuseriennummer_'+ela[1]);
  $(inp).show();
  $(inp).next().show();
  $('#span_'+ela[1]).hide();
}

$(document).ready(function() {
  $('#divunterseriennummern').dialog(
  {
    modal: true,
    minWidth: 940,
    title:'Unterseriennummern [USERIENNUMMER]',
    buttons: {
      SPEICHERN: function()
      {
        $('#frmuseriennummern').submit();
      },
      ABBRECHEN: function() {
        $(this).dialog('close');
      }
    },
    close: function(event, ui){
    $('#produktionunterseriennummer').focus();
    }
  }
);
});
</script>
