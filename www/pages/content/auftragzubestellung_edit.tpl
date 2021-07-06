<script type="application/javascript">
function changelieferant(id)
{
  var lieferant = $('#lieferant_'+id).val();
  var preise = $('#eps_'+id).val();
  var preisea = preise.split(';');
  var menge = parseInt($('#menge_'+id).html());
  $.each(preisea, function(k,v)
  {
    var va = v.split(':');
    if(va[0] == lieferant)
    {
      var einzelpreis = parseFloat(va[1]);
      var preiseur = parseFloat(va[2]);
      var gesamtpreis = menge * einzelpreis;
      var gesamtpreiseur = preiseur * menge;
      var waehrung = va[3];
      
      $('#einzelpreis_'+id).html(einzelpreis.toFixed(2)+' '+waehrung+(waehrung != 'EUR'?' = '+(preiseur.toFixed(2))+' EUR':''));
      $('#gesamtpreis_'+id).html(gesamtpreis.toFixed(2)+' '+waehrung+(waehrung != 'EUR'?' = '+(gesamtpreiseur.toFixed(2))+' EUR':''));
    }
  });
}
$(document).ready(function(){
    $( "#editform" ).submit(function( event ) {
        var fieldstr = '';
        $('#positiontable').find('select').each(function (){
            fieldstr += $(this).attr('name') + ':' + $(this).val() + ';';
        });
        $('#positiontable').find(':text').each(function (){
            fieldstr += $(this).attr('name') + ':' + ($(this).val() + '').replace(':', '').replace(';', '') + ';';
        });
        $('#positiontable').find(':hidden').each(function (){
            fieldstr += $(this).attr('name') + ':' + ($(this).val() + '').replace(':', '').replace(';', '') + ';';
        });
        $('#positiontable').find("input[type='radio']:checked").each(function () {
            fieldstr += $(this).attr('name') + ':' + ($(this).val() + '').replace(':', '').replace(';', '') + ';';
        });
        $('#positiontable').find("input[type='checkbox']:checked").each(function () {
            fieldstr += $(this).attr('name') + ':1;';
        });
        $('#fields').val(fieldstr);
        $(this).submit();
    });
});
</script>
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">[TABTEXT]</a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">

[MESSAGE]
    <div id="positiontable">
[TAB1]
    </div>
    <form method="POST" id="editform">
<table width="100%" border="0"><tr><td width="100%" align="center" border="0">
<input type="checkbox" id="freifelder" name="freifelder" value="1" [FREIFELDER]>&nbsp;Freifelder übernehmen
<input type="checkbox" id="beschreibung" name="beschreibung" value="1" [BESCHREIBUNG]>&nbsp;Artikelbeschreibung übernehmen
<input type="checkbox" id="bestellungpdf" name="bestellungpdf" value="1" [BESTELLUNGPDF]> Bestellung PDF als Anhang bei Auftrag anhängen
&nbsp;<input type="hidden" name="fields" id="fields"  value="" />
<input type="submit" value="Bestellung(en) erzeugen" name="erzeugen" /></td></tr></table>
</form>
[TAB1NEXT]
</div>

<!-- tab view schließen -->
</div>
<script>
[ADDAZBJS]
</script>
