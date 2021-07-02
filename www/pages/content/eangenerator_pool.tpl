<!-- gehort zu tabview -->
<div id="tabs">
	<ul>
		<li><a href="#tabs-1">[TABTEXT]</a></li>
	</ul>
	<!-- ende gehort zu tabview -->

	<!-- erstes tab -->
	<div id="tabs-1">
		[MESSAGE]

		<div class="row">
			<div class="row-height">
				<div class="col-xs-12 col-md-10 col-md-height">
					<div class="inside_white inside-full-height">

						<fieldset class="white">
							<legend>&nbsp;</legend>
							[TAB1]
						</fieldset>

					</div>
				</div>
				<div class="col-xs-12 col-md-2 col-md-height">
					<div class="inside inside-full-height">

						<fieldset>
							<legend>{|Aktionen|}</legend>
							<input class="btnGreenNew" type="button" name="neuedit" id="eangenerator_create_button" value="&#10010; {|Neuen Eintrag anlegen|}">
						</fieldset>

					</div>
				</div>
			</div>
		</div>

		[TAB1NEXT]
	</div>

	<!-- tab view schließen -->
</div>

<div id="editEanGenerator" style="display:none;" title="Bearbeiten">
	<div class="row">
		<div class="row-height">
			<div class="col-xs-12 col-md-12 col-md-height">
				<div class="inside inside-full-height">
					<form method="post">
						<input type="hidden" id="eangenerator_id">
						<fieldset>
							<legend>{|EAN Generator|}</legend>
							<table>
								<tr>
									<td width="80"><label for="eangenerator_ean">{|EAN|}:</label></td>
									<td><input type="text" name="eangenerator_ean" id="eangenerator_ean"></td>
								</tr>
								<tr>
									<td><label for="eangenerator_available">{|Verf&uuml;gbar|}:</label></td>
									<td><input type="checkbox" name="eangenerator_available" id="eangenerator_available" value="1"></td>
								</tr>
								<tr>
									<td valign="top"><label for="eangenerator_articles">{|Artikel|}:</label></td>
									<td><span id="eangenerator_articles">keine</span></td>
								</tr>
							</table>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
