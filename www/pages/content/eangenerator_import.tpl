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
		<div class="row">
			<div class="row-height">
				<div class="col-xs-12 col-md-6 col-md-height">
					<div class="inside inside-full-height">
						<fieldset>
							<legend>{|CSV DATEI HOCHLADEN|}</legend>
							<form action="#" method="post" name="eangenerator_csv" enctype="multipart/form-data">
								<input type="file" name="eangenerator_file">
								<input type="submit" name="eangenerator_import" value="Importieren">
								<br />
							</form>
							<table>
								<tr>
									<td width="100">Kodierung: </td><td>UTF-8</td><td></td>
								</tr>
								<tr>
									<td>Format: </td><td width="300">"ean";"verfuegbar";</td><td><i>(bei "verfuegbar" ja oder nein verwenden)</i></td>
								</tr>
							</table>
						</fieldset>
					</div>
				</div>
				<div class="col-xs-12 col-md-6 col-md-height">
					<div class="inside inside-full-height">
            <fieldset></fieldset>
					</div>
				</div>
			</div>
		</div>
		[TAB1NEXT]
	</div>

	<!-- tab view schließen -->
</div>