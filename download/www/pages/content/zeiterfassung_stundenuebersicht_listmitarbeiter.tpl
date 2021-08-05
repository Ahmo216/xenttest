<!-- gehort zu tabview -->
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">[TABTEXT]</a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
[MESSAGE]
[TAB1]
<form action="" method="post">
  <div>
    <table>
      <tr>
        <td width="130">
    	   Jahr: <select name="jahr" id="jahr" onchange="window.location.href='index.php?module=zeiterfassung_stundenuebersicht&action=listmitarbeiter&id=[ID]&jahr=' + $('select[name=jahr]').val()">[JAHRE]</select>
        </td>
        <td width="330">{|Arbeitsvertrags-Basis|}: [STUNDENWOCHE]&nbsp;h/W</td>
        <td width="310">[TOLERANZ]&nbsp;{|&Uuml;berstundentoleranz|}</td>
        <td width="300">[URLAUBJAHR]&nbsp;{|Urlaubstage pro Jahr|}</td>
    	 </tr>
    </table>
  </div>
  <div>
    <table class="mkTable">
      <tr>
        <td colspan="1"></td>
        <td colspan="6"><center>{|Stunden|}</center></td>
        <td colspan="2"><center>{|Urlaub|}</center></td>
        <td colspan="1"></td>
      </tr>
      <tr>
        <td><b>{|Monat|}</b></td>
        <td><b>{|Ist-Stunden|}</b></td>
        <td><b>{|Soll-Stunden|}</b></td>
        <td><b>{|Differenz-Stunden|}</b></td>
        <td><b>{|Stunden minus Toleranz|}</b></td>
        <td><b>{|&Uuml;berstd. eingel&ouml;st|}</b></td>
        <td><b>{|&Uuml;berstunden aktueller Stand|}</b></td>
        <td><b>{|Urlaub eingel&ouml;st|}</b></td>
        <td><b>{|Urlaub aktueller Stand|}</b></td>
        <td><b>{|Notizen|}</b></td>
      </tr>
      <tr>
        <td>{|Rest|} [JAHRVERGANGEN]</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>[RESTUEBERSTUNDEN]</td>
        <td></td>
        <td>[RESTURLAUB]</td>
        <td></td>
      </tr>
      [TABELLE]
    </table>
  </div>
</form>
[TAB1NEXT]
</div>

<!-- tab view schließen -->
</div>

