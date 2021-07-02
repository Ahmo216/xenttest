<div id="tabs">
    <ul>
        <li><a href="#tabs-1">[TABTEXT]</a></li>
    </ul>
<!-- erstes tab -->
<div id="tabs-1">
<table class="tableborder" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody>

      <tr valign="top" cellpadding="0" cellspacing="0">
        <td>
<table width="100%" align="center" style="background-color:#cfcfd1;">
<tr>
<td width="33%"></td>
<td align="center" nowrap width="33%"><b style="font-size: 14pt">{|Kommissionierlauf|} <font color="blue">[ID]</font></b> vom [DATUM]</td>
<td align="right" width="33%">&nbsp;</td>

</tr>
</table></td></tr></table>
<br>
[MESSAGE]
[TAB1]
[TAB1NEXT]

<script type="application/javascript">
  $(document).on('ready',function(){
    $('#skipconfirmboxscan').on('change',function () {
      $.ajax({
        url: 'index.php?module=kommissionierlauf&action=edit&cmd=changeskipconfirmbox&id=[ID]',
        type: 'POST',
        dataType: 'json',
        data: {
          value:$('#skipconfirmboxscan').prop('checked')?1:0
        },
        success: function(data) {

        }
      });
    })
  });
function ausblenden(artikel, lager_platz,lieferschein, element)
{
  $(element).parents('tr').first().hide();
      $.ajax({
        url: 'index.php?module=kommissionierlauf&action=edit&id=[ID]',
        type: 'POST',
        dataType: 'json',
        data: { art: artikel, lp: lager_platz, do:'ausblenden',ls:lieferschein},
        success: function(data) {
          var anz = 0;
          $('table.mkTable').find('img').parents('tr').first().each(function(){if($(this).css('display') != 'none')anz++});
          if(anz == 0)window.location ='index.php?module=kommissionierlauf&action=edit&cmd=abschliessen&id=[ID]';
        },
        beforeSend: function() {

        }
    });
}
</script>
</div>

<!-- tab view schließen -->
</div>

