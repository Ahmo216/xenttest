<!-- gehort zu tabview -->
<div id="tabs">
    <ul>
        <li><a href="#tabs-1"></a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
[MESSAGE]
<div id="jsmessage"></div>
[TAB1]
</div>

<!-- tab view schließen -->
</div>
<div id="klaerfallpopup">
  <fieldset><legend>{|Kl&auml;rfall|}</legend>
    <table>
      <tr><td><label for="klaerfall">{|Kl&auml;rfall|}:</label></td><td><input type="hidden" value="" id="kid" /><input type="checkbox" value="1" id="klaerfall" name="klaerfall" /></td></tr>
      <tr><td><label for="klaergrund">{|Grund|}:</label></td><td><input type="text" size="45" id="klaergrund" name="klaergrund" /></td></tr>
    </table>
  </fieldset>
</div>
<style>
  #challengepopup {
    text-align: center;
  }

  #challengepopup div {
    padding: 5px;
  }

  #pdfvorschaudiv {
  position:absolute;
    z-index:1000;
    width:900px;
    height:700px;
    background-color:#fff;
  }
  #pdfiframe
  {
    height:650px;
  }
  
  .verbindlichkeitspan
  {
    white-space:nowrap;
  }
  
  .verbindlichkeitspan img
  {
    top:5px;
    position:relative;
  }
#pdfclosebutton {
    position: absolute;
    background: none;
    padding: 0;
    cursor: pointer;
    margin: 0;
    border: none;
    right: 10px;
    top: 10px;
    color:#333;
}
  #verbindlichkeiten_create > thead > tr > th:nth-child(9),
  #verbindlichkeiten_create > tfoot > tr > th:nth-child(9),
  #verbindlichkeiten_create > tbody > tr > td:nth-child(9),
  #verbindlichkeiten_create > thead > tr > th:nth-child(10),
  #verbindlichkeiten_create > tfoot > tr > th:nth-child(10),
  #verbindlichkeiten_create > tbody > tr > td:nth-child(10),
  #verbindlichkeiten_create > thead > tr > th:nth-child(11),
  #verbindlichkeiten_create > tfoot > tr > th:nth-child(11),
  #verbindlichkeiten_create > tbody > tr > td:nth-child(11),
  #verbindlichkeiten_create > thead > tr > th:nth-child(12),
  #verbindlichkeiten_create > tfoot > tr > th:nth-child(12),
  #verbindlichkeiten_create > tbody > tr > td:nth-child(12)
  {
    display:none;
  }
  
  </style>
<div id="pdfvorschaudiv" style="display:none;"><button id="pdfclosebutton" role="button" aria-disabled="false" title="close">
<svg xmlns="http://www.w3.org/2000/svg" width="19px" height="19px" viewBox="0 0 401.68 401.66"><path fill="#333" d="M401.69 60.33L341.33 0 200.85 140.5 60.35 0 0 60.33l140.5 140.5L0 341.33l60.35 60.33 140.5-140.5 140.48 140.5 60.36-60.33-140.51-140.5 140.51-140.5z"></path></svg>
</button>
  <iframe id="pdfiframe" src="" width="890;" style="border:none;margin-top:30px;margin-left:5px;" border=""></iframe>
</div>
<div id="addverbindlichkeitpopup">
  <table width="100%"><tr><td valign="top">
  [POPUPADDV]</td><td valign="top">[POPUPADDVTABELLE]</td></tr></table>
</div>
<script type="text/javascript">
  var pdfinterval = null;
  function pdfleave()
  {
    if(pdfinterval != null)clearTimeout(pdfinterval);
    pdfinterval = setInterval(function(){ 
      $('#pdfvorschaudiv').hide();
    
    },2000);
  }
  
  function loadverbindlichkeit(vid, dummy, el)
  {
    var tr = $(el).parents('tr')[1];
    var children = $(tr).children();
    $('#addverbindlichkeit_adresse').val($(children[2]).text()+' '+$(children[3]).text());
    $('#addverbindlichkeit_betrag').val($(children[7]).text());
    $('#addverbindlichkeit_rechnungsdatum').val($(children[4]).text());
    $('#addverbindlichkeit_rechnung').val($(children[5]).text());
    var verwendungszweck = $(children[6]).html();
    var ind = verwendungszweck.indexOf('<i>Grund');
    if(ind > -1)
    {
      var grund = $(verwendungszweck.substring(ind)).text();
      grund = grund.substring(6);
      $('#addverbindlichkeit_klaergrund').val(grund);
      verwendungszweck = $(verwendungszweck.substring(0,ind)).text();
      $('#addverbindlichkeit_klaerfall').prop('checked', true);
    }else{
      $('#addverbindlichkeit_klaerfall').prop('checked', false);
      $('#addverbindlichkeit_klaergrund').val('');
    }

    verwendungszweck = verwendungszweck.replace(/<\/?[^>]+(>|$)/g, "");
    $('#addverbindlichkeit_verwendungszweck').val(verwendungszweck);
  }

  function useverbindlichkeit(vid)
  {
   $('#verbindlichkeit_parameter'+$('#addverbindlichkeit_id').val()).val(vid);
    $('#addverbindlichkeitpopup').dialog('close');
  }
  
  function verbindlichkeitadd(el)
  {
    var tr = $('#verbindlichkeit_parameter'+el).parents('tr')[2];
    var soll = $(tr).children()[4];
    var waehrung = $(tr).children()[7];
    var verwendungszweck = $(tr).children()[2];
    $('#addverbindlichkeit_id').val(el);
    $('#addverbindlichkeit_adresse').val('');
    $('#addverbindlichkeit_rechnung').val('');
    $('#addverbindlichkeit_rechnungsdatum').val('');
    $('#addverbindlichkeit_eingangsdatum').val('');
    $('#addverbindlichkeit_betrag').val($(soll).html().replace('-',''));
    $('#addverbindlichkeit_waehrung').val($(waehrung).html());
    $('#addverbindlichkeit_verwendungszweck').val($(verwendungszweck).text());
    $('#addverbindlichkeitpopup').dialog('open');
    updateLiveTable();
  }

  function updateLiveTable() {
    var oTableL = $('#verbindlichkeiten_create').dataTable();
    var tmp = $('.dataTables_filter input[type=search]').val();
    oTableL.fnFilter('%');
    //oTableL.fnFilter('');
    oTableL.fnFilter(tmp);   
  }

  
  function editklaerfall(kontoauszugid)
  {
    $.ajax({
        url: 'index.php?module=zahlungseingang&action=import&cmd=getklaerfall&id=[ID]',
        type: 'POST',
        dataType: 'json',
        data: { kid: kontoauszugid},
        success: function(data) {
          $('#klaerfall').prop('checked', data.klaerfall == '1'?true:false);
          $('#klaergrund').val(data.klaergrund);
          $('#kid').val(data.id);
          $('#klaerfallpopup').dialog('open');
        },
        beforeSend: function() {
          
        }
    });
  }
  
  function pdfvorschau(el)
  {
    if(pdfinterval != null)clearTimeout(pdfinterval);
    var aktionel = $('select[name="aktion'+el+'"]');
    var pos = $('#pdf_'+el).parents('td').first().position();
    
    var aktion = $(aktionel).val();
    var parameter = '';
    switch(aktion)
    {
      case 'auftrag':
        parameter = $('#auftrag_parameter'+el).val();
      break;
      case 'rechnung':
        parameter = $('#rechnung_parameter'+el).val();
        break;
        
      case 'gutschrift':
        parameter = $('#gutschrift_parameter'+el).val();
        break;
      case 'verbindlichkeit':
        parameter = $('#verbindlichkeit_parameter'+el).val();
      break;
    }
    if(parameter != '') 
    {
      $.ajax({
          url: 'index.php?module=zahlungseingang&action=import&cmd=pdfvorschau&aktion='+aktion+'&parameter='+ parameter.split(' ')[ 0 ] ,
          type: 'POST',
          dataType: 'json',
          data: {},
          success: function(data) {
            $('#pdfiframe').prop('src',data.src);
            $('#pdfvorschaudiv').show();
            $('#pdfvorschaudiv').css('top', pos.top + 25);
            $('#pdfvorschaudiv').css('left', pos.left > 1000? pos.left - 1000.0: 100);
          },
          beforeSend: function() {

          }
      });
    }
    
  }
  
  function CreateVerbindlichkeit()
  {
    if($('#addverbindlichkeit_adresse').val() != '')
    {
      $.ajax({
          url: 'index.php?module=zahlungseingang&action=import&cmd=addverbindlichkeit&id=[ID]',
          type: 'POST',
          dataType: 'json',
          data: {
            kid:$('#addverbindlichkeit_id').val(),
            adresse:$('#addverbindlichkeit_adresse').val(),
            rechnung:$('#addverbindlichkeit_rechnung').val(),
            betrag:$('#addverbindlichkeit_betrag').val(),
            projekt:$('#addverbindlichkeit_projekt').val(),
            klaerfall:($('#addverbindlichkeit_klaerfall').prop('checked')?1:0), 
            klaergrund:$('#addverbindlichkeit_klaergrund').val(),
            rechnungsdatum:$('#addverbindlichkeit_rechnungsdatum').val(),
            eingangsdatum:$('#addverbindlichkeit_eingangsdatum').val(),
            verwendungszweck:$('#addverbindlichkeit_verwendungszweck').val(),
                },
            success: function(data) {
            $('#verbindlichkeit_parameter'+data.el).val(data.parameter);

            $('#jsmessage').after('<div class="warning">{|Verbindlichkeit |} <a href="index.php?module=verbindlichkeit&action=edit&id='+(data.verbindlichkeit)+'" target="_blank"><input type="button" value="'+data.verbindlichkeit+'" /></a> {|wurde angelegt|}</div>');

            $('#addverbindlichkeitpopup').dialog('close');
          },
          beforeSend: function() {

          }
      });
    }else alert('{|Bitte einen Lieferanten auswählen|}');
  }
  
  $(document).ready(function() {
    $('#pdfclosebutton').on('click',function(){
      if(pdfinterval != null)clearTimeout(pdfinterval);
      $('#pdfvorschaudiv').hide();
    });
    $('#pdfvorschaudiv').on('mouseover', function(){
      if(pdfinterval != null)clearTimeout(pdfinterval);
    });
    $('#pdfvorschaudiv').on('mouseleave', function(){
      if(pdfinterval != null)clearTimeout(pdfinterval);
      pdfinterval = setInterval(function(){ 
        $('#pdfvorschaudiv').hide();

      },1000);
    });
    
    $('#addverbindlichkeitpopup').dialog( {
      modal: true,
      autoOpen: false,
      minWidth: 1440,
      title:'{|Verbindlichkeit|}',
      buttons: {
        '{|SPEICHERN|}': function()
        {
          CreateVerbindlichkeit();
        },
        '{|ABBRECHEN|}': function() {
          $(this).dialog('close');
        }
      },
      close: function(event, ui){
        
      }
    });
    
    $('#klaerfallpopup').dialog( {
      modal: true,
      autoOpen: false,
      minWidth: 940,
      title:'{|Klärfall|}',
      buttons: {
        '{|SPEICHERN|}': function()
        {
          $.ajax({
              url: 'index.php?module=zahlungseingang&action=import&cmd=saveklaerfall&id=[ID]',
              type: 'POST',
              dataType: 'json',
              data: {kid:$('#kid').val(), klaerfall: ($('#klaerfall').prop('checked')?1:0), klaergrund: $('#klaergrund').val()},
              success: function(data) {
                window.location.href=window.location.href.split('#')[0];
              },
              beforeSend: function() {

              }
          });
        },
        '{|ABBRECHEN|}': function() {
          $(this).dialog('close');
        }
      },
      close: function(event, ui){
        
      }
    });
    
    
  });
</script>
