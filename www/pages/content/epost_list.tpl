
<div id="tabs">
	<ul>
		<li><a href="#tabs-1">[TABTEXT]</a></li>
	</ul>
	<!-- ende gehort zu tabview -->

	<!-- erstes tab -->
	<div id="tabs-1">
		[MESSAGE]
		[TAB1]

		<form action="index.php?module=epost&action=uebertragen" method="post">
			<div class="clearfix">
				<fieldset>
					<legend>{|Filter|}</legend>
					<div>
						<label class="switch">&nbsp;&nbsp;
							<input type="checkbox" id="epost_auch_versendete" name="epost_auch_versendete"
										 class="datatable-filter"
										 data-datatable-filter="epost_auch_versendete"
										 data-datatable-target="epost_files">
							<span class="slider round"></span>
                {|Auch versendete anzeigen|}
						</label>
						<label>
								<input type="text" id="epost_date" name="epost_date"
											 class="datatable-filter"
											 data-datatable-filter="epost_date"
											 data-datatable-target="epost_files"
											 style="margin-left: 25mm">
							{|Rechnungsdatum|}
						</label>
					</div>
				</fieldset>
			</div>

		[FILES_LIST]

			<input type="submit" value="&Uuml;bertragen">
		</form>

		[TAB1NEXT]
	</div>

	<!-- tab view schließen -->
</div>
