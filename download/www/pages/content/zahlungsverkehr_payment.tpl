<div id="tabs">
  <ul>
    <li><a href="#tabs-1">{|offen|}</a></li>
    [TABHEADERS]

    <li><a href="#tabs-[TABSAMMELUEBERWEISUNGINDEX]">{|SEPA Sammel&uuml;berweisung|} <span class="legacy">LEGACY</span></a></li>
  </ul>
  <div id="tabs-1">
    <form method="post">
      [MESSAGE]
      <div class="row">
        <div class="row-height">
          <div class="col-xs-12 col-md-10 col-md-height">
            <div class="inside inside-full-height">
              <fieldset id="negativeliabilities" class="[CLASSNEGATIVELIABILITIES]">
                <div class="info">{|Hinweis: Es gibt zu Lieferanten offene Beträge die abgezogen werden dürfen!|}</div>
                <legend>{|offene Betr&auml;ge|}</legend>
                [TABOPEN]
              </fieldset>
              <fieldset>
                <div class="filter-box filter-usersave">
                  <div class="filter-block filter-inline">
                    <div class="filter-title">{|Filter|}</div>
                    <ul class="filter-list">
                      <li class="filter-item">
                        <label for="open" class="switch">
                          <input type="checkbox" id="open" />
                          <span class="slider round"></span>
                        </label>
                        <label for="open">{|nur Offene|}</label>
                      </li>
                      <li class="filter-item">
                        <label for="failed" class="switch">
                          <input type="checkbox" id="failed" />
                          <span class="slider round"></span>
                        </label>
                        <label for="failed">{|nur Fehlgeschlagene|}</label>
                      </li>
                      <li class="filter-item">
                        <label for="ok" class="switch">
                          <input type="checkbox" id="ok" />
                          <span class="slider round"></span>
                        </label>
                        <label for="ok">{|nur Ausgef&uuml;hrte|}</label>
                      </li>
                      <li class="filter-item">
                        <label for="onlyliability" class="switch">
                          <input type="checkbox" id="onlyliability" />
                          <span class="slider round"></span>
                        </label>
                        <label for="onlyliability">{|nur Verbindlichkeiten|}</label>
                      </li>
                      <li class="filter-item">
                        <label for="onlyreturnorder" class="switch">
                          <input type="checkbox" id="onlyreturnorder" />
                          <span class="slider round"></span>
                        </label>
                        <label for="onlyreturnorder">{|nur Gutschriften|}</label>
                      </li>
                      <li class="filter-item">
                        <label for="usediscountdate" class="switch">
                          <input type="checkbox" id="usediscountdate" />
                          <span class="slider round"></span>
                        </label>
                        <label for="usediscountdate">{|Skonto-Datum verwenden|}</label>
                      </li>
                    </ul>
                  </div>
                </div>
              </fieldset>

            </div>
          </div>
          <div class="col-xs-12 col-md-2 col-md-height">
            <div class="inside inside-full-height">
              <div class="clearfix">
                <fieldset>
                  <legend>{|&Uuml;bersicht|}</legend>
                  <table width="100%>">
                    <tr>
                      <td>{|Gesamt offen|}</td>
                    </tr>
                    <tr>
                      <td class="greybox" width="20%">[GESAMTOFFEN]</td>
                    </tr>
                  </table>
                </fieldset>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="row-height">
          <div class="col-xs-12 col-md-10 col-md-height">
            <div class="inside_white inside-full-height">
              <fieldset class="white">
                <legend> </legend>
                [TAB1]
              </fieldset>
            </div>
          </div>
          <div class="col-xs-12 col-md-2 col-md-height">
            <div class="inside inside-full-height">
              <fieldset>
                <legend>{|Aktion|}</legend>
                <table width="100%>">
                  <tr>
                    <td>
                      <input type="button" class="button button-primary button-block" value="&#10010; {|Neue Überweisung|}" data-editpaymenttransaction="0" >
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <input type="button" class="button button-secondary button-block" id="loadReturnorderBtn"
                             value="{|Gutschriften Laden|}" />
                    </td>
                  </tr>
                  [BEFORESHOWLOADLIABILITYBUTTON]
                  <tr>
                    <td>
                      <input type="button" class="button button-secondary button-block" id="loadLiabilityBtn"
                             value="{|Verbindlichkeiten Laden|}" />
                    </td>
                  </tr>
                  [AFTERSHOWLOADLIABILITYBUTTON]
                  <tr>
                    <td>
                      [VERBINDLICHKEITENLADEN]
                    </td>
                  </tr>
                </table>
              </fieldset>
            </div>
          </div>
        </div>
      </div>

      <fieldset>
        <legend>{|Stapelverarbeitung|}</legend>
        [SAMMELDRUCK]
      </fieldset>
    </form>
  </div>
  [TABLE]
  <div id="tabs-[TABSAMMELUEBERWEISUNGINDEX]">
    [TABSAMMELUEBERWEISUNG]
  </div>
</div>
<!-- ende tab view schließen -->
<div id="editReturnOrder" style="display: none;">
  <form id="editReturnOrderForm" name="editReturnOrderForm" method="post">
    <div class="row">
      <div class="row-height">
        <div class="col-xs-12 col-md-12 col-md-height">
          <div class="inside inside-full-height">
            <fieldset>
              <legend>{|Zahlungsempf&auml;nger|}</legend>
              <table class="mkTableFormular">
                <tr>
                  <td>
                    <input type="hidden" name="save" id="save" value="" />
                    <input type="hidden" name="payment_transaction_id" id="payment_transaction_id" value="" />
                    <label for="payment_transaction_address">{|Zahlungsempf&auml;nger|}:</label>
                  </td>
                  <td>
                    <input type="text" name="payment_transaction_address" id="payment_transaction_address" size="50" />
                  </td>
                </tr>
              </table>
            </fieldset>
          </div>
        </div>
      </div>
    </div>

    <div id="editReturnOrderContent">

    </div>
  </form>
</div>

<div id="editUeberweisung" style="display:none;" title="Überweisung bearbeiten">
  <form id="editUeberweisungForm" name="editUeberweisungForm">
    <input type="hidden" name="entryid" id="entryid" value="" />
    <input type="hidden" name="adresseid" id="adresseid" value="" />
    <div class="row">
      <div class="row-height">
        <div class="col-xs-12 col-md-12 col-md-height">
          <div class="inside inside-full-height">
            <fieldset class="ueberweisungstraeger">
              <legend>{|Zahlungsempf&auml;nger|}</legend>
              <table class="mkTableFormular">
                <tr>
                  <td class="headline">{|Zahlungsempf&auml;nger:|}</td>
                  <td><input type="text" name="adresse" id="adresse" value="" size="50"/></td>
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
            <fieldset class="ueberweisungstraeger">
              <legend>{|Angaben zum Zahlungsempf&auml;nger|}</legend>
              <table class="mkTableFormular">
                <tr>
                  <td class="headline">{|Name:|}</td>
                  <td><input type="text" id="empfaenger" name="empfaenger" value="" placeholder="" size="50"/></td>
                </tr>
                <tr>
                  <td class="headline">{|IBAN:|}</td>
                  <td><input id="iban" name="iban" type="text" value="" placeholder="" size="50"/></td>
                </tr>
                <tr>
                  <td class="headline">{|BIC:|}</td>
                  <td><input id="bic" name="bic" type="text" value="" placeholder="" size="50"/></td>
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
            <fieldset class="ueberweisungstraeger">
              <legend>{|Betrag|}</legend>
              <table class="mkTableFormular">
                <tr>
                  <td class="headline">{|Euro, Cent:|}</td>
                  <td style="vertical-align: top;"><input type="text" id="betrag" name="betrag" value="" placeholder="" size="50"/></td>
                </tr>
                <tr>
                  <td class="headline">{|W&auml;hrung:|}</td>
                  <td><input type="text" id="waehrung" name="waehrung" value="" placeholder="" size="50"/></td>
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
            <fieldset class="ueberweisungstraeger">
              <legend>{|Verwendungszweck|}</legend>
              <table class="mkTableFormular">
                <tr>
                  <td class="headline">{|Zeile 1:|}</td>
                  <td><input type="text" id="vz1" name="vz1" value="" placeholder="" size="50"/></td>
                </tr>
                <tr>
                  <td class="headline">{|Zeile 2:|}</td>
                  <td><input type="text" id="vz2" name="vz2" value="" placeholder="" size="50"/></td>
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
            <fieldset class="ueberweisungstraeger">
              <legend>{|Datum|}</legend>
              <table class="mkTableFormular">
                <tr>
                  <td class="headline">{|Datum:|}</td>
                  <td><input type="text" id="datumueberweisung" name="datumueberweisung" value="" placeholder="" /></td>
                </tr>
              </table>
            </fieldset>
          </div>
        </div>
      </div>
		</div>
  </form>
</div>
<div id="loadLiabilityDiv" class="hidden">
  [TABLELIABILITY]
</div>
<div id="loadReturnorderDiv" class="hidden">
  [TABLERETURNORDER]
</div>
<div id="pdfvorschaudiv" style="display:none;">
  <button id="pdfclosebutton" role="button" aria-disabled="false" title="close">
<svg xmlns="http://www.w3.org/2000/svg" width="19px" height="19px" viewBox="0 0 401.68 401.66">
  <path fill="#333" d="M401.69 60.33L341.33 0 200.85 140.5 60.35 0 0 60.33l140.5 140.5L0 341.33l60.35 60.33 140.5-140.5 140.48 140.5 60.36-60.33-140.51-140.5 140.51-140.5z"></path>
</svg>
</button>
  <iframe id="pdfiframe" src="" width="890;" style="border:none;margin-top:30px;margin-left:5px;" border=""></iframe>
</div>

