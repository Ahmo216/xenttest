<div id="tabs">
    <ul>
        <li><a href="#tabs-1">{|offen|}</a></li>
        <li><a href="#tabs-2">{|abgeschlossen|}</a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
[MESSAGE]
[TAB1]
[TAB1NEXT]
</div>


<!-- erstes tab -->
<div id="tabs-2">
[MESSAGE]
[VORABSCHLIESSEN]
<center><a href="#" onclick="if(confirm('{|Sollen wirklich alle Kommissionierungen auf abgeschlossen gesetzt werden? Dies kann nicht rückgängig gemacht werden|}'))window.location='index.php?module=kommissionierlauf&action=ferigsetzen'"><input type="button" value="{|Alle offenen Kommissionierungen auf fertig setzen|}" /></a></center>
[NACHABSCHLIESSEN]
[TAB2]
[TAB2NEXT]
</div>

<!-- tab view schließen -->
</div>

