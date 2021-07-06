<div id="tabs">
    <ul>
        <li><a href="#tabs-1">[TABTEXT]</a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
[MESSAGE]
<form method="POST">
<fieldset><legend>{|Einstellungen|}</legend>
<table>
<tr><td>Bezeichnung:</td><td><input type="text" name="bezeichnung" id="bezeichnung" value="[BEZEICHNUNG]" /></td></tr>
<tr><td>Projekt:</td><td><input type="text" name="projekt" id="projekt" value="[PROJEKT]" /></td></tr>
<tr><td>Drucker Paketmarke:</td><td><select name="paketmarke_drucker" id="paketmarke_drucker">[PAKETMARKE_DRUCKER]</select></td></tr>
<tr><td>Drucker Export:</td><td><select name="export_drucker" id="export_drucker">[EXPORT_DRUCKER]</select></td></tr>
<tr><td>Aktiv:</td><td><input type="checkbox" value="1" name="aktiv" id="aktiv" [AKTIV] /></td></tr>
[JSON]
<tr><td></td><td><input type="submit" name="speichern" value="speichern" /></td></tr>

</table>
</fieldet>
</form>
[TAB1NEXT]
</div>

<!-- tab view schließen -->
</div>

