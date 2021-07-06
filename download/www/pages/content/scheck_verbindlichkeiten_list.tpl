<!-- gehort zu tabview -->
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">[TABTEXT]</a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
  [MESSAGE]

	<div class="filter-box filter-usersave">
		<div class="filter-block filter-inline">
			<div class="filter-title">{|Filter|}</div>
			<ul class="filter-list">
				<li class="filter-item">
					<label for="nurbezahlt" class="switch">
						<input type="checkbox" name="nurbezahlt" id="nurbezahlt">
						<span class="slider round"></span>
					</label>
					<label for="nurbezahlt">{|nur bezahlte|}</label>
				</li>
				<li class="filter-item">
					<label for="nurnichtbezahlt" class="switch">
						<input type="checkbox" name="nurnichtbezahlt" id="nurnichtbezahlt">
						<span class="slider round"></span>
					</label>
					<label for="nurnichtbezahlt">{|nur nicht bezahlte|}</label>
				</li>
			</ul>
		</div>
	</div>

  [TAB1]
  [TAB1NEXT]
</div>

<!-- tab view schließen -->
</div>
