<br><br><table id="paketmarketab" align="center">
<tr>
<td align="center">
<br>
<form action="" method="post">
[ERROR]
<h1>Paketmarken Drucker f&uuml;r [ZUSATZ]</h1>
<!--<br>
  <h3 style="color:red;">Beim Versand ins Ausland mit UPS müssen die Felder <b>Name</b>, <b>Name 2</b> und <b>Telefon</b> ausgefüllt werden!</h3>-->
<br>
<b>Empf&auml;nger</b>
<br>
<br>
<table>
<tr><td>


<table style="float:left;">
<tr><td>Name:</td><td><input type="text" size="36" value="[NAME]" name="name" id="name"><script type="text/javascript">document.getElementById("name").focus(); </script></td></tr>
<tr><td>Name 2:</td><td><input type="text" size="36" value="[NAME2]" name="name2"></td></tr>
<tr><td>Name 3:</td><td><input type="text" size="36" value="[NAME3]" name="name3"></td></tr>
[VORBUNDESSTAAT]<tr><td>Bundesstaat:</td><td>[EPROO_SELECT_BUNDESSTAAT]</td></tr>[NACHBUNDESSTAAT]
<tr><td>Land:</td><td>[EPROO_SELECT_LAND]</td></tr>
<tr><td>PLZ/Ort:</td><td><input type="text" name="plz" size="5" value="[PLZ]">&nbsp;<input type="text" size="30" name="ort" value="[ORT]"></td></tr>
<tr><td>Strasse/Hausnummer:</td><td><input type="text" size="36" value="[STRASSEKOMPLETT]" name="strasse"></td></tr>
<tr><td>Telefon:</td><td><input type="text" size="36" value="[TELEFON]" name="telefon"></td></tr>
</table>


<table style="float:left;">
<tr><td>Anzahl Pakete:</td><td><input type="text" name="anzahl" size="5" value="1"></td></tr>
[GEWICHT]

<tr><td><br></td><td></td></tr>
<tr><td>Länge:</td><td><input type="text" name="laenge" size="5" value="[LAENGE]">&nbsp;<i>in cm</i></td></tr>
<tr><td>Breite:</td><td><input type="text" name="breite" size="5" value="[BREITE]">&nbsp;<i>in cm</i></td></tr>
<tr><td>Höhe:</td><td><input type="text" name="hoehe" size="5" value="[HOEHE]">&nbsp;<i>in cm</i></td></tr>
<!--<tr><td>Foto:</td><td><img src="http://t3.gstatic.com/images?q=tbn:QTV_X4YJEI2p7M:http://notebook.pege.org/2005-inode/paket.jpg"></td></tr>
<tr><td></td><td><input type="button" value="Nochmal Wiegen+Foto"></td></tr>-->
</table>
</tr>
</table>
<br><br>


<table align="center">
  <tr><td colspan="2"><b>Service</b></td></tr>
  <tr><td>Nachnahme:</td><td><input type="checkbox" name="nachnahme" value="1" [NACHNAHME]> (Betrag: [BETRAG] EUR)<input type="hidden" name="betrag" value="[BETRAG]"></td></tr>
<!--  <tr><td>Versichert 2500 EUR:</td><td><input type="checkbox" name="versichert" value="1" [VERSICHERT]></td></tr>
  <tr><td>Versichert 25000 EUR:</td><td><input type="checkbox" name="extraversichert" value="1" [EXTRAVERSICHERT]></td></tr>
-->
</table>

[ACCOUNTNUMBER]

<br><br>
<center><input class="btnGreen" type="submit" value="Paketmarke drucken" name="drucken">&nbsp;
[TRACKINGMANUELL]
&nbsp;<input type="button" class="btnBlue" value="{|Andere Versandart auswählen|}" onclick="window.location.href='index.php?module=versanderzeugen&action=wechsel&id=[ID]'" name="anders">&nbsp;
<!--<input type="button" value="Abbrechen">--></center>
</form>
</td></tr></table>
<br><br>