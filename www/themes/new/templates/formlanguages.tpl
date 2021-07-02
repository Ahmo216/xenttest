<div id="globepopup" style="display:none;"><span class="ueberschrift">{|&Uuml;bersetzungen|}</span>
  <button id="globeclosebutton" role="button" aria-disabled="false" title="close">
    <svg xmlns="http://www.w3.org/2000/svg" width="19px" height="19px" viewBox="0 0 401.68 401.66"><path fill="#333" d="M401.69 60.33L341.33 0 200.85 140.5 60.35 0 0 60.33l140.5 140.5L0 341.33l60.35 60.33 140.5-140.5 140.48 140.5 60.36-60.33-140.51-140.5 140.51-140.5z"></path></svg>
  </button>
  <fieldset><legend> </legend>
  <div id="globepopupcontent">
    <div id="globepopupparameterdiv"><input type="text" id="globepopupparameter" /></div>
    <br />
    
      [TABELLEPOPUPGLOBE]
    
  </div>
  </fieldset>
  <input type="button" class="btnGreen" value="{|Neuer Eintrag|}" onclick="Uebersetzungpopup_Edit(0);" />
</div>
<style>
#globeclosebutton {
    position: absolute;
    background: none;
    padding: 0;
    cursor: pointer;
    margin: 0;
    border: none;
    right: 10px;
    top: 10px;
    color:#333;
    height:25px;
    width:30px;
}
  
  div.globe 
  {
    position:relative;
    display:inline-block;
    top:8px;
  }
  
  div.globe > img {
    width:25px;
    height:25px;
    /*top:8px;*/
    cursor:pointer;
  }
  
  div.cke + div.globe 
  {
    display:table-cell;
    vertical-align:bottom;
    top:0;
  }
  
  span.beforeglobe + textarea + div.cke {
    width:100%;
    display:table-cell;
  }
  
  
  #globepopup
  {
    position:fixed;
    width:50vw;
    height:50vh;
    z-index:999;
    background-color:#fff;
    left:25vw;
    top:25vh;
    padding-bottom:10px;
    padding-top:10px;
  }
  
  #globepopup .btnGreen
  {
    float:right;
    margin-right:10px;
  }
  
  #globepopup > span.ueberschrift
  {
    font-size:14px;
    padding-left:10px;
    font-weight:bold;
  }
  
  #globepopupcontent{
    margin-top:20px;
    width:100%;
    
  }
  
  #globepopup fieldset
  {
    margin-top:15px;
    margin-left:10px;
    margin-right:10px;
    height:calc(50vh - 80px);
    overflow:auto;
    padding-bottom:20px;
  }
  
  #globepopupparameterdiv
  {
    overflow:hidden;
    width:0;
    height:0;
    padding:0;
    margin:0;
  }
</style>


<div id="editUebersetzungpopup" style="display:none;" title="Bearbeiten">
  <fieldset>
    <legend>&nbsp;</legend>
    <input type="hidden" id="editidpopup" />
    <table>
      <tr>
        <td>{|Sprache|}:</td>
        <td><select name="editsprachepopup" id="editsprachepopup">
            [SELUEBERSETZUNGSPRACHE]
            </select>
        </td>        
      </tr>
      <tr>
        <td>{|&Uuml;bersetzung|}:</td>
        <td><textarea name="edituebersetzungpopup" id="edituebersetzungpopup" cols="80" rows="20"></textarea></td>
      </tr>
<!--
      <tr>
        <td width="180">{|Text aus Standardsprache|}:</td>
        <td><textarea name="editstandardpopup" id="editstandardpopup" cols="50" rows="7"></textarea></td>
      </tr>
-->
    </table>
  </fieldset>      
</div>

<script type="text/javascript">

$(document).ready(function() {
    $('#editvar').trigger('focus');

    $("#editUebersetzungpopup").dialog({
    modal: true,
    bgiframe: true,
    closeOnEscape:false,
    minWidth:750,
    autoOpen: false,
    buttons: {
      '{|ABBRECHEN|}': function() {
        Uebersetzungpopup_Reset();
        $(this).dialog('close');
      },
      '{|SPEICHERN|}': function() {
        Uebersetzungpopup_EditSave();
      }
    }
  });

  $("#editUebersetzungpopup").dialog({
    close: function( event, ui ) { Uebersetzungpopup_Reset();}
  });

});

function Uebersetzungpopup_Reset(){
  $('#editUebersetzungpopup').find('#editidpopup').val('');
  $('#editUebersetzungpopup').find('#editsprachepopup').val('');
  $('#editUebersetzungpopup').find('#edituebersetzungpopup').val('');
  //$('#editUebersetzungpopup').find('#editstandardpopup').val('');
}

function Uebersetzungpopup_EditSave() {

    $.ajax({
        url: 'index.php?module=uebersetzung&action=save',
        data: {
            //Alle Felder die fürs editieren vorhanden sind
            editid: $('#editidpopup').val(),
            editvar: $('#globepopupparameter').val(),
            editsprache: $('#editsprachepopup').val(),
            edituebersetzung: $('#edituebersetzungpopup').val()
//            editstandard: $('#editstandardpopup').val()            
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            App.loading.open();
        },
        success: function(data) {
            App.loading.close();
            if (data.status == 1) {
                $('#editUebersetzungpopup').find('#editidpopup').val('');
                $('#editUebersetzungpopup').find('#editsprachepopup').val('');
                $('#editUebersetzungpopup').find('#edituebersetzungpopup').val('');
//                $('#editUebersetzungpopup').find('#editstandardpopup').val('');
                Uebersetzungpopup_Reset();
                var oTable = $('#uebersetzung_popup').DataTable( );
                oTable.ajax.reload();
                $("#editUebersetzungpopup").dialog('close');
            } else {
                alert(data.statusText);
            }
        }
    });

}

function Uebersetzungpopup_Edit(id) {
  if(id > 0)
  { 
    $.ajax({
        url: 'index.php?module=uebersetzung&action=edit&cmd=get',
        data: {
            id: id
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            App.loading.open();
        },
        success: function(data) {
            $('#editUebersetzungpopup').find('#editidpopup').val(data.id);
            $('#editUebersetzungpopup').find('#editsprachepopup').val(data.sprache);
            $('#editUebersetzungpopup').find('#edituebersetzungpopup').val(data.beschriftung);
//            $('#editUebersetzungpopup').find('#editstandardpopup').val(data.original);
            App.loading.close();
            $("#editUebersetzungpopup").dialog('open');
        }
    });
  }else{
    Uebersetzungpopup_Reset(); 
    $("#editUebersetzungpopup").dialog('open');
  }

}

function Uebersetzungpopup_Delete(id) {

    var conf = confirm('Wirklich löschen?');
    if (conf) {
        $.ajax({ 
            url: 'index.php?module=uebersetzung&action=delete',
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
                  var oTable = $('#uebersetzung_popup').DataTable( );
                  oTable.ajax.reload();
                } else {
                    alert(data.statusText);
                }
                App.loading.close();
            }
        });
    }

    return false;

}

  var globetimeout = null;
  
  function clearGlobe()
  {
    if(globetimeout)clearTimeout(globetimeout);
    $('#globepopup').hide();
  }
  
  function openGlobe(el)
  {
    if(globetimeout)clearTimeout(globetimeout);
    $('#globepopupparameter').val($(el).data('lang'));
    var oTable = $('#uebersetzung_popup').DataTable( );
    oTable.ajax.reload();
    var offsetglobe = el.offset();
    $('#globepopupparameter').val($(el).data('lang'));
    $('#globepopupparameter').trigger('change');
    $('#globepopup').show();
    //$('#globepopup').css('top',offsetglobe.top > 200? (offsetglobe.top- 20):180);
    //$('#globepopup').css('left', offsetglobe.left > 150? (offsetglobe.left -100):50);
  }
  
  $(document).ready(function() {
    $('[data-lang]').each(function(){
      $(this).before('<span class="beforeglobe"></span>');
      $(this).after('<div class="globe"><img src="themes/new/images/web.png" /></div>');
    });
    $(document).on('click', '.globe', function(){
      var el = $(this).prev();
      if($(el).data() && $(el).data('lang'))
      {
        openGlobe(el);
      }else{
        if($(el).hasClass('cke'))
        {
          el = $(el).prev();
          if($(el).data() && $(el).data('lang'))openGlobe(el);
        }
      }
    });
    /*
    $('.globe').on('mouseover',function(){
      var el = $(this).prev();
      if($(el).data() && $(el).data('lang'))openGlobe(el);
    });
    $('.globe').on('mouseleave',function(){
      if(globetimeout)clearTimeout(globetimeout);
      globetimeout = setTimeout(function(){clearGlobe();},2000);
    });
  
    $('#globepopup').on('mouseover',function(){
      if(globetimeout)clearTimeout(globetimeout);
    });
  
    $('#globepopup').on('mouseleave',function(){
      if(globetimeout)clearTimeout(globetimeout);
      globetimeout = setTimeout(function(){clearGlobe();},2000);
    });*/
    $('#globeclosebutton').on('click',function(){
      $('#globepopup').hide();    
      if(globetimeout)clearTimeout(globetimeout);
    });
  
  });
</script>
