
<!-- gehort zu tabview -->
<div id="tabs">
    <ul>
        <li><a href="#tabs-1"><!--Alle Tickets--></a></li>
    </ul>

<!-- erstes tab -->
<div id="tabs-1">

  <div class="filter-box filter-usersave">
    <div class="filter-block filter-inline">
      <div class="filter-title">{|Filter|}</div>
      <ul class="filter-list">
        <li class="filter-item">
          <label for="meine" class="switch">
            <input type="checkbox" id="meine" title="Meine Tickets">
            <span class="slider round"></span>
          </label>
          <label for="meine">{|Meine Tickets|} [MEINE]</label>
        </li>
        <li class="filter-item">
          <label for="neu" class="switch">
            <input type="checkbox" id="neu" title="">
            <span class="slider round"></span>
          </label>
          <label for="neu">{|neu|} [NEU]</label>
        </li>
        <li class="filter-item">
          <label for="offen" class="switch">
            <input type="checkbox" id="offen" title="">
            <span class="slider round"></span>
          </label>
          <label for="offen">{|offen|} [OFFEN]</label>
        </li>
        <li class="filter-item">
          <label for="warten_kd" class="switch">
            <input type="checkbox" id="warten_kd" title="">
            <span class="slider round"></span>
          </label>
          <label for="warten_kd">{|warten auf Kunde|} [WARTEN_KD]</label>
        </li>
        <li class="filter-item">
          <label for="warten_e" class="switch">
            <input type="checkbox" id="warten_e" title="">
            <span class="slider round"></span>
          </label>
          <label for="warten_e">{|warten auf Intern|} [WARTEN_E]</label>
        </li>
        <li class="filter-item">
          <label for="klaeren" class="switch">
            <input type="checkbox" id="klaeren" title="">
            <span class="slider round"></span>
          </label>
          <label for="klaeren">{|klären|} [KLAEREN]</label>
        </li>
        <li class="filter-item">
          <label for="abgeschlossen" class="switch">
            <input type="checkbox" id="abgeschlossen" title="">
            <span class="slider round"></span>
          </label>
          <label for="abgeschlossen">{|abgeschlossen|}</label>
        </li>
        <li class="filter-item">
          <label for="spam" class="switch">
            <input type="checkbox" id="spam" title="">
            <span class="slider round"></span>
          </label>
          <label for="spam">{|Papierkorb|}</label>
        </li>
        <li class="filter-item">
          <label for="privat" class="switch">
            <input type="checkbox" id="privat" title="">
            <span class="slider round"></span>
          </label>
          <label for="privat">{|als persönlich markieren|}</label>
        </li>
        <li class="filter-item">
          <label for="alle" class="switch">
            <input type="checkbox" id="alle" title="">
            <span class="slider round"></span>
          </label>
          <label for="alle">{|Archiv|}</label>
        </li>
      </ul>
    </div>
  </div>

[MESSAGEPROZESS]
<!--[MESSAGE]-->
[SCHNELLSUCHE]


[TAB1]
</div>




<!-- tab view schließen -->
</div>

<div id="popup-queue" class="hide">
  <fieldset>
    <legend>{|Warteschlange zuweisen|}</legend>
    <table>
      <tr>
        <td><label for="popup-queue-input">{|Warteschlange|}:</label></td>
        <td><select id="popup-queue-input">[SELQUEUE]</select></td>
      </tr>
    </table>
  </fieldset>
</div>
<div id="popup-address" class="hide">
  <fieldset>
    <legend>{|Adresse zuweisen|}</legend>
    <table>
      <tr>
        <td><label for="popup-address-input">{|Adresse|}:</label></td>
        <td><input id="popup-address-input" size="40" /></td>
      </tr>
    </table>
  </fieldset>
</div>
