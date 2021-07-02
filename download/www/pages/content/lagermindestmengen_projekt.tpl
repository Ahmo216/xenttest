<div id="tabs">
    <ul>
        <li><a href="#tabs-1">[TABTEXT]</a></li>
    </ul>
    <div id="tabs-1">

        <form action="" method="POST">
        <fieldset><legend>Lieferung für Projekt/Filiale</legend>
        </fieldset>
<div class="info">Bitte w&auml;hlen Sie das Projekt bzw. die Filiale aus.</div>
            <table width="100%">
                <tr>
                    <td colspan="2">
                        <input type="hidden" name="module" value="lagermindestmengen">
                        <input type="hidden" name="action" value="aktionen">
                        <input type="hidden" name="aktion" value="[AKTION]">
                        <ul style="list-style: none; padding: 0; margin: 0; margin-left:50px;">
                            [ADRESSEN]
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td><input type="button" name="" value="Zur&uuml;ck" onclick="window.location.href='index.php?module=lagermindestmengen&action=aktionen'"></td>
                    <td align="right"><input type="submit" name="" value="Weiter"></td>
                </tr>
            </table>

        </form>
    </div>
</div>

