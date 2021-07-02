
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
    	   {|Jahr|}: <select name="jahr" id="jahr" onchange="window.location.href='index.php?module=zeiterfassung_stundenuebersicht&action=edit&id=[ID]&jahr=' + $('select[name=jahr]').val()">[JAHRE]</select>
        </td>
        <td width="330">{|Arbeitsvertrags-Basis|}: <input type="text" name="stundenprowoche" value="[STUNDENWOCHE]" size="5">&nbsp;h/W</td>
        <td width="310"><input type="text" name="ueberstundentoleranz" value="[TOLERANZ]" size="5"> {|&Uuml;berstundentoleranz|}</td>
        <td width="300"><input type="text" name="urlaubimjahr" value="[URLAUBJAHR]" size="5"> {|Urlaubstage pro Jahr|}</td>
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
        <td>Rest [JAHRVERGANGEN]</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><input type="text" name="restueberstunden" value="[RESTUEBERSTUNDEN]" size="10"></td>
        <td></td>
        <td><input type="text" name="resturlaub" value="[RESTURLAUB]" size="10"></td>
        <td><input type="text" name="restnotiz" value="[RESTNOTIZ]" size="40"></td>
      </tr>
      [TABELLE]
    </table>
  </div>
  <div>
    <input type="submit" value="Speichern" name="speichern">
  </div>
</form>
[TAB1NEXT]
</div>

<!-- tab view schließen -->
</div>

