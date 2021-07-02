<div id="tabs">
  <ul>
    <li><a href="#tabs-1"><!--[TABTEXT]--></a></li>
  </ul>
  <div id="tabs-1">
    [MESSAGE]
    <table width="100%" style="background-color: #fff; border: solid 1px #000;" align="center">
      <tr>
      <td align="center">
      <br><b style="font-size: 14pt">{|Retoure|}</b>
      <br>
      <br>
      {|Auswahl der Artikel f&uuml;r die Retoure. Bestimmen Sie welche Artikel als Retoure weitergef&uuml;hrt werden sollen.|}
      <br>
      <br>
      </td>
      </tr>
    </table>
    <br>
    <center>
      <form action="" method="post">
        [TAB1]
        <a href="[RETOUREBACKLINK]"><input type="button" class="button button-secondary"
                                           value="{|Abbrechen - doch keine Retoure|}" /></a>
        <input type="submit" class="button button-primary" name="erzeugen" value="{|Retoure erzeugen|}" />
      </form>
    </center>
    [TAB1NEXT]
  </div>
</div>
[POPUP]
<script type="text/javascript">
  $(document).ready(function() {
  $('#popupretoure').dialog(    {
      modal: true,
      autoOpen: false,
      minWidth: 940,
      title:'Retoure',
      buttons: {
        'OK': function()
        {
          var mengeinp = $('[name='+$('#popup_id').val()+']');
          var tr = $(mengeinp).parents('tr').first();
          /*$(tr).find('.selgrund').first().val($('#popup_grund').val());*/
          /*$(tr).find('.aktionauswahl').first().val($('#popup_art').val());*/
          $(tr).find('.grundbeschreibung').first().val($('#popup_grundbeschreibung').val());
          /*$(tr).find('.artbeschreibung').first().val($('#popup_artbeschreibung').val());*/
          $('#popupretoure').dialog('close');
        },
        'ABBRECHEN': function() {
          $(this).dialog('close');
        }
      },
      close: function(event, ui){
        
      }
    });
  
  });
  
  function deleteelement(el)
  {
    var tr = $(el).parents('tr').first().parents('tr').first();
    var inp = $(tr).find('.retoureinp');
    var type = $(inp).attr('type');
    if(type === 'checkbox') {
      $(inp).prop('checked', false);
    }
    else{
      $(inp).val('');
    }
    $(tr).hide();
  }
  
  function editelement(el)
  {
    var tr = $(el).parents('tr').first().parents('tr').first();
    var inp = $(tr).find('.retoureinp');
    $('#popup_id').val($(inp).attr('name'));
    /*$('#popup_grund').val($(tr).find('.selgrund').first().val());*/
    $('#popup_grundbeschreibung').val($(tr).find('.grundbeschreibung').first().val());
    /*$('#popup_art').val($(tr).find('.aktionauswahl').first().val());
    $('#popup_artbeschreibung').val($(tr).find('.artbeschreibung').first().val());*/
    $('#popupretoure').dialog('open');
  }
  
</script>