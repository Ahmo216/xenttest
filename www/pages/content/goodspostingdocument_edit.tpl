<div id="tabs">
    <ul>
        <li><a href="#tabs-1">{|Warenbuchungsbeleg|}</a></li>
        <li><a href="#tabs-2" class="positiontab">{|Positionen|}</a></li>
        <li><a href="index.php?module=goodspostingdocument&action=inlinepdf&id=[ID]&frame=true#tabs-3">{|Vorschau|}</a></li>
        <li><a href="#tabs-4">{|Protokoll|}</a></li>
    </ul>

    <div id="tabs-1">
        [MESSAGE]
        <form method="post">
            <div class="row">
                <div class="row-height">
                    <div class="col-xs-12 col-sm-height">
                        <div class="inside inside-full-height">
                            <table width="100%" align="center">
                                <tr>
                                    <td>&nbsp;<b style="font-size: 14pt">[BEZEICHNUNGTITEL] <font color="blue">[NUMMER]</font></b>[KUNDE][RABATTANZEIGE]</td>
                                    <td >[STATUSICONS]</td>
                                    <td width="" align="right">
                                        <label for="actionmenu">{|Aktion|}:</label> <select data-id="[ID]" id="actionmenu"><option>{|bitte w&auml;hlen ...|}</option>[MENU]</select>&nbsp;<input type="submit" name="save" value="{|Speichern|}" class="button-sticky">
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="row-height">
                    <div class="col-xs-12 col-sm-6 col-sm-height">
                        <div class="inside inside-full-height">

                            <fieldset><legend>{|Allgemein|}</legend>
                                <table class="mkTableFormular">
                                    <tr><td><label for="name">{|Bezeichnung|}:</label></td><td nowrap><input type="text" size="30" name="name" id="name" value="[NAME]" [DISABLED] />&nbsp;</td></tr>
                                    <tr><td><label for="status">{|Status|}:</label></td><td><input type="text" size="30" name="status" id="status" value="[STATUS]" disabled="disabled" /></td></tr>
                                    <tr><td><label for="document_date">{|Datum|}:</label></td><td><input type="text" size="10" name="document_date" id="document_date" value="[DOCUMENT_DATE]" [DISABLED] /></td></td></tr>
                                    <tr><td><label for="schreibschutz">{|Schreibschutz|}:</label></td><td><input type="checkbox" value="1" name="schreibschutz" id="schreibschutz" [SCHREIBSCHUTZ] /></td></tr>
                                    <tr><td><label for="project">{|Projekt|}:</label></td><td><input type="text" size="30" name="project" id="project" value="[PROJECT]" [DISABLED] /></td></tr>
                                    <tr><td><label for="document_type">{|Belegart|}:</label></td><td>
                                            <select [DISABLED] name="document_type" id="document_type">
                                                <option value="restore" [DOCUMENT_TYPE_RESTORE]>{|Umlagern|}</option>
                                                <option value="stockin" [DOCUMENT_TYPE_STOCKIN]>{|Einlagern|}</option>
                                                <option value="stockout" [DOCUMENT_TYPE_STOCKOUT]>{|Auslagern|}</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="storagesort">{|Sortierung|}:</label></td>
                                        <td>
                                            <select [DISABLED] name="storagesort" id="storagesort">
                                                <option value="localstorage" [STORAGESORT_LOCALSTORAGE]>{|Lagerplatz|}</option>
                                                <option value="fifo" [STORAGESORT_FIFO]>{|FIFO|}</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr class="storage_from">
                                        <td><label for="storage_location_from_id">{|Lagerplatz von|}:</label></td>
                                        <td><input type="text" size="30" name="storage_location_from_id" id="storage_location_from_id" [DISABLED] value="[STORAGE_LOCATION_FROM_ID]" /></td>
                                    </tr>

                                    <tr class="storage_to">
                                        <td><label for="storage_location_to_id">{|Lagerplatz nach|}</label></td>
                                        <td><input type="text" size="30" name="storage_location_to_id" id="storage_location_to_id" [DISABLED] value="[STORAGE_LOCATION_TO_ID]" /></td>
                                    </tr>
                                </table>
                            </fieldset>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-sm-height">
                        <div class="inside inside-full-height">
                            <fieldset><legend>{|Freitext|}</legend>
                                <table class="mkTableFormular" width="100%">
                                    <tr>
                                        <td>
                                            <textarea id="document_info" name="document_info">[DOCUMENT_INFO]</textarea>
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        [TAB1NEXT]
    </div>
    <div id="tabs-2">
        [MESSAGE2]
        <div class="overflow-scroll">

            <!-- // rate anfang -->
            <table width="100%" cellpadding="0" cellspacing="5" border="0" align="center">
                <tr><td>


                        <!-- // ende anfang -->
                        <table width="100%" style="" align="center">
                            <tr>
                                <td width="33%"></td>
                                <td align="center"><b style="font-size: 14pt">Warenbuchungssbeleg <font color="blue">[NUMBER]</font></b>[KUNDE][RABATTANZEIGE]</td>
                                <td width="33%" align="right">[ICONMENU2]</td>
                            </tr>
                        </table>


                        [POS]

                    </td></tr></table>

        </div>
        [TAB2NEXT]
    </div>
    <div id="tabs-3">
        [MESSAGE3]
        <!--<iframe width="100%" id="goodspostingdocumentiframe" height="100%" style="height:calc(100vh - 110px)" src="./js/lib/pdfviewer/web/viewer.html?file=[FILE]"></iframe>-->
        [TAB3NEXT]
    </div>
    <div id="tabs-4">
        [MESSAGE4]
        [TAB4]
        [TAB4NEXT]
    </div>
</div>

