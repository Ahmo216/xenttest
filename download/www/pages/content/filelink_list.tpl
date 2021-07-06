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

    <div class="row">
    <div class="row-height">
    <div class="col-xs-12 col-md-10 col-md-height">
    <div class="inside-white inside-full-height">
      [TAB1]
      <i style="float:right; font-size:10px;color:#6d6d6f;"><span style="color:red">*</span> Interne Bemerkung vorhanden </i>
    </div>
    </div>
    <div class="col-xs-12 col-md-2 col-md-height">
    <div class="inside inside-full-height">
      <fieldset>
        <legend>{|Aktionen|}</legend>
        <input type="button" class="btnGreenNew" name="newfilelink" value="&#10010; Neuer Eintrag" onclick="FileLinkEdit(0);">
      </fieldset>
    </div>
    </div>
    </div>
    </div>
  
    [TAB1NEXT]
  </div>

<!-- tab view schließen -->
</div>

<div id="editFileLink" style="display:none;" title="Bearbeiten">
  <form method="post">
    <input type="hidden" id="e_id">
    <input type="hidden" id="e_article_id" value="[ID]">
    <fieldset>
  	  <legend>{|Verkn&uuml;pfung|}</legend>
    	<table>
        <tr>
          <td width="150">{|Bezeichnung|}:</td>
          <td><input type="text" name="e_label" id="e_label" size="40"></td>
        </tr>
        <tr>
          <td>{|Dateipfad|}:</td>
          <td><input type="text" name="e_file_link" id="e_file_link" size="40"></td>
        </tr>
        <tr>
          <td>{|Interne Bemerkung|}:</td>
          <td><textarea name="e_internal_note" id="e_internal_note" rows="5" cols="30"></textarea></td>
        </tr>
      </table>
    </fieldset>    
  </div>
</form>

<script type="text/javascript">

</script>
