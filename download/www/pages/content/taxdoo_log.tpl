<div id="tabs">
	<ul>
		<li><a href="#tabs-2">[TABTEXT]</a></li>
	</ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
	<div id="tabs-2">
		<div class="row">
			<div class="row-height">
				<div class="col-xs-12 col-sm-10 col-sm-height">
					<div class="inside-full-height">
						<div>
							[MESSAGE]
							[TAB2]
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-2 col-sm-height">
					<div class="inside inside-full-height">
						<fieldset>
							<legend>{|Aktion|}</legend>
							<form action="index.php?module=taxdoo&action=toggle" method="post">
								<input type="submit" class="btnBlueNew" value="[ACTIVATE_BUTTON_TEXT]">
							</form>
							<form action="index.php?module=taxdoo&action=reset" method="post">
								<input type="submit" class="btnBlueNew" value="Prozessstarter zurücksetzen">
							</form>
						</fieldset>
					</div>
				</div>
			</div>
		</div>
	</div>

<!-- tab view schließen -->
</div>

