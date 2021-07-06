<div id="tabs">
    <ul>
        <li><a href="#tabs-1"></a></li>
    </ul>
    <div id="tabs-1">
        <div class="row">
            [MESSAGE]
        </div>
        <div class="row">
            <div class="row-height">
                <div class="col-xs-12 col-sm-6 col-sm-height">
                    <div class="inside inside-full-height">
                        <form action="" method="post" id="snap_form">
                            <input name="snapaddy" value="snapaddy" type="hidden">
                            <fieldset>
                                <legend>snapADDY API</legend>
                                <table width="100%" cellspacing="5">
                                    <tbody>
                                    <tr>
                                        <td width="300">API Token:</td>
                                        <td><input id="api-key"
                                                   name="api-key"
                                                   value="[KEY]"
                                                   placeholder=""
                                                   size="50"
                                                   autocomplete="off"
                                                   type="password">
                                            <br><br>
                                            <a id="api-test">API Key testen</a>
                                            <span id="api-test-result"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="300"></td>
                                        <td>
                                            <input name="submit" value="Speichern" type="submit">
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </fieldset>
                        </form>

                            <fieldset>
                                <legend>Direktimport</legend>
                                <table width="100%" cellspacing="5">
                                    <tbody>
                                    <tr>
                                        <td width="300">Authorization-Header:</td>
                                        <td><input id="browser-key"
                                                   name="browser-key"
                                                   value="[BROWSER-KEY]"
                                                   placeholder=""
                                                   size="50"
                                                   readonly="readonly"
                                                   autocomplete="off"
                                                   type="text"></td>
                                    </tr>
                                    <tr>
                                        <td width="300"><label for="endpoint">URL:</label></td>
                                        <td><input id="endpoint"
                                                   value=""
                                                   placeholder="Bitte aktiviere JavaScript"
                                                   readonly="readonly"
                                                   type="text"
                                                   size="50"
                                                   autocomplete="off"></td>
                                    </tr>
                                    <tr>
                                        <td width="300"></td>
                                        <td>
                                            <input id="browser-reset" value="Authorization-Header erneuern" type="submit">
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </fieldset>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-sm-height">
                    <div class="inside inside-full-height">
                        [LOGTABLE]
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
