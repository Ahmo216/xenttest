

<style>

textarea {
    box-sizing: border-box;
    resize: none;
    min-height:200px;
}

</style>
  <script type="text/JavaScript" language="javascript">
  function mbfieldchange(el)
  {
    var field = el.id.split('_');
    var value = el.value;
    if($('#'+el.id).is(':checkbox')){
      if($('#'+el.id).prop('checked')){
        value=1;
      }else{
        value=0;
      }
    }

    $.ajax({
      url: 'index.php?module=massenbearbeitung&action=edit&cmd=aktualisieren',
      type: 'POST',
      dataType: 'json',
      data: {
        id: field[field.length-1],
        feld: field.splice(0,field.length-1).join('_'),
        wert: value,
        typ: '[MBTYP]'
      },
      success: function(data) {
      },
      beforeSend: function() {

      }
    });

  };
function uebernahmeprimaerfeld(){
  var liste = $('#liste').val().split(";");
  var felder = $('#felder').val().split(";");

  for (var i = 0; i < felder.length; i++) {
    if($('#'+felder[i]+'_uebernehmen').prop('checked')){
      var value = '';
      if($('#'+felder[i]+'_'+liste[0]).is(':checkbox')){

        for (var j = 0; j < liste.length; j++) {
          if($('#'+felder[i]+'_'+liste[0]).prop('checked')){
            $('#'+felder[i]+'_'+liste[j]).prop('checked', true);
            value = 1;
          }else{
            $('#'+felder[i]+'_'+liste[j]).prop('checked', false);
            value = 0;
          }
        }
      }else{
        value = $('#'+felder[i]+'_'+liste[0]).val();
        for (var j = 0; j < liste.length; j++) {
          $('#'+felder[i]+'_'+liste[j]).val($('#'+felder[i]+'_'+liste[0]).val());
        }
      }

      $.ajax({
        url: 'index.php?module=massenbearbeitung&action=edit&cmd=alleaktualisieren',
        type: 'POST',
        dataType: 'json',
        data: {
          feld: felder[i],
          wert: value,
          typ: '[MBTYP]'
        },
        success: function(data) {
        },
        beforeSend: function() {

        }
      });
    }
  }


}


function aktionausfuehren()
{
  var auswahl = $('#sel_aktion').val();
  switch(auswahl)
  {
    case "uebernahmeprimaerfeld": uebernahmeprimaerfeld(); break;
  }
}


$(document).ready(function() {
  [MBSCRIPT]

  CKEDITOR.on('instanceReady', function(){
     $.each( CKEDITOR.instances, function(instance) {
      CKEDITOR.instances[instance].on("change", function(e) {
        for ( instance in CKEDITOR.instances )
          CKEDITOR.instances[instance].updateElement();
      });
    });
  });
  window.parent.$('#massenedit').parent().loadingOverlay('remove');
});
    
</script>
<form method="POST" id="frmmassenbearbeitung"><input type="hidden" name="bearbeiten" value="1" />
<input type="hidden" name="liste" id="liste" value="[LISTE]" />
<input type="hidden" name="felder" id="felder" value="[FELDER]" />
[PARAMETER]
[MESSAGE]
[TABELLE]
[TAB1NEXT]
</form>






<fieldset><legend>{|Stapelverarbeitung|}</legend>
<select id="sel_aktion" name="sel_aktion">
<option value="">bitte w&auml;hlen ...</option>
<option value="uebernahmeprimaerfeld">{|Werte mit Markierung aus Zeile 1 für alle Artikel übernehmen|}</option>
</select>&nbsp;<input type="button" class="btnBlue" onclick="aktionausfuehren();" name="ausfuehren" value="{|ausf&uuml;hren|}" />
</fieldset>
