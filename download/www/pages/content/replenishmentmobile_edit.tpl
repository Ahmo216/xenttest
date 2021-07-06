<div id="tabs">
    <ul>
        <li><a href="#tabs-1"></a></li>
    </ul>
    <!-- ende gehort zu tabview -->
    <style type="text/css">
        #lagermobiltabelle {max-width:500px;width:100%;}
        #lagermobiltabelle input[type="button"],#lagermobiltabelle input[type="submit"]
        {
            width:100%;
            border-radius: 7px;
            font-size:2em;
            min-height:2em;
            cursor:pointer;

        }
        #weitereinfoslagerdiv {
            max-height:50vh;
            overflow-y: auto;
        }
    </style>
    <script>
        $(document).ready(function() {
            $('.storagelocation').on('click', function () {
                $('#locationstorage').val($(this).html());

            });

            $('.batch').on('click',function(){
                $('#batch').val($(this).html());
                var batchamount = parseFloat($(this).parents('td').first().next().html());
                var amounthidden = parseFloat($('#amounthidden').val());
                if(!isNaN(batchamount)) {
                    $('#batchamount').val(batchamount);
                    if(batchamount > amounthidden) {
                        $('#batchamount').val(amounthidden);
                    }
                }

            });
            $('.bestbefore').on('click',function(){
                $('#bestbefore').val($(this).data('bestbefore'));
                var bestbeforemount = parseFloat($(this).data('amount'));
                var amounthidden = parseFloat($('#amounthidden').val());
                if(!isNaN(bestbeforemount)) {
                    $('#bestbeforeamount').val(bestbeforemount);
                    if(bestbeforemount > amounthidden) {
                        $('#bestbeforeamount').val(amounthidden);
                    }
                }
            });
        });
    </script>
    <!-- erstes tab -->
    <div id="tabs-1">

        [MESSAGE]
        <form method="POST">
            <table id="lagermobiltabelle">
                <tr><td colspan="2"><a href="[LINK]"><input type="button" value="zur&uuml;ck zur &Uuml;bersicht" /></a></td></tr>


                <tr>
                    <td colspan="2"><input type="hidden" name="articleid" id="articleid" value="[ARTICLEID]" />[ARTICLENAME]</td>
                </tr>
                [BEFOREEAN]<tr><td colspan="2">EAN: [EAN]</td></tr>[AFTEREAN]
                <tr><td colspan="2">Nr. [ARTICLENUMBER]</td></tr>
                [BEFOREDEFAULTLOCALSTORAGE]
                <tr>
                    <td>
                        {|Standardlager|}:
                    </td>
                    <td>
                        [DEFAULTLOCALSTORAGE]
                    </td>
                </tr>
                [AFTERDEFAULTLOCALSTORAGE]
                [BEFORELOCATIONSTORAGE]
                <tr>
                    <td><label for="locationstorage">{|Lagerplatz|}:</label></td>
                    <td><input type="text" name="locationstorage" id="locationstorage" value="[LOCALSTORAGE]" /></td>
                </tr>
                [AFTERLOCATIONSTORAGE]

                [BEFOREAMOUNTINFO]
                <tr>
                    <td><label for="amounthidden">{|Menge|}:</label></td>
                    <td>[AMOUNT]<input type="hidden" id="amounthidden" name="amounthidden" value="[AMOUNT]" /></td>
                </tr>
                [AFTERAMOUNTINFO]





                [BEFOREAMOUNT]
                <tr>
                    <td><label for="amount">{|Menge|}:</label></td>
                    <td><input type="text" id="amount" name="amount" value="[AMOUNT]" /></td>
                </tr>
                [AFTERAMOUNT]

                <tr><td>{|Von:|}</td><td>[STORAGELOCATION]</td></tr>
                [BEFORELOCATIONSTORAGEINFO]
                <tr>
                    <td><label for="locationstoragehidden">{|nach|}:</label></td>
                    <td>[LOCALSTORAGE]<input type="hidden" id="locationstoragehidden" name="locationstoragehidden" value="[LOCALSTORAGEID]" /></td>
                </tr>
                [AFTERLOCATIONSTORAGEINFO]
                <tr><td>{|ben&ouml;tigte Menge|}:</td><td>[AMOUNTNEEDED]</td></tr>
                <tr><td>{|Menge im Nachschublager|}:</td><td>[REREPLENSHMENTAMOUNT]</td></tr>
                [BEFOREBESTBEFORE]
                <tr>
                    <td><label for="bestbefore">{|MHD|}:</label></td>
                    <td><input type="text" id="bestbefore" name="bestbefore" value="[BESTBEFORE]" /></td>
                    <td><label for="bestbeforeamount">{|Menge|}:</label></td>
                    <td><input type="text" id="bestbeforeamount" name="bestbeforeamount" value="[BESTBEFOREAMOUNT]" /></td>
                </tr>
                [AFTERBESTBEFORE]
                [BEFOREBATCH]
                <tr>
                    <td><label for="batch">{|Charge|}:</label></td>
                    <td><input type="text" id="batch" name="batch" value="[BATCH]" /></td>
                    <td><label for="batchamount">{|Menge|}:</label></td>
                    <td><input type="text" id="batchamount" name="batchamount" value="[BATCHAMOUNT]" /></td>
                </tr>
                [AFTERBATCH]
                <tr>
                    <td colspan="2">
                        <div id="weitereinfoslagerdiv">
                            [STORAGEINFO]
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><input type="hidden" name="do" value="[DO]" /><input type="submit" name="save" id="save" value="[SAVETEXT]" /></td>
                </tr>
            </table>

            [WEITEREINFOSLAGER]

        </form>
        [TAB1]
    </div>


    <!-- tab view schließen -->
</div>


<script type="application/javascript">
    $(document).ready(function() {
        $('span.lagerplatz').on('click', function (){
            $('#locationstorage').val($(this).html());
        });
    });
</script>

