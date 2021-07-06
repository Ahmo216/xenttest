<!-- gehort zu tabview -->
<div id="tabs">
    <ul>
        <li><a href="#tabs-1"></a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">

[MESSAGEZUVIEL]
[POSKASSE]

  <table width="100%">
    <tr>
      <td>{|Datum heute|}:</td>
      <td>{|Kassenstand Vortag|}:</td>
      <td>{|Kassensaldo heute|}:</td>
      <td>{|Kassenstand aktuell|}:</td>
      <td>{|Letzter Kassenabschluss|}:</td>
    </tr>
    <tr>
      <td class="greybox" width="20%">[DATUM]</td>
      <td class="greybox" width="20%">[VORTAG]</td>
      <td class="greybox" width="20%">[HEUTE]</td>
      <td class="greybox" width="20%">[AKTUELL]</td>
      <td class="greybox" width="20%">[ABSCHLUSS]</td>
    </tr>
  </table>

  <div class="filter-box filter-usersave">
    <div class="filter-block filter-inline">
      <div class="filter-title">{|Filter|}</div>
      <ul class="filter-list">
        <li class="filter-item">
          <label for="einnahmen" class="switch">
            <input type="checkbox" id="einnahmen" title="Einnahmen">
            <span class="slider round"></span>
          </label>
          <label for="einnahmen">{|Einnahmen|}</label>
        </li>
        <li class="filter-item">
          <label for="ausgaben" class="switch">
            <input type="checkbox" id="ausgaben" title="Ausgaben">
            <span class="slider round"></span>
          </label>
          <label for="ausgaben">{|Ausgaben|}</label>
        </li>
        <li class="filter-item">
          <label for="mitbemerkung" class="switch">
            <input type="checkbox" id="mitbemerkung" title="mit Bemerkung">
            <span class="slider round"></span>
          </label>
          <label for="mitbemerkung">{|mit Bemerkung|}</label>
        </li>
        <li class="filter-item">
          <label for="datumvon">{|von|}:</label>
          <input type="text" id="datumvon" name="datumvon" title="Datum von" />
        </li>
        <li class="filter-item">
          <label for="datumbis">{|bis|}:</label>
          <input type="text" id="datumbis" name="datumbis" title="Datum bis" />
        </li>
      </ul>
    </div>
  </div>

[MESSAGE]
<br>

[POSDISABLESTART]
<center>
<input type="button" onclick='window.location="index.php?module=kasse&action=create&id=[ID]"' class="btnGreen" title="Neue Buchung anlegen" id="mehr" value="Neue Buchung anlegen">&nbsp;
<input type="button" onclick='window.location="index.php?module=kasse&action=abschluss&id=[ID]"' class="btnBlue" title="Kassenabschluss durchführen" id="abschluss" value="Kassenabschluss durchführen">&nbsp;
<input type="button" onclick='window.location="index.php?module=kasse&action=berechnen&id=[ID]"' class="btnBlue" title="Offene Kasse neu berechnen" id="berechnen" value="Offene Kasse neu berechnen">&nbsp;
</center>
[POSDISABLEENDE]


[TAB1]
</div>




<!-- tab view schließen -->
</div>

