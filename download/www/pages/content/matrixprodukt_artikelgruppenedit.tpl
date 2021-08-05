<div id="tabs">
    <ul>
        <li><a href="#tabs-1">Artikelgruppe</a></li>
        <li><a href="#tabs-2">[TABTEXT2]</a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
  <h1>[NAME]</h1>
[MESSAGE]
  <form method="POST">
  <table class="tableborder" border="0" cellpadding="3" cellspacing="0" width="100%">
    <tbody>
      <tr valign="top">
        <td>
          <fieldset><legend>{|Einstellungen|}</legend>
            <table width="100%">
            <tr><td>Name:</td><td><input type="text" name="name" value="[NAME]" /></td><td></tr>
            <tr><td>Name Extern:</td><td><input type="text" name="name_ext" value="[NAME_EXT]" /></td><td></tr>
            <tr><td>Sort:</td><td><input type="text" name="sort" id="sort" value="[SORT]" /></td><td></tr>
            <tr><td>Pflicht:</td><td><input type="checkbox" value="1" name="pflicht" [PFLICHT] /></td><td></tr>
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
<div id="tabs-2">
  <h1>[NAME]</h1>
[TAB2]
</div>
<!-- tab view schließen -->
</div>

