
<div id="tabs">
    <ul>
        <li><a href="#tabs-1"></a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">

<div id="artikeledit" style="display:none" title="Artikeleinstellungen">
  <div class="row">
    <div class="row-height">
      <div class="col-xs-12 col-md-10 col-md-height">
        <div class="inside inside-full-height">
          <input type="hidden" name="csid" id="csid" value="0">
          <input type="hidden" name="artikelid" id="artikelid" value="[ARTIKELID]">
          <fieldset>
            <legend>{|Einstellungen|}</legend>
            <table>
              <tr [ARTIKELZEILE]>
                <td width="150">
                  {|Artikel|}:
                </td>
                <td>
                  <input type="text" name="artikel" id="artikel" size="40"/>
                </td>
              </tr>
              <tr>
                <td>
                  {|Crossselling Artikel|}:
                </td>
                <td>
                  <input type="text" name="crosssellingartikel" id="crosssellingartikel" size="40" />
                </td>
              </tr>
              <tr>
                <td>
                  {|Shop|}:
                </td>
                <td>
                  <input type="text" name="shop" id="shop" size="40" />
                </td>
                <td>
                  <small><i>(Optional)</i></small>
                </td>
              </tr>
              <tr>
                <td>
                  {|Art|}:
                </td>
                <td>
                  <select name="art" id="art">
                    <option value="1">Ähnliches Produkt</option>
                    <option value="2">Zubehör</option>
                  </select>
                </td>
                <td>
                  <small><i>(Sofern vom Shop/Importer unterstützt)</i></small>
                </td>
              </tr>
              <tr>
                <td>
                  {|Reihenfolge|}:
                </td>
                <td>
                  <input type="text" name="reihenfolge" id="reihenfolge" size="40" />
                </td>
                <td>
                  <small><i>(0 für automatisch)</i></small>
                </td>
              </tr>
              <tr>
                <td>
                  {|Bemerkung|}:
                </td>
                <td>
                  <textarea cols="38" rows="5" id="bemerkung" name="bemerkung"></textarea>
                </td>
              </tr>
              <tr>
                <td>
                  {|Gegenseitig zuweisen|}:
                </td>
                <td>
                  <input type="checkbox" name="gegenseitigzuweisen" id="gegenseitigzuweisen" />
                </td>
              </tr>
              <tr>
                <td>
                  {|Aktiv|}:
                </td>
                <td>
                  <input type="checkbox" name="aktiv" id="aktiv" />
                </td>
              </tr>
            </table>
          </fieldset>
        </div>
      </div>
    </div>
  </div>
</div>


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
      <input class="btnGreenNew" type="button" name="neuedit" value="&#10010; Neuen Artikel eintragen" onclick="neuedit(0);">
    </fieldset>

  </div>
  </div>
  </div>
  </div>

  [TAB1NEXT]
</div>


<!-- tab view schließen -->
</div>

<script>
  oMoreData1crossselling_list = [ARTIKELID];
$(document).ready(function() {
  $("#artikeledit").dialog({
  modal: true,
  bgiframe: true,
  closeOnEscape:false,
  minWidth: 750,
  autoOpen: false,
  buttons: {
    ABBRECHEN: function() {
      $(this).dialog('close');
    },
    SPEICHERN: function() {
      artikelspeichern();
      updateLiveTable();
    }
  }
  });

  $("#artikeledit").dialog({
    close: function( event, ui ){}
  });
});


  function neuedit(nr)
  {
    if(nr == 0){
      document.getElementById("csid").value = '0';
      document.getElementById('artikel').value = '';
      document.getElementById('crosssellingartikel').value = '';
      document.getElementById('shop').value = '';
      document.getElementById('bemerkung').value = '';
      document.getElementById('reihenfolge').value = '0';
      document.getElementById('art').value = '1';
      $('#aktiv').prop("checked",true);
      $('#gegenseitigzuweisen').prop("checked",false);
      $("#artikeledit").dialog('open');
    }else{
      $.ajax({
        url: 'index.php?module=crossselling&action=list&cmd=editartikel&id='+nr,
        type: 'POST',
        dataType: 'json',
        data: {},
        success: function(data) {
          document.getElementById("csid").value = data.csid;
          document.getElementById('artikel').value = data.artikel;
          document.getElementById('crosssellingartikel').value = data.crosssellingartikel;
          document.getElementById('shop').value = data.shop;
          document.getElementById('bemerkung').value = data.bemerkung;
          document.getElementById('art').value = data.art;
          document.getElementById('reihenfolge').value = data.sort;
          $('#aktiv').prop("checked",data.aktiv==1?true:false);
          $('#gegenseitigzuweisen').prop("checked",data.gegenseitigzuweisen==1?true:false);
          $('#artikeledit').dialog('open');
        },
        beforeSend: function() {

        }
      });
    } 
  }



  function artikelspeichern() {
    $.ajax({
      url: 'index.php?module=crossselling&action=list&cmd=artikelspeichern',
      type: 'POST',
      dataType: 'json',
      data: {
        csid: $('#csid').val(),
        artikel: $('#artikel').val(),
        artikelid: $('#artikelid').val(),
        crosssellingartikel: $('#crosssellingartikel').val(),
        shop: $('#shop').val(),
        art: $('#art').val(),
        bemerkung: $('#bemerkung').val(),
        sort: $('#reihenfolge').val(),
        aktiv: $('#aktiv').prop("checked")?1:0,
        gegenseitigzuweisen: $('#gegenseitigzuweisen').prop("checked")?1:0
      },
      success: function(data) {
        if(data == 'success'){
          $("#artikeledit").dialog('close'); 
          updateLiveTable();
        }else{
          alert(data);
        }
       },
      beforeSend: function() {

      }
    });
  }


  function deleteeintrag(nr)
  {
    if(!confirm("Soll der Eintrag wirklich entfernt werden?")) return false;
    $.ajax({
        url: 'index.php?module=crossselling&action=list&cmd=delete&id='+nr,
        data: { 
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
        },
        success: function(data) {
          updateLiveTable();
        }
    });
  }

  function updateLiveTable() {
      var oTableL = $('#crossselling_list').dataTable();
      oTableL.fnFilter('%');
      oTableL.fnFilter('');
  }
</script>
