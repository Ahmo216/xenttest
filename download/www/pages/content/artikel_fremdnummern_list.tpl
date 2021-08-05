<!-- gehort zu tabview -->
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">[TABTEXT]</a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
  [MESSAGE]
  <form method="post" id="article-external-number-form">
  <div class="filter-box filter-usersave">
    <div class="filter-block filter-inline">
      <div class="filter-title">{|Filter|}</div>
      <ul class="filter-list">
        <li class="filter-item">
          <label for="inaktiv" class="switch">
            <input type="checkbox" name="inaktiv" id="inaktiv">
            <span class="slider round"></span>
          </label>
          <label for="inaktiv">{|auch inaktive|}</label>
        </li>
      </ul>
    </div>
  </div>
  
  [TAB1]
  <fieldset>
    <legend>{|Stapelverarbeitung|}</legend>
    <table>
      <tr>
        <td><input type="checkbox" id="select-all" /></td><td><label for="select-all">{|Alle markieren|}</label></td>
        <td>
          <select id="selected-action" name="selected-action">
            <option value="">{|Bitte wählen...|}</option>
            <option value="activate">{|aktivieren|}</option>
            <option value="deactivate">{|deaktivieren|}</option>
            <option value="delete">{|löschen|}</option>
          </select>
        </td>
        <td>
          <input type="submit" value="{|ausführen|}" />
        </td>
      </tr>
    </table>
  </fieldset>
  </form>
  [TAB1NEXT]
</div>

<!-- tab view schließen -->
</div>

<div id="editArtikelFremdnummern" style="display:none;" title="Bearbeiten">
  <div class="row">
    <div class="row-height">
      <div class="col-xs-12 col-md-12 col-md-height">
        <div class="inside inside-full-height">
          <form method="post">
            <input type="hidden" id="e_id">
            <fieldset>
              <legend>{|Fremdnummer|}</legend>
              <table>
                <tr>
                  <td width="120">{|Artikel|}:</td><td><input type="text" name="e_artikel" id="e_artikel" size="40"></td>
                </tr>
                <tr>
                  <td>{|Fremdnummer|}:</td><td><input type="text" name="e_fremdnummer" id="e_fremdnummer" size="40"></td>
                </tr>
                <tr>
                  <td>{|Shop|}:</td><td><input type="text" name="e_shop" id="e_shop" size="40"></td>
                </tr>
                <tr>
                  <td>{|Barcodescanner|}:</td><td><input type="checkbox" name="e_scannable" id="e_scannable"></td>
                </tr>
                <tr>
                  <td>{|Aktiv|}:</td><td><input type="checkbox" name="e_aktiv" id="e_aktiv"></td>
                </tr>
              </table>
            </fieldset>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>



<script type="text/javascript">

$(document).ready(function() {
  $('#e_name').focus();

  $("#editArtikelFremdnummern").dialog({
    modal: true,
    bgiframe: true,
    closeOnEscape:false,
    minWidth:650,
    maxHeight:700,
    autoOpen: false,
    buttons: {
      ABBRECHEN: function() {
        artikelfremdnummernReset();
        $(this).dialog('close');
      },
      SPEICHERN: function() {
        ArtikelFremdnummernEditSave();
      }
    }
  });

    $("#editArtikelFremdnummern").dialog({

  close: function( event, ui ) { artikelfremdnummernReset();}
});

});


function artikelfremdnummernReset()
{
  $('#editArtikelFremdnummern').find('#e_id').val('');
  $('#editArtikelFremdnummern').find('#e_artikel').val('');
  $('#editArtikelFremdnummern').find('#e_fremdnummer').val('');
  $('#editArtikelFremdnummern').find('#e_shop').val('');
  $('#editArtikelFremdnummern').find('#e_aktiv').prop("checked",true);  
  $('#editArtikelFremdnummern').find('#e_scannable').prop("checked",true);  
}

function ArtikelFremdnummernEditSave() {
	$.ajax({
    url: 'index.php?module=artikel_fremdnummern&action=save',
    data: {
      //Alle Felder die fürs editieren vorhanden sind
      id: $('#e_id').val(),
      artikel: $('#e_artikel').val(),
      fremdnummer: $('#e_fremdnummer').val(),
      shop: $('#e_shop').val(),
      aktiv: $('#e_aktiv').prop("checked")?1:0,
      scannable: $('#e_scannable').prop("checked")?1:0,
                      
    },
    method: 'post',
    dataType: 'json',
    beforeSend: function() {
      App.loading.open();
    },
    success: function(data) {
     	App.loading.close();
      if (data.status == 1) {
        artikelfremdnummernReset();
        updateLiveTable();
        $("#editArtikelFremdnummern").dialog('close');
      } else {
        alert(data.statusText);
      }
    }
  });


}

function ArtikelFremdnummernEdit(id) {
  if(id > 0)
  { 
    $.ajax({
      url: 'index.php?module=artikel_fremdnummern&action=edit&cmd=get',
      data: {
        id: id
      },
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
      	App.loading.open();
      },
      success: function(data) {
        $('#editArtikelFremdnummern').find('#e_id').val(data.id);
        $('#editArtikelFremdnummern').find('#e_artikel').val(data.artikel);
        $('#editArtikelFremdnummern').find('#e_fremdnummer').val(data.fremdnummer);
        $('#editArtikelFremdnummern').find('#e_aktiv').prop("checked", data.aktiv==1?true:false);
        $('#editArtikelFremdnummern').find('#e_scannable').prop("checked", data.scannable==1?true:false);
        $('#editArtikelFremdnummern').find('#e_shop').val(data.shop);
        
        App.loading.close();
        $("#editArtikelFremdnummern").dialog('open');
      }
    });
  } else {
    artikelfremdnummernReset(); 
    $("#editArtikelFremdnummern").dialog('open');
  }

}

function updateLiveTable(i) {
  var oTableL = $('#artikel_fremdnummern_list').dataTable();
  var tmp = $('.dataTables_filter input[type=search]').val();
  oTableL.fnFilter('%');
  //oTableL.fnFilter('');
  oTableL.fnFilter(tmp);   
}

function ArtikelFremdnummernDelete(id) {
  var conf = confirm('Wirklich löschen?');
  if (conf) {
    $.ajax({
      url: 'index.php?module=artikel_fremdnummern&action=delete',
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


<script type="application/json" id="messages">
  [MESSAGESJSON]
</script>
