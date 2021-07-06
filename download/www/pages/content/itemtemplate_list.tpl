<!-- gehort zu tabview -->
<div id="tabs">
	<ul>
		<li><a href="#tabs-1">[TABTEXT]</a></li>
	</ul>
	<!-- ende gehort zu tabview -->

	<!-- erstes tab -->
	<div id="tabs-1">
		<form method="post">
			[MESSAGE]
		</form>

		[TAB1]

		[TAB1NEXT]
	</div>

	<!-- tab view schließen -->
</div>

<div id="editItemTemplate" style="display:none;" title="Bearbeiten">
	<form method="post">
		<input type="hidden" id="itemtemplate_id">
		<fieldset>
			<legend>{|Vorlage|}</legend>
			<table>
				<tr>
					<td width="150">{|Bezeichnung|}:</td>
					<td><input type="text" name="itemtemplate_name" id="itemtemplate_name" size="40"></td>
				</tr>
				<tr>
					<td>{|Art|}:</td>
					<td><select name="itemtemplate_type" id="itemtemplate_type">
								<option value="angebot">Angebot</option>
								<option value="auftrag">Auftrag</option>
								<option value="rechnung">Rechnung</option>
								<option value="lieferschein">Lieferschein</option>
								<option value="gutschrift">Gutschrift</option>
								<option value="produktion">Produktion</option>
								<option value="projekt">Projekt</option>
								<option value="adresse">Adresse</option>
								<option value="artikel">Artikel</option>
							</select>
					</td>
				</tr>
				<tr>
					<td>{|Objekt|}:</td>
					<td><input type="text" name="itemtemplate_object" id="itemtemplate_object" size="40"></td>
				</tr>
				<tr>
					<td>{|Aktiv|}:</td>
					<td><input type="checkbox" name="itemtemplate_active" id="itemtemplate_active"></td>
				</tr>
			</table>
		</fieldset>
	</form>
</div>


<script type="text/javascript">

    $(document).ready(function() {
        $('#itemtemplate_name').focus();

        $("#editItemTemplate").dialog({
            modal: true,
            bgiframe: true,
            closeOnEscape:false,
            minWidth:650,
            maxHeight:700,
            autoOpen: false,
            buttons: {
                ABBRECHEN: function() {
                    ItemTemplateReset();
                    $(this).dialog('close');
                },
                SPEICHERN: function() {
                    ItemTemplateEditSave();
                }
            }
        });

        $("#editItemTemplate").dialog({
            close: function( event, ui ) { ItemTemplateReset();}
        });

        $('#itemtemplate_type').on('change',function(){
            ItemTemplateAutoCompletes();
        });

    });

    function ItemTemplateAutoCompletes(){
        $('#editItemTemplate').find('#itemtemplate_object').val('');
        if($('#itemtemplate_type').val() == 'angebot'){
            $( "input#itemtemplate_object" ).autocomplete({
                source: "index.php?module=ajax&action=filter&filtername=angebot"
            });
        }
        if($('#itemtemplate_type').val() == 'auftrag'){
            $( "input#itemtemplate_object" ).autocomplete({
                source: "index.php?module=ajax&action=filter&filtername=auftrag"
            });
        }
        if($('#itemtemplate_type').val() == 'rechnung'){
            $( "input#itemtemplate_object" ).autocomplete({
                source: "index.php?module=ajax&action=filter&filtername=rechnung"
            });
        }
        if($('#itemtemplate_type').val() == 'lieferschein'){
            $( "input#itemtemplate_object" ).autocomplete({
                source: "index.php?module=ajax&action=filter&filtername=lieferschein"
            });
        }
        if($('#itemtemplate_type').val() == 'gutschrift'){
            $( "input#itemtemplate_object" ).autocomplete({
                source: "index.php?module=ajax&action=filter&filtername=gutschrift"
            });
        }
        if($('#itemtemplate_type').val() == 'produktion'){
            $( "input#itemtemplate_object" ).autocomplete({
                source: "index.php?module=ajax&action=filter&filtername=produktion"
            });
        }
        if($('#itemtemplate_type').val() == 'projekt'){
            $( "input#itemtemplate_object" ).autocomplete({
                source: "index.php?module=ajax&action=filter&filtername=projektname"
            });
        }
        if($('#itemtemplate_type').val() == 'adresse'){
            $( "input#itemtemplate_object" ).autocomplete({
                source: "index.php?module=ajax&action=filter&filtername=adresse"
            });
        }
        if($('#itemtemplate_type').val() == 'artikel'){
            $( "input#itemtemplate_object" ).autocomplete({
                source: "index.php?module=ajax&action=filter&filtername=artikelnummer"
            });
        }
    }

    function ItemTemplateReset()
    {
        $('#editItemTemplate').find('#itemtemplate_id').val('');
        $('#editItemTemplate').find('#itemtemplate_name').val('');
        $('#editItemTemplate').find('#itemtemplate_type').val('');
        $('#editItemTemplate').find('#itemtemplate_object').val('');
        $('#editItemTemplate').find('#itemtemplate_active').prop("checked",true);

        var type = document.getElementById('itemtemplate_type');
        type.selectedIndex = 0;
        $( "input#itemtemplate_object" ).autocomplete({
            source: "index.php?module=ajax&action=filter&filtername=angebot"
        });
    }

    function ItemTemplateEditSave() {
        $.ajax({
            url: 'index.php?module=itemtemplate&action=save',
            data: {
                //Alle Felder die fürs editieren vorhanden sind
                id: $('#itemtemplate_id').val(),
								name: $('#itemtemplate_name').val(),
                type: $('#itemtemplate_type').val(),
                object: $('#itemtemplate_object').val(),
                active: $('#itemtemplate_active').prop("checked")?1:0,

            },
            method: 'post',
            dataType: 'json',
            beforeSend: function() {
                App.loading.open();
            },
            success: function(data) {
                App.loading.close();
                if (data.status == 1) {
                    ItemTemplateReset();
                    updateLiveTable();
                    $("#editItemTemplate").dialog('close');
                } else {
                    alert(data.statusText);
                }
            }
        });
    }

    function ItemTemplateEdit(id) {
        if(id > 0)
        {
            $.ajax({
                url: 'index.php?module=itemtemplate&action=edit&cmd=get',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                beforeSend: function() {
                    App.loading.open();
                },
                success: function(data) {
                    $('#editItemTemplate').find('#itemtemplate_id').val(data.id);
                    $('#editItemTemplate').find('#itemtemplate_name').val(data.name);
                    $('#editItemTemplate').find('#itemtemplate_type').val(data.type);

                    ItemTemplateAutoCompletes();

                    $('#editItemTemplate').find('#itemtemplate_object').val(data.object);
                    $('#editItemTemplate').find('#itemtemplate_active').prop("checked", data.active==1?true:false);

                    App.loading.close();
                    $("#editItemTemplate").dialog('open');
                }
            });
        } else {
            ItemTemplateReset();
            $("#editItemTemplate").dialog('open');
        }
    }

    function updateLiveTable(i) {
        var oTableL = $('#itemtemplate_list').dataTable();
        var tmp = $('.dataTables_filter input[type=search]').val();
        oTableL.fnFilter('%');
        oTableL.fnFilter(tmp);
    }

    function ItemTemplateDelete(id) {
        var conf = confirm('Wirklich löschen?');
        if (conf) {
            $.ajax({
                url: 'index.php?module=itemtemplate&action=delete',
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

</script>
