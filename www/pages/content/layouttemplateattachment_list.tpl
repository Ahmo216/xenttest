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

<div id="editLayoutTemplateAttachment" style="display:none;" title="Bearbeiten">
  <form method="post">
    <input type="hidden" id="e_id">
    <fieldset>
      <legend>{|Layoutvorlage Anhang|}</legend>
  	  <table>
        <tr>
          <td><label for="e_filename">{|Dateiname|}:</label></td>
          <td><input type="text" name="e_filename" id="e_filename" size="40" /></td>
        </tr>
        <tr>
          <td width="150"><label for="e_module">{|Modul|}:</label></td>
          <td><select name="e_module" id="e_module">
              [SELOBJECTS]
              </select></td>
        </tr>
        <tr id="trarticlecategory">
          <td><label for="e_articlecategory">{|Artikelkategorie|}:</label></td>
          <td><input type="text" name="e_articlecategory" id="e_articlecategory"></td>
        </tr>
        <tr id="trgroup">
          <td><label for="e_group">{|Gruppe|}:</label></td>
          <td><input type="text" name="e_group" id="e_group"></td>
        </tr>
        <tr>
          <td><label for="e_layouttemplate">{|Layoutvorlage|}:</label></td>
          <td><input type="text" name="e_layouttemplate" id="e_layouttemplate" size="40"></td>
        </tr>
        <tr>
          <td><label for="e_language">{|Sprache|}:</label></td>
          <td><select name="e_language" id="e_language">
                [LANGUAGES]
              </select></td>
        </tr>
        <tr>
          <td><label for="e_country">{|Land|}:</label></td>
          <td><select name="e_country" id="e_country">
                [COUNTRIES]
              </select></td>
        </tr>
        <!--<tr id="moduleparameter">
          <td>{|Parameter|}:</td>
          <td><input type="text" name="e_parameter" id="e_parameter" size="40"></td>
        </tr>-->
        <tr>
          <td><label for="e_project">{|Projekt|}:</label></td>
          <td><input type="text" name="e_project" id="e_project" size="40"></td>
        </tr>
        <tr>
          <td><label for="e_active">{|Aktiv|}:</label></td>
          <td><input type="checkbox" name="e_active" id="e_active" value="1"></td>
        </tr>
      </table>
    </fieldset>
  </form>
</div>

