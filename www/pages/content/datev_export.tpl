<!-- gehort zu tabview -->
<div id="tabs">
	<ul>
		<li><a href="#tabs-3">[TABTEXT]</a></li>
	</ul>
	<!-- ende gehort zu tabview -->

	<!-- erstes tab -->
	<div class="row">
		<div class="row-height">
			<div class="col-xs-12 col-sm-10 col-sm-height">
				<div class="inside-full-height">
					<div>
						[MESSAGE]

						[TAB1]
					</div>

					<fieldset>
						<legend><a style="text-transform: initial">{|EXPORT DATEVconnect online|}</a></legend>
						<form action="" method="POST" id="">
							<table>
								<tr>
									<td width="200">Typ:</td>
									<td><select name="type">
											<option value="rechnung">Rechnungen</option>
											<option value="gutschrift">Gutschriften</option>
											<option value="verbindlichkeit">Verbindlichkeiten</option>
										</select>
									</td>
								</tr>
								<tr>
									<td>Letzter &uuml;bertragener Zeitraum</td>
									<td>[LAST_MONTH]</td>
								</tr>
								<tr>
									<td width="200">Startdatum:</td>
									<td><input type="date" size="30" name="start" value="[START]"></td>
								</tr>
								<tr>
									<td width="200">Enddatum:</td>
									<td><input type="date" size="30" name="end" value="[END]"></td>
								</tr>
							</table>
							<input type="submit" value="Export" name="export">
						</form>
						[EXPORT_TABLE]
					</fieldset>

				</div>
			</div>
		</div>
	</div>

	<!-- tab view schließen -->
</div>

