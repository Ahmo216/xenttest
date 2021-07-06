<div id="tabs">
    <ul>
        <li><a href="#tabs-1"><!--[TABTEXT]--></a></li>
    </ul>
<!-- ende gehort zu tabview -->
    <!-- erstes tab -->
    <div id="tabs-1">
        [MESSAGE]
        <div class="row">
            <div class="row-height">
                <div class="col-xs-12 col-sm-10 col-sm-height">
                    <div class="inside inside-full-height">
                        <fieldset>
                            <legend></legend>
                            [TAB1]
                        </fieldset>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-2 col-sm-height">
                    <div class="inside inside-full-height">
                        <fieldset>
                            <legend>Aktionen</legend>
                            <input type="button" class="btnGreenNew" id="new" value="&#10010; Neuer Eintrag" />
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
        [TAB1NEXT]
    </div>
<!-- tab view schließen -->
</div>

<div id="popupattatch" data-id="[ID]">
    <form method="post" id="frmnewoffer">
        <input type="hidden" value="newoffer" name="newoffer" />
        <div class="row">
            <div class="row-height">
                <div class="col-xs-12 col-md-12 col-sm-height">
                    <div class="inside inside-full-height">
                        <fieldset><legend>{|Neues angeh&auml;ngtes Angebot|}</legend>
                            <table>
                                <tr>
                                    <td><label for="popuparticle">{|Artikel|}:</label></td>
                                    <td><input type="text" id="popuparticle" name="article" />
                                        <span class="red"><b id="popuparticleinfo"></b></span>
                                    <input id="lastarticle" type="hidden" />
                                    <input id="lastean" type="hidden" />
                                    </td>
                                </tr>
                            </table>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="row-height">
                <div class="col-xs-12 col-md-12 col-sm-height">
                    <div class="inside inside-full-height">
                        <fieldset><legend>{|Bestehene Angebote|}</legend>
                            <table id="newoffertable">
                                <tr>
                                    <td>{|Marktplatz|}</td>
                                    <td>{|ASIN|}</td>
                                    <td>{|Title|}</td>
                                    <td>{|Typ|}</td>
                                    <td>{|Amazon FBM Versandvorlagen|}</td>
                                    <td>{|SKU|}</td>
                                    <td>{|SKU FBA|}</td>
                                    <td>{|eigener Preis|}</td>
                                    <td>{|Zustand|}</td>
                                    <td>{|konkurrierende Preise|}</td>
                                </tr>
                                [POPUPMARKETPLACES]
                            </table>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="row-height">
            <div class="col-xs-12 col-md-12 col-sm-height">
                <div class="inside inside-full-height">
                    <fieldset><legend>{|Suche|}</legend>
                        <table>
                            <tr>
                                <td><label for="searchtype">{|Suche|}:</label></td>
                                <td>
                                    <select id="searchtype">
                                        <option value="ASIN">ASIN</option>
                                        <option value="EAN">EAN</option>
                                        <option value="ISBN">ISBN</option>
                                        <option value="query">Textsuche</option>
                                    </select>
                                </td>
                                <td><label for="popupasin">{|Begriff|}:</label></td>
                                <td><input type="text" id="popupasin" name="asin" /></td>
                                <!--<td><label for="searchinmarketplace">{|in Marktplatz|}:</label></td>
                                    <td>
                                        <select id="searchinmarketplace">
                                            <option value="all">{|Alle|}</option>
                                            [SELSEARCHINMARKETPLACE]
                                        </select>
                                    </td>-->
                                <td><input type="button" id="searchasin" value="suchen" /></td>
                            </tr>
                        </table>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="row-height">
            <div class="col-xs-12 col-md-12 col-sm-height">
                <div class="inside inside-full-height">
                    <fieldset>
                        <legend>{|Ergebnis|}</legend>
                        <div id="searchresultsdiv">
                            <table id="searchresults">
                                <tr id="searchresultshead" class="hide">
                                    <td>{|Vorschau|}</td>
                                    <td>{|Marktplatz|}</td>
                                    <td>{|ASIN|}</td>
                                    <td>{|Title|}</td>
                                    <td>{|Hersteller|}</td>
                                    <td>{|Marke|}</td>
                                    <td>{|Variante|}</td>
                                    <td>{|übernehmen|}</td>
                                </tr>
                            </table>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>
