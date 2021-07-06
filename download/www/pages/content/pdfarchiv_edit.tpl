<div id="tabs">
    <ul>
        <li><a href="#tabs-1">[TABTEXT]</a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
[MESSAGE]
<form method="POST">

<input type="hidden" value="1" name="aktiv">
[AUSBLENDENSTART]
  <fieldset><legend>{|Informationen|}</legend>
  <table width="100%">
    <tr><td width="250">{|Status|}:</td><td>[STATUS]</td></tr>
    <tr><td width="250">{|Kommentar|}:</td><td>[KOMMENTAR]</td></tr>
    <tr><td width="250">{|Download|}:</td><td>[DOWNLOAD]</td></tr>
  </table>
  </fieldset>
[AUSBLENDENENDE]
  <fieldset><legend>{|Einstellungen|}</legend>
  <table width="100%">
    <tr><td width="250">Auswahl Belegarten:</td><td>
      [TABELLENCHECKBOXEN]
    </td></tr>
    <tr><td><label for="monat_von">{|Datum von|}:</label></td><td>
        <select id="monat_von" name="monat_von">[MONAT_VON]</select> <select name="jahr_von">[JAHR_VON]</select>
      </td></tr>
    <tr><td><label for="monat_bis">{|Datum bis|}:</label></td><td>
        <select id="monat_bis" name="monat_bis">[MONAT_BIS]</select> <select name="jahr_bis">[JAHR_BIS]</select>
      </td></tr>
    <tr><td><label for="format">{|Format|}:</label></td><td>
        <select id="format" name="format">
          [BEFOREZIP]<option value="zip" [ZIP]>ZIP</option>[AFTERZIP]
          <option value="gz" [GZ]>tar.gz</option>
          <option value="" [NURARCHIVIEREN]>{|nur Archivieren|}</option>
        </select>
      </td>
    </tr>
    <!--<tr><td>FTP hochladen</td><td><input type="checkbox" value="1" name="ftp" [FTP] /></td></tr>-->
  </table>
  </fieldset>
  <fieldset><legend>{|Experten Modus|}</legend>
  <table width="100%">
    <tr><td width="250"><label for="experten">{|Experteneinstellungen|}</label></td><td><input type="checkbox" id="experten" onchange="checkexpert();" /></td></tr>
    <tr class="expert"><td><label for="generiere_nur_neue">{|Generiere noch nicht archivierte PDFs|}</label></td><td><input type="checkbox" value="1" name="generiere_nur_neue" id="generiere_nur_neue" [GENERIERE_NUR_NEUE] />&nbsp;<i>({|Standard Einstellung|})</i></td></tr>
    <tr class="expert"><td><label for="allepdf">{|Alle PDF-Versionen|}</label></td><td><input type="checkbox" value="1" name="allepdf" id="allepdf" [ALLEPDF] />&nbsp;<i>({|Auch &auml;ltere Versionen von gleichen Belegen werden mit in die Ausgabe generiert|})</i></td></tr>
    <tr class="expert"><td><label for="pdfneu">{|Generiere alle Dokumente neu|}</label></td><td><input type="checkbox" value="1" id="pdfneu" name="pdfneu" [PDFNEU] />&nbsp;<i>({|Nur wenn alle PDF Dateien neu im Archiv generiert werden sollen|})</i></td></tr>
    <tr class="expert"><td><label for="daily">{|PDFs in einzelne Tage bündlen|}</label></td><td><input type="checkbox" value="1" id="daily" name="daily" [DAILY] /></td></tr>
  </table>
  </fieldset>
    <table width="100%"><tr><td align="center"><input type="submit" value="{|Speichern|}" name="speichern" /></td></tr></table>

</form>
[TAB1NEXT]
</div>

<!-- tab view schließen -->
</div>
<script>
  function checkexpert()
  {
    if($('#experten').prop('checked'))
    {
      $('.expert').show(300);
    }else{
      $('.expert').hide(300);
    }
  }
  $(document).ready(function() {
    checkexpert();
  
  });
</script>
