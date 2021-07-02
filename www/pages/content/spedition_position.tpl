<script>
function deleletepos(elid)
{
  $.ajax({
      url: 'index.php?module=spedition&action=edit&id=[ID]&cmd=deleteavipos',
      data: {
          //Alle Felder die fürs editieren vorhanden sind
          element: elid
      },
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
          
      },
      success: function(data) {
          if (typeof data.status != 'undefined' && data.status == 1) {
            window.location = data.url;
          } else {
              alert(data.statusText);
          }
      }
  });
}



</script>