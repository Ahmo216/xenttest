<!-- gehort zu tabview -->
<div id="tabs">
	<ul>
		<li><a href="#tabs-1">[TABTEXT]</a></li>
	</ul>
	<!-- ende gehort zu tabview -->

	<!-- erstes tab -->
	<div id="tabs-1">
		[MESSAGE]

		[TAB1]

		<fieldset>
			<legend>{|Stapelverarbeitung|}</legend>
			<input type="checkbox" id="standardpackages_selectall" onchange="StandardpackagesSelectAll();" />&nbsp;{|alle markieren|}
			&nbsp; <select id="standardpackages_batch" name="standardpackages_batch">
					<option value="">{|bitte w&auml;hlen|} ...</option>
					<option value="active">{|Aktiv|}</option>
				</select>&nbsp;
				<input type="button" class="btnBlue" onclick="StandardpackagesRunAction();" name="standardpackagesrunaction" value="{|ausf&uuml;hren|}" />
		</fieldset>

		[TAB1NEXT]
	</div>

	<!-- tab view schließen -->
</div>

<div id="editStandardpackages" style="display:none;" title="Bearbeiten">
	<div class="row">
		<div class="row-height">
			<div class="col-xs-12 col-md-12 col-md-height">
				<div class="inside inside-full-height">
					<form method="post">
						<input type="hidden" id="standardpackages_id">
						<fieldset>
							<legend>{|Verpackung|}</legend>
							<table>
								<tr>
									<td width="150">{|Name|}:</td>
									<td><input type="text" name="standardpackages_name" id="standardpackages_name" size="40"></td>
								</tr>
								<tr>
									<td>{|Beschreibung|}:</td>
									<td><input type="text" name="standardpackages_description" id="standardpackages_description" size="40"></td>
								</tr>
								<tr>
									<td>{|Breite|}:</td>
									<td><input type="text" name="standardpackages_width" id="standardpackages_width">&nbsp;cm</td>
								</tr>
								<tr>
									<td>{|H&ouml;he|}:</td>
									<td><input type="text" name="standardpackages_height" id="standardpackages_height">&nbsp;cm</td>
								</tr>
								<tr>
									<td>{|L&auml;nge|}:</td>
									<td><input type="text" name="standardpackages_length" id="standardpackages_length">&nbsp;cm</td>
								</tr>
								<tr>
									<td>{|XVP|}:</td>
									<td><input type="text" name="standardpackages_xvp" id="standardpackages_xvp"></td>
								</tr>
								<tr>
									<td>{|Farbe|}:</td>
									<td><input type="text" name="standardpackages_color" id="standardpackages_color"></td>
								</tr>
								<tr>
									<td>{|Aktiv|}:</td>
									<td><input type="checkbox" name="standardpackages_active" id="standardpackages_active"></td>
								</tr>
							</table>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>



<script type="text/javascript">

    $(document).ready(function() {
        $('#standardpackages_name').focus();

        $("#editStandardpackages").dialog({
            modal: true,
            bgiframe: true,
            closeOnEscape:false,
            minWidth:650,
            maxHeight:700,
            autoOpen: false,
            buttons: {
                ABBRECHEN: function() {
                    StandardpackagesReset();
                    $(this).dialog('close');
                },
                SPEICHERN: function() {
                    StandardpackagesEditSave();
                }
            }
        });

        $("#editStandardpackages").dialog({
            close: function( event, ui ) { StandardpackagesReset();}
        });

    });


    function StandardpackagesReset()
    {
        $('#editStandardpackages').find('#standardpackages_id').val('');
        $('#editStandardpackages').find('#standardpackages_name').val('');
        $('#editStandardpackages').find('#standardpackages_description').val('');
        $('#editStandardpackages').find('#standardpackages_width').val('');
        $('#editStandardpackages').find('#standardpackages_height').val('');
        $('#editStandardpackages').find('#standardpackages_length').val('');
        $('#editStandardpackages').find('#standardpackages_xvp').val('');
        $('#editStandardpackages').find('#standardpackages_color').val('#0b8092');
        $('#standardpackages_color').trigger('change');
        $('#editStandardpackages').find('#standardpackages_active').prop("checked",true);
    }

    function StandardpackagesEditSave() {
        $.ajax({
            url: 'index.php?module=standardpackages&action=save',
            data: {
                //Alle Felder die fürs editieren vorhanden sind
                id: $('#standardpackages_id').val(),
                name: $('#standardpackages_name').val(),
                description: $('#standardpackages_description').val(),
                width: $('#standardpackages_width').val(),
								height: $('#standardpackages_height').val(),
								length: $('#standardpackages_length').val(),
								xvp: $('#standardpackages_xvp').val(),
								color: $('#standardpackages_color').val(),
                active: $('#standardpackages_active').prop("checked")?1:0,

            },
            method: 'post',
            dataType: 'json',
            beforeSend: function() {
                App.loading.open();
            },
            success: function(data) {
                App.loading.close();
                if (data.status == 1) {
                    StandardpackagesReset();
                    updateLiveTable();
                    $("#editStandardpackages").dialog('close');
                } else {
                    alert(data.statusText);
                }
            }
        });


    }

    function StandardpackagesEdit(id) {
        if(id > 0)
        {
            $.ajax({
                url: 'index.php?module=standardpackages&action=edit&cmd=get',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                beforeSend: function() {
                    App.loading.open();
                },
                success: function(data) {
                    $('#editStandardpackages').find('#standardpackages_id').val(data.id);
                    $('#editStandardpackages').find('#standardpackages_name').val(data.name);
                    $('#editStandardpackages').find('#standardpackages_description').val(data.description);
                    $('#editStandardpackages').find('#standardpackages_width').val(data.width);
                    $('#editStandardpackages').find('#standardpackages_height').val(data.height);
                    $('#editStandardpackages').find('#standardpackages_length').val(data.length);
                    $('#editStandardpackages').find('#standardpackages_xvp').val(data.xvp);
                    $('#editStandardpackages').find('#standardpackages_color').val(data.color);
                    $('#standardpackages_color').trigger('change');
                    $('#editStandardpackages').find('#standardpackages_active').prop("checked", data.active==1?true:false);

                    App.loading.close();
                    $("#editStandardpackages").dialog('open');
                }
            });
        } else {
            StandardpackagesReset();
            $("#editStandardpackages").dialog('open');
        }

    }

    function updateLiveTable(i) {
        var oTableL = $('#standardpackages_list').dataTable();
        var tmp = $('.dataTables_filter input[type=search]').val();
        oTableL.fnFilter('%');
        //oTableL.fnFilter('');
        oTableL.fnFilter(tmp);
    }

    function StandardpackagesDelete(id) {
        var conf = confirm('Wirklich löschen?');
        if (conf) {
            $.ajax({
                url: 'index.php?module=standardpackages&action=delete',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                beforeSend: function() {
                    App.loading.open();
                },
                success: function(data) {
                    if (data.status == 1) {
                        updateLiveTable();
                    } else {
                        alert(data.statusText);
                    }
                    App.loading.close();
                }
            });
        }

        return false;
    }

    function StandardpackagesSelectAll()
    {
        var wert = $('#standardpackages_selectall').prop('checked');
        $('input.selectioncb').prop('checked', wert);
    }

    function StandardpackagesRunAction()
    {
        var wert = $('#standardpackages_batch').val();
        if(wert != '')
        {
            var elemente = $('input.selectioncb:checked');
            if(elemente.length > 0)
            {
                var elementestr = '';
                $(elemente).each(function(k,v){
                    if(elementestr != '')elementestr += ';';
                    elementestr += $(v).val();
                });

                switch(wert)
                {
                    case 'active':
                        if(confirm('Sollen die ausgewählten Verpackungsgrößen auf aktiv gesetzt werden?')) {
                            $.ajax({
                                url: 'index.php?module=standardpackages&action=list&cmd=active',
                                data: {
                                    selectedcheckboxes:elementestr
                                },
                                method: 'post',
                                dataType: 'json',
                                success: function(data) {
                                    if(typeof data.success != 'undefined' && data.success == 1)
                                    {
                                        updateLiveTable();
                                    }else if(typeof data.error != 'undefined' && data.error != ''){
                                        alert(data.error);
                                    }
                                }
                            });
                        }
                    break;
                }
            }else{
                alert('{|Bitte Verpackungsgröße auswählen|}');
            }
        }
    }

</script>


