<img src="themes/new/images/settings.png" style="display:none;" title="Etsy Einstellungen" onclick="openetsyeinstellungen[NR]();"  class="onlinshopbutton[NR]" />

<div id="divetsyeinstellungen[NR]" style="display:none;">
  <fieldset><legend>Listingdaten</legend>
    <table>
      <tr>
        <td>
          Kategorie ID:
        </td>
        <td>
          <input type="text" id="etsy_kategorieid" name="etsy_kategorieid" size="27">
        </td>
      </tr>
      <tr>
        <td>
          Hersteller:
        </td>
        <td>
          <select id='etsy_hersteller' name='etsy_hersteller'>
            <option value=''>-</option>
            <option value='i_did'>Ich war es</option>
            <option value='collective'>Ein Mitglied meines Shops</option>
            <option value='someone_else'>Jemand anders</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>
          Herstellungsdatum:
        </td>
        <td>
          <select id='etsy_herstellungsdatum' name='etsy_herstellungsdatum'>
            <option value=''>-</option>
            <option value='made_to_order'>Auf Anfrage</option>
            <option value='2020_2020'>Ab 2020</option>
            <option value='2010_2019'>2010 bis 2019</option>
            <option value='2001_2009'>2001 bis 2009</option>
            <option value='before_2001'>Vor 2001</option>
            <option value='2000_2000'>Um 2000</option>
            <option value='1990s'>1990er</option>
            <option value='1980s'>1980er</option>
            <option value='1970s'>1970er</option>
            <option value='1960s'>1960er</option>
            <option value='1950s'>1950er</option>
            <option value='1940s'>1940er</option>
            <option value='1930s'>1930er</option>
            <option value='1920s'>1920er</option>
            <option value='1910s'>1910er</option>
            <option value='1900s'>1900er</option>
            <option value='1800s'>1800er</option>
            <option value='1700s'>1700er</option>
            <option value='before_1700'>Vor 1700</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>
          Anlass:
        </td>
        <td>
          <select id='etsy_anlass' name='etsy_anlass'>
            <option value=''>-</option>
            <option value='anniversary'>Jubiläum</option>
            <option value='baptism'>Taufe</option>
            <option value='bar_or_bat_mitzvah'>Bar Mizwa</option>
            <option value='birthday'>Geburtstag</option>
            <option value='canada_day'>Kanada Tag</option>
            <option value='chinese_new_year'>Chinesisch Neujahr</option>
            <option value='cinco_de_mayo'>5. Mai</option>
            <option value='confirmation'>Konfirmation</option>
            <option value='christmas'>Weihnachten</option>
            <option value='day_of_the_dead'>Tag der Toten</option>
            <option value='easter'>Ostern</option>
            <option value='eid'>Eid</option>
            <option value='engagement'>Verlobung</option>
            <option value='fathers_day'>Vatertag</option>
            <option value='get_well'>Gute Besserung</option>
            <option value='graduation'>Abschluss</option>
            <option value='halloween'>Halloween</option>
            <option value='hanukkah'>Chanukka</option>
            <option value='housewarming'>Einzugsfest</option>
            <option value='kwanzaa'>Kwanzaa</option>
            <option value='prom'>Schulball</option>
            <option value='july_4th'>4. Juli</option>
            <option value='mothers_day'>Muttertag</option>
            <option value='new_baby'>Neugeborenes</option>
            <option value='new_years'>Neujahr</option>
            <option value='quinceanera'>Quinceanera</option>
            <option value='retirement'>Ruhestand</option>
            <option value='st_patricks_day'>St. Patricks Tag</option>
            <option value='sweet_16'>16. Geburtstag</option>
            <option value='sympathy'>Anteilnahme</option>
            <option value='thanksgiving'>Erntedank</option>
            <option value='valentines'>Valentinstag</option>
            <option value='wedding'>Hochzeit</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>Materialien:</td>
        <td><input type="text" id="etsy_materialien" size="27"></td>
      </tr>
      <tr>
        <td>Tags:</td>
        <td><input type="text" id="etsy_tags" size="27"></td>
      </tr>
      <tr>
        <td>Ist Werkstoff, Material, oder Werkzeug:</td>
        <td><input type="checkbox" id="etsy_istmaterial" size="27"></td>
      </tr>
    </table>
  </fieldset>

  <fieldset><legend>Kategorienuebersicht</legend>
    [TAXONOMYTABLE]
  </fieldset>
</div>


<script>
  $(document).ready(function() {
    $('#divetsyeinstellungen[NR]').dialog(
            {
              modal: true,
              autoOpen: false,
              minWidth: 940,
              title:'Etsy Einstellungen',
              buttons: {
                SPEICHERN: function()
                {
                  $.ajax({
                    url: 'index.php?module=shopimporter_etsy&action=list&cmd=etsyartikelsave&artikel=[ARTIKELID]&id=[SHOPID]',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                      etsy_kategorieid:$('#etsy_kategorieid').val(),
                      etsy_hersteller:$('#etsy_hersteller').val(),
                      etsy_herstellungsdatum:$('#etsy_herstellungsdatum').val(),
                      etsy_anlass:$('#etsy_anlass').val(),
                      etsy_materialien:$('#etsy_materialien').val(),
                      etsy_tags:$('#etsy_tags').val(),
                      etsy_istmaterial:$('#etsy_istmaterial').prop("checked")?1:0
                    },
                    success: function(data) {
                      $('#divetsyeinstellungen[NR]').dialog('close');
                    }
                  });
                },
                ABBRECHEN: function() {
                  $(this).dialog('close');
                }
              },
              close: function(event, ui){

              }
            });
  });

  function etsykategorieubernehmen(kategorieid){
    $('#etsy_kategorieid').val(kategorieid);
  }

  function openetsyeinstellungen[NR]()
  {
    $.ajax({
      url: 'index.php?module=shopimporter_etsy&action=list&cmd=etsyartikelget&artikel=[ARTIKELID]&id=[SHOPID]',
      type: 'POST',
      dataType: 'json',
      data: {},
      success: function(data) {
        $('#divetsyeinstellungen[NR]').dialog('open');
        $('#etsy_kategorieid').val(data.data.etsykategorieid);
        $('#etsy_hersteller').val(data.data.etsyhersteller);
        $('#etsy_herstellungsdatum').val(data.data.etsyherstellungsdatum);
        $('#etsy_anlass').val(data.data.etsyanlass);
        $('#etsy_tags').val(data.data.etsytags);
        $('#etsy_materialien').val(data.data.etsymaterialien);
        $('#etsy_istmaterial').prop("checked",data.data.etsyistmaterial==1?true:false);
      },
      beforeSend: function() {

      }
    });

    $('#divetsyeinstellungen[NR]').dialog('open');
  }
</script>