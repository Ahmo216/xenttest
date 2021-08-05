<!-- gehort zu tabview -->
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">[TABTEXT]</a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
  [MESSAGE]

    <!--<fieldset>
      <legend>Filter</legend>
      <table>
        <tr>
          <td>Projekt:</td><td width="200"><input type="text" name="fprojekt" id="fprojekt"></td>
          <td>Adresse:</td><td><input type="text" name="fadresse" id="fadresse"></td>
        </tr>
      </table>
    </fieldset>-->

  	<!--<center><input style="width:20em" type="button" name="anlegen" value="Neuen Eintrag anlegen" onclick="KopiebelegempfaengerEdit(0);"></center>-->
  [TAB1]
  [TAB1NEXT]
</div>

<!-- tab view schließen -->
</div>

<div id="editKopiebelegempfaenger" style="display:none;" title="Bearbeiten">
  <div class="row">
    <div class="row-height">
      <div class="col-xs-12 col-md-12 col-md-height">
        <div class="inside inside-full-height">
          <form method="post">
            <input type="hidden" id="e_id">
            <fieldset>
              <legend>Auswahl</legend>
              <table>
                <tr>
                  <td width="120">Belegtyp:</td><td><select name="e_belegtyp" id="e_belegtyp">
                                          <option value="angebot">Angebot</option>
                                          <option value="auftrag">Auftrag</option>
                                          <option value="rechnung">Rechnung</option>
                                          <option value="gutschrift">Gutschrift</option>
                                          <option value="lieferschein">Lieferschein</option>
                                          <option value="proformarechnung">Proformarechnung</option>
                                        </select></td>
                </tr>
                <tr>
                  <td>Art:</td><td><select name="e_art" id="e_art">
                                      <option value="email">E-Mail</option>
                                      <option value="drucker">Drucker</option>
                                    </select>
                  </td>
                </tr>
                <tr id="hautoversand">
                  <td>Nur bei Autoversand:</td><td><input type="checkbox" name="e_autoversand" id="e_autoversand" value="1"></td>
                </tr>
                <tr id="hempfaengeremail">
                  <td>Empf&auml;nger E-Mail:</td><td><input type="text" name="e_empfaengeremail" id="e_empfaengeremail" size="40"></td>
                </tr>
                <tr id="hempfaengername">
                  <td>Empf&auml;nger Name:</td><td><input type="text" name="e_empfaengername" id="e_empfaengername" size="40"></td>
                </tr>
                <tr id="hdrucker">
                  <td>Drucker:</td><td><select name="e_drucker" id="e_drucker">
                                          [DRUCKER]
                                        </select></td>
                </tr>
                <tr id="hanzahlausdrucke">
                  <td>Anzahl Ausdrucke:</td><td><input type="text" name="e_anzahlausdrucke" id="e_anzahlausdrucke" size="5"></td>
                </tr>
                <tr>
                  <td>Aktiv:</td><td><input type="checkbox" name="e_aktiv" id="e_aktiv" value="1"></td>
                </tr>
              </table>
            </fieldset>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="row-height">
      <div class="col-xs-12 col-md-12 col-md-height">
        <div class="inside inside-full-height">
          <fieldset>
            <legend>Filter</legend>
            <table>
              <tr>
                <td width="120">Projekt:</td><td><input type="text" name="e_projekt" id="e_projekt" size="40"></td>
              </tr>
              <tr>
                <td>Adresse:</td><td><input type="text" name="e_adresse" id="e_adresse" size="40"></td>
              </tr>
            </table>
          </fieldset>
        </div>
      </div>
    </div>
  </div>
</form>
 
</div>



<script type="text/javascript">

$(document).ready(function(){
    $('#e_belegtyp').focus();

    $("#editKopiebelegempfaenger").dialog({
    modal: true,
    bgiframe: true,
    closeOnEscape:false,
    minWidth:650,
    maxHeight:700,
    autoOpen: false,
    buttons: {
      ABBRECHEN: function() {
        kopiebelegempfaengerReset();
        $(this).dialog('close');
      },
      SPEICHERN: function() {
        KopiebelegempfaengerEditSave();
      }
    }
  });

    $("#editKopiebelegempfaenger").dialog({

  close: function( event, ui ) { kopiebelegempfaengerReset();}
});

});


function kopiebelegempfaengerReset()
{
  $('#editKopiebelegempfaenger').find('#e_id').val('');  
  $('#editKopiebelegempfaenger').find('#e_belegtyp').val('angebot');
  $('#editKopiebelegempfaenger').find('#e_art').val('email');
  $('#editKopiebelegempfaenger').find('#e_empfaengeremail').val('');
  $('#editKopiebelegempfaenger').find('#e_empfaengername').val('');
  $('#editKopiebelegempfaenger').find('#e_drucker').val('');
  $('#editKopiebelegempfaenger').find('#e_anzahlausdrucke').val('');
  $('#editKopiebelegempfaenger').find('#e_projekt').val('');
  $('#editKopiebelegempfaenger').find('#e_adresse').val('');
  $('#editKopiebelegempfaenger').find('#e_aktiv').prop("checked",true);
  $('#editKopiebelegempfaenger').find('#e_autoversand').prop("checked",false);
}

function KopiebelegempfaengerEditSave(){
	$.ajax({
        url: 'index.php?module=kopiebelegempfaenger&action=save',
        data: {
            //Alle Felder die fürs editieren vorhanden sind
            id: $('#e_id').val(),            
            belegtyp: $('#e_belegtyp').val(),
            art: $('#e_art').val(),
            empfaengeremail: $('#e_empfaengeremail').val(),
            empfaengername: $('#e_empfaengername').val(),
            drucker: $('#e_drucker').val(),
            anzahlausdrucke: $('#e_anzahlausdrucke').val(),
            projekt: $('#e_projekt').val(),
            adresse: $('#e_adresse').val(),
            aktiv: $('#e_aktiv').prop("checked")?1:0,
            autoversand: $('#e_autoversand').prop("checked")?1:0
            
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function(){
            App.loading.open();
        },
        success: function(data){
        	App.loading.close();
            if(data.status == 1){
                kopiebelegempfaengerReset();
                updateLiveTable();
                $("#editKopiebelegempfaenger").dialog('close');
            }else{
                alert(data.statusText);
            }
        }
    });


}

$("#e_art").change(function(){ 
  if($(this).val() == "email"){
    $("#hempfaengeremail").show();
    $("#hempfaengername").show();
    $("#hautoversand").show();
    
    $("#hdrucker").hide();
    $("#hanzahlausdrucke").hide();
  }else if($(this).val() == "drucker"){
    $("#hempfaengeremail").hide();
    $("#hempfaengername").hide();
    $("#hautoversand").hide();
    $("#hdrucker").show();
    $("#hanzahlausdrucke").show();
  }

});

function KopiebelegempfaengerEdit(id){
  $('#hempfaengeremail').hide();
  $('#hempfaengername').hide();
  $("#hautoversand").hide();
  $('#hdrucker').hide();
  $('#hanzahlausdrucke').hide();
  $("#e_art").trigger('change');
  if(id > 0){ 
    $.ajax({
      url: 'index.php?module=kopiebelegempfaenger&action=edit&cmd=get',
      data: {
          id: id
      },
      method: 'post',
      dataType: 'json',
      beforeSend: function(){
      	App.loading.open();
      },
      success: function(data){
        if(data.id > 0)
        {
          $('#editKopiebelegempfaenger').find('#e_id').val(data.id);            
          //$('#editKopiebelegempfaenger').find('#e_belegtyp').val(data.belegtyp);
          //$('#editKopiebelegempfaenger').find('#e_art').val(data.art);
          $('#editKopiebelegempfaenger').find('#e_empfaengeremail').val(data.empfaenger_email);
          $('#editKopiebelegempfaenger').find('#e_empfaengername').val(data.empfaenger_name);
          $('#editKopiebelegempfaenger').find('#e_drucker').val(data.drucker);
          $('#editKopiebelegempfaenger').find('#e_anzahlausdrucke').val(data.anzahl_ausdrucke);
          $('#editKopiebelegempfaenger').find('#e_projekt').val(data.projekt);
          $('#editKopiebelegempfaenger').find('#e_adresse').val(data.adresse);
          $('#editKopiebelegempfaenger').find('#e_aktiv').prop("checked", data.aktiv==1?true:false);
          $('#editKopiebelegempfaenger').find('#e_autoversand').prop("checked", data.autoversand==1?true:false);
          if(data.belegtyp == "" || data.belegtyp <=0 ){
            $('#editKopiebelegempfaenger').find('#e_belegtyp').val('angebot');            
          }else{ 
            $('#editKopiebelegempfaenger').find('#e_belegtyp').val(data.belegtyp);            
          }

          if(data.art == "" || data.art <= 0){
            $('#editKopiebelegempfaenger').find('#e_art').val('email');
            $('#hempfaengeremail').show();
            $('#hempfaengername').show();
          }else{
            $('#editKopiebelegempfaenger').find('#e_art').val(data.art);
            if(data.art == "email"){
              $('#hempfaengeremail').show();
              $('#hempfaengername').show();
            }else if(data.art == "drucker"){
              $('#hdrucker').show();
              $('#hanzahlausdrucke').show();
            }
          }
        }else{
          $('#editKopiebelegempfaenger').find('#e_belegtyp').val('angebot');
          $('#editKopiebelegempfaenger').find('#e_art').val('email');
          $('#hempfaengeremail').show();
          $('#hempfaengername').show();
        }
        App.loading.close();
        $("#editKopiebelegempfaenger").dialog('open');
      }
    });
  }else{
    kopiebelegempfaengerReset(); 
    $("#editKopiebelegempfaenger").dialog('open');
    $('#editKopiebelegempfaenger').find('#e_belegtyp').val('angebot');
    $('#editKopiebelegempfaenger').find('#e_art').val('email');
    $('#hempfaengeremail').show();
    $('#hempfaengername').show();
  }

}

function updateLiveTable(i){
  var oTableL = $('#kopiebelegempfaenger_list').dataTable();
  oTableL.fnFilter('%');
  oTableL.fnFilter('');   
}

function KopiebelegempfaengerDelete(id){
  var conf = confirm('Wirklich löschen?');
  if(conf){
    $.ajax({
      url: 'index.php?module=kopiebelegempfaenger&action=delete',
      data: {
          id: id
      },
      method: 'post',
      dataType: 'json',
      beforeSend: function(){
        App.loading.open();
      },
      success: function(data){
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


