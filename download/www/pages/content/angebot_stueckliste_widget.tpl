<div id="stuecklistepopup" style="display:none;">
[TABELLESTUECKLISTE]
</div>
<div id="stuecklistepopup2" style="display:none">
<div id="stuecklistepopup2inhalt"></div>
</div>
<script>
$(document).ready(function() {
    $('#stuecklistepopup').dialog(
    {
      modal: true,
      autoOpen: false,
      minWidth: 940,
      title:'Stückliste hinzufügen',
      buttons: {
        OK: function()
        {
          $(this).dialog('close');
        }
      },
      close: function(event, ui){
        
      }
    });
    $('#stuecklistepopup2').dialog(
    {
      modal: true,
      autoOpen: false,
      minWidth: 940,
      title:'Stückliste hinzufügen',
      buttons: {
        OK: function()
        {
          $.ajax({
            url: 'index.php?module=[DOCTYP]&action=positionen&cmd=addstuecklisteel&id=[ID]&fmodul=[FMODUL]',
            type: 'POST',
            dataType: 'json',
            data:  $('#frmangebotsl').serialize(),
            success: function(data) {
            $('#stuecklistepopup2').dialog('close');
            window.location = 'index.php?module=[DOCTYP]&action=positionen&id=[ID]&fmodul=[FMODUL]';
          }
        });
        
          
        },
        ABBRECHEN: function()
        {
          $(this).dialog('close');
        }
      },
      close: function(event, ui){
        
      }
    });
});

function addstelement(artid)
{
  $.ajax({
    url: 'index.php?module=[DOCTYP]&action=positionen&cmd=addstueckliste&id=[ID]&fmodul=[FMODUL]',
    type: 'POST',
    dataType: 'text',
    data: {artikel:artid },
    success: function(data) {
      $('#stuecklistepopup').dialog('close');
      $('#stuecklistepopup2inhalt').html(data);
      $('#stuecklistepopup2').dialog('open');
    }
  });
}

function addstueckliste()
{
  $('#stuecklistepopup').dialog('open');
}

</script>
