<div id="tabs">
    <ul>
        <li><a href="#tabs-1">Archivierungen</a></li>
        <li><a href="#tabs-2">Statistiken</a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
[MESSAGE]
[TAB1]
[TAB1NEXT]
</div>


<!-- erstes tab -->
<div id="tabs-2">
[MESSAGE2]
<div id="tab2">[TAB2]Loading...</div>
[TAB2NEXT]
</div>

<!-- tab view schließen -->
</div>

<script>
 $(document).ready(function() {
 
      $.ajax({
          url: 'index.php?module=pdfarchiv&action=list&cmd=getarchiv',
          type: 'POST',
          dataType: 'text',
          data: { },
          success: function(data) {
            $('#tab2').html(data);
          },
          beforeSend: function() {

            }
        });
 });
</script>