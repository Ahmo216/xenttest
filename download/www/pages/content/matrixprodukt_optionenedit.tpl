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
            <tr><td>Name:</td><td><input type="hidden" name="gruppe" value="[GRUPPE]" /><input type="text" name="name" value="[NAME]" /></td><td></tr>
            <tr><td>Name Extern:</td><td><input type="text" name="name_ext" value="[NAME_EXT]" /></td><td></tr>
            <tr><td>Sortierung:</td><td><input type="text" name="sort" id="sort" value="[SORT]" /></td><td></tr>
            <tr><td>Aktiv:</td><td><input type="checkbox" value="1" name="aktiv" [AKTIV] /></td><td></tr>
            <tr><td></td><td><input type="submit" value="speichern" name="speichern" /></td><td></tr>
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

