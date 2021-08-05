<div id="tabs">
  <ul>
    <li><a href="#tabs-1"><!--[TABTEXT]--></a></li>
  </ul>
  <div id="tabs-1">
    [MESSAGE]
    <div class="row">
      <div class="row-height">
        <div class="col-xs-12 col-md-6 col-md-height">
          <div class="inside inside-full-height">
            <fieldset>
              <legend>{|Einstellungen|}</legend>
              <table>
                <tr><td width="250">{|Modul aktivieren|}:&nbsp;</td><td><input type="checkbox" name="mailversandaktivieren" id="mailversandaktivieren" [MAILVERSANDAKTIVIEREN] /></td></tr>
                <tr><td nowrap>{|Empf&auml;nger f&uuml;r Mail aus Cronjob|}:</td><td><input type="text" id="cronjobempfaenger" name="cronjobempfaenger" value="[CRONJOBEMPFAENGER]" size="40"/>&nbsp;<i>{|Werte durch ; getrennt|}</i></td></tr>
                <tr><td>{|Benachrichtigung nur an Werktagen|}:&nbsp;</td><td><input type="checkbox" name="keinemailamwochenende" id="keinemailamwochenende" [KEINEMAILAMWOCHENENDE] /></td></tr>
              </table>
            </fieldset>
          </div>
        </div>
        <div class="col-xs-12 col-md-6 col-md-height">
          <div class="inside inside-full-height">
            <fieldset>
              <legend>{|Prozessstarter|}</legend>
              <table>
                <tr><td width="250">{|Einstellungen|}:</td><td>[CRONJOBAUX]</td></tr>
              </table>
            </fieldset>
          </div>
        </div>
      </div>
    </div>

 
    [TAB1]
    [TAB1NEXT]
  </div>
<!-- tab view schließen -->
</div>
