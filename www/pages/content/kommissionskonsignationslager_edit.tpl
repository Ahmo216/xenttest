<div id="tabs">
    <ul>
        <li><a href="#tabs-1">[TABTEXT]</a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
<div id="ajaxmessage"></div>
[MESSAGE]

<table border="0" width="100%" cellpadding="0" cellspacing="0"><tr><td>
<table width="100%" align="center" style="background-color:#cfcfd1;">
<tr>
<td width="33%"></td>
<td align="center" nowrap><b style="font-size: 14pt">Adresse <font color="blue">[KUNDE]</font></b> [KUNDELIEFERANT]: [KUNDENNUMMER]</td>
<td width="33%" align="right">&nbsp;</td>
</tr>
</table>
<div style="height:10px"></div>

</td></tr></table>
<fieldset><legend>{|Filter|}</legend><table><tr><td>Artikel summiert:</td><td><input type="checkbox" id="gruppieren" value="1" onchange="gruppierenchange();" [GRUPPIEREN] /></td></tr></table></fieldset>
[TAB1]

<fieldset>
  <form method="POST" >
    <table width="100%">
      <tr>
        <td>
          <select name="aktion">
          [VORRECHNUNG]<option value="rechnung">als Rechnung weiterf&uuml;hren</option>[NACHRECHNUNG]
          <option value="auslagern">nur auslagern</option>
          </select>
        </td>
        <td>
          <input type="submit" name="speichern" value="durchf&uuml;hren">
        </td>
      </tr>
    </table>
  </form>
</fieldset>

[TAB1NEXT]




<script>

function KommissionskonsignationsEtikettenDrucken(id)
{
  if(confirm('Etiketten wirklich drucken?'))
  {
    $.ajax({
      url: "index.php?module=kommissionskonsignationslager&action=etikettendrucken&id="+id,
      type: 'POST',
      dataType: 'json',
      data: {
      }}).done( function(data) {

      }).fail( function( jqXHR, textStatus ) {
    });
  }
}

function gruppierenchange()
{
  if(true || confirm('Filter wirklich anwenden? Eingegebene Mengen werden zur&uuml;ckgesetzt'))
  {
    $.ajax({
      url: 'index.php?module=kommissionskonsignationslager&action=edit&id=[ID]&cmd=gruppieren',
      type: 'POST',
      dataType: 'json',
      data: { gruppieren:$('#gruppieren').prop('checked')?1:0,adresse:[ID] },
      success: function(data) {
        var oTable = $('#kommissionskonsignationslager_edit').DataTable( );
        oTable.ajax.reload();
      }
    }); 
  }else{
    $('#gruppieren').prop('checked', !$('#gruppieren').prop('checked'));
  }
}

function changemenge(el)
{
  var ida = el.id.split('_');
    $.ajax({
      url: 'index.php?module=kommissionskonsignationslager&action=edit&id=[ID]&cmd=changemenge',
      type: 'POST',
      dataType: 'json',
      data: { artikel:ida[ 1 ], lspos: ida[ 2 ], menge: $(el).val() },
      success: function(data) {

      }
    }); 
}

</script>
</div>

<!-- tab view schließen -->
</div>

