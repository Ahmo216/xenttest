<style>
  #produktionszentrum_chargen_baugruppen2 tfoot {
    display: table-header-group;
    position:absolute;
    top:-42px;
    left:-2px;
    border:0px;
  }   

 #produktionszentrum_chargen_baugruppen2 table.display tfoot th {
    border-top: 0px solid #DDD;
    border:0px; 
  }   
</style>
<script>
  $( document ).ready(function() {
    $("#produktionszentrum_chargen_baugruppen2 > tfoot:nth-child(3) > tr:nth-child(1) > th:nth-child(7) > span:nth-child(1) > input:nth-child(1)").hide();
  });
</script>
<div id="tabs">
  <ul>
    <li><a href="#tabs-1">{|Eingesetzte Chargen|}</a></li>
    <li><a href="#tabs-2">{|Produzierte Chargen|}</a></li>
        <!--<li><a href="#tabs-2">Baugruppen Chargen</a></li>-->
        <!--<li><a href="#tabs-3">Neue Charge</a></li>-->
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
<form method="POST">
[MESSAGE]
[MESSAGE1]
[MESSAGE2]
[VORCHARGENADD]

<fieldset><legend>Eingesetzte Charge hinzuf&uuml;gen</legend>
<table>
<tr>
  [NACHCHARGENADD]<!--<td valign="top">Bezeichnung:</td><td valign="top"><input type="text" name="bezeichnung" id="bezeichnung" value="[BEZEICHNUNG]" /></td>-->[VORCHARGENADD]
  <td>Artikel:</td><td valign="top"><input type="text" name="artikel" id="artikel" size="40" value="[ARTIKEL]" /></td>
  [NACHCHARGENADD]<!--<td valign="top">Typ</td><td valign="top"><select name="typ" id="typ">[TYPSELECT]</select></td>-->[VORCHARGENADD]
  <td class="chargennummer">Charge:</td><td class="chargennummer" valign="top"><input type="text" name="chargennummer" id="chargennummer" value="[CHARGENNUMMER]" /></td>
  <td class="mhd">Mindesthaltbarkeitsdatum:</td><td class="mhd" valign="top"><input type="text" name="mhd" id="mhd" value="[MHD]" /></td>
  <td>Anzahl:</td><td valign="top"><input type="text" name="anzahl" id="anzahl" value="[ANZAHL]" size="8"/>&nbsp;<i>(optional)</i></td>
  [NACHCHARGENADD]<!--<td valign="top">Bemerkung:</td><td valign="top"><textarea name="kommentar" id="kommentar" >[KOMMENTAR]</textarea></td>-->[VORCHARGENADD]
  <td valign="top"><input type="submit" name="speichern" value="hinzuf&uuml;gen" /></td>
</tr>
</table>
</fieldset>
</form>
[NACHCHARGENADD]
[TAB1]
[TAB1NEXT]
</div>
<div id="tabs-2">
[MESSAGE]
[MESSAGE2]
    <fieldset>
      <legend>{|Filter|}</legend>
      <table>
        <tr>
          <td height="25px"></td>
        </tr>
      </table>
    </fieldset>
[TAB2]
[TAB2NEXT]
</div>
<!--
<div id="tabs-2">
[MESSAGE]
[MESSAGE2]
[TAB2]
[TAB2NEXT]
</div>
<div id="tabs-3">
[MESSAGE]
[TAB3]
<form method="POST">

</form>
[TAB3NEXT]
</div>
-->
<!-- tab view schließen -->
</div>

