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
				<div class="col-xs-12 col-md-12 col-md-height">
					<div class="inside inside-full-height">
						<fieldset>
							<legend>{|Wert|}</legend>
							<table>
								<tr>
									<td width="120">{|Gutscheinbetrag|}:</td><td><input type="text" name="voucherpos_value" id="voucherpos_value"></td>
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
							<legend>{|Kunde|}</legend>
							<table>
								<tr>
									<td width="120"><input type="radio" id="voucherpos_casual_customer" name="voucherpos_customer" value="voucherpos_casual_customer" [CASUALCUSTOMER]><label for="voucherpos_casual_customer">{|Laufkundschaft|}</label></td>
								</tr>
								<tr>
									<td width="120"><input type="radio" id="voucherpos_regular_customer" name="voucherpos_customer" value="voucherpos_regular_customer" [REGULARCUSTOMER]><label for="voucherpos_regular_customer">{|Stammkunde|}</label></td><td><input type="text" name="voucherpos_regular_customer_input" id="voucherpos_regular_customer_input" value="[VOUCHERPOSREGULARCUSTOMER]"></td>
								</tr>
							</table>
						</fieldset>
					</div>
				</div>
			</div>
		</div>

		<table>
			<tr>
				<td><input type="button" class="voucherpos-cancel" name="voucherpos_cancel_create" id="voucherpos_cancel_create" value="Abbrechen"></td>
				<td><input type="button" class="voucherpos-print" name="voucherpos_print" id="voucherpos_print" value="Gutschein drucken"></td>
				<td><input type="button" class="voucherpos-create" name="voucherpos_create" id="voucherpos_create" value="Gutschein anlegen"></td>
			</tr>
		</table>

		<div class="row">
			<div class="row-height">
				<div class="col-xs-12 col-md-12 col-md-height">
					<div class="inside inside-full-height">
						<fieldset>
							<legend>{|QR Code|}</legend>
							<div id="voucherpos_barcode"></div>
						</fieldset>
					</div>
				</div>
			</div>
		</div>


		[TAB1]

		[TAB1NEXT]
	</div>

	<!-- tab view schließen -->
</div>
