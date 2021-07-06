<div id="buyversion" data-hubspotevent="[RENTPOPUPHUBSPOTEVENT]"
     data-hubspoteventok="[RENTPOPUPOKHUBSPOTEVENT]"
     data-hubspoteventerror="[RENTPOPUPERRORHUBSPOTEVENT]"
     data-hubspoteventabort="[RENTPOPUPABORTHUBSPOTEVENT]"
     data-hubspoteventinit="[RENTPOPUPINITHUBSPOTEVENT]"
>
    <fieldset>
        <legend>{|xentral Home DIY mieten|}</legend>
        [BEFOREDEMOINFO]
        <p>Eine User-Lizenz gibt es bereits für [USERPRICE] Euro, Hosting optional.</p>
        [AFTERDEMOINFO]

        <p class="right">Ich möchte folgende User-Lizenzen buchen.</p>

        <div class="clearfix"></div>
        <table id="customerInfoTable">
            <tr>
                <td>
                    <label for="customercompany">{|Firmenname|}</label>
                </td>
                <td colspan="3">
                    <input [DISABLEDFRM] type="text" id="customercompany" name="customercompany" value="[CUSTOMERCOMPANY]" size="40" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="customeremail">{|Email|}</label>
                </td>
                <td colspan="3">
                    <input [DISABLEDFRM] type="text" id="customeremail" name="customeremail" value="[CUSTOMEREMAIL]" size="40" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="customername">{|Ansprechpartner|}</label>
                </td>
                <td colspan="3">
                    <input [DISABLEDFRM] type="text" id="customername" name="customername" value="[CUSTOMERNAME]" size="40" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="customerstreet">{|Straße|}</label>
                </td>
                <td colspan="3">
                    <input [DISABLEDFRM] type="text" id="customerstreet" name="customerstreet" value="[CUSTOMERSTREET]" size="40" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="customeraddress2">{|Adresszusatz|}</label>
                </td>
                <td colspan="3">
                    <input [DISABLEDFRM] type="text" id="customeraddress2" name="customeraddress2" value="[CUSTOMERADDRESS2]" size="40" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="customerzip">{|PLZ|}</label>
                </td>
                <td>
                    <input [DISABLEDFRM] type="text" id="customerzip" name="customerzip" value="[CUSTOMERZIP]" size="5" />
                </td>
                <td>
                    <label for="customercity">{|Ort|}</label>
                </td>
                <td>
                    <input [DISABLEDFRM] type="text" id="customercity" name="customercity" value="[CUSTOMERCITY]" size="30" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="customercountry">{|Land|}</label>
                </td>
                <td colspan="3">
                    <select [DISABLEDFRM] id="customercountry" name="customercountry">
                        [COUNTRYSEL]
                    </select>
                </td>
            </tr>

            <tr>
                <td>
                    <label for="customerbank">{|Bank|}</label>
                </td>
                <td colspan="3">
                    <input [DISABLEDFRM] type="text" id="customerbank" name="customerbank" value="[CUSTOMERBANK]" size="40" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="customerbankname">{|Inhaber|}</label>
                </td>
                <td colspan="3">
                    <input [DISABLEDFRM] type="text" id="customerbankname" name="customerbankname" value="[CUSTOMERBANKNAME]" size="40" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="customeriban">{|IBAN|}</label>
                </td>
                <td colspan="3">
                    <input [DISABLEDFRM] type="text" id="customeriban" name="customeriban" value="[CUSTOMERIBAN]" size="40" />
                </td>
            </tr>

            <tr>
                <td>
                    <label for="customerbic">{|BIC|}</label>
                </td>
                <td colspan="3">
                    <input [DISABLEDFRM] type="text" id="customerbic" name="customerbic" value="[CUSTOMERBIC]" size="40" />
                </td>
            </tr>
        </table>

        <table class="right">
            <tr>
                <td><label for="customeruser">{|Benutzer|}</label></td>
                <td colspan="3">
                    <div class=counter-component>
                        <div class="sub-button noselect">-</div>
                        <input type=text class="counter-component-input" value="[CUSTOMERUSERDEMO]"
                               id="customeruser" name="customeruser" />
                        <div class="plus-button noselect">+</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <div>
                        <strong id="landing-total-price-popup" data-usercount="[CUSTOMERUSERDEMO]">[USERPRICE]</strong><strong> €</strong><br>
                        <span id="landing-total-price-timespan-popup">[CUSTOMERUSERDEMO] User/Monat</span>
                        <br><span>inkl. [CLOUDPRICE] € Hosting*</span>
                    </div>
                </td>
            </tr>
        </table>
    </fieldset>

    <fieldset id="fieldsetmodules">
        <legend>{|Module|}</legend>
        <div id="buyedmodule">

        </div>
    </fieldset>
</div>
