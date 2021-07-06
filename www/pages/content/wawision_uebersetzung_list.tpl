<div id="tabs">
    <ul>
        <li><a href="#tabs-1"></a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
[MESSAGE]
<div id="jsmsg"></div>
  <table width="100%">
    <tr>
      <td valign="top">
        <fieldset>
          <legend>{|Filter|}</legend>
          <table>
          <tr>
            <td>{|Sprache:|}</td>
            <td><select id="filtersprache" name="filtersprache">[SELFILTERSPRACHE]</select></td>
            <td>{|Typ:|}</td>
            <td><select id="filtertyp" name="filtertyp">[SELFILTERTYP]</select></td>
          </tr>
          </table>
        </fieldset>
      </td>
      <td valign="top">
        <fieldset>
          <legend>{|Aktion|}</legend>
          <table>
            <tr>
              <td>{|Sprachauswahl:|}</td>
              <td><select id="sprache" name="sprache">[SELSPRACHE]</select></td>
              <td><input type="submit" onclick="spracheauswaehlen();return false;" value="{|Sprache ausw&auml;hlen|}" /></td>
              <td><input type="button" onclick="sprachespeichern();" value="{|Sprache in Datei speichern|}" /></td>
            </tr>
          </table>
        </fieldset>
      </td>
      <td valign="top">
        <fieldset>
          <legend>{|Anlegen|}</legend>
          <form method="POST">
            <table>
              <tr>
                <td>{|Neue Sprache anlegen:|}</td>
                <td><input type="text" size="16" id="neuesprache" name="neuesprache" /></td>
                <td><input type="submit" name="anlegen" value="{|Anlegen|}" /></td>
              </tr>
            </table>
          </form>
        </fieldset>
      </td>
    </tr>
  </table>
[TAB1]

[TAB1NEXT]
</div>


<!-- tab view schließen -->
</div>

<script>

function saveueber(ueberid, el)
{
    $.ajax({
        url: 'index.php?module=wawision_uebersetzung&action=list&cmd=saveueber',
        type: 'POST',
        dataType: 'json',
        data: { uid: ueberid, uebersetzung: $(el).val()},
        success: function(data) {
          if(typeof data.status != 'undefined' && data.status == 1)
          {
            
          }else{
            alert('Fehler beim erstellen');
          }
        }
    });
}

function sprachespeichern()
{
  var selsprache = $('#sprache').val();
  if(selsprache != '')
  {
    $('body').loadingOverlay();
    $('#ui-translation-overlay').hide();

    $.ajax({
        url: 'index.php?module=wawision_uebersetzung&action=list&cmd=createfile',
        type: 'POST',
        dataType: 'json',
        data: { sprache: selsprache},
        success: function(data) {
          if(typeof data.status != 'undefined' && data.status == 1) {
            alert('Datei angelegt');
          }else{
            alert('Fehler beim erstellen');
          }
        },
        complete: function () {
          $('body').loadingOverlay('remove');
          $('#ui-translation-overlay').show();
        }
    }); 
  }else{
    alert('Bitte eine Sprache wählen');
  }
}

function spracheauswaehlen() {
  var selsprache = $('#sprache').val();
  var cmd = selsprache === '' ? 'stop-translation' : 'start-translation';

  $.ajax({
    url: 'index.php?module=wawision_uebersetzung&action=list&cmd=' + cmd,
    type: 'POST',
    dataType: 'json',
    data: {selected: selsprache},
    success: function (data) {
      if (typeof data.success === 'undefined') {
        alert('Unbekannter Fehler beim Auswählen der Sprache.');
      }
      if (typeof data.success === false) {
        alert('FEHLER: ' + data.error);
      }
      window.location.reload(true);
    }
  });
}
</script>
