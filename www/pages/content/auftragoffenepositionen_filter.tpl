<fieldset data-filter="auftragoffenepositionen" class="table_filter" style="display:none">
	<legend>{|Filter|}</legend>
	<table width="100%">
		<tr>
			<td colspan="2">
				<!-- <input type="text" name="suche" value="" style="width: 48.35%"> -->
			</td>
		</tr>
		<tr>
			<td width="100%" valign="top">
				<!-- Spalte1 -->
				<div class="table_filter_container table_filter_container_left">
					<table>
						<tr>
							<td>Datum von:</td>
							<td><input type="text" name="datum_von" id="datum_von" value=""></td>
						
						
							<td>Datum bis:</td>
							<td><input type="text" name="datum_bis" id="datum_bis" value="" id=""></td>
							<td>Projekt:</td>
							<td><input type="text" name="projekt" id="projekt" value="" id=""></td>
	
						</tr>

					</table>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="right">
				<button onclick="table_filter.clearParameters('auftragoffenepositionen');">Alles zurücksetzen</button>
				<button onclick="table_filter.setParameters('auftragoffenepositionen');">Filter anwenden</button>
			</td>
		</tr>
	</table>
</fieldset>

<style>
.table_filter_container {
	border: 1px solid #d7d7d7;
	margin: 0 5px 10px 0;
	padding: 5px;
}

.table_filter_container_right {
	margin: 0 0 10px 5px;
}
</style>









