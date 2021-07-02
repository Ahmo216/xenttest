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
<div id="snpopup">
    <fieldset><legend>{|Seriennummern &Auml;ndern|}</legend>
    <table>
      <tr><td>{|Seriennummer|}:</td><td><input type="text" id="sn" name="sn" /><input type="hidden" id="snid" /><input type="hidden" id="snartikel" /></td></tr>
    </table>
    </fieldset>
</div>
<script type="application/javascript">
  $(document).ready(function() {
    $('#snpopup').dialog(
    {
      modal: true,
      autoOpen: false,
      minWidth: 940,
      title:'Seriennummern',
      buttons: {
        '{|SPEICHERN|}': function()
        {
          $.ajax({
            url: 'index.php?module=seriennummern&action=list&cmd=savesn',
            type: 'POST',
            dataType: 'json',
            data: { snid: $('#snid').val(), sn: $('#sn').val()},
            success: function(data) {
              $('#snpopup').dialog('close');
              var oTable = $('#seriennummernlist').DataTable( );
              oTable.ajax.reload();
            },
            beforeSend: function() {

            }
          });
        },
        '{|ABBRECHEN|}': function() {
          $(this).dialog('close');
        }
      },
      close: function(event, ui){

      }
    });
  });
  function changesn(doubleid)
  {
    var doubleida = doubleid.split('-');

    if(doubleida[ 0 ] != '2')
    {
      window.location.href = 'index.php?module=seriennummern&action=edit&id='+doubleid;
    }else{
      $.ajax({
        url: 'index.php?module=seriennummern&action=list&cmd=getsn',
        type: 'POST',
        dataType: 'json',
        data: { snid: doubleid},
        success: function(data) {
          if(data.status == 1) {
            $('#snpopup').dialog('open');
            $('#sn').val(data.sn);
            $('#snid').val(data.id);
            $('#snartikel').val(data.artikel);
          }
        },
        beforeSend: function() {

        }
      });
    }
  }
</script>

