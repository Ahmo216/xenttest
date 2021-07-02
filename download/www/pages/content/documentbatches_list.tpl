<div id="tabs">
    <ul>
        <li><a href="#tabs-1">{|Auftr&auml;ge|}</a></li>
        <li><a href="#tabs-2">{|in Bearbeitung|}</a></li>
    </ul>
<!-- ende gehort zu tabview -->

    <!-- erstes tab -->
    <div id="tabs-1">
        [MESSAGE]

        <div class="filter-box filter-usersave">
            <div class="filter-block filter-inline">
                <div class="filter-title">{|Filter|}</div>
                <ul class="filter-list">
                    <li class="filter-item">
                        <label for="withoutinvoice" class="switch">
                            <input type="checkbox" name="withoutinvoice" id="withoutinvoice" value="A">
                            <span class="slider round"></span>
                        </label>
                        <label for="withoutinvoice">{|Ohne Rechnung|}</label>
                    </li>
                    <li class="filter-item">
                        <label for="invoiceswithdifferrenttaxes" class="switch">
                            <input type="checkbox" name="invoiceswithdifferrenttaxes" id="invoiceswithdifferrenttaxes" value="A">
                            <span class="slider round"></span>
                        </label>
                        <label for="invoiceswithdifferrenttaxes">{|Mit steuerabweichende Rechnung|}</label>
                    </li>
                    <li class="filter-item">
                        <label for="withinvoice" class="switch">
                            <input type="checkbox" name="withinvoice" id="withinvoice" value="A">
                            <span class="slider round"></span>
                        </label>
                        <label for="withinvoice">{|Mit Rechnung|}</label>
                    </li>
                    <li class="filter-item">
                        <label for="withoutdeliverynote" class="switch">
                            <input type="checkbox" name="withoutdeliverynote" id="withoutdeliverynote">
                            <span class="slider round"></span>
                        </label>
                        <label for="withoutdeliverynote">{|Ohne Lieferschein|}</label>
                    </li>
                    <li class="filter-item">
                        <label for="withdeliverynote" class="switch">
                            <input type="checkbox" name="withdeliverynote" id="withdeliverynote">
                            <span class="slider round"></span>
                        </label>
                        <label for="withdeliverynote">{|Mit Lieferschein|}</label>
                    </li>
                    <li class="filter-item">
                        <label for="onlyshop" class="switch">
                            <input type="checkbox" name="onlyshop" id="onlyshop">
                            <span class="slider round"></span>
                        </label>
                        <label for="onlyshop">{|Nur Shopauftr&auml;ge|}</label>
                    </li>
                    <li class="filter-item">
                        <label for="hiderunning" class="switch">
                            <input type="checkbox" name="onlyshop" id="hiderunning">
                            <span class="slider round"></span>
                        </label>
                        <label for="hiderunning">{|Auftr&auml;ge mit laufenden Abfrage ausblenden|}</label>
                    </li>
                    [HOOK_FILTER_1]
                </ul>
            </div>
        </div>

        <form method="post">
            [TAB1]
            <span style="background-color: rgb(210, 236, 157);display: inline-block;width:16px;height:15px;"></span>
            <i style="color: grey">{|In Bearbeitung|}</i>
            <fieldset>
                <legend>{|Stapelverarbeitung|}</legend>
                <input type="checkbox" id="checkall" /> <label for="checkall">{|alle markieren|}</label>
                <label for="action">{|Aktion|}:</label>
                <select id="action" name="action">
                    <option value="">{|Bitte w&auml;hlen...|}</option>
                    <option value="createinvoice">{|Weiterf&uuml;hren zur Rechnung|}</option>
                    <option value="createinvoicedeliverydate">{|Weiterf&uuml;hren zur Rechnung (Rechnungsdatum = Lieferdatum setzen)|}</option>
                    <option value="createinvoiceorderdate">{|Weiterf&uuml;hren zur Rechnung (Rechnungsdatum = Auftragsdatum setzen)|}</option>
                    <option value="createdeliverynote">{|Weiterf&uuml;hren zu Lieferschein|}</option>
                    <option value="changeinvoicetaxes">{|Steuers&auml;tze in Rechnungen an Auftr&auml;gen anpassen|}</option>
                    [ACTIONOPTIONS]
                </select>

                <input type="checkbox" value="1" id="archive" name="archive" />

                <label for="archive">{|Beleg abschlie&szlig;en und archivieren|}</label>
                <input type="checkbox" value="1" id="setpayed" name="setpayed" />
                <label for="setpayed">{|Rechnung als bezahlt markieren|}</label>

                <input type="submit" value="ausf&uuml;hren" name="createbatches" />
            </fieldset>
        </form>
        [TAB1NEXT]
    </div>
    <div id="tabs-2">
        <form method="post">
            [TAB2]
            <fieldset><legend>{|Aktion|}</legend>
                <input type="checkbox" id="checkallinwork" /> <label for="checkallinwork">{|alle markieren|}</label>
                <input type="submit" value="abbrechen" name="reset" />
            </fieldset>
        </form>
    </div>
<!-- tab view schließen -->
</div>

