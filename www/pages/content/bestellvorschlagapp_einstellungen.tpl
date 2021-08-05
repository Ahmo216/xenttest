<div id="tabs">
  <ul>
    <li><a href="#tabs-1"><!--[TABTEXT]--></a></li>
  </ul>
  <div id="tabs-1">
    [MESSAGE]
    <form method="post">
      <div class="row">
        <div class="row-height">
          <div class="col-xs-12 col-md-6 col-md-height">
            <div class="inside inside-full-height">
              <fieldset>
                <legend>{|Einstellungen|}</legend>
                <table>
                  <tr><td nowrap width="300"><label for="bedarfsstaffelnaechste">{|Bedarfsstaffel n&auml;chste X Tage|}:</label></td><td><input type="text" id="bedarfsstaffelnaechste" name="bedarfsstaffelnaechste" value="[BEDARFSSTAFFELNACHESTE]" />&nbsp;<i>{|Werte ; getrennt|}</i></td></tr>
                  <tr><td nowrap><label for="bedarfsstaffelletzte">{|Bedarfsstaffel letzte X Tage|}:</label></td><td><input type="text" id="bedarfsstaffelletzte" name="bedarfsstaffelletzte" value="[BEDARFSSTAFFELLETZTE]" /></td></tr>
                  <tr><td nowrap><label for="bezugletztetage">{|Bedarf letzte Tage beziehen auf|}:</label></td><td><select id="bezugletztetage" name="bezugletztetage">[SELBEZUGLETZTETAGE]</select></td></tr>
                  <tr><td nowrap><label for="link">{|Link in &Uuml;bersicht hinzuf&uuml;gen|}:</label></td><td><input type="text" name="link" id="link" value="[LINK]" size="40" /></td></tr>
                  <tr><td nowrap>
                      <label for="articlepicture">{|Artikelbilder anzeigen|}:</label>
                    </td>
                    <td>
                      <input type="checkbox" name="articlepicture" id="articlepicture" value="1" [ARTICLEPICTURE] />
                    </td>
                  </tr>

                  <tr><td>&nbsp;</td></tr>
                </table>
              </fieldset>
            </div>
          </div>
          <div class="col-xs-12 col-md-6 col-md-height">
            <div class="inside inside-full-height">
              <fieldset><legend>{|Zusatzfelder Erw. Bestellvorschlag|}</legend>
                <table cellspacing="5" width="100%">
                  <tr><td width="300">Spalte 1:</td><td><select id="erwbestellvorschlagtabellezusatz1" name="erwbestellvorschlagtabellezusatz1">[SELERWBESTELLVORSCHLAGTABELLEZUSATZ1]</select></td></tr>
                  <tr><td width="300">Spalte 2:</td><td><select id="erwbestellvorschlagtabellezusatz2" name="erwbestellvorschlagtabellezusatz2">[SELERWBESTELLVORSCHLAGTABELLEZUSATZ2]</select></td></tr>
                  <tr><td width="300">Spalte 3:</td><td><select id="erwbestellvorschlagtabellezusatz3" name="erwbestellvorschlagtabellezusatz3">[SELERWBESTELLVORSCHLAGTABELLEZUSATZ3]</select></td></tr>
                  <tr><td width="300">Spalte 4:</td><td><select id="erwbestellvorschlagtabellezusatz4" name="erwbestellvorschlagtabellezusatz4">[SELERWBESTELLVORSCHLAGTABELLEZUSATZ4]</select></td></tr>
                  <tr><td width="300">Spalte 5:</td><td><select id="erwbestellvorschlagtabellezusatz5" name="erwbestellvorschlagtabellezusatz5">[SELERWBESTELLVORSCHLAGTABELLEZUSATZ5]</select></td></tr>
                </table>
              </fieldset>
            </div>
          </div>
        </div>
      </div>
      <input type="submit" name="erwbestellvorschlagzusatz" id="erwbestellvorschlagzusatz" value="Speichern" style="float:right">
    </form>
    [TAB1]
    [TAB1NEXT]
  </div>
<!-- tab view schließen -->
</div>