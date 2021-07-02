<div id="tabs">
    <ul>
        <li><a href="#tabs-1"><!--[TABTEXT]--></a></li>
    </ul>
    <div id="tabs-1">
        [MESSAGE]
        <div class="row">
            <div class="row-height">
                <div class="col-xs-12 col-sm-10 col-sm-height">
                    <div class="inside inside-full-height">
                        <fieldset>
                            <legend>{|Filter|}</legend>
                            <div class="filter-box">
                                <div class="filter-block filter-inline">
                                    <!--<div class="filter-title">{|Filter|}</div>-->
                                    <ul class="filter-list">
                                        <li class="filter-item">
                                            <label for="shopimporter-amazon-sendarticles-filter-sent" class="switch">
                                                <input type="checkbox" id="shopimporter-amazon-sendarticles-filter-sent">
                                                <span class="slider round"></span>
                                            </label>
                                            <label for="shopimporter-amazon-sendarticles-filter-sent">{|gesendete|}</label>
                                        </li>
                                        <li class="filter-item">
                                            <label for="shopimporter-amazon-sendarticles-filter-deactivated" class="switch">
                                                <input type="checkbox" id="shopimporter-amazon-sendarticles-filter-deactivated">
                                                <span class="slider round"></span>
                                            </label>
                                            <label for="shopimporter-amazon-sendarticles-filter-deactivated">{|deaktivierte|}</label>
                                        </li>
                                        <li class="filter-item">
                                            <label for="shopimporter-amazon-sendarticles-filter-error" class="switch">
                                                <input type="checkbox" id="shopimporter-amazon-sendarticles-filter-error">
                                                <span class="slider round"></span>
                                            </label>
                                            <label for="shopimporter-amazon-sendarticles-filter-error">{|mit Fehlern|}</label>
                                        </li>
                                        <li class="filter-item">
                                            <label for="shopimporter-amazon-sendarticles-filter-notsent" class="switch">
                                                <input type="checkbox" id="shopimporter-amazon-sendarticles-filter-notsent">
                                                <span class="slider round"></span>
                                            </label>
                                            <label for="shopimporter-amazon-sendarticles-filter-notsent">{|nicht gesendet|}</label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="row-height">
                <div class="col-xs-12 col-sm-10 col-sm-height">
                    <div class="inside inside-full-height">
                        <fieldset>
                            <legend></legend>
                            [TAB1]
                            <label for="selaction">{|Aktion|}:</label>
                            <select id="selaction">
                                <option value="send">{|senden|}</option>
                                <option value="delete">{|l&ouml;schen|}</option>
                            </select>
                            <input type="button" value="{|ausf&uuml;hren|}" id="send" />
                        </fieldset>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-2 col-sm-height">
                    <div class="inside inside-full-height">
                        <fieldset>
                            <legend>Aktionen</legend>
                            <input type="button" class="btn btn-primary btn-block btnGreenNew" id="new" value="&#10010; Neuer Eintrag" />
                            <input type="button"
                                   class="btn btn-secondary btn-block btnGreenNew"
                                   id="newExportSendArticles"
                                   value="&#10010; neue Exportvorlage" />
                            <input type="button"
                                   class="btn btn-secondary btn-block btnGreenNew"
                                   id="newImportSendArticles"
                                   value="&#10010; neue Importvorlage" />
                            <!--<input type="button" class="btn btn-secondary btn-block btnGreenNew" id="send" value="senden" />-->
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
        [TAB1NEXT]
    </div>
    <!-- tab view schließen -->
</div>
<div id="getarticlediv" data-shopid="[ID]">
    <form id="getarticlefrm" method="post" action="index.php?module=shopimporter_amazon&action=sendarticles">

    </form>
</div>

<div id="popupTemplate">
    <fieldset>
        <legend>{|Vorlage|}:</legend>
        <select id="flatfile">
            [SELFLATFILE]
        </select>
        <div id="flatFileTemplateInfo">

        </div>
    </fieldset>
</div>
