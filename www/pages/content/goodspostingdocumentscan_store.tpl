<!--<div id="tabs">
    <ul id="storetab">
        <li><a href="#tabs-1">[NAME]</a></li>
    </ul>
    <div id="tabs-1">-->
<div id="storecontainer">
        <form method="POST" id="frmstore">
            <div id="storeheader">[TYPE]: [NAME]</div>
            [MESSAGE]
            <input type="hidden" name="batchinfo" value="[BATCH]" />
            <input type="hidden" name="bestbeforeinfo" value="[BESTBEFORE]" />
            <input type="hidden" name="locationstoragefrom2" id="locationstoragefrom2" value="[LOCATIONSTORAGEFROM2]" />
            <table id="goodspostingdocumenttable">
                <tr><td colspan="2" nowrap><input type="button" id="stepbefore" value="&lt;" [DISABLESTEPBEFORE] /><a href="index.php?module=goodspostingdocumentscan&action=list"><input type="button" value="&Uuml;bersicht" /></a><input type="button" id="nextstep" value="&gt;" [DISABLENEXTSTEP] /></td></tr>
                [BEFOREDOCUMENTINFO]<tr><td colspan="2">[DOCUMENTINFO]</td></tr>[AFTERDOCUMENTINFO]
                [BEFOREARTICLE]<tr><td align="right" width="40%"><label for="article">{|Artikel|}:</label>&nbsp;</td><td><input type="text" autofocus="autofocus" name="article" id="article" value="[ARTICLE]" /></td></tr>[AFTERARTICLE]
                [BEFOREARTICLEINFO]
                <tr>
                    <td colspan="2">
                        Nr. [ARTICLENUMBER] | [ARTICLENAME] [AMOUNTOF]<input type="hidden" name="articleid" id="articleid" value="[ARTICLEID]" />
                    </td>
                </tr>
                [AFTERARTICLEINFO]
                [BEFOREDEFAULTSTORAGE]<tr><td>{|Standardlager|}:</td><td>[DEFAULTSTORAGE]</td></tr>[AFTERDEFAULTSTORAGE]
                <tr><td colspan="2">[ARTIKELLAGERPLATZ]</td></tr>

                [BEFORELOCATIONSTORAGEFROM]<tr><td align="right"><label for="locationstoragefrom">{|von Lagerplatz|}:</label>&nbsp;</td><td><input type="text" autofocus="autofocus" name="locationstoragefrom" id="locationstoragefrom" value="[LOCALSTORAGEFROM]" /></td></tr>[AFTERLOCATIONSTORAGEFROM]
                [BEFORELOCATIONSTORAGEFROMINFO]<tr><td align="right"><label for="locationstoragefromhidden">{|von Lagerplatz|}:</label>&nbsp;</td><td>[LOCALSTORAGEFROM]<input type="hidden" id="locationstoragefromhidden" name="locationstoragefromhidden" value="[LOCALSTORAGEFROMID]" /></td></tr>[AFTERLOCATIONSTORAGEFROMINFO]

                [BEFORELOCATIONSTORAGE]<tr><td align="right"><label for="locationstorage">{|nach Lagerplatz|}:</label>&nbsp;</td><td><input type="text" autofocus="autofocus" name="locationstorage" id="locationstorage" value="[LOCALSTORAGE]" /></td></tr>[AFTERLOCATIONSTORAGE]
                [BEFORELOCATIONSTORAGEINFO]<tr><td align="right"><label for="locationstoragehidden">{|nach Lagerplatz|}:</label>&nbsp;</td><td>[LOCALSTORAGE]<input type="hidden" id="locationstoragehidden" name="locationstoragehidden" value="[LOCALSTORAGEID]" /></td></tr>[AFTERLOCATIONSTORAGEINFO]

                [BEFOREAMOUNTINFO]<tr><td align="right"><label for="amounthidden">{|Menge|}:</label>&nbsp;</td><td>[AMOUNT]<input type="hidden" id="amounthidden" name="amounthidden" value="[AMOUNT]" /></td></tr>[AFTERAMOUNTINFO]
                <tr><td colspan="2">[STORAGEINFO]</td></tr>

                [BEFOREAMOUNT]<tr><td align="right"><label for="amount">{|Menge|}:</label>&nbsp;</td><td><input type="text" autofocus="autofocus" id="amount" name="amount" value="[AMOUNT]" /></td></tr>[AFTERAMOUNT]

                [BEFOREBESTBEFORE]<tr><td align="right"><label for="bestbefore">{|MHD|}:</label>&nbsp;</td><td><input type="text" name="bestbefore" id="bestbefore" value="[BESTBEFORE]" /></td></tr>[AFTERBESTBEFORE]
                [BEFOREBATCH]<tr><td align="right"><label for="batch">{|Charge|}:</label>&nbsp;</td><td><input type="text" name="batch" id="batch" value="[BATCH]" /></td></tr>[AFTERBATCH]
                [BEFOREBESTBEFOREINFO]<tr><td align="right">{|MHD|}&nbsp;</td><td>[BESTBEFORE]</td></tr>[AFTERBESTBEFOREINFO]
                [BEFOREBATCHINFO]<tr><td align="right">{|Charge|}&nbsp;</td><td>[BATCH]</td></tr>[AFTERBATCHINFO]


                [BEFORESR]<tr id="trsr"></tr>[AFTERSR]
                [SRNTAB]
                [BEFORESAVE]<tr><td colspan="2" align="center"><input type="submit" name="save" id="save" value="{|Buchen|}" /></td></tr>[AFTERSAVE]
            </table>
            <input type="hidden" name="step" id="step" value="[STEP]" />
            <input type="hidden" name="stepto" id="stepto" value="[STEPTO]" />
            [WEITEREINFOSLAGER]

            [TAB1]
        </form>

    <!--</div>
</div>-->

</div>
