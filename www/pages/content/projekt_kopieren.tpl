<div id="tabs">
    <ul>
        [TAB1START]<li><a href="#tabs-1">[TABTEXT]</a></li>[TAB1ENDE]
        [TAB2START]<li><a href="#tabs-2">[TABTEXT2]</a></li>[TAB2ENDE]
        [TAB3START]<li><a href="#tabs-3">[TABTEXT3]</a></li>[TAB3ENDE]
        [TAB4START]<li><a href="#tabs-4">[TABTEXT4]</a></li>[TAB4ENDE]
        [TAB5START]<li><a href="#tabs-5">[TABTEXT5]</a></li>[TAB5ENDE]
    </ul>
<!-- ende gehort zu tabview -->
<form method="POST">
<input type="hidden" name="toid" value="[TOID]" /><input type="hidden" name="prkopieren" value="1" />
<!-- erstes tab -->
<div id="tabs-1">
[MESSAGE]
[TAB1]
[TAB1NEXT]
</div>


<!-- erstes tab -->
<div id="tabs-2">
[MESSAGE2]
[TAB2]
[TAB2NEXT]
</div>

<!-- erstes tab -->
<div id="tabs-3">
[MESSAGE3]
[TAB3]
[TAB3NEXT]
</div>

<div id="tabs-4">
[MESSAGE4]
[TAB4]
[TAB4NEXT]
</div>

<div id="tabs-5">
[MESSAGE5]
[TAB5]
[TAB5NEXT]
</div>

</form>
<!-- tab view schließen -->
</div>
<script>
$(document).ready(function() {
  $('#alle_teilprojekte').on('change',function(){
    $('.arbeitspaket').prop('checked',$(this).prop('checked'));
  });
  $('#alle_artikel').on('change',function(){
    $('.artikel').prop('checked',$(this).prop('checked'));
  });
  $('#alle_aufgaben').on('change',function() {
      $('.aufgaben').prop('checked',$(this).prop('checked'));
  });
  $('#alle_adressrollen').on('change',function(){
    $('.adressrollen').prop('checked',$(this).prop('checked'));
  });
  $('#alle_wiedervorlagen').on('change',function() {
    $('.wiedervorlagen').prop('checked',$(this).prop('checked'));
  });
});

function buttonaktion(wert)
{
  switch(wert)
  {
    case 'tabs-1':
    case 'tabs-2':
    case 'tabs-3':
    case 'tabs-4':
    case 'tabs-5':
      $('a[href="#'+wert+'"]').click();
    break;
  }

}
</script>
