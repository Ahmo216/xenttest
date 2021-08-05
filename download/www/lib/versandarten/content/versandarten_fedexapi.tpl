<br><br>
<table id="paketmarketab" align="center">
    <tr>
        <td align="center">
            <br>
            <form action="" method="post">
                [ERROR]
                <h1>{|Paketmarken Drucker f&uuml;r|} [ZUSATZ]</h1>
                <br>
                <b>{|Empf&auml;nger|}</b>
                <br>
                <br>
                <table>
                    <tr>
                        <td>
                            <table style="float:left;">
                                <tr>
                                    <td>{|Name|}:</td>
                                    <td><input type="text" size="36" value="[NAME]" name="name" id="name">
                                        <script type="text/javascript">document.getElementById("name").focus(); </script>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{|Name 2|}:</td>
                                    <td><input type="text" size="36" value="[NAME2]" name="name2"></td>
                                </tr>
                                <tr>
                                    <td>{|Name 3|}:</td>
                                    <td><input type="text" size="36" value="[NAME3]" name="name3"></td>
                                </tr>
                                <tr>
                                    <td>{|Tel.|}:</td>
                                    <td><input type="text" size="36" value="[TELEFON]" name="phone"></td>
                                </tr>
                                <tr>
                                    <td>{|E-Mail|}:</td>
                                    <td><input type="text" size="36" value="[EMAIL]" name="email"></td>
                                </tr>
                                <tr>
                                    <td>{|Land|}:</td>
                                    <td>[EPROO_SELECT_LAND]</td>
                                </tr>
                                <tr>
                                    <td>{|PLZ/Ort|}:</td>
                                    <td><input type="text" name="plz" size="5" value="[PLZ]">&nbsp;<input type="text"
                                                                                                          size="30"
                                                                                                          name="ort"
                                                                                                          value="[ORT]">
                                    </td>
                                </tr>
                                <tr>
                                    <td>{|Strasse/Hausnummer|}:</td>
                                    <td><input type="text" size="30" value="[STRASSE]" name="strasse">&nbsp;<input
                                                type="text" size="5" name="hausnummer" value="[HAUSNUMMER]"></td>
                                </tr>
                                <tr>
                                    <td>{|Service-Typ|}:</td>
                                    <td><select name="serviceType">
                                            <option>INTERNATIONAL_ECONOMY</option>
                                            <option>INTERNATIONAL_PRIORITY</option>
                                            <option>INTERNATIONAL_ECONOMY_FREIGHT</option>
                                            <option>INTERNATIONAL_FIRST</option>
                                            <option>INTERNATIONAL_PRIORITY_FREIGHT</option>
                                            <option>EUROPE_FIRST_INTERNATIONAL_PRIORITY</option>
                                            <option>FEDEX_1_DAY_FREIGHT</option>
                                            <option>FEDEX_2_DAY</option>
                                            <option>FEDEX_2_DAY_AM</option>
                                            <option>FEDEX_2_DAY_FREIGHT</option>
                                            <option>FEDEX_3_DAY_FREIGHT</option>
                                            <option>FEDEX_EXPRESS_SAVER</option>
                                            <option>FEDEX_FIRST_FREIGHT</option>
                                            <option>FEDEX_FREIGHT_ECONOMY</option>
                                            <option>FEDEX_FREIGHT_PRIORITY</option>
                                            <option>FEDEX_GROUND</option>
                                            <option>FIRST_OVERNIGHT</option>
                                            <option>GROUND_HOME_DELIVERY</option>
                                            <option [SELECTEDOVERNICHT]>PRIORITY_OVERNIGHT</option>
                                            <option>SMART_POST</option>
                                            <option>STANDARD_OVERNIGHT</option>
                                        </select></td>
                                </tr>
                                <tr id="states">
                                    <td>{|Staat|}:</td>
                                    <td>
                                        <select name="statecode">
                                            [STATES]
                                        </select>
                                    </td>
                                </tr>
                            </table>
                            <table style="float:left;">
                                [GEWICHT]

                                <tr>
                                    <td>{|H&ouml;he (in cm)|}:</td>
                                    <td>
                                        <input type="text" size="10" value="[HEIGHT]" name="height">
                                    </td>
                                </tr>
                                <tr>
                                    <td>{|Breite (in cm)|}:</td>
                                    <td>
                                        <input type="text" size="10" value="[WIDTH]" name="width">
                                    </td>
                                </tr>
                                <tr>
                                    <td>{|L&auml;nge (in cm)|}:</td>
                                    <td>
                                        <input type="text" size="10" value="[LENGTH]" name="length">
                                    </td>
                                </tr>
                                <tr>
                                    <td>{|Paketwert|}:</td>
                                    <td>
                                        <input type="text" size="10" value="[SUMME]" name="summe">
                                    </td>
                                </tr>

                            </table>
                    </tr>
                </table>
                <br><br>
                <br><br>
                <input class="btnGreen" type="submit" value="{|Paketmarke drucken|}" name="drucken">&nbsp;
                [TRACKINGMANUELL]
                &nbsp;<input type="button" value="{|Andere Versandart auswählen|}"
                             onclick="window.location.href='index.php?module=versanderzeugen&action=wechsel&id=[ID]'"
                             name="anders">&nbsp;
            </form>
        </td>
    </tr>
</table>

<script>
    var $statesSelect,
        $countrySelect;

    $(document).ready(function () {
        $statesSelect = $("#states");
        $countrySelect = $('#land');

        toggleStatesfield();

        $countrySelect.on('change', function () {
            toggleStatesfield();
        });
    });

    function toggleStatesfield() {
        var currentCountry = $countrySelect.val();

        filterStateOptions(currentCountry);
        resetStateSelection();

        if (currentCountry === 'US' || currentCountry === 'CA') {
            $statesSelect.show();
        } else {
            $statesSelect.hide();
        }
    }

    function resetStateSelection() {
        $statesSelect.find('select').val('');
    }

    function filterStateOptions(selectedCountry) {
        var option,
            options = $statesSelect.find('option');

        options.hide();

        for (var i = 0; i < options.length; i++) {
            option = $(options[i]);

            if (option.hasClass(selectedCountry)) {
                option.show();
            }
        }
    }
</script>
