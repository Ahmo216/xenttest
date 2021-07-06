<div id="tabs" class="mobile-ui">
	<ul>
		<li><a href="#tabs-1"></a></li>
	</ul>
	<script>
      function artselect(el) {
          $('#artikel').first().val($(el).html());
          $('#menge').val($(el).parent().parent().find('span.spanmenge').html());
          $('#menge').first().focus();
      }
	</script>
	<!-- erstes tab -->
	<div id="tabs-1">

		<div class="row">
			<div class="col-md-6 col-sm-12">
				[MESSAGE]
			</div>
		</div>

		<form class="form form-horizontal" method="POST">
			<div class="form-group form-group-lg">
				<div class="col-md-6 col-sm-12">
					<a class="btn btn-block btn-lg btn-secondary" href="index.php?module=lagermobil&action=list">
						zurück zur Übersicht
					</a>
				</div>
			</div>
			[VORARTIKEL]
			<div class="form-group form-group-lg">
				<label for="artikel" class="col-md-1 col-sm-2 control-label">Artikel</label>
				<div class="col-md-5 col-sm-10">
					<div class="input-autocomplete">
						<input type="text" name="artikel" id="artikel" class="form-control" value="[ARTIKEL]">
					</div>
				</div>
			</div>
			[NACHARTIKEL]
			<div class="form-group form-group-lg">
				<div class="col-md-offset-1 col-md-5 col-sm-offset-2 col-sm-10">
					<p>[ARTIKELLAGERPLATZ]</p>
				</div>
			</div>
			[VORLAGERZU]
			<div class="form-group form-group-lg">
				<label for="lager_platz_zu" class="col-md-1 col-sm-2 control-label">Lagerplatz zu</label>
				<div class="col-md-5 col-sm-10">
					<div class="input-autocomplete">
						<input type="text" name="lager_platz_zu" id="lager_platz_zu" class="form-control" value="[LAGER_PLATZ_ZU]"/>
					</div>
				</div>
			</div>
			[NACHLAGERZU]
			<div class="row">
				<div class="col-md-offset-1 col-md-5 col-sm-offset-2 col-sm-10">
					<p>[ARTIKELMHD]</p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-offset-1 col-md-5 col-sm-offset-2 col-sm-10">
					<p>[ARTIKELSERIENNUMMERN]</p>
				</div>
			</div>
			[VORMENGE]
			<div class="form-group form-group-lg">
				<label for="menge" class="col-md-1 col-sm-2 control-label">Menge</label>
				<div class="col-md-5 col-sm-10">
					<input type="text" name="menge" id="menge" class="form-control" value="[MENGE]"/>
				</div>
			</div>
			[NACHMENGE]
			<div class="form-group form-group-lg">
				<div class="col-sm-offset-2 col-sm-10 col-md-offset-1 col-md-5">
					<input type="submit" class="btn btn-primary btn-block btn-lg" name="speichern" id="speichern" value="[SPEICHERNTEXT]"/>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6 col-sm-12">
					[WEITEREINFOSLAGER]
				</div>
			</div>
		</form>
		[TAB1]
	</div>

	<!-- tab view schließen -->
</div>
