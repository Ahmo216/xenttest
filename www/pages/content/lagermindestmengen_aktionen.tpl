<div id="tabs">
    <ul>
        <li><a href="#tabs-1">[TABTEXT]</a></li>
    </ul>
    <div id="tabs-1">

<fieldset><legend>{|Aktionen|}</legend>
</fieldset>
<div class="info">{|Bitte w&auml;hlen Sie eine Aktion|}</div>
<table width="100%">
<tr><td>
        <form action="" method="POST">
            <input type="hidden" name="module" value="lagermindestmengen">
            <input type="hidden" name="action" value="aktionen">
            <ul style="list-style: none; padding: 0; margin: 0; margin-left:50px;">
                <li style="padding: 10px 0;">
                    <label>
                        <input type="radio" name="aktion" value="lieferungpdf" checked="checked"> 
                        {|Manuelle Umlagerliste um Mindestmengen auff&uuml;llen zu k&ouml;nnen (PDF)|}
                    </label>
                </li>
                <li style="padding: 10px 0;">
                    <label>
                        <input type="radio" name="aktion" value="projekt"> 
                        {|Lieferung f&uuml;r Projekt / Filiale / Au&szlig;enlager anlegen|}
                    </label>
                </li>
                <li style="padding: 10px 0;">
                    <label>
                        <input type="radio" name="aktion" value="mindestmenge">
                        {|Prüfen ob die gewünschte Lagermindestmenge der Filiale im Hauptlager vorhanden ist, und somit nur Mengen vorschlagen, die auch wirklich umgelagert werden können|}
                    </label>
                </li>

<!--                 <li style="padding: 10px 0;">
                    <label>
                        <input type="radio" name="aktion" value="umlagern" disabled> 
                        Direkt Umlagerung vorschlagen und durchf&uuml;hren
                    </label>
                </li>
                 <li style="padding: 10px 0;">
                    <label>
                        <input type="radio" name="aktion" value="mobile" disabled> 
                        Umlager-Auftrag f&uuml;r Mobile Oberfl&auml;che erstellen
                    </label>
                </li>
-->
 
            </ul>
</td></tr>
<tr><td align="right"><input type="submit" name="" value="{|Weiter|}"></td></tr>
</table>

        </form>
    </div>
</div>

