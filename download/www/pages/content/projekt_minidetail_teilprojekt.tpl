<!--<h2>Beschreibung</h2><hr>[BESCHREIBUNG]<br><br>-->
<!--<h2>Vorg&auml;nger</h2><hr>[VORGAENGER]<br><br>-->

<!--<h2>Abgeschlossene Zeit</h2><hr>[GEBUCHTEZEIT]<br><br>-->

<style>
.auftraginfo_cell {
  color: #636363;border: 1px solid #ccc;padding: 5px;
}

.auftrag_cell {
  color: #636363;border: 1px solid #fff;padding: 0px; margin:0px;
}

</style>


<table width="100%" border="0" cellpadding="10" cellspacing="5">
<tr valign="top"><td width="">

<h2 class="greyh2">Artikel</h2>
<div style="padding:10px;">[ARTIKEL]
<table width="100%">
<tr valign="top"><td>
<table class="mkTable" width="100%">
<tr><th></th><th></th><th></th><th></th><th colspan="2">Eingehend</th><th colspan="5">Ausgehend</th><th></th></tr>
<tr><th>&nbsp;</th><th>Artikel-Nr.</th><th width="20%">Name</th><th>Geplant</th><th>Lager</th><th><a title="Artikel in Bestellungen">BE</a></th><th><a title="Artikel in Produktionen">PR</a></th>
  <th><a title="Artikel in Angebote">AN</a></th>
  <th><a title="Artikel in Aufträgen">AB</a></th>
  <th><a title="Artikel in Lieferscheinen">LS</a></th>
  <th><a title="Artikel in Rechnungen">RE</a></th>
  <th><a title="Artikel in Gutschriften">GS</a></th><th>Menü</th></tr>
<tr><td><input type=checkbox></td><td>89888</td><td widtd="20%">Muster XX</td><td>1</td><td>1</td><td>-</td><td>-</td><td>1</td><td>-</td><td>-</td><td>-</td><td>-</td><td><img src=./themes/new/images/copy.svg>&nbsp;<img src=./themes/new/images/edit.svg>
<tr><td><input type=checkbox></td><td>89888</td><td widtd="20%">Muster XX</td><td>1</td><td>1</td><td>-</td><td>-</td><td>1</td><td>-</td><td>-</td><td>-</td><td>-</td><td><img src=./themes/new/images/copy.svg>&nbsp;<img src=./themes/new/images/edit.svg>
<tr><td><input type=checkbox></td><td>89888</td><td widtd="20%">Muster XX</td><td>1</td><td>1</td><td>-</td><td>-</td><td>1</td><td>-</td><td>-</td><td>-</td><td>-</td><td><img src=./themes/new/images/copy.svg>&nbsp;<img src=./themes/new/images/edit.svg>
<tr><td><input type=checkbox></td><td>89888</td><td widtd="20%">Muster XX</td><td>1</td><td>1</td><td>-</td><td>-</td><td>1</td><td>-</td><td>-</td><td>-</td><td>-</td><td><img src=./themes/new/images/copy.svg>&nbsp;<img src=./themes/new/images/edit.svg>
<img src=./themes/new/images/delete.svg></td></tr>
</table>
Auswahl:&nbsp;<select>
<option>Lieferanten Anfrage anlegen</option>
<option>Angebot anlegen</option>
<option>Auftrag anlegen</option>
<option>Bestellung anlegen</option>
</select>
</td><td width="200">
<center>
<input type="button" value="Neuen Artikel anlegen" style="width:190px">&nbsp;
<input type="button" value="Neue St&uuml;ckliste anlegen" style="width:190px">&nbsp;
<input type="button" value="Artikelliste importieren" style="width:190px">&nbsp;
</center>
</td></tr></table>
</div>

<h2 class="greyh2">Gebuchte Zeit</h2>
<div style="padding:10px;">[BESTELLUNGEN]
<table width="100%"><tr valign="top"><td>
<form action="index.php?module=arbeitsnachweis&action=createfromproject&id=[PROJEKT]" method="post"><table width="100%"><tr><td>
[OFFENEZEIT]
</td></tr></table>
</td><td width="200" align="center">
<input type="submit" value="in RE oder AB &uuml;bernehmen" style="width:190px">&nbsp;
<input type="submit" value="in Arbeitsnachweis &uuml;bernehmen" style="width:190px">&nbsp;
<input type="submit" value="als abgerechnet markieren" name="abgerechnet" style="width:190px" onclick='$(this).closest("form").attr("action", "index.php?module=projekt&action=arbeitspaket&id=[PROJEKT]");'>&nbsp;
</td></tr></table>
      </form>
</div>


<h2 class="greyh2">Manuelle Aufgaben</h2>
<div style="padding:10px;">[BESTELLUNGEN]
<table width="100%"><tr valign="top"><td>
<form action="index.php?module=arbeitsnachweis&action=createfromproject&id=[PROJEKT]" method="post"><table width="100%"><tr><td>
[OFFENEZEIT]
</td></tr></table>
</td><td width="200" align="center">
<input type="submit" value="in RE oder AB &uuml;bernehmen" style="width:190px">&nbsp;
<input type="submit" value="in Arbeitsnachweis &uuml;bernehmen" style="width:190px">&nbsp;
<input type="submit" value="als abgerechnet markieren" name="abgerechnet" style="width:190px" onclick='$(this).closest("form").attr("action", "index.php?module=projekt&action=arbeitspaket&id=[PROJEKT]");'>&nbsp;
</td></tr></table>
      </form>
</div>

<h2 class="greyh2">Vorhandene Belege f&uuml;r Artikel im Projekt</h2>
<div style="padding:10px;">[BESTELLUNGEN]
<table width="100%"><tr valign="top"><td>
<form action="index.php?module=arbeitsnachweis&action=createfromproject&id=[PROJEKT]" method="post"><table width="100%"><tr><td>
[OFFENEZEIT]
</td></tr></table>
</td><td width="200" align="center">
<input type="submit" value="Angebot anlegen" style="width:190px">&nbsp;
<input type="submit" value="Auftrag anlegen" style="width:190px">&nbsp;
<input type="submit" value="Lieferschein anlegen" style="width:190px">&nbsp;
<input type="submit" value="Rechnung anlegen" style="width:190px">&nbsp;
<input type="submit" value="Gutschrift anlegen" style="width:190px">&nbsp;
</td></tr></table>
</form>
</div>





</td><td width="550">  

<div style="overflow:scroll; height:550px; width:500px;">
<div style="background-color:white">

<h2 class="greyh2">Controlling</h2>
<div style="padding:10px;">
<center>
[BUTTONS]
</center>
</div>
<!--
<h2 class="greyh2">Datei Anhang</h2>
<div style="padding:10px;">[DATEIANHANGLISTE]</div>
-->

<!--
<h2 class="greyh2">Arbeitsschritte</h2>
<div style="padding:10px">
<table width="100%>">
<tr><td>Fertigstellung in %</td><td>Anzahl Schritte</td></tr>
<tr>
  <td class="greybox" width="25%">[DECKUNGSBEITRAG]</td>
  <td class="greybox" width="25%">[DBPROZENT]</td>
</tr>
</table>
</div>

-->


<h2 class="greyh2">Zeiterfassung Gesamt</h2>
<div style="padding:10px;">
<table width="100%>">
<tr><td>Zeit Geplant</td><td>Zeit Gebucht</td></tr>
<tr>
  <td class="greybox" width="25%">[ZEITGEPLANT]20.00</td>
  <td class="greybox" width="25%">[ZEITGEBUCHT]6.50</td>
</tr>
</table>
</div>

<h2 class="greyh2">Deckungsbeitrag</h2>
<div style="padding:10px">
<table width="100%>">
<tr><td>Deckungsbeitrag in EUR</td><td>DB in %</td></tr>
<tr>
        <td class="greybox" width="25%">[DECKUNGSBEITRAG]</td>
  <td class="greybox" width="25%">[DBPROZENT]</td>
</tr>
</table>


<br><br>

</div>
<h2 class="greyh2">Abschluss Bericht</h2>
<div style="padding:10px;">
<table width="100%>">
<tr><td>Menge Geplant</td><td>Menge Ausschuss</td></tr>
<tr>
  <td class="greybox" width="25%">[MENGEGEPLANT]</td>
  <td class="greybox" width="25%">[MENGEAUSSCHUSS]</td>
</tr>
</table>
</div>


</div>

</td></tr>
</table>

<div class="info">Dies ist ein Logistik- bzw. Rechteprojekt / eventuell andere Uebersicht (falls es so wäre / dies ist nur ein Kommentar in der Entwicklung)</div>
