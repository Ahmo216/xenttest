<div id="tabs">
  <ul>
    <li><a href="#tabs-1"><!--[TABTEXT]--></a></li>
  </ul>
  <div id="tabs-1">
    [MESSAGE]
    <form method="POST">
      [BEFORETABLE]
      [TABLE]
      <fieldset><legend>Auswahl</legend>
        <table>
          <tr>
            <td>alle markieren:</td>
            <td><input type="checkbox" id="all" onclick="clickall();" /></td>
            <td>
              <select name="command">
                <option value="freigegeben">als freigegeben markieren</option>
                <option value="abgeschlossen">als abgeschlossen markieren</option>
                <option value="storniert">Stornieren</option>
                <option value="versandnichtok">&quot;F&uuml;r den Versand&quot; entfernen</option>
                <option value="versandok">F&uuml;r den Versand freigeben</option>
                <option value="vorabbezahltentfernen">&quot;Manuelle Zahlungsfreigabe&quot; entfernen</option>
                <option value="vorabbezahltmarkieren">Manuelle Zahlungsfreigabe setzen</option>
              </select>
            </td>
            <td>
              <input type="submit" value="Ausf&uuml;hren" />&nbsp;<input type="submit" name="doall" value="F&uuml;r alle Ausf&uuml;hren" />&nbsp;<input type="submit" value="Abbrechen" class="btnRed" name="delete" />
            </td>
          </tr>
        </table>
      </fieldset>
      [AFTERTABLE]
      [BEFOREUPLOAD]
      <textarea cols="100" rows="20" name="ordernumber"></textarea>
      <fieldset><legend>Auswahl</legend>
        <table>
          <tr>
            <td>
              <select name="numbertype">
                <option value="belegnr">Auftragsnummer</option>
                <option value="internet">Internet</option>
                <option value="transaktionsnummer">Transaktionsnummer</option>
              </select>
            </td>
            <td>
              <select name="separator">
                <option value="semicolon">;</option>
                <option value="comma">,</option>
                <option value="newline">Neue Zeile</option>
              </select>
            </td>
            <td>
              <input type="submit" value="weiter" />
            </td>
          </tr>
        </table>
      </fieldset>
      [AFTERUPLOAD]
    </form>
    [TAB1NEXT]
  </div>
</div>

<script type="application/javascript">
  function clickall()
  {
    var checked = $('#all').prop('checked');
    $('#orderstatus_list').find('input').prop('checked', checked);
  }
</script>
