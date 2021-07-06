<!-- gehort zu tabview -->
<div id="tabs">
    <ul>
        <li><a href="#tabs-1"></a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
[MESSAGE]

	<table width="100%">
		<tr>
			<td width="100%">
				<center>Barcode erfassen:&nbsp;<form action="" method="post"><input type="text" id="lieferschein" name="lieferschein" size="20"></form></center><br>
				<div class="filter-box filter-usersave">
					<div class="filter-block filter-inline">
						<div class="filter-title">{|Filter|}</div>
						<ul class="filter-list">
							<li class="filter-item">
								<label for="auchabgeschlossene" class="switch">
									<input type="checkbox" id="auchabgeschlossene" />
									<span class="slider round"></span>
								</label>
								<label for="auchabgeschlossene">{|auch abgeschlossene Retouren anzeigen|}</label>
							</li>
						</ul>
					</div>
				</div>
			</td>
		</tr>
	</table>
[TAB1]
</div>


<!-- tab view schließen -->
</div>
<script>
$(document).ready(function() {
  $('#lieferschein').focus();
});
</script>
