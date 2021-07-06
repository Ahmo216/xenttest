<div id="tabs">
    <ul>
        <li><a href="#tabs-1"><!--[TABTEXT]--></a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
[MESSAGE]

[VORSCANNEN]
<center>
<span style="font-size:150%">Bestellung</span> scannen:
<form method="POST"><input type="text" id="scannen" name="scannen" /> <input type="submit" name="ok" value="OK" /></form>
</center>
<script>
$(document).ready(function() {
  $('#scannen').focus();
});
</script>
[NACHSCANNEN]
[VOREINLAGERN]
<form method="POST">
<center>
<table>
<tr><td>Einlagern in:</td><td><input type="text" name="lager_platz" id="lager_platz" /><input type="hidden" name="bestellung" id="bestellung" value="[BESTELLUNG]" /> <input type="submit" class="btnGreen" value="Artikel einlagern" name="einlagern" />&nbsp;<a href="index.php?module=bestellung_einlagern&action=list"><input type="button" value="Abbrechen" /></a></td></tr>
</table>
</center>
</form>
<script>
$(document).ready(function() {
  $('#lager_platz').focus();
});
</script>
[BESTELLPOSITIONEN]
[NACHEINLAGERN]

[TAB1]
[TAB1NEXT]
</div>

<!-- tab view schließen -->
</div>

