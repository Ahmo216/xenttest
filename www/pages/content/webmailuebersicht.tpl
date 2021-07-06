<!-- gehort zu tabview -->
<div id="tabs">
  <ul>
    <li><a href="#tabs-1"></a></li>
  </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
	<div id="tabs-1">
		<table width="100%">
			<tr>
				<td>[SCHNELLSUCHE]</td>
			</tr>
		</table>
		<form action="" method="post">			
			<div class="row">
			<div class="row-height">
			<div class="col-xs-12 col-md-10 col-md-height">
			<div class="inside_white inside-full-height">
				
				[MESSAGE]


				<div class="filter-box filter-usersave">
					<div class="filter-block filter-inline">
						<div class="filter-title">{|Filter|}</div>
						<ul class="filter-list">
							<li class="filter-item">
								<label for="ungelesen" class="switch">
									<input type="checkbox" id="ungelesen">
									<span class="slider round"></span>
								</label>
								<label for="ungelesen">{|alle ungelesenen E-Mails anzeigen|}</label>
							</li>
							<li class="filter-item">
								<label for="antworten" class="switch">
									<input type="checkbox" id="antworten">
									<span class="slider round"></span>
								</label>
								<label for="antworten">{|dringend antworten|}</label>
							</li>
							<li class="filter-item">
								<label for="warteschlange" class="switch">
									<input type="checkbox" id="warteschlange">
									<span class="slider round"></span>
								</label>
								<label for="warteschlange">{|alle mit Markierung|}</label>
							</li>
						</ul>
					</div>
				</div>

				[TAB1]

				<fieldset>
					<legend>{|Stapelverarbeitung|}</legend>
				  [MANUELLCHECKBOX]&nbsp;{|alle markieren|}&nbsp;
				  <input type="submit" value="E-Mails l&ouml;schen" name="delete" class="btnBlue">
				</fieldset>

			</div>
			</div>
			<div class="col-xs-12 col-md-2 col-md-height">
			<div class="inside inside-full-height">
				<fieldset>
					<legend>{|Aktionen|}</legend>
					<input type="button" class="btnBlueNew" onclick="window.location.href='index.php?module=webmail&action=aktualisieren'" value="E-Mails manuell abholen" style="width:100%">
				</fieldset>
			</div>
			</div>
			</div>
			</div>
		</form>
	</div>

<!-- tab view schließen -->
</div>

