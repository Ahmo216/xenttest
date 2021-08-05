<div id="tabs">
    <ul>
        <li><a href="#tabs-1"></a></li>
    </ul>
<!-- ende gehort zu tabview -->
<style>
#lagermobiltabelle {max-width:500px;width:100%;}
#lagermobiltabelle input[type="button"],#lagermobiltabelle input[type="submit"]
{
width:100%;
border-radius: 7px;
font-size:2em;
min-height:2em;
cursor:pointer;

}
</style>
<script>
function artselect(el)
{
  $('#artikel').val($(el).parent().parent().find('span').first().html());
  $('#menge').val($(el).parent().parent().find('span.spanmenge').html());
  $('#menge').first().focus();
}
</script>
<!-- erstes tab -->
<div id="tabs-1">

[MESSAGE]
<form method="POST">
<table id="lagermobiltabelle">
<tr><td colspan="2"><a href="index.php?module=lagermobilold&action=list"><input type="button" value="zur&uuml;ck zur &Uuml;bersicht" /></a></td></tr>
[VORLAGERVON]<tr><td>Lagerplatz von</td><td><input type="text" name="lager_platz_von" id="lager_platz_von" value="[LAGER_PLATZ_VON]" /></td></tr>[NACHLAGERVON]
<tr><td colspan="2">[ARTIKELLAGERPLATZ]</td></tr>
[VORLAGERZU]<tr><td>Lagerplatz zu</td><td><input type="text" name="lager_platz_zu" id="lager_platz_zu" value="[LAGER_PLATZ_ZU]" /></td></tr>[NACHLAGERZU]
[VORARTIKEL]<tr><td>Artikel</td><td><input type="text" name="artikel" id="artikel" value="[ARTIKEL]" /></td></tr>[NACHARTIKEL]
<tr><td colspan="2">[ARTIKELMHD]</td></tr>
<tr><td colspan="2">[ARTIKELSERIENNUMMERN]</td></tr>
[VORMENGE]<tr><td>Menge</td><td><input type="text" id="menge" name="menge" value="[MENGE]" /></td></tr>[NACHMENGE]
<tr><td colspan="2"><input type="submit" name="speichern" id="speichern" value="[SPEICHERNTEXT]" /></td></tr>

</table>
[WEITEREINFOSLAGER]
</form>
[TAB1]
</div>


<!-- tab view schließen -->
</div>

