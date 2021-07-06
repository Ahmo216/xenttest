<!-- gehort zu tabview -->

<div id="tabs">
  <ul>
    <li><a href="#tabs-1">[TABTEXT]</a></li>
  </ul>



<!-- erstes tab -->
<div id="tabs-1">

  [MESSAGE]

  <div class="row">
  <div class="row-height">
  <div class="col-xs-12 col-md-10 col-md-height">
  <div class="inside-white inside-full-height">
    <form method="post">
      <fieldset>
        <legend>{|Filter|}</legend>
        <table width="100%" cellspacing="5">
          <tr>
            <td width="200"><label for="von">{|Von|}:&nbsp;</label><input type="text" id="von" name="von" value="[VON]"></td>
            <td width="200"><label for="bis">{|Bis|}:&nbsp;</label><input type="text" id="bis" name="bis" value="[BIS]"></td>
            <td><input class="btnBlue" type="button" name="laden" id="laden" value="Laden" style="width:5em" onclick="updateLiveTable()"></td>
          </tr>
        </table>
      </fieldset>
    </form>
    
    <fieldset class="white">
      <legend></legend>
      [TAB1]
    </fieldset>

  
  </div>
  </div>
  <div class="col-xs-12 col-md-2 col-md-height">
  <div class="inside inside-full-height">
    <fieldset>
      <legend>{|Aktionen|}</legend>
      <form method="post">
        <input type="submit" class="btnBlueNew" name="zmmeldungdownload" value="ZM Meldung Download">
      </form>
    </fieldset>
  </div>
  </div>
  </div>
  </div>  


</div>

</div>

<script type="text/javascript">

function updateLiveTable(i) {
  var oTableL = $('#zmmeldung_list').dataTable();
  var tmp = $('.dataTables_filter input[type=search]').val();
  oTableL.fnFilter('%');
  //oTableL.fnFilter('');
  oTableL.fnFilter(tmp);   
}

</script>



