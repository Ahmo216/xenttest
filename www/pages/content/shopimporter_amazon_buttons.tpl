<style>
  span.inputinfo.red{
    color:red;
    font-weight: bold;
  }
  td.tdfirst{
    min-width:70px;
  }

  fieldset.plaintext textarea
  {
    width:100%;
    min-height:200px;
  }

  fieldset.fieldset_allgemein > table tr > td:first-child {
    min-width: 140px;
  }

</style>
<!--<input type="button" style="min-width:150px;display:none;" onclick="openamazoneinstellungen[NR]();" value="Amazon Einstellungen" class="onlinshopbutton[NR]" />-->
<img src="themes/new/images/settings.svg" alt="Amazon Einstellungen" title="Amazon Einstellungen" style="display:none;" onclick="openamazoneinstellungen[NR]();" class="onlinshopbutton[NR]" />
<div id="divamazoneinstellungen[NR]" style="display:none;">
  <div class="row">
    <div class="row-height">
      <div class="col-xs-12 col-sm-6 col-sm-height">
        <div class="inside inside-full-height">
          <fieldset class="fieldset_allgemein"><legend>Allgemein</legend>
            <table style="mkTableFormular">
              <tr><td><label for="amazon[NR]_ASIN">ASIN:</label></td><td><input type="text" size="40" id="amazon[NR]_ASIN" value="" /></td></tr>
              <tr><td><label for="amazon[NR]_ean">EAN:</label></td><td><input type="text" size="40" id="amazon[NR]_ean" value="[AMAZON_EAN]" disabled="disabled" /></td></tr>
              <tr><td><label for="amazon[NR]_bezeichnung">Bezeichnung:</label></td><td><input type="text" placeholder="[AMAZON_BEZEICHNUNG]" size="40" id="amazon[NR]_bezeichnung" value="" data-maxlength="500" /></td></tr>
              <tr><td><label for="amazon[NR]_beschreibung">Beschreibung:</label></td><td><textarea placeholder="[AMAZON_BESCHREIBUNG]" id="amazon[NR]_beschreibung" cols="50" rows="6" data-maxlength="2000"></textarea></td></tr>
              <tr><td><label for="amazon[NR]_brand">Marke:</label></td><td><input type="text" data-type="notempty" size="40" id="amazon[NR]_brand" value="" /></td></tr>
              <tr><td><label for="amazon[NR]_hersteller">Hersteller:</label></td><td><input type="text" data-type="notempty" size="40" id="amazon[NR]_hersteller" value="[AMAZON_HERSTELLER]" disabled="disabled" /></td></tr>
              <tr><td><label for="amazon[NR]_Fulfillment">Fulfillment</label></td><td><select id="amazon[NR]_Fulfillment"><option></option><option value="FBM">FBM</option><option value="FBA">FBA</option></select></td></tr>
              <tr><td>Verk&auml;uferversandgruppe:</td><td>[SELMERCHANTGROUP]</td></tr>
              <tr><td><label for="amazon[NR]_handlingtime">Bearbeitungszeit:</label></td><td><input type="text" id="amazon[NR]_handlingtime" size="10" value="" data-type="positiveint" />&nbsp;Tage</td></tr>
            </table>
            <table style="mkTableFormular" id="amazonproduktkategorien[NR]">
              [PRODUKTKATEGORIEN]
            </table>
            <table>
              [PRODUCTIMAGES]
            </table>
            <table id="amazon[NR]_categorieelements">

            </table>
          </fieldset>
        </div>
      </div>

      <div class="col-xs-12 col-sm-6 col-sm-height">
        <div class="inside inside-full-height">
          <fieldset class="noneplain"><legend>Kategorie und Bullets</legend>
            <table style="mkTableFormular">
              <tr><td><label for="amazon[NR]_kategorie1">Kategorie 1:</label></td><td><input type="text" size="40" id="amazon[NR]_kategorie1" value="" /></td></tr>
              <tr><td><label for="amazon[NR]_kategorie2">Kategorie 2:</label></td><td><input type="text" size="40" id="amazon[NR]_kategorie2" value="" /></td></tr>
              <tr><td><label for="amazon[NR]_bulletpoint1">Bulletpoint 1:</label></td><td><input type="text" size="40" id="amazon[NR]_bulletpoint1" value="" data-maxlength="500" /></td></tr>
              <tr><td><label for="amazon[NR]_bulletpoint2">Bulletpoint 2:</label></td><td><input type="text" size="40" id="amazon[NR]_bulletpoint2" value="" data-maxlength="500" /></td></tr>
              <tr><td><label for="amazon[NR]_bulletpoint3">Bulletpoint 3:</label></td><td><input type="text" size="40" id="amazon[NR]_bulletpoint3" value="" data-maxlength="500" /></td></tr>
              <tr><td><label for="amazon[NR]_bulletpoint4">Bulletpoint 4:</label></td><td><input type="text" size="40" id="amazon[NR]_bulletpoint4" value="" data-maxlength="500" /></td></tr>
              <tr><td><label for="amazon[NR]_bulletpoint5">Bulletpoint 5:</label></td><td><input type="text" size="40" id="amazon[NR]_bulletpoint5" value="" data-maxlength="500" /></td></tr>
            </table>
          </fieldset>
          <fieldset class="noneplain"><legend>Marktpl&auml;tze</legend>
            [MARKETPLACES]
          </fieldset>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="row-height">
      <div class="col-xs-12 col-sm-6 col-sm-height">
        <div class="inside inside-full-height">

          <fieldset class="noneplain"><legend>Abmessungen / Sonstiges</legend>
            <table style="mkTableFormular">

              <tr><td><label for="amazon[NR]_ISBN">ISBN:</label></td><td><input type="text" size="40" id="amazon[NR]_ISBN" value="" /></td></tr>
              <tr><td><label for="amazon[NR]_UPC">UPC</label>:</td><td><input type="text" size="40" id="amazon[NR]_UPC" value="" /></td></tr>
              <tr><td><label for="amazon[NR]_GTIN">GTIN:</label></td><td><input type="text" size="40" id="amazon[NR]_GTIN" value="" /></td></tr>
              <tr><td><label for="amazon[NR]_GCID">GCID:</label></td><td><input type="text" size="40" id="amazon[NR]_GCID" value="" /></td></tr>
              <tr><td><label for="amazon[NR]_PZN">PZN:</label></td><td><input type="text" size="40" id="amazon[NR]_PZN" value="" /></td></tr>

              <tr><td><label for="amazon[NR]_length">Abmessung Karton:</label></td><td nowrap><input type="text" size="5" id="amazon[NR]_length" value="" data-type="positivedecimal" /> x <input type="text" size="5" id="amazon[NR]_width" value="" data-type="positivedecimal" /> x <input type="text" size="5" id="amazon[NR]_height" value="" data-type="positivedecimal" /></td></tr>
              <tr><td><label for="amazon[NR]_weight">Gewicht Karton:</label></td><td><input type="text" size="5" id="amazon[NR]_weight" value="" data-type="positivedecimal" />&nbsp;<i>in kg</i></td></tr>
              <tr><td><label for="amazon[NR]_anzahl">Anzahl Kartons:</label></td><td><input type="text" size="5" id="amazon[NR]_anzahl" value="" data-type="positiveint" /></td></tr>
              <tr><td><label for="amazon[NR]_anzahlinkarton">Anzahl Produkte in Karton:</label></td><td><input type="text" size="5" id="amazon[NR]_anzahlinkarton" value="" data-type="positiveint" /></td></tr>
               <!--<tr><td>Bild 1:</td><td><input type="text" size="40" id="amazon[NR]_bild1" value="" /></td></tr>
              <tr><td>Bild 2:</td><td><input type="text" size="40" id="amazon[NR]_bild2" value="" /></td></tr>
              <tr><td>Bild 3:</td><td><input type="text" size="40" id="amazon[NR]_bild3" value="" /></td></tr>-->
              [WEITEREINFOS]
            </table>
          </fieldset>
        </div>
      </div>
      <div class="col-xs-12 col-sm-6 col-sm-height">
        <div class="inside inside-full-height">

          <fieldset class="noneplain"><legend>Suchbegriffe / Zielgruppen</legend>
            <table style="mkTableFormular">
              <tr><td><label for="amazon[NR]_searchterm1">Suchbegriff 1:</label></td><td><input type="text" size="40" id="amazon[NR]_searchterm1" value="" data-maxlength="200" /></td></tr>
              <tr><td><label for="amazon[NR]_searchterm2">Suchbegriff 2:</label></td><td><input type="text" size="40" id="amazon[NR]_searchterm2" value="" data-maxlength="200" /></td></tr>
              <tr><td><label for="amazon[NR]_searchterm3">Suchbegriff 3:</label></td><td><input type="text" size="40" id="amazon[NR]_searchterm3" value="" data-maxlength="200" /></td></tr>
              <tr><td><label for="amazon[NR]_searchterm4">Suchbegriff 4:</label></td><td><input type="text" size="40" id="amazon[NR]_searchterm4" value="" data-maxlength="200" /></td></tr>
              <tr><td><label for="amazon[NR]_searchterm5">Suchbegriff 5:</label></td><td><input type="text" size="40" id="amazon[NR]_searchterm5" value="" data-maxlength="200" /></td></tr>

              <tr class="platinumkeywords"><td><label for="amazon[NR]_platinumkeywords1">Keywords 1:</label></td><td><input type="text" size="40" id="amazon[NR]_platinumkeywords1" value="" data-maxlength="50" /></td></tr>
              <tr class="platinumkeywords"><td><label for="amazon[NR]_platinumkeywords2">Keywords 2:</label></td><td><input type="text" size="40" id="amazon[NR]_platinumkeywords2" value="" data-maxlength="50" /></td></tr>
              <tr class="platinumkeywords"><td><label for="amazon[NR]_platinumkeywords3">Keywords 3:</label></td><td><input type="text" size="40" id="amazon[NR]_platinumkeywords3" value="" data-maxlength="50" /></td></tr>
              <tr class="platinumkeywords"><td><label for="amazon[NR]_platinumkeywords4">Keywords 4:</label></td><td><input type="text" size="40" id="amazon[NR]_platinumkeywords4" value="" data-maxlength="50" /></td></tr>
              <tr class="platinumkeywords"><td><label for="amazon[NR]_platinumkeywords5">Keywords 5:</label></td><td><input type="text" size="40" id="amazon[NR]_platinumkeywords5" value="" data-maxlength="50" /></td></tr>
              <tr class="platinumkeywords"><td><label for="amazon[NR]_platinumkeywords6">Keywords 6:</label></td><td><input type="text" size="40" id="amazon[NR]_platinumkeywords6" value="" data-maxlength="50" /></td></tr>
              <tr class="platinumkeywords"><td><label for="amazon[NR]_platinumkeywords7">Keywords 7:</label></td><td><input type="text" size="40" id="amazon[NR]_platinumkeywords7" value="" data-maxlength="50" /></td></tr>
              <tr class="platinumkeywords"><td><label for="amazon[NR]_platinumkeywords8">Keywords 8:</label></td><td><input type="text" size="40" id="amazon[NR]_platinumkeywords8" value="" data-maxlength="50" /></td></tr>
              <tr class="platinumkeywords"><td><label for="amazon[NR]_platinumkeywords9">Keywords 9:</label></td><td><input type="text" size="40" id="amazon[NR]_platinumkeywords9" value="" data-maxlength="50" /></td></tr>
              <tr class="platinumkeywords"><td><label for="amazon[NR]_platinumkeywords10">Keywords 10:</label></td><td><input type="text" size="40" id="amazon[NR]_platinumkeywords10" value="" data-maxlength="50" /></td></tr>
              <tr class="platinumkeywords"><td><label for="amazon[NR]_platinumkeywords11">Keywords 11:</label></td><td><input type="text" size="40" id="amazon[NR]_platinumkeywords11" value="" data-maxlength="50" /></td></tr>
              <tr class="platinumkeywords"><td><label for="amazon[NR]_platinumkeywords12">Keywords 12:</label></td><td><input type="text" size="40" id="amazon[NR]_platinumkeywords12" value="" data-maxlength="50" /></td></tr>
              <tr class="platinumkeywords"><td><label for="amazon[NR]_platinumkeywords13">Keywords 13:</label></td><td><input type="text" size="40" id="amazon[NR]_platinumkeywords13" value="" data-maxlength="50" /></td></tr>
              <tr class="platinumkeywords"><td><label for="amazon[NR]_platinumkeywords14">Keywords 14:</label></td><td><input type="text" size="40" id="amazon[NR]_platinumkeywords14" value="" data-maxlength="50" /></td></tr>
              <tr class="platinumkeywords"><td><label for="amazon[NR]_platinumkeywords15">Keywords 15:</label></td><td><input type="text" size="40" id="amazon[NR]_platinumkeywords15" value="" data-maxlength="50" /></td></tr>
              <tr class="platinumkeywords"><td><label for="amazon[NR]_platinumkeywords16">Keywords 16:</label></td><td><input type="text" size="40" id="amazon[NR]_platinumkeywords16" value="" data-maxlength="50" /></td></tr>
              <tr class="platinumkeywords"><td><label for="amazon[NR]_platinumkeywords17">Keywords 17:</label></td><td><input type="text" size="40" id="amazon[NR]_platinumkeywords17" value="" data-maxlength="50" /></td></tr>
              <tr class="platinumkeywords"><td><label for="amazon[NR]_platinumkeywords18">Keywords 18:</label></td><td><input type="text" size="40" id="amazon[NR]_platinumkeywords18" value="" data-maxlength="50" /></td></tr>
              <tr class="platinumkeywords"><td><label for="amazon[NR]_platinumkeywords19">Keywords 19:</label></td><td><input type="text" size="40" id="amazon[NR]_platinumkeywords19" value="" data-maxlength="50" /></td></tr>
              <tr class="platinumkeywords"><td><label for="amazon[NR]_platinumkeywords20">Keywords 20:</label></td><td><input type="text" size="40" id="amazon[NR]_platinumkeywords20" value="" data-maxlength="50" /></td></tr>

              <tr><td><label for="amazon[NR]_targetaudience1">Zielgruppe 1:</label></td><td><input type="text" size="40" id="amazon[NR]_targetaudience1" value="" data-maxlength="50" /></td></tr>
              <tr><td><label for="amazon[NR]_targetaudience2">Zielgruppe 2:</label></td><td><input type="text" size="40" id="amazon[NR]_targetaudience2" value="" data-maxlength="50" /></td></tr>
              <tr><td><label for="amazon[NR]_targetaudience3">Zielgruppe 3:</label></td><td><input type="text" size="40" id="amazon[NR]_targetaudience3" value="" data-maxlength="50" /></td></tr>
              <tr><td><label for="amazon[NR]_targetaudience4">Zielgruppe 4:</label></td><td><input type="text" size="40" id="amazon[NR]_targetaudience4" value="" data-maxlength="50" /></td></tr>

              <tr><td><label for="amazon[NR]_usedfor1">Usedfor 1:</label></td><td><input type="text" size="40" id="amazon[NR]_usedfor1" value="" data-maxlength="200" /></td></tr>
              <tr><td><label for="amazon[NR]_usedfor2">Usedfor 2:</label></td><td><input type="text" size="40" id="amazon[NR]_usedfor2" value="" data-maxlength="200" /></td></tr>
              <tr><td><label for="amazon[NR]_usedfor3">Usedfor 3:</label></td><td><input type="text" size="40" id="amazon[NR]_usedfor3" value="" data-maxlength="200" /></td></tr>
              <tr><td><label for="amazon[NR]_usedfor4">Usedfor 4:</label></td><td><input type="text" size="40" id="amazon[NR]_usedfor4" value="" data-maxlength="200" /></td></tr>
              <tr><td><label for="amazon[NR]_usedfor5">Usedfor 5:</label></td><td><input type="text" size="40" id="amazon[NR]_usedfor5" value="" data-maxlength="200" /></td></tr>
            </table>
          </fieldset>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="application/javascript">
  var savedata2 = {};
  var withkat[NR] = 1;

  function setInputinfo(element)
  {
    $(element).on($(element).is('select')?'change':'keyup', function() {
      var value = $(this).val()+'';
      var elafter = $(this).next('span.inputinfo');
      if(elafter.length === 0) {
        $(this).after('<span class="inputinfo"></span>');
      }
      window.console.log(this);
      if(value.length === 0) {
        if($(this).data('type') === 'notempty') {
          $(this).next('span.inputinfo').toggleClass('red', true);
          $(this).next('span.inputinfo').text('Bitte ausfüllen');
          return
        }
        $(this).next('span.inputinfo').toggleClass('red',false);
        $(this).next('span.inputinfo').text('');
        return;
      }
      else {
        if($(this).data('type') === 'notempty') {
          $(this).next('span.inputinfo').toggleClass('red',false);
          $(this).next('span.inputinfo').text('');
          return;
        }
      }

      switch($(this).data('type'))
      {
        case 'positiveint':
          if(/([^0-9])/.test(value))
          {
            $(this).next('span.inputinfo').toggleClass('red',true);
            $(this).next('span.inputinfo').text('Bitte eine positive ganze Zahl eingaben');
          }else{
            $(this).next('span.inputinfo').toggleClass('red',false);
            $(this).next('span.inputinfo').text('');
          }
          break;
        case 'positivedecimal':
          if(/[^0-9,.]/.test(value))
          {
            $(this).next('span.inputinfo').toggleClass('red',true);
            $(this).next('span.inputinfo').text('Bitte eine positive Decimalzahl eingaben');
          }else{
            $(this).next('span.inputinfo').toggleClass('red',false);
            $(this).next('span.inputinfo').text('');
          }
          break;
      }
    });
  }

  $(document).ready(function() {
    //$('.platinumkeywords').hide();
    $('#divamazoneinstellungen[NR]').on('click',".createexport",function()
      {
        var prefix = this.id.split('_')[ 0 ]  ;
        var cat = $('#'+prefix+'_ProductData').val();
        $.ajax({
          url: 'index.php?module=shopimporter_amazon&action=list&cmd=createexport&artikel=[ARTIKELID]&id=[SHOPID]',
          type: 'POST',
          dataType: 'json',
          data: {categorie:cat},
          success: function(data) {
            $('#divamazoneinstellungen[NR]').loadingOverlay('remove');
            if(typeof data.id != 'undefined') {
              $('#'+prefix+'_createexport').next('div.cbox').remove();
              $('#'+prefix+'_createexport').after('<div class="cbox">{|Zur Exportvorlage|}: <a target="_blank" href="index.php?module=exportvorlage&action=edit&id='+data.id+'">Amazon '+cat+'</a></div>');
            }
          },fail:function() {
            $('#divamazoneinstellungen[NR]').loadingOverlay('remove');
          }
        });

      }
    );

    $('.platinumkeywords input').on('keyup',function(){
      var notempty = $('.platinumkeywords input');
      var len = notempty.length;
      var j = 19;
      var i = len - 1;
      for(i = len - 1; i >= 0; i--)
      {
        if($(notempty[i]).val()+'' == '')
        {
          j = i;
        }else{
          break;
        }
      }
      if(j < 19)
      {
        j += 1;
      }
      for(i = 0; i < len; i++)
      {
        if(i <= j)
        {
          $(notempty[i]).parents('tr').first().show();
        }else{
          $(notempty[i]).parents('tr').first().hide();
        }
      }

      /*var wert = $(this).val()+'';
      if(wert != '')
      {
        $(this).parents('tr').fist().prevAll().find('.platinumkeywords').show();
        $(this).parents('tr').fist().next().find('.platinumkeywords').show();
        $(this).parents('tr').fist().next().find('.platinumkeywords').trigger('change');
      }else{

      }*/
    });
    $('.platinumkeywords input').first().trigger('keyup');

    $('[data-maxlength]').on('keyup',function(){
      var anz = parseInt($(this).data('maxlength'));
      if(!isNaN(anz) && anz > 0) {
        var strlength = ($(this).val() + '').length;
        var elafter = $(this).next('span.inputinfo');
        if(elafter.length === 0)
        {
          $(this).after('<span class="inputinfo"></span>');
        }
        if(anz >= strlength)
        {
          $(this).next('span.inputinfo').text((anz - strlength)+' Zeichen übrig');
          $(this).next('span.inputinfo').toggleClass('red',false);
        }else{
          $(this).next('span.inputinfo').text((strlength - anz)+' Zeichen zuviel');
          $(this).next('span.inputinfo').toggleClass('red',true);
        }
      }
    });

    $('[data-type]').each(function(){
      setInputinfo(this);
    });



    /*$('[data-type]').on('keyup',function() {
      var value = $(this).val()+'';
      var elafter = $(this).next('span.inputinfo');
      if(elafter.length === 0) {
        $(this).after('<span class="inputinfo"></span>');
      }
      if(value.length === 0) {
        if($(this).data('type') === 'notempty') {
          $(this).next('span.inputinfo').toggleClass('red', true);
          $(this).next('span.inputinfo').text('Bitte ausfüllen');
          return
        }
        $(this).next('span.inputinfo').toggleClass('red',false);
        $(this).next('span.inputinfo').text('');
        return;
      }

      switch($(this).data('type'))
      {
        case 'positiveint':
          if(/([^0-9])/.test(value))
          {
            $(this).next('span.inputinfo').toggleClass('red',true);
            $(this).next('span.inputinfo').text('Bitte eine positive ganze Zahl eingaben');
          }else{
            $(this).next('span.inputinfo').toggleClass('red',false);
            $(this).next('span.inputinfo').text('');
          }
          break;
        case 'positivedecimal':
          if(/[^0-9,.]/.test(value))
          {
            $(this).next('span.inputinfo').toggleClass('red',true);
            $(this).next('span.inputinfo').text('Bitte eine positive Decimalzahl eingaben');
          }else{
            $(this).next('span.inputinfo').toggleClass('red',false);
            $(this).next('span.inputinfo').text('');
          }
          break;
      }

    });*/


    $('[data-type]').on('change',function(){
      $('[data-type]').trigger('keyup');
    });

    $('[data-type]').trigger('keyup');

    $('select.VariationTheme').on('change',function(){
      var elname = this.id.split('_')[ 1 ];
      var value = $(this).val()+'';
      if(value != '') {
        var valuea = value.split('-');
        if(valuea.length > 2)
        {
          $('tr.eigenschaft3').show();
        }

        $('tr.eigenschaft2').show();
        $('tr.eigenschaft1').show();
      }else{
        $('tr.eigenschaft1').hide();
        $('tr.eigenschaft2').hide();
        $('tr.eigenschaft3').hide();
      }

    });


    $('#divamazoneinstellungen[NR]').dialog(
    {
      modal: true,
      autoOpen: false,
      minWidth: 1200,
      title:'Amazon Einstellungen',
      buttons: {
        SPEICHERN: function()
        {
          $('#divamazoneinstellungen[NR]').loadingOverlay('show');
          var savedata = {
            [DATASAVE]
          };

          var savedata3 = {};
          for (var k in savedata) {
            if(k+'' !== '' && savedata[k]+'' !== '')
            {
              savedata2[k] = savedata[k];
            }
          }

          for (var k in savedata2) {
            if(k+'' !== '' && savedata2[k]+'' !== '')
            {
              savedata3[k] = savedata2[k];
            }
          }
          $('#amazon[NR]_categorieelements').find('input').each(function(){
            var el = this.id.split('_')[ 1 ];

            if($(this).is('input[type="checkbox"]')) {
              savedata3[el] = $(this).prop('checkbox')?'1':'0';
            } else {
              savedata3[el] = $(this).val();
            }
          });
          $('#amazon[NR]_categorieelements').find('select').each(function(){
            var el = this.id.split('_')[ 1 ];
            savedata3[el] = $(this).val();
          });
          $.ajax({
              url: 'index.php?module=shopimporter_amazon&action=list&cmd=amazonartikelsave&artikel=[ARTIKELID]&id=[SHOPID]',
              type: 'POST',
              dataType: 'json',
              data: savedata3,
              success: function(data) {
                $('#divamazoneinstellungen[NR]').loadingOverlay('remove');
                $('#divamazoneinstellungen[NR]').dialog('close');
              },fail:function() {
                $('#divamazoneinstellungen[NR]').loadingOverlay('remove');
              }
          });
        },
        ABBRECHEN: function() {
          $(this).dialog('close');
        }
      },
      close: function(event, ui){
        $('#tabs').loadingOverlay('remove');
      }
    });
  });
  function openamazoneinstellungen[NR]()
  {
    $('#tabs').loadingOverlay('show');
    var widthkat = trim($('#amazonproduktkategorien[NR]').html()) != ''?0:1;
    $.ajax({
        url: 'index.php?module=shopimporter_amazon&action=list&cmd=amazonartikelget&artikel=[ARTIKELID]&id=[SHOPID]',
        type: 'POST',
        dataType: 'json',
        data: {nr:'[NR]', withkat: withkat[NR]},
        success: function(data) {
          if(typeof data.kategorien != 'undefined') {
            $('#amazonproduktkategorien[NR]').html(data.kategorien);
            withkat[NR] = 0;

            data.kategorien = null;
            $('#amazonproduktkategorien[NR]').find('[data-type]').each(function() {
              setInputinfo(this);
            });
          }
          $('.parentagechange').off('change');
          $('.showonchange').off('change');
          $('.showonchange').each(function(){
            setInputinfo(this);
          });
          $('.showonchange').trigger('change');
          $('.parentagechange').on('change',function() {
            var elname = this.id.split('_')[ 1 ];
            var value = $(this).val()+'';
            $('tr.eigenschaft1').hide();
            $('tr.eigenschaft2').hide();
            $('tr.eigenschaft3').hide();
            if(elname != '') {
              $('tr.Parentage').hide();
              $('tr.VariationTheme').hide();
              $('tr.Element').hide();
              if(value != '') {
                $('tr.Parentage'+'.'+value).show();
                $('tr.VariationTheme'+'.'+value).show();
                $('tr.VariationTheme'+'.'+value).find('select.VariationTheme').trigger('change');
                $('tr.Element'+'.'+value).show();
                $('tr.Element'+'.'+elname).show();
              }
            }
          });
          $('.showonchange').on('change',function() {
            var elname = this.id.split('_')[ 1 ];
            var value = $(this).val();
            $('tr.eigenschaft1').hide();
            $('tr.eigenschaft2').hide();
            $('tr.eigenschaft3').hide();
            if(elname != '') {
              $('tr.'+elname).hide();
              $('tr.Parentage').hide();
              $('tr.VariationTheme').hide();
              $('tr.Element').hide();
              if(value != '') {
                if($('tr.'+elname+'.'+value).length > 0) {
                  $.ajax({
                    url: 'index.php?module=shopimporter_amazon&action=list&cmd=amazongetelementsbycateogrie&articleid=[ARTIKELID]&id=[SHOPID]',
                    type: 'POST',
                    dataType: 'json',
                    async: false,
                    data: {producttypename:elname,producttype:value,prefix:'[NR]_'},
                    success: function(data) {
                      $('#amazon[NR]_categorieelements').html(data.html);
                      $('#amazon[NR]_categorieelements').find('[data-type]').each(function(){
                        setInputinfo(this);
                      });
                    },fail:function() {

                    }
                  });
                  $('tr.'+elname+'.'+value).show();
                  $('tr.'+elname+'.'+value).find('select').trigger('change');
                }
              }
            }
          });
          $('#tabs').loadingOverlay('remove');
          $('#divamazoneinstellungen[NR]').dialog('open');
          setTimeout(function() {
            $('.showonchange').trigger('change');
            $('.selformat').trigger('change');
          },100);
          savedata2 = {
            [DATASAVE2]
          }
          [DATAGET]

        },fail:function(){
          $('#tabs').loadingOverlay('remove');
        }
        ,
        beforeSend: function() {

        }
    });
  /*
    $('#divamazoneinstellungen[NR]').dialog('open');
    $('.showonchange').trigger('change');
    $('.parentagechange').trigger('change');*/
  }



</script>
