<!-- gehort zu tabview -->
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">[TABTEXT]</a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
  [MESSAGE]
  
  [TAB1]
  
  [TAB1NEXT]
</div>

<!-- tab view schließen -->
</div>

<div id="editPropertyTranslation" style="display:none;" title="Bearbeiten">
  <form method="post">
    <div class="row">
      <div class="row-height">
        <div class="col-xs-12 col-md-12 col-md-height">
          <div class="inside inside-full-height">
            <input type="hidden" id="e_id">
            <fieldset>
              <legend>{|Eigenschaft &Uuml;bersetzung|}</legend>
              <table>
                <tr>
                  <td width="90">{|Artikel|}:</td>
                  <td><input type="text" id="e_article" name="e_article" size="40"></td>
                </tr>
              </table>
            </fieldset>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="row-height">
        <div class="col-xs-12 col-md-12 col-md-height">
          <div class="inside inside-full-height">
            <fieldset>
              <legend>{|Von|}</legend>
              <table>
                <tr>
                  <td width="90">{|Sprache|}:</td>
                  <td><select name="e_languageFrom" id="e_languageFrom">
                        [SPRACHEN]
                      </select>
                  </td>
                </tr>
                <tr id="propertyde">
                  <td>{|Eigenschaft|}:</td>
                  <td><input type="text" name="e_propertyFrom" id="e_propertyFrom" size="40"></td>
                </tr>
                <tr id="propertyelse">
                  <td>{|Eigenschaft|}:</td>
                  <td><input type="text" name="e_propertyFromElse" id="e_propertyFromElse" size="40"></td>
                </tr>
                <tr id="propertyvaluede">
                  <td>{|Wert|}:</td>
                  <td><input type="text" name="e_propertyValueFrom" id="e_propertyValueFrom" size="40"></td>
                </tr>
                <tr id="propertyvalueelse">
                  <td>{|Wert|}:</td>
                  <td><input type="text" name="e_propertyValueFromElse" id="e_propertyValueFromElse" size="40"></td>
                </tr>
              </table>
            </fieldset>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="row-height">
        <div class="col-xs-12 col-md-12 col-md-height">
          <div class="inside inside-full-height">
            <fieldset>
              <legend>{|Nach|}</legend>
              <table>
                <tr>
                  <td width="90">{|Sprache|}:</td>
                  <td><select name="e_languageTo" id="e_languageTo">
                        [SPRACHEN]
                      </select>
                  </td>
                </tr>
                <tr>
                  <td>{|Eigenschaft|}:</td>
                  <td><input type="text" name="e_propertyTo" id="e_propertyTo" size="40"></td>
                </tr>
                <tr>
                  <td>{|Wert|}:</td>
                  <td><input type="text" name="e_propertyValueTo" id="e_propertyValueTo" size="40"></td>
                </tr>
              </table>
            </fieldset>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="row-height">
        <div class="col-xs-12 col-md-12 col-md-height">
          <div class="inside inside-full-height">
            <fieldset>
              <legend>{|Shop|}</legend>
              <table>
                <tr>
                  <td width="90">{|Shop|}:</td>
                  <td><input type="text" name="e_shop" id="e_shop" size="40"></td>
                </tr>
              </table>
            </fieldset>
          </div>
          </form>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">



</script>


