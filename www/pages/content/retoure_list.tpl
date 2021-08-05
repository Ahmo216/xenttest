<div id="tabs">
  <ul>
    <li><a href="#tabs-1">{|Retouren|}</a></li>
    <li><a href="#tabs-2">{|nicht versendete Retouren|}</a></li>
    <li><a href="#tabs-3">{|in Bearbeitung|}</a></li>
  </ul>
<!-- ende gehort zu tabview -->

  <!-- erstes tab -->
  <div id="tabs-1">

    <div class="row">
      <div class="row-height">
        <div class="col-xs-12 col-md-8 col-md-height">
          <div class="inside inside-full-height">


            <div class="filter-box filter-usersave">
              <div class="filter-block filter-reveal">
                <div class="filter-title">{|Status|}<span class="filter-icon"></span></div>
                <ul class="filter-list">
                  <li class="filter-item"><input type="checkbox" id="retoureoffene"><label for="retoureoffene">{|offen|}</label></li>
                  <li class="filter-item"><input type="checkbox" id="retourestornierte"><label for="retourestornierte">{|storniert|}</label></li>
                  <li class="filter-item"><input type="checkbox" id="retoureabgeschlossene"><label for="retoureabgeschlossene">{|abgeschlossen|}</label></li>
                  <li class="filter-item"><input type="checkbox" id="retoureversendete"><label for="retoureversendete">{|versendet|}</label></li>
                </ul>
              </div>

              <div class="filter-block filter-inline">
                <div class="filter-title">{|Fortschritt|}</div>
                <ul class="filter-list">
                  <li class="filter-item">
                    <label for="retoureangekuendigt" class="switch">
                      <input type="checkbox" id="retoureangekuendigt">
                      <span class="slider round"></span>
                    </label>
                    <label for="retoureangekuendigt">{|angek&uuml;ndigt|}</label>
                  </li>
                  <li class="filter-item">
                    <label for="retoureeingegangen" class="switch">
                      <input type="checkbox" id="retoureeingegangen">
                      <span class="slider round"></span>
                    </label>
                    <label for="retoureeingegangen">{|eingegangen|}</label>
                  </li>
                  <li class="filter-item">
                    <label for="retouregeprueft" class="switch">
                      <input type="checkbox" id="retouregeprueft">
                      <span class="slider round"></span>
                    </label>
                    <label for="retouregeprueft">{|gepr&uuml;ft|}</label>
                  </li>
                  <li class="filter-item">
                    <label for="retoureerledigt" class="switch">
                      <input type="checkbox" id="retoureerledigt">
                      <span class="slider round"></span>
                    </label>
                    <label for="retoureerledigt">{|erledigt|}</label>
                  </li>
                </ul>
              </div>
            </div>

          </div>
        </div>
        <div class="col-xs-12 col-md-4 col-md-height">
          <div class="inside inside-full-height">
            <fieldset style="min-height:70px;">
              <legend>{|Scan|}</legend>
              <form method="post">
                <div class="clear"></div>
                <div class="filter-item"><label for="scan">{|Eingabe|}:</label> <input type="text" id="scan" name="scan" /></div>
              </form>
            </fieldset>

          </div>
        </div>
      </div>
    </div>



    [MESSAGE]
    [TAB1]
    [TAB1NEXT]
  </div>


  <!-- erstes tab -->
  <div id="tabs-2">
    [MESSAGE2]
    [TAB2]
    [TAB2NEXT]
  </div>

  <!-- erstes tab -->
  <div id="tabs-3">
    [MESSAGE3]
    [TAB3]
    [TAB2NEXT]
  </div>

<!-- tab view schließen -->
</div>

<script type="text/javascript">
  document.getElementById('scan').focus();
</script>
