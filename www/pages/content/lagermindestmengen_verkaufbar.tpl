<div id="tabs">
	<ul>
		<li><a href="#tabs-1">[TABTEXT]</a></li>
	</ul>
	<div id="tabs-1">

		<form action="" method="POST">

			<input type="hidden" name="module" value="lagermindestmengen">
			<input type="hidden" name="action" value="aktionen">
			<input type="hidden" name="aktion" value="mindestmenge">
			<input type="hidden" name="projektId" value="[PROJEKTID]">

			<fieldset><legend>Lieferung für </legend>
				<p>[ADRESSE]</p>
			</fieldset>

			<div class="info">Folgende Artikel werden mit der Mindestmenge f&uuml;r die einzelnen Positionen in die Lieferung bzw. den Auftrag &uuml;bernommen.</div>
			<table width="100%" class="mkTable">
				<tr>
					<th align="left">Artikelnummer</th>
					<th align="left">Artikelname</th>
					<th align="left">Mindestmenge</th>
					<th align="left">Verf&uuml;gbar</th>
				</tr>

				[ARTIKELLIST]
			</table>
			<table width="100%">
				<tr>
					<td><input type="button" name="" value="Abbrechen" onclick="window.location.href='index.php?module=lagermindestmengen&action=aktionen'"></td>
					<td align="right" ><input type="submit" name="getList" value="Auftrag erstellen"></td>
				</tr>
			</table>

		</form>
	</div>
</div>

