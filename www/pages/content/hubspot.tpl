<!-- gehort zu tabview -->
<div id="tabs">
	<ul>
		<li><a href="#tabs-1"><!--[TABTEXT]--></a></li>
	</ul>
	<div id="tabs-1">
		[MESSAGE]
		<form method="post" id="hs-configurator-form" action="index.php?module=hubspot&action=apikey">
			<div class="row" id="docscan-module">
				<div class="row-height">
					<div class="col-xs-12 col-md-4 col-md-height">
						<div class="inside inside-full-height">
							<fieldset>
								<legend>{|HubSpot API konfigurieren|}</legend>
								<div>
									<table>
										<tr>
											<td width="150">API-Key:</td>
											<td>
												<input type="text" name="apikey" class="d_apikey" value="[API_KEY]"
															 placeholder="API KEY" required>
											</td>
										</tr>
									</table>
								</div>
							</fieldset>
							<fieldset class="[SHOW_CUSTOM_FIELD_SETTING]">
								<legend>{|Personalisierte Eigenschaften für Kontakte (Optional)|}</legend>
								<div>
									<table>
										[HUBSPOT_ADDRESS_FREE_FIELDS]
									</table>
								</div>
								<div id="hs-add-more-field">{|Weitere Personalisierte Eigenschaften hinzufügen|}</div>
							</fieldset>
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-md-height">
						<div class="inside inside-full-height">
							<fieldset>
								<legend>{|Sync Einstellungen|}</legend>
								<div>
									<table id="settings-container">
										<tbody>
										<tr>
											<td width="300">Kontakte:</td>
											<td>
												<label class="switch">
													<input id="my-checkbox" name="hs_sync_addresses" type="checkbox" [SYNC_ADDRESSES]>
													<span class="slider round"></span>
												</label>
												<label for="hs-sync-address">{|Kontakte synchronisieren|}</label>
											</td>
										</tr>
										<tr>
											<td width="300">{|Startzeit für Kontakte Synchronisation|}</td>
											<td>
												<input id="from-date-contact" name="hs_sync_addresses_from" value="[SYNC_ADDRESSES_FROM_DATE]" type="datetime-local">
											</td>
										</tr>
										<tr>
											<td width="300">{|Lead-Status für Kontakte Synchronisation|}</td>
											<td>
												<select name="hs_sync_address_status" id="select-hs_sync_address_status" >
													[LEAD_STATUS_OPTIONS]
												</select>
											</td>
										</tr>
										<tr>
											<td width="300">Deals:</td>
											<td>
												<label class="switch">
													<input id="my-checkbox" name="hs_sync_deals" type="checkbox" [SYNC_DEALS]>
													<span class="slider round"></span>
												</label>
												<label for="hs-sync-deals">{|Deals synchronisieren|}</label>
											</td>
										</tr>
										</tbody>
									</table>
								</div>
							</fieldset>
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-md-height" style="[SYNC_CUSTOM_OPTION]">
						<div class="inside inside-full-height">
							<fieldset style="[SYNC_CUSTOM_OPTION]">
								<legend>{|Custom Deal-Phase/Lead-Status|}</legend>
								<div>
									<table cellspacing="5" width="100%">
										<tr>
											<td width="300">Von Hubspot zu Xentral:</td>
											<td>
												<button style="float: right;" class="button" id="sync_hs_xt">Aktualisieren/Synchronisieren
												</button>
											</td>
										</tr>
									</table>
								</div>
							</fieldset>
						</div>
					</div>

				</div>
			</div>

			<div class="row">
				<!-- content -->
				<div class="row-height">
					<div class="col-xs-12 col-md-6 col-md-height">
						<div class="inside inside-full-height">
							<fieldset class="[SHOW_CUSTOM_DEAL_SETTING]">
								<legend>{|Hubspot Einstellung|}</legend>
								<table class="mkTableFormular" id="settings-container">
									<tbody>
									<tr>
										<td width="200">{|Gruppe für Kontakte|}:</td>
										<td>
											<input type="text" name="hs_contact_grp" class="hs-input" style="width: 100%"
														 placeholder="Hubspot" value="[HS_CONTACT_GRP]">
										</td>
									</tr>
									<tr>
										<td width="100">{|Automatisch|}:</td>
										<td>
											<label class="no-switch">
												<input id="no-matching" name="no_matching" type="radio" [CUSTOM_DEAL_NO_MATCH]>
											</label>
											<label for="no-matching">Stages bzw. Hubspot Deals 1:1 übernehmen</label>
										</td>
									</tr>
									<tr>
										<td width="100">{|Benutzerdefiniert|}:</td>
										<td>
											<label class="no-switch">
												<input id="do-matching" name="do_matching" type="radio" [CUSTOM_DEAL_MATCH]>
											</label>
											<label for="do-matching">Manuell zuordnen</label>
										</td>
									</tr>
									</tbody>
								</table>
							</fieldset>
						</div>
					</div>
					<div class="col-xs-12 col-md-6 col-md-height">
						<div class="inside inside-full-height">
							<fieldset>
								<legend>Deal-Phasen</legend>
								<table class="mkTableFormular" id="settings-container-suite">
									<tbody>
									[BLOCK]
									</tbody>
								</table>
							</fieldset>
						</div>
					</div>
				</div>
				<!-- content -->
			</div>
			<div style="margin-bottom: 10px;float: right;"><input type="submit" name="hubspot_settings" value="Speichern">
			</div>
		</form>
		[TAB1NEXT]
	</div>
</div>
