<form action="" method="post" name="eprooform" id="eprooform">
  <br /> 
  <table class="tableborder" border="0" cellpadding="3" cellspacing="0" width="100%">
    <tbody>
      <tr valign="top" colspan="3">
        <td>
          <table width="100%" style="background-color: #fff; border: solid 1px #000;" align="center">
            <tr>
              <td align="center">
                <br />
                <table height="200" border="0" width="400">
                  <tr valign="top"><td><b>{|Artikel|}:</b></td><td><u>[NAME]</u></td></tr> 
                  <tr valign="top"><td><b>{|Nummer|}:</b></td><td>[NUMMER]</td></tr> 
                  <tr valign="top"><td><br></td><td align="center"></td></tr>
                  [SHOWIMGSTART]<tr valign="top"><td><b>{|Bild|}:</b></td><td><img src="index.php?module=dateien&action=send&id=[DATEI]" width="200"></td></tr> [SHOWIMGEND]
                  <tr valign="top"><td><br></td><td align="center"></td></tr>
                  <tr valign="top"><td><b>{|Auswahl|}:</b></td><td align="center">
                    <select name="wunsch">
                      <option value="auswahl">{|-- Bitte ausw&auml;hlen --|}</option>
                      <option value="reparieren">{|Bitte Reparieren|}</option>
                      <option value="14tage">{|14 Tage R&uuml;ckgaberecht|}</option>
                      <option value="retoure">{|Retourensendung|}</option>
                      <option value="sofort">{|Defekt und sofort neues schicken|}</option>
                      <option value="falscher">{|Falscher Artikel geliefert|}</option>
                    </select>&nbsp;<br />
                    </td></tr> 
                  <tr valign="top"><td><br /></td><td align="center"></td></tr> 
                  [DISPLAY_WARENEINGANG_RMA_HOOK1]
                  <tr><td><b>{|Fehler Beschreibung|}:&nbsp;&nbsp;</b></td><td><textarea cols="35" rows="5" name="bemerkung">[BEMERKUNG]</textarea></td></tr>
                  <tr valign="top"><td><br /></td><td align="center"></td></tr>
                  <tr valign="top"><td><br /></td><td align="center"><input type="hidden" id="kommartikel" value="[KOMMARTIKEL]" /><input type="submit" name="submit" value="{|Speichern|}" />&nbsp;<input type="button" onclick="window.location.href='index.php?module=wareneingang&action=distriinhalt&id=[ID]'" value="{|Abbrechen|}" /></td></tr>
                </table>
                <br />
                <br />
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <!--
          <tr valign="" height="" bgcolor="" align="" bordercolor="" class="klein" classname="klein">
          <td width="" valign="" height="" bgcolor="" align="right" colspan="3" bordercolor="" classname="orange2" class="orange2">
          <input type="submit" name="submit" value="Speichern" /><br><br></td>
          </tr>
      -->
    </tbody>
  </table>
</form>

<script type="text/javascript">
 var firstsubmit = false;

  $(document).ready(function() {
    $( "#eprooform" ).submit(function( event ) {
      if(firstsubmit)
      {
        event.preventDefault();
        return false;
      }
      firstsubmit = true;
      if(!$('#kommartikel').val())return true;
      if(!confirm('Der Artikel ist ein [KOMMBEZEICHNUNG]-Artikel. Wirklich fortsetzen?'))
      {
        firstsubmit = false;
        return false;
      }
      return true;
    });

  });
</script>
