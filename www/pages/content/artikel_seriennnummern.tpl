<!-- gehort zu tabview -->
<div id="tabs">
    <ul>
        <li><a href="#tabs-2">Kunden</a></li>
        <li><a href="#tabs-1">Im Lager</a></li>
        <li><a href="#tabs-3">Generator</a></li>
    </ul>
<!-- ende gehort zu tabview -->
<div id="tabs-2">
[TAB2]
</div>


<!-- erstes tab -->
<div id="tabs-1">
[SERIENNUMMERNFORMULAR]
[MESSAGE]
[TAB1]
[TAB1NEXT]
</div>
<!-- ende gehort zu tabview -->
<div id="tabs-3">
[TAB3]
[STARTDISABLE]
[MESSAGE]
<form action="" method="post">
<center>
<h2>Seriennummern erzeugen</h2><br><br>

<fieldset><legend>{|Seriennummer|}</legend>
<table width="" border="0">
<tr><td width="200">Startnummer:</td>
		<td width="200"><input type="text" name="startnummer" size="30"></td><td width="90"></td>
<td width="200">Zuletzt vergebene Nr.:</td><td><input type="text" name="letzteseriennummer" value="[LETZTESERIENNUMMER]"></td></tr>

<tr><td width="200">Anzahl forlaufende Nr.:</td><td width="200"><input type="text" name="menge" size="30"></td><td width="90"></td>
<td width="200">Anzahl Ausdrucke pro Nr.:</td><td width="200"><input type="text" name="anzahletiketten" size="30" value="[ANZAHLETIKETTEN]"></td></tr>
</table>
</fieldset>

<fieldset><legend>{|Ausdruck|}</legend>
<table width="" border="0">
<tr>
<td width="200"><input type="checkbox" name="drucken" value="1" [ETIKETTENDRUCKEN]>&nbsp;Etiketten drucken:</td>
<td width="200"><select name="etiketten">[ETIKETTEN]</select>&nbsp;</td><td width="90"></td>
<td width="200">Drucker:</td><td width="200"><select name="etikettendrucker">[ETIKETTENDRUCKER]</select></td></tr>
</table>
</fieldset>

<fieldset><legend>{|Lager|}</legend>
<table width="" border="0">
<tr>
	<td width="200"><input type="checkbox" name="lager" value="1">&nbsp;direkt einlagern:</td>
  <td width="200"><input type="text" size="26" name="lager_platz" id="lager_platz"></td><td width="90"></td>
  <td width="200"></td><td width="200"></td></tr>
</table>
</fieldset>

<br><input type="submit" value="Seriennummern jetzt erstellen" name="erstellen">
&nbsp;<input type="submit" value="Zuletzt vergebene Seriennummern speichern" name="speichern" class="btnBlue">

</center>
</form>
[ENDEDISABLE]
</div>

<!-- tab view schließen -->
</div>

