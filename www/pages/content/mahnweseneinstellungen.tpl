<form action="" method="post">
	<div class="info">{|Bitte beachten Sie bei &Auml;nderungen w&auml;hrend des Betriebs k&ouml;nnen die Laufzeiten der einzelnen Stufen variieren.|}</div>
	<br><br>

	<table width="100%" align="center" class="mkTable">
		<tr>
			<td class="gentable" width="300"><b>{|Mahnstufe (Mahndokumente)|}</b></td>
			<td class="gentable" width="300"><b>{|Tage seit letzter Stufe|}</b></td>
			<td align="center" width="200"><b>{|Mailversand|}</b></td>
			<td class="gentable"><b>{|Mahngeb&uuml;hr|}</b></td>
			<td><b>{|Pos in Tabelle anzeigen|}</b></td>
		</tr>
		<tr style="background-color:#fff;display:;">
			<td class="gentable"><span id="mahnwesen_einst_zahlungerinnerung">{|Zahlungerinnerung|}</span></td>
			<td class="gentable">{|berechnetes Zahlungsziel|}</td>
			<td align="center"><input type="checkbox" name="mahnwesen_ze_versand" value="1" [MAHNWESENZE]>
			<td></td>
			<td><input type="checkbox" name="mahnwesenzepos" value="1" [MAHNWESENZEPOS] /></td>
		<tr >
			<td class="gentable"><span id="mahnwesen_einst_sofort">{|Zahlungerinnerung bei Vorkasse und Rechnung zahlbar sofort|}</span></td>
			<td class="gentable"><input type="text" name="mahnwesen_ze_wenn_null_versand" value="[MAHNWESENZNULLTAGE]" size="5"></td>
			<td align="center"></td>
    	<td class="gentable"></td>
    	<td class="gentable"></td>
    </tr>
		<tr >
			<td class="gentable"><span id="mahnwesen_einst_lastschrift">{|Zahlungerinnerung bei Lastschrift (+Einzugsdatum)|}</span></td>
			<td class="gentable"><input type="text" name="mahnwesen_ze_lastschrift" value="[MAHNWESENLASTSCHRIFT]" size="5"></td>
			<td align="center"></td>
    	<td class="gentable"></td>
    	<td class="gentable"></td>
    </tr>
		<tr style="background-color:#fff;display:;">
			<td class="gentable"><span id="mahnwesen_einst_mahnung1">{|Mahnung 1|}</span></td>
			<td class="gentable"><input type="text" name="mahnwesen_m1_tage" value="[MAHNWESENM1TAGE]" size="5"></td>
			<td align="center"><input type="checkbox" name="mahnwesen_m1_versand" value="1" [MAHNWESENM1]></td>
  		<td class="gentable"><input type="text" size="5" name="mahnwesen_m1_gebuehr" value="[MAHNWESENM1GEBUEHR]">&nbsp;EUR</td>
  		<td><input type="checkbox" name="mahnwesen1pos" value="1" [MAHNWESEN1POS] /></td>
  	</tr>
		<tr >
			<td class="gentable"><span id="mahnwesen_einst_mahnung2">{|Mahnung 2|}</span></td>
			<td class="gentable"><input type="text" size="5" name="mahnwesen_m2_tage" value="[MAHNWESENM2TAGE]"></td>
			<td align="center"><input type="checkbox" name="mahnwesen_m2_versand" value="1" [MAHNWESENM2]></td>
  		<td class="gentable"><input type="text" size="5" name="mahnwesen_m2_gebuehr" value="[MAHNWESENM2GEBUEHR]">&nbsp;EUR</td>
  		<td><input type="checkbox" name="mahnwesen2pos" value="1" [MAHNWESEN2POS] /></td>
  	</tr>
		<tr style="background-color:#fff;display:;">
			<td class="gentable"><span id="mahnwesen_einst_mahnung3">{|Mahnung 3|}</span></td>
			<td class="gentable"><input type="text" name="mahnwesen_m3_tage" value="[MAHNWESENM3TAGE]" size="5"></td>
			<td align="center"><input type="checkbox" name="mahnwesen_m3_versand" value="1" [MAHNWESENM3]></td>
  		<td class="gentable"><input type="text" size="5" name="mahnwesen_m3_gebuehr" value="[MAHNWESENM3GEBUEHR]">&nbsp;EUR</td>
  		<td><input type="checkbox" name="mahnwesen3pos" value="1" [MAHNWESEN3POS] /></td>
  	</tr>
		<tr >
			<td class="gentable"><span id="mahnwesen_einst_inkasso">{|Inkasso|}</span></td>
			<td class="gentable"><input type="text" size="5" name="mahnwesen_ik_tage" value="[MAHNWESENIKTAGE]"></td>
			<td align="center"><input type="checkbox" name="mahnwesen_ik_versand" value="1" [MAHNWESENIK]></td>
  		<td class="gentable"><input type="text" size="5" name="mahnwesen_ik_gebuehr" value="[MAHNWESENIKGEBUEHR]">&nbsp;EUR</td>
  		<td><input type="checkbox" name="mahnweseninkassopos" value="1" [MAHNWESENINKASSOPOS] /></td>
  	</tr>
	</table>

	<table width="100%" align="center" class="mkTable">
		<tr >
			<td class="gentable" width="300"><b>{|Vorkasse Erinnerung (E-Mail Erinnerung)|}</b></td>
			<td class="gentable" width="300"><b>{|nach Anzahl Tage|}</b></td>
			<td align="center" width="200"><b>{|Mailversand|}</b></td>
			<td></td>
		</tr>
		<tr style="background-color:#fff;display:;">
			<td class="gentable"><span id="mahnwesen_einst_buchhaltung">{|Skonto E-Mail an Buchhaltung|}</span></td>
			<td class="gentable"></td>
			<td align="center"><input type="checkbox" name="mahnwesen_skontomail" value="1" [SKONTOMAIL]>
			<td></td>
		</tr>
		<tr>
			<td class="gentable"><span id="mahnwesen_einst_vorkasse">{|Vorkasse E-Mail Erinnerung (Auftrag)|}</span></td>
			<td class="gentable"><input type="text" size="5" name="mahnwesen_zahlungserinnerungtage" value="[ZAHLUNGSERINNERUNGTAGE]"></td>
			<td align="center"><input type="checkbox" name="mahnwesen_erinnerungsmail" value="1" [ERINNERUNGSMAIL]>
			<td></td>
		</tr>
		<tr style="background-color:#fff;display:;">
			<td class="gentable"><span id="mahnwesen_einst_storno">{|E-Mail für Storno an Buchhaltung|}</span></td>
			<td><input type="text" size="5" name="mahnwesen_klaerungsmailtage" value="[KLAERUNGSMAILTAGE]"></td>
			<td class="gentable" align="center"><input type="checkbox" name="mahnwesen_klaerungsmail" value="1" [KLAERUNGSMAIL]></td>
			<td></td>
		</tr>
		<tr style="background-color:#fff;display:;">
			<td class="gentable"><span id="mahnwesen_einst_schwelle">{|Zahlungsmail Betrag-Schwelle|}</span></td>
			<td></td>
			<td></td>
			<td><input type="text" size="5" name="mahnwesen_schwelle" value="[MAHNWESEN_SCHWELLE]">&nbsp;EUR</td>
		</tr>
	</table>

	<table width="100%" align="center"  class="mkTable">
		<tr >
			<td class="gentable" width="300"><b>{|Sonstige Einstellungen|}</b></td>
			<td class="gentable"></td>
		</tr>
		<tr>
			<td class="gentable" width="300"><span id="mahnwesen_einst_ueberziehen">{|Skonto im Zahlungseingang &uuml;berziehen erlauben|}</span></td>
			<td class="gentable"><input type="text" size="5" name="mahnwesen_skontoueberziehenerlauben" value="[SKONTOUEBERZIEHENERLAUBEN]"></td>
		</tr>
  	<tr style="background-color:#fff;display:;">
  		<td class="gentable" width="300"><span id="mahnwesen_einst_bearbeiter">{|Bearbeiter + Vertrieb anzeigen|}</span></td>
  		<td align="left"><input type="checkbox" name="mahnwesen_bearbeiter_anzeigen" value="1" [MAHNWESENBEARBEITERANZEIGEN] /></td>
  	</tr>
		<tr style="background-color:#fff;display:;">
			<td class="gentable" width="300"><span>{|Versand Mahnungen über E-Mail-Account|}:</span></td>
			<td align="left"><select name="mahnwesen_abweichender_versender" id="mahnwesen_abweichender_versender">[MAHNWESENABWEICHENDERVERSENDER]</select></td>
		</tr>
	</table>
	<br><br><center><input type="submit" value="Einstellungen speichern" name="speichern"></center><br><br>
</form>
