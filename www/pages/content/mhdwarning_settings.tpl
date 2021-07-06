<!-- gehort zu tabview -->
<div id="tabs">
    <ul>
        <li><a href="#tabs-1"><!--[TABTEXT]--></a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
  <form action="" method="post">
    <div class="row">
      <div class="row-height">
        <div class="col-xs-12 col-md-6 col-md-height">
          <div class="inside inside-full-height">
            <fieldset><legend>{|Allgemein|}</legend>
              <table>
                <tr><td width="300">{|Benachrichtigung Anzahl Tage vor Ablauf|}:</td>
                  <td><input type="text" size="3" name="mhd_warnung_tage" value="[MHDWARNUNGTAGE]"></td>
                  <td></td>
                  <td></td></tr>
                  <!--  <tr><td width="25%">E-Mail Benachrichtigung</td>
                    <td><input type="text" size="60" name="mhd_warnung_email" value="[MHDWARNUNGEMAIL]">&nbsp;<i>(E-Mail Adressen mit Komma getrennt.)</i></td>
                    <td></td>
                    <td></td></tr>-->
              </table>
            </fieldset>
          </div>
        </div>
        <div class="col-xs-12 col-md-6 col-md-height">
          <div class="inside inside-full-height">
            <fieldset></fieldset>
          </div>
        </div>
      </div>
    </div>
    <input type="submit" value="{|Speichern|}" name="submit" style="float:right">
  </form>
</div>

<!-- tab view schließen -->
</div>

