[PAYMENTFILTER]
<div class="row">
    <div class="row-height">
        <div class="col-xs-12 col-md-9 col-md-height">
            <div class="inside inside-full-height">
                <fieldset class="white maintab">
                    <legend> </legend>
                    [SUBTAB]
                </fieldset>
            </div>
        </div>
        <div class="col-xs-12 col-md-3 col-md-height">
            <div class="inside inside-full-height">
                <fieldset class="subtab">
                    <legend>{|Transaktionsmonitor|}</legend>
                    [LASTTRANSACTIONS]
                </fieldset>
            </div>
        </div>
    </div>
</div>
<fieldset>
    <legend>{|Stapelverarbeitung|}</legend>
    <input type="checkbox" id="all[PAYMENTACCOUNTID]" data-paymentaccountid="[PAYMENTACCOUNTID]" class="selectall" />
    <label for="all[PAYMENTACCOUNTID]">{|alle markieren|}</label>
    <input type="button" class="btnGreen dopayment" value="{|Zahlung ausf&uuml;hren|}"
           data-paymentaccountid="[PAYMENTACCOUNTID]" />
</fieldset>
