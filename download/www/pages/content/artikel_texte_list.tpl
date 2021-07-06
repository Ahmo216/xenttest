<!-- gehort zu tabview -->
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">[TABTEXT]</a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
  [MESSAGE]

  <div class="row">
  <div class="row-height">
  <div class="col-xs-12 col-md-10 col-md-height">
  <div class="inside_white inside-full-height">

    <fieldset class="white">
      <legend>&nbsp;</legend>
      [TAB1]
    </fieldset>
  	
  </div>
  </div>
  <div class="col-xs-12 col-md-2 col-md-height">
  <div class="inside inside-full-height">

    <fieldset>
      <legend>{|Aktionen|}</legend>
      <input class="btnGreenNew" type="button" name="anlegen" value="&#10010; Neuen Text anlegen" onclick="ArtikeltexteEdit(0);">
    </fieldset>
  
  </div>
  </div>
  </div>
  </div>


  [TAB1NEXT]


  
</div>

<!-- tab view schließen -->
</div>

<div id="editArtikeltexte" style="display:none;" title="Bearbeiten">
<form method="post">
  <input type="hidden" id="e_id">
  <input type="hidden" name = "e_artikelid" id="e_artikelid" value="[ID]">
  <fieldset>
  	<legend>{|Beschreibung|}</legend>
  	<table>
      <tr>
        <td>Aktiv:<input type="checkbox" name="e_aktiv" id="e_aktiv"></td>
      </tr>
      <tr>
        <td>Sprache:<br /><select name="e_sprache" id="e_sprache">
        					          [SPRACHE]
             			        </select>
        </td>
      </tr>
      <tr>
        <td>Shop:<br /><input type="text" name="e_shop" id="e_shop" size="70"></td>
      </tr>   
      <tr>
        <td>Artikel:<br /><input type="text" name="e_name" id="e_name" size="70" maxlength="255"></textarea></td>
      </tr>
      <tr>
        <td>Kurztext:<br /><textarea rows="2" cols="70" name="e_kurztext" id="e_kurztext"></textarea></td>
      </tr>
      <tr>
        <td nowrap>Artikelbeschreibung:<br /><i>f&uuml;r Angebote, Auftr&auml;ge, etc.</i><br /><textarea rows="5" cols="70" name="e_beschreibung" id="e_beschreibung"></textarea></td>
      </tr>
    </table>
  </fieldset>

  <fieldset>
  	<legend>Online-Shop Texte</legend>
  	<table>
      <tr>
        <td>Artikelbeschreibung:<br /><textarea rows="2" cols="25" name="e_beschreibung_online" id="e_beschreibung_online"></textarea></td>
      </tr>
      <tr>
        <td>Meta-Title:<br /><textarea rows="1" cols="70" name="e_meta_title" id="e_meta_title"></textarea></td>
      </tr>
      <tr>
        <td>Meta-Description:<br /><textarea rows="2" cols="70" name="e_meta_description" id="e_meta_description"></textarea></td>
      </tr>
      <tr>
        <td>Meta-Keywords:<br /><textarea rows="2" cols="70" name="e_meta_keywords" id="e_meta_keywords"></textarea></td>
      </tr>
  	</table>
  </fieldset>
  
  <fieldset>
  	<legend>{|Katalog|}</legend>
  	<table>
  	  <tr>
  	  	<td>Katalogartikel:<input type="checkbox" name="e_katalogartikel" id="e_katalogartikel"></td>
  	  </tr>
	  <tr>
        <td>Bezeichnung:<br /><input type="text" size="70" name="e_katalog_bezeichnung" id="e_katalog_bezeichnung"></td>
      </tr>
      <tr>
        <td>Katalogtext:<br /><textarea rows="6" cols="70" name="e_katalog_text" id="e_katalog_text"></textarea></td>
      </tr>
    </table>  
  </fieldset>    
  
</div>


</form>
<script type="text/javascript">

$(document).ready(function() {
    $('#e_aktiv').focus();

    $("#editArtikeltexte").dialog({
    modal: true,
    bgiframe: true,
    closeOnEscape:false,
    minWidth:650,
    maxHeight:700,
    autoOpen: false,
    buttons: {
      ABBRECHEN: function() {
        artikeltexteReset();
        $(this).dialog('close');
      },
      SPEICHERN: function() {
        artikeltexteEditSave();
      }
    }
  });

    $("#editArtikeltexte").dialog({

  close: function( event, ui ) { artikeltexteReset();}
});

});


function artikeltexteReset()
{
  $('#editArtikeltexte').find('#e_id').val('');
  $('#editArtikeltexte').find('#e_aktiv').prop("checked",true);
  //$('#editArtikeltexte').find('#e_sprache option[value=DE]').attr('selected','selected');
  $('#editArtikeltexte').find('#e_sprache').val('DE');
  $('#editArtikeltexte').find('#e_name').val('');
  $('#editArtikeltexte').find('#e_kurztext').val('');
  $('#editArtikeltexte').find('#e_beschreibung').val('');
  $('#editArtikeltexte').find('#e_beschreibung_online').val('');
  $('#editArtikeltexte').find('#e_meta_title').val('');
  $('#editArtikeltexte').find('#e_meta_description').val('');
  $('#editArtikeltexte').find('#e_meta_keywords').val('');
  $('#editArtikeltexte').find('#e_katalogartikel').prop("checked",false);
  $('#editArtikeltexte').find('#e_katalog_bezeichnung').val('');
  $('#editArtikeltexte').find('#e_katalog_text').val('');
  $('#editArtikeltexte').find('#e_shop').val('');
}

function artikeltexteEditSave() {
	$.ajax({
        url: 'index.php?module=artikel_texte&action=save',
        data: {
            //Alle Felder die fürs editieren vorhanden sind
            id: $('#e_id').val(),
            aktiv: $('#e_aktiv').prop("checked")?1:0,
            sprache: $('#e_sprache').val(),
            name: $('#e_name').val(),
            kurztext: $('#e_kurztext').val(),
            beschreibung: $('#e_beschreibung').val(),
            beschreibung_online: $('#e_beschreibung_online').val(),
            meta_title: $('#e_meta_title').val(),
            meta_description: $('#e_meta_description').val(),
            meta_keywords: $('#e_meta_keywords').val(),
            katalogartikel: $('#e_katalogartikel').prop("checked")?1:0,
            katalog_bezeichnung: $('#e_katalog_bezeichnung').val(),
            katalog_text: $('#e_katalog_text').val(),
            shop: $('#e_shop').val(),
            artikelid: $('#e_artikelid').val()
            
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            App.loading.open();
        },
        success: function(data) {
        	App.loading.close();
            if (data.status == 1) {
                artikeltexteReset();
                updateLiveTable();
                $("#editArtikeltexte").dialog('close');
            } else {
                alert(data.statusText);
            }
        }
    });


}

function ArtikeltexteEdit(id) {
    if(id > 0)
    { 
      $.ajax({
        url: 'index.php?module=artikel_texte&action=edit&cmd=get',
        data: {
            id: id
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
        	App.loading.open();
        },
        success: function(data) {
            if(data.id > 0)
            {
            $('#editArtikeltexte').find('#e_id').val(data.id);
            $('#editArtikeltexte').find('#e_aktiv').prop("checked", data.aktiv==1?true:false);
            $('#editArtikeltexte').find('#e_name').val(data.name);
            $('#editArtikeltexte').find('#e_kurztext').val(data.kurztext);
            $('#editArtikeltexte').find('#e_beschreibung').val(data.beschreibung);
            $('#editArtikeltexte').find('#e_beschreibung_online').val(data.beschreibung_online);
            $('#editArtikeltexte').find('#e_meta_title').val(data.meta_title);
            $('#editArtikeltexte').find('#e_meta_description').val(data.meta_description);
            $('#editArtikeltexte').find('#e_meta_keywords').val(data.meta_keywords);
            $('#editArtikeltexte').find('#e_katalogartikel').prop("checked",data.katalogartikel==1?true:false);
            $('#editArtikeltexte').find('#e_katalog_bezeichnung').val(data.katalog_bezeichnung);
            $('#editArtikeltexte').find('#e_katalog_text').val(data.katalog_text);
            $('#editArtikeltexte').find('#e_shop').val(data.shop);
            $('#editArtikeltexte').find('#e_artikelid').val([ID]);
            if(data.sprache=="" || data.sprache <=0 )
              $('#editArtikeltexte').find('#e_sprache').val('DE');
            else 
              $('#editArtikeltexte').find('#e_sprache').val(data.sprache);
            } else {
              $('#editArtikeltexte').find('#e_sprache').val('DE');
            }
            App.loading.close();
            $("#editArtikeltexte").dialog('open');
        }
    });
  } else {
    artikeltexteReset(); 
    $("#editArtikeltexte").dialog('open');
  }

}

function updateLiveTable(i) {
  var oTableL = $('#artikel_texte_list').dataTable();
  var tmp = $('.dataTables_filter input[type=search]').val();
  oTableL.fnFilter('%');
  //oTableL.fnFilter('');
  oTableL.fnFilter(tmp);   
}

function ArtikeltexteDelete(id) {

    var conf = confirm('Wirklich löschen?');
    if (conf) {
        $.ajax({
            url: 'index.php?module=artikel_texte&action=delete',
            data: {
                id: id
            },
            method: 'post',
            dataType: 'json',
            beforeSend: function() {
                App.loading.open();
            },
            success: function(data) {
                if (data.status == 1) {
                    updateLiveTable();
                } else {
                    alert(data.statusText);
                }
                App.loading.close();
            }
        });
    }

    return false;

}

</script>


