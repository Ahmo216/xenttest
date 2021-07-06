<script>
  [JAVASCRIPT]
  [DATATABLES]
  [AUTOCOMPLETE]
  [JQUERY]
  
  
  function aktuallisiereteilprojektebelegeform()
  {
    var zeitencount = 0;
    var teilprojektbelegartikel = 0;
    var letzte = '';
    $('#tabelleteilprojektbeleg select').each(function(){
      var wert = $(this).val();
      if(wert)letzte = wert;
      /*
      wert == 'geplantezeitalspos' || wert == 'geplantezeitalspostext' || wert == 'geplantezeitalsposunterpos' ||
      */
      
      if(wert == 'offenezeitensumme' || wert == 'offenezeitenmitangabe' || wert == 'offenezeitenohneangabe')
      {
        zeitencount++;
      }
      if(wert == 'geplantezeitalspos' || wert == 'geplantezeitalspostext' || wert == 'geplantezeitalsposunterpos' ||wert == 'offenezeitensumme' || wert == 'offenezeitenmitangabe' || wert == 'offenezeitenohneangabe')
      {
        teilprojektbelegartikel++;
      }
      
 
      
    });
    if(zeitencount > 0)
    {
      $('tr.teilprojektbelegdatum').show();
    }else{
      $('tr.teilprojektbelegdatum').hide();
    }
    if(teilprojektbelegartikel > 0)
    {
      $('tr.teilprojektbelegartikel').show();
    }else{
      $('tr.teilprojektbelegartikel').hide();
    }
    
    $.ajax({
        url: 'index.php?module=projekt&action=dashboard&cmd=saveletzteauswahl',
        type: 'POST',
        dataType: 'json',
        data: { letzteauswahl: letzte}
    }); 
    
  }
  $(document).ready(function() {
    aktuallisiereteilprojektebelegeform();
  });
  
</script>
<form id="frmpopupdiv" method="POST">
<input type="hidden" value="[BELEG]" name="belegtyp" id="belegtyp" />
<input type="hidden" value="teilprojekt_zu_beleg" name="typ" id="typ" />
<input type="hidden" value="1" name="saveteilprojekt_zu_beleg" id="saveteilprojekt_zu_beleg" />
<table class="mkTable" id="tabelleteilprojektbeleg">
<tr>
<th></th>
<th>Nr.</th>
<th>Teilprojekt</th>
<th>Artikel-Nr.</th>
<th>Artikel-Name</th>
<th>Zeit<br>summiert</th>
<th>Zeit<br>geplant</th>
<th>Zeit<br>nicht abgerechnet</th>
<th>Zeit<br>offen</th>
<th>Auswahl</th>
</tr>
[POSITIONEN]
</table>




<fieldset><legend>{|Auswahl|}</legend>
<table>
<tr>
  <td style="width:200px" align="right">
  <input type="radio" checked="checked" id="belegradioneu" name="belegradio" value="neu" />
  </td><td width="200">
   [BELEG] neu anlegen 
  </td><td width="200">[KUNDELIEFERANT]:</td><td><input type="text" name="adresse" id="tpzubelegadresse" value="[ADRESSE]" size="50"></td>
</tr>
  [VORBESTEHEND]
<tr>
  <td align="right">
  <input type="radio" id="belegradiobestehend" name="belegradio" value="bestehend" /></td><td>zu bestehenden [BELEG] hinzuf&uuml;gen 
  </td><td>Auswahl:</td><td><input type="text" name="zubeleg" id="zubeleg" /></td>
</tr>
<tr><td></td><td>Artikelnameformat</td><td><select name="nummerierung"><option value="">ohne Nummerierung</option><option value="nummerierung">mit Nummerierung</option></select></td></tr>
[NACHBESTEHEND]
</table>

<table class="mkTableFormular">
<tr class="teilprojektbelegartikel"><td></td><td width="200"></td><td width="200">Artikel für Stundensatz:</td><td><input type="text" size="50" name="zeiterfassungsartikel" value="[ZEITERFASSUNGSARTIKEL]" id="zeiterfassungsartikel" /></td></tr>
<tr class="teilprojektbelegdatum"><td></td><td><td>Datum von:</td><td><input type="text" value="[DATUMALT]" name="datumalt" size="10" id="datumalt">&nbsp;<i>Dient für offene Zeiten</i></td></tr>
<tr class="teilprojektbelegdatum"><td></td><td><td>Datum bis: </td><td><input type="text" value="[DATUMNEU]" size="10" name="datumneu" id="datumneu">&nbsp;<i>Dient für offene Zeiten</i></td></tr>
</table>
</fieldset>
[MESSAGE]
[TAB1]
[TAB1NEXT]

</form>
