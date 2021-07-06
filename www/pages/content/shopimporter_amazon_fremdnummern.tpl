<!--<fieldset><legend>{|Filter|}</legend>auch inaktive Anzeigen: <input type="checkbox" id="inaktiv" name="inaktiv" value="1" /></fieldset>-->
<fieldset><legend>{|Filter|}</legend>
  <label for="ignorierte">{|Ignorierte Verkn&uuml;pfungen anzeigen|}:</label> <input type="checkbox" id="ignorierte" name="ignorierte" value="1" />
  <label for="ohnezuordnung">{|Verkn&uuml;pfte Artikel ausblenden|}:</label> <input type="checkbox" id="ohnezuordnung" name="ohnezuordnung" value="1" />
</fieldset>
<form method="POST">
  [AMAZONFREMDNUMMERN]
  <div><center>{|Nicht zugeordnete Artikel anlegen|}:<input type="checkbox" value="1" name="leereanlegen" /> {|SKUs als WaWi-Artikelnummern verwenden|}:<input type="checkbox" value="1" name="skualsnummer" [SKUALSNUMMER] /> <input type="submit" name="updatefremdnummern" value="{|Artikel updaten|}" style="width:250px;" /></center></div>
</form>
<script>

  $(document).ready(function() {
    $.ajax({
      url: 'index.php?module=onlineshops&action=edit&cmd=getthirdnumbermessage&id=[ID]',
      type: 'POST',
      dataType: 'json',
      data: {},
      success: function (data) {
        if (typeof data.message !== undefinded) {
          $('#messagewrapper').html(data.message);
        }
      },
      beforeSend: function () {

      }
    });
  });
  function getinv()
  {
    $('#frmgetinv').submit();
  }
  function ignorieren(maxid)
  {
    $.ajax({
        url: 'index.php?module=onlineshops&action=edit&cmd=ignorieren&withgui=1&id=[ID]',
        type: 'POST',
        dataType: 'json',
        data: { maxid: maxid},
        success: function(data) {
          var oTable = $('#shopimport_amazon_fremdnummern').DataTable( );
          oTable.ajax.reload();
        }
    });
  }
  function nichtignorieren(maxid)
  {
    $.ajax({
        url: 'index.php?module=onlineshops&action=edit&cmd=nichtignorieren&withgui=1&id=[ID]',
        type: 'POST',
        dataType: 'json',
        data: { maxid: maxid},
        success: function(data) {
          var oTable = $('#shopimport_amazon_fremdnummern').DataTable( );
          oTable.ajax.reload();
        }
    });
  }
</script>
<style>
  table#shopimport_amazon_fremdnummern tbody tr td:nth-child(4) {
    white-space: nowrap;
  }
</style>
