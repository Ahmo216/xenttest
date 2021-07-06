<style>
table#umsatzstatistiknettogesamt > tfoot > tr > th:nth-child(13) > span
{
display:none;
}
</style>

<div id="tabs">
<ul>   
        <li><a href="#tabs-1"></a></li>
 </ul>

<div id="tabs-1">
  [MESSAGE]
<form action="" method="post">
<table width="100%"><tr><td>
  <fieldset><legend>&nbsp;{|Datum|}</legend>

  <table width="100%" height="140" cellspacing="5">
  <tr valign="top">
<td>
<table><tr>

    <td>{|Von|}:</td>
    <td><input type="text" id="von" name="von" size="10" value="[VON]"></td>
    <td>{|Bis|}:</td>
    <td><input type="text" id="bis" name="bis" size="10" value="[BIS]"></td>
</tr>
</table>
</td>

<td>
   <table><tr> 
    <td valign="top"><table>
      <!--<tr><td><input type="checkbox" id="angebot" name="angebot" value="1" [ANGEBOT] ></td><td>{|Angebot|}</td></tr>-->
      <tr><td><input type="checkbox" id="auftrag" name="auftrag" value="1" [AUFTRAG] ></td><td>{|Auftrag|}</td></tr>
      <tr><td><input type="checkbox" id="rechnung" name="rechnung" value="1" [RECHNUNG] ></td><td>{|Rechnung|}</td></tr>
      <tr><td><input type="checkbox" id="gutschrift" name="gutschrift" value="1" [GUTSCHRIFT] ></td><td>{|Gutschrift|}</td></tr>
    </table></td>
    <td valign="top"><table>
      <tr><td><input type="checkbox" id="angelegt" name="angelegt" value="1" [ANGELEGT] ></td><td>{|angelegt|}</td></tr>
      <tr><td><input type="checkbox" id="freigegeben" name="freigegeben" value="1" [FREIGEGEBEN] ></td><td>{|freigegeben|}</td></tr>
      <tr><td><input type="checkbox" id="versendet" name="versendet" value="1" [VERSENDET] ></td><td>{|versendet|}</td></tr>
      <tr><td><input type="checkbox" id="abgeschlossen" name="abgeschlossen" value="1" [ABGESCHLOSSEN] ></td><td>{|abgeschlossen|}</td></tr>
      <tr><td><input type="checkbox" id="storniert" name="storniert" value="1" [STORNIERT] ></td><td>{|storniert|}</td></tr>
    </table></td>    
  </tr>
</table>

</td>
  </table>
  </fieldset>



</td><td>
  <fieldset ><legend>&nbsp;{|Filter|}</legend>
  <table width="100%" height="140" cellspacing="5">
  <tr>
    <td>{|Kunde|}: </td>
    <td><input type="text" id="kunde" name="kunde" size="35" value="[KUNDE]" onclick=document.getElementById("kunde").value='';>&nbsp;</td>
  </tr><tr>
    <td>{|Projekt|}: </td><td valign="top"><input type="text" id="projekt" name="projekt" size="35" value="[PROJEKT]" onclick=document.getElementById("projekt").value='';></td>
  </tr>
  <tr>
    <td>{|Artikelkategorie|}: </td><td valign="top"><input type="text" id="kategorie" name="kategorie" size="35" value="[KATEGORIE]" onclick=document.getElementById("kategorie").value='';></td>
  </tr>
  <tr>
    <td>{|Artikel|}: </td><td valign="top"><input type="text" id="artikel" name="artikel" size="35" value="[ARTIKEL]" onclick=document.getElementById("artikel").value='';></td>
  </tr>
<tr height="100%">
  <td></td>
  <td></td>
</tr>
  </table>
  </fieldset>
</td><td>
  <fieldset ><legend>&nbsp;{|Starten|}</legend>
  <table width="100%" height="140" cellspacing="5">
  <tr>
    <td><input type="submit" value="{|Ums&auml;tze laden|}" name="laden"></td>
  </tr>
  <tr height="100%"><td></td></tr>
  <tr height="100%"><td></td></tr>

  </table>
  </fieldset>


</td></tr></table>
</form>



<table width="100%>">
  <tr><td>{|Anzahl Belege|}</td><td>{|Umsatz Gesamt (netto)|}</td><td>{|Umsatz Gesamt (brutto)|}</td><td>{|Erl&ouml;se (netto)|}</td><td>{|Deckungsbeitrag in %|}</td></tr>
  <tr>
    <td class="greybox" width="20%">[ANZAHL]</td>
    <td class="greybox" width="20%">[UMSATZ]</td>
    <td class="greybox" width="20%">[UMSATZBRUTTO]</td>
    <td class="greybox" width="20%">[ERLOESE]</td>
    <td class="greybox" width="20%">[DECKUNGSBEITRAG]</td>
  </tr>
</table>

[UMSATZTABELLE]

</div>
</div>
