<div id="tabs">
    <ul>
        <li><a href="#tabs-1"><!--[TABTEXT]--></a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
[MESSAGE]
<!--<fieldset style="min-height:50px;"><legend>{|Filter|}</legend>-->
<!--<input style="margin-left:700px;margin-top:20px;" type="button" value="{|&#10010; Neuer Eintrag|}" onclick="editeintrag(0);" />-->
<!--</fieldset>-->
[TAB1]
[TAB1NEXT]
</div>

<!-- tab view schließen -->
</div>
<div id="editpopup" style="display:none;">
  <div class="row">
    <div class="row-height">
      <div class="col-xs-12 col-md-12 col-md-height">
        <div class="inside inside-full-height">
          <fieldset>
            <legend>{|Lagerst&uuml;ckliste|}</legend>
            <div id="popupmessage"></div>
            <table>
              <tr><td width="200">{|Artikel|}:</td><td><input type="hidden" id="sid" value="" /><input type="text" id="artikel" size="40" /></td></tr>
              <tr><td>{|Lager|}:</td><td><input type="text" id="lager" size="40" /></td></tr>
              <tr><td>{|sofort im Auftrag explodieren|}:</td><td><input type="checkbox" id="sofortexplodieren" value="1" /></td></tr>
            </table>
          </fieldset>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  $(document).ready(function() {
    $('#editpopup').dialog(
    {
      modal: true,
      autoOpen: false,
      minWidth: 700,
      buttons: {
        ABBRECHEN: function() {
          $(this).dialog('close');
        },
        SPEICHERN: function()
        {
          if($('#artikel').val() != '' || $('#lager').val() != '')
          {
            $.ajax({
                url: 'index.php?module=lagerstueckliste&action=list&cmd=save',
                type: 'POST',
                dataType: 'json',
                data: {sid: $('#sid').val(),artikel:$('#artikel').val(),lager:$('#lager').val(),sofortexplodieren:($('#sofortexplodieren').prop('checked')?1:0) },
                success: function(data) {
                  $('#editpopup').dialog('close');
                  var oTable = $('#lagerstueckliste_list').DataTable( );
                  oTable.ajax.reload();
                },
                beforeSend: function() {

                }
            });
          }else{
            $('#popupmessage').html('<div class="error">{|Bitte Artikel oder Lager f&uuml;llen|}</div>');
            setTimeout(function(){$('#popupmessage').html('');},3000);
          }
        }
      },
      close: function(event, ui){

      }
    });

  });
function editeintrag(id)
{
  $('#artikel').val('');
  $('#lager').val('');
  $('#sid').val('');
  $('#sofortexplodieren').prop('checked', false);
  $.ajax({
      url: 'index.php?module=lagerstueckliste&action=list&cmd=get',
      type: 'POST',
      dataType: 'json',
      data: {sid: id },
      success: function(data) {
        $('#editpopup').dialog('open');
        if(data && typeof data.id != 'undefined')$('#sid').val(data.id);
        if(data && typeof data.artikel != 'undefined')$('#artikel').val(data.artikel);
        if(data && typeof data.lager != 'undefined')$('#lager').val(data.lager);
        if(data && typeof data.sofortexplodieren != 'undefined')$('#sofortexplodieren').prop('checked', (data.sofortexplodieren == '1'?true:false));
      },
      beforeSend: function() {

      }
  });

}
function deleteeintrag(id)
{
  if(confirm('{|Eintrag wirklich löschen?|}'))
  {
    $.ajax({
        url: 'index.php?module=lagerstueckliste&action=list&cmd=delete',
        type: 'POST',
        dataType: 'json',
        data: {sid: id },
        success: function(data) {
          var oTable = $('#lagerstueckliste_list').DataTable( );
          oTable.ajax.reload();
        },
        beforeSend: function() {

        }
    });
  }
}
</script>
