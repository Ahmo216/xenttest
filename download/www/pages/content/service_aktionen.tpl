<!-- gehort zu tabview -->
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">[TABTEXT]</a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">

<fieldset><legend>{|Aktionen|}</legend>
<!--<a href="#" onclick="if(!confirm('sas wirklich anlegen?')) return false; else window.location.href='index.php?module=adresse&action=createdokument&id=1&cmd=1';">
<table width="150" height="40"><tr><td>Ersatzteillieferung anlegen</td></tr></table></a>
<a href="#" onclick="if(!confirm('sas wirklich anlegen?')) return false; else window.location.href='index.php?module=adresse&action=createdokument&id=1&cmd=1';">
<table width="150" height="40"><tr><td>Rechnung anlegen</td></tr></table></a>
<a href="#" onclick="if(!confirm('sas wirklich anlegen?')) return false; else window.location.href='index.php?module=adresse&action=createdokument&id=1&cmd=1';">
<table width="150" height="40"><tr><td>Weitere Serviceanfrage anlegen</td></tr></table></a>-->
<input type="button" class="btnBlue" onclick="if(!confirm('Soll die Anfrage abgeschossen werden?')) return false; else window.location.href='index.php?module=service&action=abschluss&id=[ID]';" value="Ticket schlie&szlig;en">
<input type="button" class="btnBlue" onclick="if(!confirm('Soll die Anfrage abgeschlossen werden und ein Eintrag in der Zeiterfassung angelegt werden?')) return false; else window.location.href='index.php?module=service&action=abschlusszeit&id=[ID]';" value="Ticket schlie&szlig;en und Zeiterfassung anlegen">



</fieldset>
</div>


<!-- tab view schließen -->
</div>

