<!-- gehort zu tabview -->
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">[TABTEXT]</a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
<form method="post">
  [MESSAGE]
</form>
  
  [TAB1]
  
  [TAB1NEXT]
</div>

<!-- tab view schließen -->
</div>

<div id="editUps" style="display:none;" title="Bearbeiten">
<form method="post">
  <input type="hidden" id="e_id">
    <fieldset>
      <legend>{|Adresse|}</legend>
  	  <table>
        <tr>
          <td width="150">{|Adresse|}:</td>
          <td><input type="text" name="e_adresse" id="e_adresse" size="40"></td>
        </tr>
      </table>
    </fieldset>
    <fieldset>
      <legend>{|UPS|}</legend>
      <table>
        <tr>
          <td width="150">{|UPS Account Nummer|}:</td>
          <td><input type="text" name="e_account_nummer" id="e_account_nummer" size="40"></td>
        </tr>
        <tr>
          <td>{|Bemerkung|}:</td>
          <td><input type="text" name="e_bemerkung" id="e_bemerkung" size="40"></td>
        </tr>
      </table>
    </fieldset>
    <fieldset>
      <legend>{|Erstellung Paketmarke|}</legend>
      <table>
        <tr>
          <td width="150">{|Auswahl|}:</td>
          <td><select name="e_auswahl" id="e_auswahl">
              <option value="0">Eigener UPS-Account vorausgew&auml;hlt</option>
              <option value="1">Dieser UPS-Account vorausgew&auml;hlt</option>
              </select></td>
        </tr>
        <tr>
          <td>{|Aktiv|}:</td>
          <td><input type="checkbox" name="e_aktiv" id="e_aktiv"></td>
        </tr>
      </table>
    </fieldset>
</div>
</form>


<script type="text/javascript">



</script>
