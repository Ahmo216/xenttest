<div id="tabs">
    <ul>
        <li><a href="#tabs-1">[TABTEXT]</a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
<fieldset><legend>&nbsp;Filter</legend>
<table style="float:left">
  <tr><td>Firmenname:</td><td><input type="text" id="name" size="50" name="name" value="[NAME]"></td></tr>
  <tr><td>Ansprechpartner:</td><td><input type="text" id="ansprechpartner" size="50" name="ansprechpartner" value="[ANSPRECHPARTNER]"></td></tr>
  <tr><td>PLZ / Ort:</td><td><input type="text" size="10" name="plz" id="plz" value="[PLZ]">&nbsp;<input type="text" size="19" name="ort" id="ort" value="[ORT]"></td></tr>
  <tr><td>Land:</td><td><select name="land" id="land">[LAND]</select></td></tr>
</table>

<table style="float:left;padding-left:100px">
  <tr><td width="150">Zeitraum:</td><td>von:</td><td><input type="text" size="30" name="von" value="[VON]" id="von"></td><td width="50" align="right">bis:</td><td><input type="text" size="30" name="bis" value="[BIS]" id="bis"></td></tr>
  <tr><td width="150">Umsatzhöhe:</td><td>von:</td><td><input type="text" size="30" name="umsatzvon" id="umsatzvon" value="[UMSATZVON]"></td><td align="right">bis:</td><td><input type="text" size="30" id="umsatzbis" name="umsatzbis" value="[UMSATZBIS]"></td></tr>
  [FREIFELDER]
  <!--<tr><td width="150">Artikelkategorie:</td><td></td><td><input type="text" size="30" name="artikelkategorie" value="[ARTIKELKATEGORIE]"></td><td></td><td></td></tr>-->
</table>


</fieldset>
[MESSAGE]
[TAB1]
[TAB1NEXT]
</div>

<!-- tab view schließen -->
</div>
<script>
$(document).ready(function() {
  if([CALCCACHE])
  {
    $.ajax({
        url: 'index.php?module=vertriebscockpit&action=umsaetze&cmd=calcumsaetze',
        type: 'POST',
        dataType: 'json',
        data: { },
        success: function(data) {

        }
    });
  }
});
</script>
