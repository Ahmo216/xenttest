
[WIEDERVORLAGEPOPUP]

<div id="tabs">
	<ul>
		<li><a href="#tabs-1"></a></li>
	</ul>
	<div id="tabs-1">

		<div class="rTabs">
			<ul>
				<li><a href="index.php?module=wiedervorlage&action=dashboard&mitarbeiter=[MITARBEITER]">{|Dashboard|}</a></li>
				<li><a href="index.php?module=wiedervorlage&action=pipes&mitarbeiter=[MITARBEITER]">{|Pipelines|}</a></li>
				<li class="aktiv"><a href="index.php?module=wiedervorlage&action=table&mitarbeiter=[MITARBEITER]">{|Liste|}</a></li>
				<li><a href="index.php?module=wiedervorlage&action=creationdate&mitarbeiter=[MITARBEITER]">{|Eingangsdatum|}</a></li>
				<li><a href="index.php?module=wiedervorlage&action=closingdate&mitarbeiter=[MITARBEITER]">{|Abschlussdatum|}</a></li>
				<li><a href="index.php?module=wiedervorlage&action=winsloses&mitarbeiter=[MITARBEITER]">{|Wins/Losses|}</a></li>
				<li><a href="index.php?module=wiedervorlage&action=calendar&mitarbeiter=[MITARBEITER]">{|Kalender|}</a></li>
			</ul>
			<div class="rTabSelect">[ANSICHTSELECT]&nbsp;[MITARBEITERSELECT]</div>
			<div class="clear"></div>
		</div>


		<div class="filter-box filter-usersave">
			<div class="filter-block filter-inline">
				<div class="filter-title">{|Status|}</div>
				<ul class="filter-list">
					<li class="filter-item">
						<label for="prio" class="switch">
							<input type="checkbox" id="prio">
							<span class="slider round">
						</label>
						<label for="prio">{|Prio|}</label>
					</li>
					<li class="filter-item">
						<label for="faellige" class="switch">
							<input type="checkbox" id="faellige">
							<span class="slider round">
						</label>
						<label for="faellige">{|f&auml;llige|}</label>
					</li>
					[VORMEINE]
					<li class="filter-item">
						<label for="meine" class="switch">
							<input type="checkbox" id="meine">
							<span class="slider round">
						</label>
						<label for="meine">{|meine|}</label>
					</li>
					<li class="filter-item">
						<label for="nur_meine_vergebenen" class="switch">
							<input type="checkbox" id="nur_meine_vergebenen">
							<span class="slider round">
						</label>
						<label for="nur_meine_vergebenen">{|nur meine vergebenen|}</label>
					</li>
					[NACHMEINE]
					<li class="filter-item">
						<label for="abgeschlossen" class="switch">
							<input type="checkbox" id="abgeschlossen">
							<span class="slider round">
						</label>
						<label for="abgeschlossen">{|mit abgeschlossene|}</label>
					</li>
				</ul>
			</div>
		</div>

		[MESSAGE]
		[TAB1]

	</div>
</div>
