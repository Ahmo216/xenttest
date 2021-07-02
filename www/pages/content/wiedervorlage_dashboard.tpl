
[WIEDERVORLAGEPOPUP]

<div id="tabs">
	<ul>
		<li><a href="#tabs-1"></a></li>
	</ul>
	<div id="tabs-1">

		<div class="rTabs">
			<ul>
				<li class="aktiv"><a href="index.php?module=wiedervorlage&action=dashboard&mitarbeiter=[MITARBEITER]">{|Dashboard|}</a></li>
				<li><a href="index.php?module=wiedervorlage&action=pipes&mitarbeiter=[MITARBEITER]">{|Pipelines|}</a></li>
				<li><a href="index.php?module=wiedervorlage&action=table&mitarbeiter=[MITARBEITER]">{|Liste|}</a></li>
				<li><a href="index.php?module=wiedervorlage&action=creationdate&mitarbeiter=[MITARBEITER]">{|Eingangsdatum|}</a></li>
				<li><a href="index.php?module=wiedervorlage&action=closingdate&mitarbeiter=[MITARBEITER]">{|Abschlussdatum|}</a></li>
				<li><a href="index.php?module=wiedervorlage&action=winsloses&mitarbeiter=[MITARBEITER]">{|Wins/Losses|}</a></li>
				<li><a href="index.php?module=wiedervorlage&action=calendar&mitarbeiter=[MITARBEITER]">{|Kalender|}</a></li>
			</ul>
			<div class="rTabSelect">[ANSICHTSELECT]&nbsp;[MITARBEITERSELECT]</div>
			<div class="clear"></div>
		</div>

		[MESSAGE]

		<div class="row">
			<div class="row-height">
				<div class="col-sm-4 col-sm-height">
					<div class="inside_white inside-full-height">

						<h3>{|Umsatz aus Aufträge|}</h3>
						[DASHBOARDUMSATZ]

						[UMSATZALLEMITARBEITER]

						<h3>{|Gesamt &Uuml;bersicht|}</h3>
						[OPPORTUNITIESOVERVIEW]

						<h3>{|Eingang [STAGEOVERVIEW]|}</h3>
						[DASHBOARDINCOMMING]

						<h3>{|&Uuml;bersicht Abschluss|}</h3>
						[DASHBOARDOVERVIEWCOMPLETE]
					</div>
				</div>
				<div class="col-sm-4 col-sm-height">
					<div class="inside_white inside-full-height">
						<h3>{|Auftr&auml;ge im aktuellen Monat|}</h3>
						[DASHBOARDAUFTRAG]
					</div>
				</div>
				<div class="col-sm-4 col-sm-height">
					<div class="inside_white inside-full-height">
						<h3>{|Angebote im aktuellen Monat|}</h3>
						[DASHBOARDANGEBOTE]
					</div>
				</div>
			</div>
		</div>
		[TAB1NEXT]
	</div>
</div>
