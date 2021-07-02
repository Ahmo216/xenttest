<!-- gehort zu tabview -->
<div id="tabs">
	<ul>
		<li><a href="#tabs-1">[TABTEXT]</a></li>
	</ul>
	<!-- ende gehort zu tabview -->

	<!-- erstes tab -->
	<div id="tabs-1">
		<form method="post">
			[MESSAGE]
		</form>

		[TAB1]

		[TAB1NEXT]
	</div>

	<!-- tab view schließen -->
</div>

<div id="editMandatoryFields" style="display:none;" title="Bearbeiten">
	<form method="post">
		<input type="hidden" id="mandatoryfield_entry_id">
		<fieldset>
			<legend>{|Pflichtfeld|}</legend>
			<table>
				<tr>
					<td width="100">{|Modul|}:</td>
					<td><input type="text" name="mandatoryfield_module" id="mandatoryfield_module"></td>
				</tr>
				<tr>
					<td>{|Action|}:</td>
					<td><input type="text" name="mandatoryfield_action" id="mandatoryfield_action"></td>
				</tr>
				<tr>
					<td>{|Feld-ID|}:</td>
					<td><input type="text" name="mandatoryfield_field_id" id="mandatoryfield_field_id"></td>
				</tr>
				<tr>
					<td>{|Typ|}:</td>
					<td><select name="mandatoryfield_type" id="mandatoryfield_type">
								<option value="text">Text</option>
								<option value="ganzzahl">Ganzzahl</option>
								<option value="dezimalzahl">Dezimalzahl</option>
								<option value="datum">Datum</option>
								<option value="e-mail">E-Mail</option>
								<option value="kunde">Kunde</option>
							  <option value="mitarbeiter">Mitarbeiter</option>
							  <option value="artikel">Artikel</option>
							  <option value="rechnung">Rechnung</option>
							  <option value="auftrag">Auftrag</option>
							</select>
					</td>
				</tr>
				<tr class="min_max_length">
					<td>{|Mindestl&auml;nge|}:</td>
					<td><input type="text" name="mandatoryfield_min_length" id="mandatoryfield_min_length"></td>
				</tr>
				<tr class="min_max_length">
					<td>{|Maximall&auml;nge|}:</td>
					<td><input type="text" name="mandatoryfield_max_length" id="mandatoryfield_max_length"></td>
				</tr>
				<tr class="comparator">
					<td>{|Vergleichszeichen|}:</td>
					<td>
						<select name="mandatoryfield_comparator" id="mandatoryfield_comparator">
							<option value="">nur Typsicherheit</option>
							<option value="equals">=</option>
							<option value="lowerthan">&lt;</option>
							<option value="greaterthan">&gt;</option>
						</select>
				</tr>
				<tr class="comparator">
					<td>{|Vergleichswert|}:</td>
					<td><input type="text" name="mandatoryfield_compareto" id="mandatoryfield_compareto"></td>
				</tr>
				<tr>
					<td>{|Fehlermeldung|}:</td>
					<td><input type="text" name="mandatoryfield_error_message" id="mandatoryfield_error_message" size="45"></td>
				</tr>
				<tr>
					<td>{|Pflichtfeld|}:</td>
					<td><input type="checkbox" name="mandatoryfield_mandatory" id="mandatoryfield_mandatory" value="1"></td>
				</tr>
			</table>
		</fieldset>
	</form>
</div>

<script type="text/javascript">
</script>
