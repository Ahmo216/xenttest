<!-- gehort zu tabview -->
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">[TABTEXT]</a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
<form action="" method="post">
<fieldset><legend>&nbsp;Einstellungen</legend>
<table>
<tr>
  <td width="100">Monat:&nbsp;</td><td><select name="monat">[MONAT]</select></td>
  <td width="100">&nbsp;Jahr:&nbsp;</td><td><select name="jahr">[JAHR]</select></td>
  <td>&nbsp;Auswahl 2. Achse:&nbsp;</td><td><select name="auswahl"><option value="positionen" [POSITIONEN]>Anzahl Positionen</option><option value="menge" [MENGE]>Summe Menge</option></select></td>
	<td>&nbsp;Auswahl Status:&nbsp;</td><td><select name="status"><option value="freigegeben" [FREIGEGEBEN]>freigegeben</option><option value="abgeschlossen" [ABGESCHLOSSEN]>abgeschlossen</option><option value="storniert" [STORNIERT]>storniert</option></select></td>
	<td><input type="submit" value="laden" name="speichern"></td></tr>
</table>
</fieldset>
<!--<table width="100%"><tr><td align="right"><input type="submit" value="Laden" name="speichern"></td></tr></table>-->
</form>

[MESSAGE]


<div class="row">
  <div class="row-height">
    <div class="col-xs-12 col-sm-12 col-sm-height">
      <div class="inside_white inside-full-height">
        [AUFTRAEGE]
      </div>
    </div>
  </div>
</div>

<div style="clear:both;"></div>
[TAB1]

[TAB1NEXT]
</div>

<!-- tab view schließen -->
</div>
