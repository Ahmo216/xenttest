<br>
<br>
<div class="info">Die Texte für das Mahnwesen finden Sie ab der Version 19.1 unter den Geschäftsbriefvorlagen (<a href="index.php?module=geschaeftsbrief_vorlagen&action=list" target="_blank">hier</a>).</div>
<div class="row">
	<div class="row-height">
		<div class="col-xs-12 col-md-12 col-md-height">
			<div class="inside inside-full-height">
				<fieldset>
					<legend>{|Variablen|}</legend>
            {DATUM} = Ende der neu gegebenen Frist (Datum)<br/>
            {TAGE} = Anzahl Tage in aktueller Mahnfrist (Tage)<br />
            {SOLL} = Soll Betrag (EUR)<br />
            {IST} =  Ist Betrag (EUR)<br />
            {OFFEN} =  Betrag Soll - Ist<br />
            {SUMME} =  Betrag Soll - Ist + Mahngebuehr<br />
            {WAEHRUNG} = W&auml;hrung<br />
            {MAHNGEBUEHR} =  Mahngebuehr<br />
            {RECHNUNG} = Rechnungsnummer<br />
            {DATUMRECHNUNG} = Datum der Rechnung<br />
            {MAHNDATUM} = Datum der letzten Zahlungserinnerung bzw. Mahnung<br />
            {OFFENMITMAHNGEBUEHR} = Offen + Mahngebuehr<br />
            {DATUMRECHNUNGZAHLUNGSZIEL} = Datum der urspr&uuml;nglichen geforderten Bezahlung (Rechnungsdatum + Tage Zahlungsziel) der Rechnung (Datum)<br />
            {DATUMZAHLUNGSERINNERUNGFAELLIG} = Datum bis wann die Zahlungserinnerung f&auml;llig war<br />
            {DATUMZAHLUNGSERINNERUNG} = Datum der Zahlungserinnerung<br />
            {DATUMMAHNUNG1} = Datum der Mahnung 1<br />
            {DATUMMAHNUNG2} = Datum der Mahnung 2<br />
            {DATUMMAHNUNG3} = Datum der Mahnung 3<br />
				</fieldset>
			</div>
		</div>
	</div>
</div>


<!--<hr width="100%">

<table width="100%">
<form action="" method="post">
<tr valign="top">
<td><h4>Zahlungserinnerung (DE)</h4>
<textarea rows=10 cols=100 name="textz" id="textz">[TEXTZ]</textarea>
</td>
<td><h4>Zahlungserinnerung (EN)</h4>
<textarea rows=10 cols=100 name="textz_en" id="textz_en">[TEXTZ_EN]</textarea>
</td>
<td width="400"><h4>Beispieltexte:</h4>
Sehr geehrte Damen und Herren,<br><br>
vielen Dank für Ihren Auftrag, den wir gerne für Sie ausgeführt haben.
Leider konnten wir Ihrer Rechnung {RECHNUNG} bislang noch keinen Zahlungseingang verzeichnen.
Kann es sein, dass der unten aufgeführte Betrag übersehen wurde?
Falls der Betrag bereits überwiesen wurde, bitten wir Sie, dieses Schreiben als gegenstandslos anzusehen und uns bei der Zuordnung Ihrer Zahlung behilflich zu sein. Bitte senden Sie uns dazu eine E-Mail an buchhaltung@unseredomain.de.
Andernfalls freuen wir uns auf die Begleichung des offenen Forderungsbetrages in Höhe von {OFFEN} EUR bis zum {DATUM}. Bitte geben Sie im Betreff die Rechnungsnummer {RECHNUNG} an.</td>
</tr>

<tr valign="top">
<td>
<h4>Mahnung 1 (DE)</h4>
<textarea rows=10 cols=100 name="textm1" id="textm1">[TEXTM1]</textarea>
</td>
<td>
<h4>Mahnung 1 (EN)</h4>
<textarea rows=10 cols=100 name="textm1_en" id="textm1_en">[TEXTM1_EN]</textarea>
</td>
<td>
Sehr geehrte Damen und Herren,<br><br>
sicher haben Sie unsere Zahlungserinnerung übersehen.
Deshalb erhalten Sie mit diesem Schreiben nochmals eine Aufstellung der offenen Positionen.
Sollten Sie die Zahlung noch nicht vorgenommen haben, bitten wir Sie, den Betrag in Höhe von {OFFEN} EUR bis zum {DATUM} auf unser Konto zu überweisen, um weitere Mahnschreiben und Gebühren zu vermeiden. Bitte geben Sie im Betreff die Rechnungsnummer {RECHNUNG} an.
Wurde der offene Betrag bereits beglichen, so sehen Sie dieses Schreiben als gegenstandslos an.
</td>
</tr>

<tr valign="top">
<td>
<h4>Mahnung 2 (DE)</h4>
<textarea rows=10 cols=100 name="textm2" id="textm2">[TEXTM2]</textarea>
</td>
<td>
<h4>Mahnung 2 (EN)</h4>
<textarea rows=10 cols=100 name="textm2_en" id="textm2_en">[TEXTM2_EN]</textarea>
</td>
<td>
Sehr geehrte Damen und Herren,<br><br>

unsere Rechnung vom {DATUMRECHNUNG} war am {DATUMRECHNUNGZAHLUNGSZIEL} zur Zahlung fällig.
Trotz Mahnung und Fristsetzung wurde der Betrag von {OFFEN} EUR noch immer nicht beglichen.

Deshalb erhalten Sie mit diesem Schreiben erneut eine Aufstellung der offenen Positionen.

Bitte überweisen Sie den Betrag von {OFFEN} EUR bis zum {DATUM} auf unser unten genanntes Konto. Wir erlauben uns Mahngebühren in Höhe von {MAHNGEBUEHR} EUR zu berechnen. Bitte geben Sie im Betreff die Rechnungsnummer {RECHNUNG} an.

Wurde der offene Betrag bereits beglichen, so sehen Sie dieses Schreiben als gegenstandslos an.
</td>
</tr>


<tr valign="top">
<td>
<h4>Mahnung 3 (DE)</h4>
<textarea rows=10 cols=100 name="textm3" id="textm3">[TEXTM3]</textarea>
</td>
<td>
<h4>Mahnung 3 (EN)</h4>
<textarea rows=10 cols=100 name="textm3_en" id="textm3_en">[TEXTM3_EN]</textarea>
</td>
<td>
Sehr geehrte Damen und Herren,<br><br>

unsere Rechnung vom {DATUMRECHNUNG} war am {DATUMRECHNUNGZAHLUNGSZIEL} zur Zahlung fällig.
Trotz Mahnungen und Fristsetzung wurde der Betrag von {OFFEN} EUR noch immer nicht beglichen.

Deshalb erhalten Sie mit diesem Schreiben eine letzte Aufstellung der offenen Positionen. Bitte geben Sie im Betreff die Rechnungsnummer {RECHNUNG} an.

Sollte der Betrag in Höhe von {OFFEN} EUR bis zum {DATUM} noch nicht auf unserem Konto eingegangen sein, werden wir das gerichtliche Mahnverfahren einleiten.

Bitte kontaktieren Sie uns und lassen Sie uns dies vermeiden: 0123/45678</td>
</tr>


<tr valign="top">
<td>
<h4>Inkasso-Mahnung (DE)</h4>
<textarea rows=10 cols=100 name="texti" id="texti">[TEXTI]</textarea>
</td>
<td>
<h4>Inkasso-Mahnung (EN)</h4>
<textarea rows=10 cols=100 name="texti_en" id="texti_en">[TEXTI_EN]</textarea>
</td>
<td></td>
</tr>
</table>



<br><br><center><input type="submit" value="Speichern" name="mahnungstextespeichern"></center><br>

</form>
-->
