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

		[TAB1NEXT]
	</div>

	<!-- tab view schließen -->
</div>

<div id="editCollectiveDebitor" style="display:none;" title="Bearbeiten">
	<form method="post">
		<input type="hidden" id="collectivedebitor_id">
		<div class="row">
			<div class="row-height">
				<div class="col-xs-12 col-md-12 col-md-height">
					<div class="inside inside-full-height">
						<fieldset>
							<legend>{|Kriterien|}</legend>
							<table>
								<tr>
									<td width="150"><label for="collectivedebitor_paymentmethod">{|Zahlungsweise|}:</label></td>
									<td>
										<select name="collectivedebitor_paymentmethod" id="collectivedebitor_paymentmethod">
										[PAYMENTMETHODS]
										</select>
									</td>
								</tr>
								<tr>
									<td><label for="collectivedebitor_channel">{|Kanal (z.B. Shop)|}:</label></td>
									<td>
										<select name="collectivedebitor_channel" id="collectivedebitor_channel">
											[CHANNELS]
										</select>
									</td>
								</tr>
								<tr>
									<td><label for="collectivedebitor_country">{|Lieferland|}:</label></td>
									<td>
										<select name="collectivedebitor_country" id="collectivedebitor_country">
											[COUNTRIES]
										</select>

									</td>
								</tr>
								<tr>
									<td><label for="collectivedebitor_project">{|Projekt|}:</label></td>
									<td><input type="text" name="collectivedebitor_project" id="collectivedebitor_project"></td>
								</tr>
								<tr>
									<td><label for="collectivedebitor_group">{|Kundengruppe|}:</label></td>
									<td><input type="text" name="collectivedebitor_group" id="collectivedebitor_group"></td>
								</tr>
							</table>
						</fieldset>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="row-height">
				<div class="col-xs-12 col-md-12 col-md-height">
					<div class="inside inside-full-height">
						<fieldset>
							<legend>{|Einstellungen|}</legend>
							<table>
								<tr>
									<td width="150"><label for="collectivedebitor_account">{|Buchungskonto|}:</label></td>
									<td><input type="text" name="collectivedebitor_account" id="collectivedebitor_account"></td>
								</tr>

								<tr>
									<td><label for="collectivedebitor_storeaddress0">{|In Adresse speichern|}:</label></td>
									<td>
										<input type="radio" name="collectivedebitor_storeaddress" id="collectivedebitor_storeaddress0" value="0" checked>
										<label for="collectivedebitor_storeaddress0">{|Nie|}</label>
									</td>
								</tr>

								<tr>
									<td></td>
									<td>
										<input type="radio" name="collectivedebitor_storeaddress" id="collectivedebitor_storeaddress1" value="1">
										<label for="collectivedebitor_storeaddress1">{|Nur bei Neukunden|}</label>
									</td>
								</tr>

								<tr>
									<td></td>
									<td>
										<input type="radio" name="collectivedebitor_storeaddress" id="collectivedebitor_storeaddress2" value="2">
										<label for="collectivedebitor_storeaddress2">{|Bei allen Adressen (Überschreibt)|}</label>
									</td>
								</tr>

							</table>
						</fieldset>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>



