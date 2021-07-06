<div id="tabs">
  <ul>
    <li><a href="#tabs-1"><!--[TABTEXT]--></a></li>
  </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
<fieldset class="dark">
<table width="100%" align="center" style="background-color:#cfcfd1;">
<tr>
<td width="33%"></td>
<td align="center"><b style="font-size: 14pt">[BELEGTYP] <font color="blue">[NUMMER]</font></b>[KUNDE]</td>
<td width="33%" align="right" nowrap></td>
</tr>
</table>
</fieldset>
<br>[MESSAGE]
<form method="POST">
[TAB1]
[VORDURCHFUEHREN]<center><input type="submit" name="durchfuehren" value="{|Durchf&uuml;hren|}" /> &nbsp; [CHECKBOXLAGERLATZ]</center>[NACHDURCHFUEHREN]
</form>
[TAB1NEXT]
</div>

<!-- tab view schließen -->
</div>
<script>

var delimgfunction = function()
{
  $(this).parents('tr').first().remove();
};

function updateautocomplete(el)
{
  var wert = $(el).val();
  var tr = $(el).parents('tr').first();
  //var el2 = $(tr).find('input.mhdcharge').first();
  var artikelid = $(tr).find('span.artid').first().text();
  var pos = $(tr).find('input.posid').first().val();
  var el3 = $(tr).find('input.lpinput');
  //pos = '';//Nur abhaengig von Artikel
  if(wert === 'ein')
  {
    //$(el2).autocomplete({
    //  source: "index.php?module=ajax&action=filter&filtername=mhdchargebeleg&artikel="+artikelid+"&doctypeid=[ID]&doctype=[DOCTYPE]&pos="+pos
    //});
    $(el3).autocomplete({
      source: "index.php?module=ajax&action=filter&filtername=lagerplatz"
    });

  }else{
    //$(el2).autocomplete({
    //  source: "index.php?module=ajax&action=filter&filtername=lagermhdcharge&artikel="+artikelid+"&lagerplatz="+encodeURI($(el).parents('tr').first().find('input.lpinput').val())
    //});
    $(el3).autocomplete({
      source: 'index.php?module=ajax&action=filter&filtername=lagerplatzartikel&artikel='+artikelid+'&doctype=[BELEGTYP]&pos='+pos
    });
  }
}

var plusimgfunction =  function(){
    var tr = $(this).parents('tr').first();
    $(tr).after('<tr>'+$(tr).html()+'</tr>');
    $(tr).next('tr').find('img.plusimg').on('click', plusimgfunction);
    var del = $(tr).next('tr').find('img.delimg');
    if(del.length == 0)$(tr).next('tr').find('img.plusimg').after('&nbsp;<img src="./themes/[THEME]/images/delete.svg" class="delimg" />');
    $(tr).next('tr').find('img.delimg').on('click', delimgfunction);
    //$(tr).next('tr').find('input.mhdcharge').each(function(){ setautocompletemhdcharge(this, $(tr).next('tr').find('span.artid').first()) });
    $(tr).next('tr').find('select.seleinlagern').each(function(){updateautocomplete(this);});
    $(tr).next('tr').find('.autocomplete_lupe').on('click',function(){
      $('.ui-autocomplete-input').each(function(){
        if($(this).val() === ' ')
        {
          $(this).val('');
          $(this).trigger('keydown');
        }
      });
      blockclick = true;
      lastlupe = this;
      var el = this;
      //var height = $(window).scrollTop();
      var found = false;
      $(el).prev('.ui-autocomplete-input').each(function(){
      //var v = $(this).val();
        found = true;
        aktlupe = this;
        $(this).val(' ');
        $(this).trigger('keydown');
        //if(v !== '')setTimeout(trimel, 1500,this);
        //setTimeout(function(){$(window).scrollTop(height);},100);
      });
      if(!found)
      {
        $(el).prev('a').prev('.ui-autocomplete-input').each(function(){
          found = true;
          aktlupe = this;
          $(this).val(' ');
          $(this).trigger('keydown');
        });          
      }
      setTimeout(function(){blockclick = false;},200);
    });
  };

$(document).ready(function() {
$('img.plusimg').on('click',plusimgfunction);
$('select.seleinlagern').each(function(){$(this).trigger('change');});
});
</script>
