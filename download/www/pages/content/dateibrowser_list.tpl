<div id="tabs">
	<ul>
		<li><a href="#tabs-1">[TABTEXT]</a></li>
	</ul>
	<div id="tabs-1">
		[MESSAGE]

		<fieldset>
			<legend>{|Filter|}</legend>
			<table>
				<tr>
					<td height="25px"></td>
				</tr>
			</table>
		</fieldset>

		[TAB1]
		[TAB1NEXT]

		<fieldset>
			<legend>{|Stapelverarbeitung|}</legend>
			<input type="checkbox" value="1" id="dateibrowser_allemarkieren" onchange="dateibrowser_markierealle();">
			<label for="dateibrowser_allemarkieren">alle markieren</label>
			<input type="submit" class="btnBlue" value="Zip erstellen" name="dateibrowser_ziperstellen" onclick="dateibrowser_ziperstellen()">
			<input type="submit" class="btnBlue" value="Sammel PDF" name="dateibrowser_sammelpdf" onclick="dateibrowser_sammelpdf()">
			<input type="submit" class="btnRed" value="Dateien l&ouml;schen" name="dateibrowser_delete" onclick="dateibrowser_delete()">
		</fieldset>
	</div>
</div>

<script type="application/javascript">
    function dateibrowser_markierealle() {
        let checked = $('#dateibrowser_allemarkieren').prop('checked');
        $('#dateibrowser_list input').prop('checked', checked);
    }

    function dateibrowser_ziperstellen() {
        let auswahl = [];
        $('#dateibrowser_list').find(':checked').each(function () {
            auswahl.push($(this).val());
        });

        window.open('index.php?module=dateibrowser&action=list&cmd=dateizip&dateien=' + auswahl);
    }

    function dateibrowser_sammelpdf() {
        let auswahl = [];
        $('#dateibrowser_list').find(':checked').each(function () {
            auswahl.push($(this).val());
        });

        window.open('index.php?module=dateibrowser&action=list&cmd=sammelpdf&dateien=' + auswahl);
    }

    function dateibrowser_delete()  {
        let auswahl = [];
        $('#dateibrowser_list').find(':checked').each(function () {
            auswahl.push($(this).val());
        });
        $.ajax({
            url: 'index.php?module=dateibrowser&action=list&cmd=delete',
            type: 'POST',
            dataType: 'json',
            data: {dateien:auswahl},
            success: function() {
                $('#dateibrowser_list').DataTable( ).ajax.reload();
                $('#dateibrowser_allemarkieren').prop('checked', false);
            }
        });
    }
</script>
