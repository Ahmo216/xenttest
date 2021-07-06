<br><br>
<table id="paketmarketab" align="center">
    <tr>
        <td align="center">
            <br>
            <form action="" method="post">
                <input type="hidden" name="versandart" value="[ZUSATZ]">
                [ERROR]
                <h1>Paketmarken Drucker f&uuml;r <font color="red">[ZUSATZ]</font></h1>
                <br>
                <table>
                    <tr>
                      <td>
                            <table>
                              <tr>
                                  <td colspan="2"><b>Empf&auml;nger</b><br><br></td>
                                </tr>
                              <tr>
                                  <td>Name:</td>
                                    <td><input type="text" size="36" value="[NAME]" name="name" id="name"><script type="text/javascript">document.getElementById("name").focus(); </script></td>
                                </tr>
                                <tr>
                                  <td>Adresszusatz:</td>
                                    <td><input type="text" size="36" value="[NAME2]" name="name2"></td>
                                </tr>
                                <tr>
                                  <td>Ansprechpartner:</td>
                                    <td><input type="text" size="36" value="[NAME3]" name="name3"></td>
                                </tr>
                                <tr>
                                    <td>Email:</td>
                                    <td><input type="text" size="36" value="[EMAIL]" name="email" required></td>
                                </tr>
                                <tr>
                                    <td>Telefonnummer:</td>
                                    <td><input type="text" size="36" value="[TELEFON]" name="phone" required></td>
                                </tr>
                                <tr>
                                  <td>Land:</td>
                                    <td>[EPROO_SELECT_LAND]</td>
                                </tr>
                                <tr>
                                  <td>PLZ/Ort:</td>
                                    <td><input type="text" name="plz" size="5" value="[PLZ]">&nbsp;<input type="text" size="30" name="ort" value="[ORT]"></td>
                                </tr>
                                <tr>
                                  <td>Strasse/Hausnummer:</td>
                                    <td><input type="text" size="30" value="[STRASSE]" name="strasse">&nbsp;<input type="text" size="5" name="hausnummer" value="[HAUSNUMMER]"></td>
                                </tr>
                            </table>
                      </td>
                    </tr>
                    <tr>
                      <td>
                            <table>
                              <tr>
                                  <td colspan="2"><b>Sendungsdetails</b><br><br></td>
                                </tr>
                                <tr>
                                    <td>Produkt:</td>
                                    <td>
                                        <select name="produktid">
                                            [SHIPPINGSERVICEOPTIONS]
                                        </select>
                                    </td>
                                </tr>
                              [GEWICHT]
                                <tr>
                                  <td colspan="2"><em>Achtung: Das angezeigte Gewicht wird auf die Anzahl der Pakete aufgeteilt,<br>
                                    und es werden pro Paket 0,5 kg Verpackungsmaterial addiert.</em></td>
                                </tr>
                                <tr>
                                  <td>Anzahl Pakete:</td>
                                    <td><input type="text" name="anzahl" size="5" value="1"></td>
                                </tr>
                                <tr>
                                  <td>Versanddatum:</td>
                                    <td><input type="text" name="versanddatum" size="10" value="[ABHOLDATUM]" id="versanddatum"></td>
                                </tr>
                            </table></td>
                    </tr>
                </table>
                <br><br>
                
                <table align="center">
                  <tr>
                      <td colspan="2"><b>Service</b></td>
                    </tr>
                    <tr>
                      <td>Nachnahme:</td>
                        <td><input type="checkbox" name="nachnahme" value="1" [NACHNAHME]> (Betrag: [BETRAG] EUR)<input type="hidden" name="betrag" value="[BETRAG]"></td>
                    </tr>
                </table>
                <br><br>
                
                [BUTTONS]
            </form>
        </td>
    </tr>
</table>
<br><br>

<script type="text/JavaScript" language="javascript">
  $(document).ready(function() {
    $("#versanddatum").datepicker({
      dateFormat: 'dd.mm.yy',
      dayNamesMin: ['SO', 'MO', 'DI', 'MI', 'DO', 'FR', 'SA'],
      firstDay: 1,
      showWeek: true,
      monthNames: ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober',  'November', 'Dezember'],
    });
  });
</script>
