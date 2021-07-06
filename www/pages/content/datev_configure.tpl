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
						<legend>{|Mandandendaten Datev Connect Online|}</legend>
						<form action="" method="post" id="">
							<table>
								<tr>
									<td width="200">Import Benutzer:</td>
									<td>
										<select name="import-user">
											[IMPORT_USERS]
										</select>
									</td>

								</tr>
								<tr>
									<td width="200">Testzugang:</td>
									<td>
										<input type="checkbox" name="testsystem" [TEST_CHECKED]>
									</td>
								</tr>
								<tr>
									<td>{|Verbindlichkeit Datumsfilter|}</td>
									<td colspan="2"><select name="datevverbindlichkeitrechnungsdatum">
											<option value="0" [DATEVVERBINDLICHKEITRECHNUNGSDATUM0]>{|nach Eingangsdatum|}</option>
											<option value="1" [DATEVVERBINDLICHKEITRECHNUNGSDATUM1]>{|nach Rechnungsdatum|}</option>
										</select></td>
								</tr>
								<tr><td>Die "Datev"-Einstellungen im <a href="index.php?module=buchhaltungexport&action=list">Finanzbuchhaltungs-export</a> müssen auch eingerichtet werden. Diese wirken sich im Connect-online Export aus.</td></tr>
							</table>
							<input type="submit" value="Speichern" name="speichern">
							<input type="submit" value="Authentifizieren" name="authenticate">
						</form>
					</fieldset>

				</div>
			</div>
		</div>
	</div>

	<!-- tab view schließen -->
</div>
