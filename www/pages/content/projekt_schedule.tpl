<div id="tabs">
	<ul>
		<li><a href="#tabs-1">[TABTEXT]</a></li>
	</ul>
	<div id="tabs-1">
		<link href="js/lib/jsgantt/css/jsgantt.css?v=4" type="text/css" rel="stylesheet">
		<script src="js/lib/jsgantt/jquery.fn.gantt.js?v=3"></script>

		[MESSAGE]

		<div id="project-schedule-overview"></div>
		<script id="project-schedule-jsondata" type="application/json">[SCHEDULEJSON]</script>

		<div class="row">
			<div class="row-height">
				<div class="col-xs-12 col-md-12 col-md-height">
					<div class="inside_white inside-full-height">
						<a href="#" class="button btnBlue print-button">{|Seite drucken|}</a>
						<a href="index.php?module=projekt&action=schedule&cmd=downloadcsv" class="button btnBlue">{|CSV-Export|}</a>
					</div>
				</div>
			</div>
		</div>

		[TAB1]
		[TAB1NEXT]
	</div>
</div>
