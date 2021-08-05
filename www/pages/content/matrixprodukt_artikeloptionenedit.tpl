<div id="tabs">
    <ul>
        <li><a href="#tabs-1">[TABTEXT]</a></li>
    </ul>
<!-- ende gehort zu tabview -->

  <!-- erstes tab -->
  <div id="tabs-1">
  [MESSAGE]
    <form method="POST">
    <table class="tableborder" border="0" cellpadding="3" cellspacing="0" width="100%">
      <tbody>
        <tr valign="top">
          <td>
            <fieldset><legend>{|Einstellungen|}</legend>
              <table width="100%">
              <tr>
                <td><label for="name">{|Name|}:</label></td>
                <td><input type="hidden" name="gruppe" value="[GRUPPE]" />
                  <input type="text" name="name" id="name" value="[NAME]" /></td>
                <td>
              </tr>
                <tr>
                  <td><label for="articlenumber_suffix">{|Anhang an Artikelnummer|}:</label></td>
                  <td><input type="text" id="articlenumber_suffix" name="articlenumber_suffix" value="[ARTICLENUBER_SUFFIX]" /></td><td>
                </tr>
              <tr>
                <td><label for="name_ext">{|Name Extern|}:</label></td>
                <td><input type="text" id="name_ext" name="name_ext" value="[NAME_EXT]" /></td><td>
              </tr>
              <tr>
                <td><label for="sort">{|Sortierung|}:</label></td>
                <td><input type="text" name="sort" id="sort" value="[SORT]" /></td><td>
              </tr>
              <tr>
                <td><label for="aktiv">{|aktiv|}:</label></td>
                <td><input type="checkbox" value="1" name="aktiv" id="aktiv" [AKTIV] /></td><td>
              </tr>
              <tr>
                <td></td><td><input type="submit" value="speichern" name="speichern" /></td><td>
              </tr>
              </table>
            </fieldset>
          </td>
        </tr>
      </tbody>
    </table>
    </form>
  [TAB1NEXT]
  </div>
<!-- tab view schließen -->
</div>

