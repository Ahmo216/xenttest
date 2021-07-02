
<!-- gehort zu tabview -->
<div id="tabs">
	<ul>
		<li><a href="#tabs-1">[TABTEXT]</a></li>
	</ul>
	<!-- ende gehort zu tabview -->

	<!-- erstes tab -->
	<div id="tabs-2">
		[MESSAGE]
		[TAB2]

		<fieldset>
			<legend>{|Einstellungen|}</legend>
			<form method="post">
				<table width="100%" cellspacing="5" border="0">
					<tr><td>Schriftgr&ouml;&szlig;e Betreffzeile:</td>
						<td><input type="text" size="3" name="betreffszeile" value="[BETREFFSZEILE]"></td>
						<td>Schriftgr&ouml;&szlig;e Dokumententext:</td>
						<td><input type="text" size="3" name="dokumententext" value="[DOKUMENTENTEXT]"></td></tr>
					<tr><td>Schriftgr&ouml;&szlig;e Tabellenbeschriftung:</td>
						<td><input type="text" size="3" name="tabellenbeschriftung" value="[TABELLENBESCHRIFTUNG]"></td>
						<td>Schriftgr&ouml;&szlig;e Tabelleninhalt:</td>
						<td><input type="text" size="3" name="tabelleninhalt" value="[TABELLENINHALT]"></td></tr>
					<tr><td>Schriftgr&ouml;&szlig;e Artikel Beschreibung:</td>
						<td><input type="text" size="3" name="zeilenuntertext" value="[ZEILENUNTERTEXT]"></td>
						<td>Schriftgr&ouml;&szlig;e Freitext:</td>
						<td><input type="text" size="3" name="freitext" value="[FREITEXT]"></td></tr>
					<tr><td>Schriftgr&ouml;&szlig;e Infobox:</td>
						<td><input type="text" size="3" name="infobox" value="[INFOBOX]"></td>
						<td>Schriftgr&ouml;&szlig;e Brieftext:</td>
						<td><input type="text" size="3" name="brieftext" value="[BRIEFTEXT]"></td></tr>

					<tr><td>Schriftgr&ouml;&szlig;e Empf&auml;nger:</td>
						<td><input type="text" size="3" name="schriftgroesse" value="[SCHRIFTGROESSE]"></td>
						<td>Schriftgr&ouml;&szlig;e Absender:</td>
						<td><input type="text" size="3" name="schriftgroesseabsender" value="[SCHRIFTGROESSEABSENDER]"></td></tr>

					<tr><td></td>
						<td></td>
						<td>Schriftgr&ouml;&szlig;e Gesamt:</td>
						<td><input type="text" size="3" name="schriftgroesse_gesamt" value="[SCHRIFTGROESSE_GESAMT]"></td></tr>

					<tr><td></td>
						<td></td>
						<td>Schriftgr&ouml;&szlig;e Steuer:</td>
						<td><input type="text" size="3" name="schriftgroesse_gesamt_steuer" value="[SCHRIFTGROESSE_GESAMT_STEUER]"></td></tr>

					<tr><td><br></td>
						<td></td>
						<td></td>
						<td></td></tr>


					<tr><td>Abstand Empf&auml;nger oben/unten (verschieben +- in mm):</td>
						<td><input type="text" size="3" name="abstand_adresszeileoben" value="[ABSTAND_ADRESSZEILEOBEN]"></td>
						<td>Abstand Infobox oben/unten (verschieben +- in mm):</td>
						<td><input type="text" size="3" name="abstand_boxrechtsoben" value="[ABSTAND_BOXRECHTSOBEN]"></td></tr>

					<tr><td>Abstand Empf&auml;nger links (absolut in mm):</td>
						<td><input type="text" size="3" name="abstand_adresszeilelinks" value="[ABSTAND_ADRESSZEILELINKS]"></td>
						<td></td>
						<td></td>
					</tr>

					<tr><td>Abstand Betreffzeile oben/unten (verschieben +- in mm):</td>
						<td><input type="text" size="3" name="abstand_betreffzeileoben" value="[ABSTAND_BETREFFZEILEOBEN]"></td>
						<td>Abstand Infobox rechts/links (verschieben +- in mm):</td>
						<td><input type="text" size="3" name="abstand_boxrechtsoben_lr" value="[ABSTAND_BOXRECHTSOBEN_LR]"></td>
					</tr>

					<tr>
						<td>Abstand Artikelname zu Beschreibung (in mm):</td>
						<td><input type="text" size="3" name="abstand_name_beschreibung" value="[ABSTAND_NAME_BESCHREIBUNG]"></td>
						<td>Ausrichtung Infobox Text (L oder R und optional Spaltenbreite L;30;40 oder R;30;40):</td>
						<td><input type="text" size="6" name="boxausrichtung" value="[BOXAUSRICHTUNG]"></td>

					</tr>

					<tr>
						<td>Abstand Seitenrand Links / Rechts:</td>
						<td><input type="text" size="3" name="abstand_seitenrandlinks" value="[ABSTAND_SEITENRANDLINKS]">/<input type="text" size="3" name="abstand_seitenrandrechts" value="[ABSTAND_SEITENRANDRECHTS]"></td>
						<td>Abstand Artikeltabelle oben/unten (verschieben +- in mm):</td>
						<td><input type="text" size="3" name="abstand_artikeltabelleoben" value="[ABSTAND_ARTIKELTABELLEOBEN]"></td>

					</tr>
					<tr>
						<td>Abstand Seitennummer Unten:</td>
						<td><input type="text" size="3" name="abstand_seiten_unten" value="[ABSTAND_SEITEN_UNTEN]"></td>
						<td>Abstand Inhalt ab Seite 2 Oben (in mm):</td>
						<td><input type="text" size="3" name="abseite2y" value="[ABSEITE2Y]"></td>
					</tr>

					<tr>
						<td>Abstand Gesamtsumme Links:</td>
						<td><input type="text" size="3" name="abstand_gesamtsumme_lr" value="[ABSTAND_GESAMTSUMME_LR]"></td>
						<td>Abstand Umbruch unten:</td>
						<td><input type="text" size="3" name="abstand_umbruchunten" value="[ABSTAND_UMBRUCHUNTEN]"></td>

					</tr>
					<tr>
						<td>Ablageordner: </td>
						<td><input size="50" type="text" name="output_dir" value="[OUTPUT_DIR]"></td>
					</tr>

					<tr>
						<td>Rechnungen als 'versendet' markieren</td>
						<td><input type="checkbox" name="mark_as_sent" [MARK_AS_SENT] value="true"></td>
					</tr>
				</table>
				<input type="submit" name="epost_settings_save" style="float:right" value="Speichern">
			</form>
		</fieldset>


		[TAB1NEXT]
	</div>

	<!-- tab view schließen -->
</div>
