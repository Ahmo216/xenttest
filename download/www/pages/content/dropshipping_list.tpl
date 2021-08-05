<!-- gehort zu tabview -->

<div id="tabs">
  <ul>
    <li><a href="#tabs-1"></a></li>
  </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
  [MESSAGE]

  <div id="editArtikel" style="display:none;" title="Bearbeiten">
    <div class="row">
      <div class="row-height">
        <div class="col-xs-12 col-md-12 col-md-height">
          <div class="inside inside-full-height">
            <fieldset>
              <legend>{|Dropshipping|}</legend>
              <input type="hidden" id="editid">
              <table>
                <tr>
                  <td width="50">{|Gruppe|}:</td>
                  <td><input type="text" name="editgruppe" id="editgruppe" size="40"></td>
                </tr>
                <tr>
                  <td>{|Artikel|}:</td>
                  <td><input type="text" name="editartikel" id="editartikel" size="40"></td>
                </tr>
              </table>
            </fieldset>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div id="editAssistent" style="display:none;" title="Bearbeiten">
    <div class="row">
      <div class="row-height">
        <div class="col-xs-12 col-md-12 col-md-height">
          <div class="inside inside-full-height">
            <form action="" method="post" name="eprooform" >
              <input type="hidden" id="assistendid">
              <fieldset>
                <legend>{|Assistent|}</legend>
                <table>
                  <tr>
                    <td><input type="radio" name="gruppenname_hersteller" id="gruppenname_hersteller" checked></td>
                    <td>{|Gruppenname = Hersteller<br /><i>Alle Artikel verknüpfen, deren Hersteller-Feld im Artikel mit einem Dropshipping-Gruppennamen übereinstimmen.</i>|}</td>
                  </tr>
                </table>
              </fieldset>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="row">
  <div class="row-height">
  <div class="col-xs-12 col-md-10 col-md-height">
  <div class="inside-white inside-full-height">
    [TAB1]
  </div>
  </div>
  <div class="col-xs-12 col-md-2 col-md-height">
  <div class="inside inside-full-height">
    <fieldset>
      <legend>{|Aktionen|}</legend>
      <input class="btnGreenNew" type="button" name="anlegen" value="&#10010; Neuer Eintrag" onclick="DropshippingArtikelEdit(0);">
      <input class="btnBlueNew" type="button" name="alleloeschen" id="alleloeschen" value="Alle Einträge l&ouml;schen" onclick="DropshippingDeleteAll();">
      <input class="btnBlueNew" type="button" name="assistent" id="assistent" value="Assistent" onclick="DropshippingAssistent();">
    </fieldset>
  </div>
  </div>
  </div>
  </div>


</div>

<!-- tab view schließen -->
</div>


<script type='text/javascript'>

$(document).ready(function() {
    $('#editgruppe').focus();

    $("#editArtikel").dialog({
    modal: true,
    bgiframe: true,
    closeOnEscape:false,
    minWidth:420,
    autoOpen: false,
    buttons: {
      ABBRECHEN: function() {
        $(this).dialog('close');
      },
      SPEICHERN: function() {
        DropshippingArtikelEditSave();
      }
    }
  });


    $("#editAssistent").dialog({
      modal: true,
      bgiframe: true,
      closeOnEscape:false,
      minWidth:500,
      autoOpen: false,
      buttons: {
        ABBRECHEN: function() {
          $(this).dialog('close');
        },
        SPEICHERN: function() {
          DropshippingAssistentSave();
        }
      }
    });

});


function DropshippingArtikelEditSave() {
  
  $.ajax({
      url: 'index.php?module=dropshipping&action=artikelsave',
      data: {
          //Alle Felder die fürs editieren vorhanden sind
          id: $('#editid').val(),
          gruppe: $('#editgruppe').val(),
          artikel: $('#editartikel').val()           
      },
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
          App.loading.open();
      },
      success: function(data) {

          App.loading.close();
          if (data.status == 1) {
            $('#editArtikel').find('#editid').val('');
            $('#editArtikel').find('#editgruppe').val('');
            $('#editArtikel').find('#editartikel').val('');
              updateLiveTable();
              $("#editArtikel").dialog('close');
          } else {
              alert(data.statusText);
          }
      }
  });

}

function DropshippingArtikelEdit(id) {

  if(id > 0)
  {
    $.ajax({
        url: 'index.php?module=dropshipping&action=artikeledit&cmd=get',
        data: {
          id: id
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            App.loading.open();
        },
        success: function(data) {
            $('#editArtikel').find('#editid').val(data.id);
            $('#editArtikel').find('#editgruppe').val(data.gruppe);
            $('#editArtikel').find('#editartikel').val(data.artikel);
        
            App.loading.close();
            $("#editArtikel").dialog('open');
        }
    });

  } else {
    $("#editArtikel").dialog('open');
  }

}

function DropshippingAssistent(){
  $("#editAssistent").dialog('open');
}

function DropshippingAssistentSave(){
  $.ajax({
      url: 'index.php?module=dropshipping&action=artikelassistent',
      data: {
          //Alle Felder die fürs editieren vorhanden sind
          namehersteller: $('#gruppenname_hersteller').prop("checked")?1:0
      },
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
          App.loading.open();
      },
      success: function(data) {
          App.loading.close();
          if (data.status == 1) {
            $('#editAssistent').find('#gruppenname_hersteller').val('');
            updateLiveTable();
            $("#editAssistent").dialog('close');
          } else {
            alert(data.statusText);
          }
      }
  });
}

function DropshippingArtikelDelete(id) {

  var conf = confirm('Wirklich löschen?');
  if (conf) {
    $.ajax({
        url: 'index.php?module=dropshipping&action=artikeldelete',
        data: {
            id: id
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            App.loading.open();
        },
        success: function(data) {
            if(data.status == 1){
                updateLiveTable();
            }else{
                alert(data.statusText);
            }
            App.loading.close();
        }
    });
  }

    return false;

}

function updateLiveTable(i) {
    var oTableL = $('#dropshipping_list').dataTable();
    var tmp = $('.dataTables_filter input[type=search]').val();
    oTableL.fnFilter('%');
    //oTableL.fnFilter('');
    oTableL.fnFilter(tmp);  
}

function DropshippingDeleteAll(){
  var conf = confirm('Wollen Sie wirklich alle Artikelverknüpfungen für das Dropshipping komplett löschen?\nDiese Verknüpfungen lassen sich nicht wiederherstellen.');
  if (conf) {
    $.ajax({
        url: 'index.php?module=dropshipping&action=deleteall',
        data: {
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            App.loading.open();
        },
        success: function(data) {
            if(data.status == 1){
                updateLiveTable();
            }else{
                alert(data.statusText);
            }
            App.loading.close();
        }
    });
  }

    return false;
}




</script>

