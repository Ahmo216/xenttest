<!-- gehort zu tabview -->
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">[TABTEXT]</a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
<form action="" method="post">
<center>
<br><br>
<table>
<tr><td>Seriennummer &auml;ndern:</td><td><input type="text" size="40" name="seriennummer" value="[SERIENNUMMER]"></td></tr>
<tr><td>Bemerkung:</td><td><input type="text" size="40" name="internebemerkung" class="mceNoEditor" value="[INTERNEBEMERKUNG]"></td></tr>
<tr><td>Lagerplatz:</td><td><input type="text" size="40" name="lagerplatz"  id="lagerplatz" value="[LAGERPLATZ]"></td></tr>
</table>
<br><br>

<table width="40%">
<tr>
    <td align="left">
    <input type="button" name="zurueck" onclick="window.location.href='index.php?module=artikel&action=seriennummern&id=[ID]#tabs-1';"
    value="Abbrechen" /></td><td align="right">
    <input type="submit" name="submit"
    value="Speichern" /></td>
</tr>
</table>

</form>
</center>
</div>

<!-- tab view schließen -->
</div>

