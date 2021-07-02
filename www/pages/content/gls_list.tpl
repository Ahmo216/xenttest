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

<div id="editGls" style="display:none;" title="Bearbeiten">
	<form method="post">
		<input type="hidden" id="gls_id">
		<fieldset>
			<legend>{|Adresse|}</legend>
			<table>
				<tr>
					<td width="150">{|Vorlage|}:</td>
					<td><input type="text" name="gls_vorlage" id="gls_vorlage" size="40"></td>
				</tr>
				<tr>
					<td width="150">{|Name|}:</td>
					<td><input type="text" name="gls_name" id="gls_name" size="40"></td>
				</tr>
				<tr>
					<td width="150">{|Name 2|}:</td>
					<td><input type="text" name="gls_name2" id="gls_name2" size="40"></td>
				</tr>
				<tr>
					<td width="150">{|Name 3|}:</td>
					<td><input type="text" name="gls_name3" id="gls_name3" size="40"></td>
				</tr>
				<tr>
					<td width="150">{|Strasse|}:</td>
					<td><input type="text" name="gls_strasse" id="gls_strasse" size="40"></td>
				</tr>
				<tr>
					<td width="150">{|Hausnr.|}:</td>
					<td><input type="text" name="gls_hausnr" id="gls_hausnr" size="40"></td>
				</tr>
				<tr>
					<td width="150">{|PLZ|}:</td>
					<td><input type="text" name="gls_plz" id="gls_plz" size="40"></td>
				</tr>
				<tr>
					<td width="150">{|Ort|}:</td>
					<td><input type="text" name="gls_ort" id="gls_ort" size="40"></td>
				</tr>
				<tr>
					<td width="150">{|Land|}:</td>
					<td><select name="gls_land" id="gls_land">[GLSLAENDER]</select></td>
				</tr>
				<tr>
					<td width="150">{|Adresszusatz|}:</td>
					<td><input type="text" name="gls_adresszusatz" id="gls_adresszusatz" size="40"></td>
				</tr>
				<tr>
					<td width="150">{|Telefon|}:</td>
					<td><input type="text" name="gls_telefon" id="gls_telefon" size="40"></td>
				</tr>
				<tr>
					<td width="150">{|E-Mail|}:</td>
					<td><input type="text" name="gls_email" id="gls_email" size="40"></td>
				</tr>
				<tr>
					<td width="150">{|Notiz|}:</td>
					<td><input type="text" name="gls_notiz" id="gls_notiz" size="40"></td>
				</tr>
				<tr>
					<td>{|Aktiv|}:</td>
					<td><input type="checkbox" name="gls_aktiv" id="gls_aktiv"></td>
				</tr>
			</table>
		</fieldset>
</div>
</form>


<script type="text/javascript">



</script>
