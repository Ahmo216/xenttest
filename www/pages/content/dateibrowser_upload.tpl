<div id="tabs">
	<ul>
		<li><a href="#tabs-1">[TABTEXT]</a></li>
	</ul>
	<div id="tabs-1">
		[MESSAGE]

		<div class="info">Die maximale Upload-Größe pro Datei beträgt: [MAXUPLOADSIZE].</div>

		<fieldset>
			<legend>{|Dateien hochladen|}</legend>
			<div>
				<label>
					{|Stichwort-Vorlage|}
					<select id="select-keyword-template">
						[DATEISTICHWORTOPTIONEN]
					</select>
				</label>
			</div>
		</fieldset>

		<div id="dropzone"></div>

		[TAB1]
		[TAB1NEXT]

	</div>
</div>

<script type="application/javascript">
	$(document).ready(function () {
    $('#dropzone').dropzoneUpload();
  });
</script>
