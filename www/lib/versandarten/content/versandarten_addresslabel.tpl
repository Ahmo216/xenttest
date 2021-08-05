<br><br><table id="paketmarketab" align="center">
<tr>
<td align="center">
<br>
<form action="" method="post">
[ERROR]
<h1>Paketmarken Drucker f&uuml;r [ZUSATZ]</h1>
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
<tr><td>Land:</td><td>[EPROO_SELECT_LAND]</td></tr>
<tr><td>PLZ/Ort:</td><td><input type="text" name="plz" size="5" value="[PLZ]">&nbsp;<input type="text" size="30" name="ort" value="[ORT]"></td></tr>
<tr><td>Strasse/Hausnummer:</td><td><input type="text" size="30" value="[STRASSE]" name="strasse">&nbsp;<input type="text" size="5" name="hausnummer" value="[HAUSNUMMER]"></td></tr>
<tr><td>Telefon:</td><td><input type="text" name="telefon" id="telefon" size="36" value="[TELEFON]"></td></tr>
<tr><td>E-Mail:</td><td><input type="text" name="email" id="email" size="36" value="[EMAIL]"></td></tr>
</table>


<!--<table style="float:left; margin-left:20px;">-->
<!--<tr><td width="180">Anzahl Pakete:</td><td nowrap><input type="text" name="anzahl" size="5" value="[ANZAHL]" id="anzahl">&nbsp;<input type="button" onclick=window.location.href="index.php?module=versanderzeugen&action=frankieren&id=[ID]&land=[LAND]&anzahl="+document.getElementById('anzahl').value value="erstellen"></td></tr>-->
<!--[GEWICHT]
<tr><td>Anzahl Packstücke:</td><td><input type="text" size="5" name="anzahl" value="1"></td></tr>-->
<!--<tr><td>Transportverpackung:</td><td><input type="text" size="15" name="verpackung" id="verpackung" value="[VERPACKUNGEN]"></td></tr>-->
<!--<select name="verpackung"><option></option>
[VERPACKUNGEN]
<option>Colli</option>
<option>Einweg-Palette</option>
<option>DB-Euro-Flachpalette</option>
<option>DB Gitterbox</option>
<option>Halbpalette</option>
</select></td></tr>-->
<!--<tr><td>Aufkleber:</td><td><select name=""><option></option><option>Palettenaufkleber</option></select></td></tr>-->

<!--</table>-->
</tr>
</table>
<br><br>


<!--<table align="center">
  <tr><td colspan="2"><b>Service</b></td></tr>
<tr id="trspedition" style="display:none;"><td>Spedition:</td><td><input type="text" size="36" onblur="speditionchange();" name="spedition" id="spedition" value="[SPEDITION]"></td></tr>-->
<!--<tr><td>Inhalt:</td><td><input type="text" size="36" name="inhalt" id="inhalt" value="[INHALT]"></td></tr>-->


  <!--<tr><td>Abholdatum:</td><td><input type="text" size="10" id="abholdatum" name="abholdatum" value="[ABHOLDATUM]" /></td></tr>
  <tr><td>NVE erzeugen:</td><td><input type="checkbox" name="nveerzeugen" value="1" [NVE] /></td></tr>
  <tr><td>Letztes Packstück für Spedition:</td><td><input type="checkbox" name="letztes" value="1"></td></tr>
</table>-->

<br><br>
<center><input class="btnGreen" type="submit" value="Packstück erfassen" name="drucken">&nbsp;

&nbsp;<input type="button" value="{|Andere Versandart auswählen|}" onclick="window.location.href='index.php?module=versanderzeugen&action=wechsel&id=[ID]'" name="anders">&nbsp;
<!--<input type="button" value="Abbrechen">--></center>
</form>
</td></tr></table>
<br><br>

<script type="text/JavaScript" language="javascript">
  function speditionchange()
  {
    if($('#spedition').val())$('#spedition').prop('disabled',true);
    $.ajax({
        url: 'index.php?module=spedition&action=paketmarke&cmd=getvalues&saction=[ACTION]&smodule=[MODULE]&id=[ID]',
        type: 'POST',
        dataType: 'json',
        data: { spedition: $('#spedition').val()},
        success: function(data) {
          $('.trspedition').remove();
          $('#trspedition').after(data.html);
        },
        beforeSend: function() {

        }
    });
  }
  $(document).ready(function() {
  $( "#abholdatum" ).datepicker({ dateFormat: 'dd.mm.yy',dayNamesMin: ['SO', 'MO', 'DI', 'MI', 'DO', 'FR', 'SA'], firstDay:1,
          showWeek: true, monthNames: ['Januar', 'Februar', 'März', 'April', 'Mai',
          'Juni', 'Juli', 'August', 'September', 'Oktober',  'November', 'Dezember'], });
  });
  speditionchange();
</script>
