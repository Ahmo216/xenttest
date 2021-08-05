<!-- gehort zu tabview -->
<div id="tabs">
	<ul>
		<li><a href="#tabs-1">[TABTEXT]</a></li>
	</ul>
	<!-- ende gehort zu tabview -->

	<!-- erstes tab -->
	<div id="tabs-1">
		[MESSAGE]
		<form method="post">
			<div class="row">
				<div class="row-height">
					<div class="col-xs-12 col-md-10 col-md-height">
						<div class="inside-white inside-full-height">
							[TAB1]
						</div>
					</div>
					<div class="col-xs-12 col-md-2 col-md-height">
						<div class="inside inside-full-height">
							<fieldset>
								<legend>{|Aktionen|}</legend>
								<input type="button" class="btnGreenNew" name="newentry" value="&#10010; Neuer Eintrag" onclick="ReceiptProtocolItemsEdit(0);">
							</fieldset>
						</div>
					</div>
				</div>
			</div>


			<fieldset>
				<legend>{|Stapelverarbeitung|}</legend>
				<input type="checkbox" id="selectall" onchange="alleauswaehlen();" />&nbsp;{|alle markieren|}
			&nbsp;	<select id="sel_action" name="sel_action">
					<option value="">{|bitte w&auml;hlen|} ...</option>
					<option value="pdf">{|PDF|}</option>
					<option value="print">{|drucken|}</option>
				</select>&nbsp;{|Drucker|}: <select name="selprinter">[SELPRINTER]</select>&nbsp;<input type="submit" class="btnBlue" name="execute" id="execute" value="ausf&uuml;hren" />
			</fieldset>
		</form>


		[TAB1NEXT]
	</div>

	<!-- tab view schließen -->
</div>

<div id="editReceiptProtocolItems" style="display:none;" title="Bearbeiten">
	<form method="post">
		<input type="hidden" id="receiptprotocolitems_id">
		<fieldset>
			<legend>{|Belegprotokolleintrag|}</legend>
			<table>
				<tr>
					<td width="150">{|Bezeichnung|}:</td>
					<td><input type="text" name="receiptprotocolitems_name" id="receiptprotocolitems_name" size="40"></td>
				</tr>
				<tr>
					<td>{|Protokolleintrag|}:</td>
					<td><textarea name="receiptprotocolitems_descritpion" id="receiptprotocolitems_descritpion"></textarea></td>
				</tr>
				<tr>
					<td>{|Aktiv|}:</td>
					<td><input type="checkbox" name="receiptprotocolitems_active" id="receiptprotocolitems_active"></td>
				</tr>
			</table>
		</fieldset>
	</form>
</div>



<script type="text/javascript">

    $(document).ready(function() {
        $('#receiptprotocolitems_name').focus();

        $("#editReceiptProtocolItems").dialog({
            modal: true,
            bgiframe: true,
            closeOnEscape:false,
            minWidth:650,
            maxHeight:700,
            autoOpen: false,
            buttons: {
                ABBRECHEN: function() {
                    ReceiptProtocolItemsReset();
                    $(this).dialog('close');
                },
                SPEICHERN: function() {
                    ReceiptProtocolItemsEditSave();
                }
            }
        });

        $("#editReceiptProtocolItems").dialog({
            close: function( event, ui ) { ReceiptProtocolItemsReset();}
        });

    });

    function alleauswaehlen()
    {
        var wert = $('#selectall').prop('checked');
        $('#receiptprotocolitems_settings').find(':checkbox').prop('checked',wert);
    }

    function ReceiptProtocolItemsReset()
    {
        $('#editReceiptProtocolItems').find('#receiptprotocolitems_id').val('');
        $('#editReceiptProtocolItems').find('#receiptprotocolitems_name').val('');
        $('#editReceiptProtocolItems').find('#receiptprotocolitems_descritpion').val('');
        $('#editReceiptProtocolItems').find('#receiptprotocolitems_active').prop("checked",true);
    }

    function ReceiptProtocolItemsEditSave() {
        $.ajax({
            url: 'index.php?module=receiptprotocolitems&action=settingssave',
            data: {
                //Alle Felder die fürs editieren vorhanden sind
                id: $('#receiptprotocolitems_id').val(),
                name: $('#receiptprotocolitems_name').val(),
                description: $('#receiptprotocolitems_descritpion').val(),
                active: $('#receiptprotocolitems_active').prop("checked")?1:0
            },
            method: 'post',
            dataType: 'json',
            beforeSend: function() {
                App.loading.open();
            },
            success: function(data) {
                App.loading.close();
                if (data.status == 1) {
                    ReceiptProtocolItemsReset();
                    updateLiveTable();
                    $("#editReceiptProtocolItems").dialog('close');
                } else {
                    alert(data.statusText);
                }
            }
        });
    }

    function ReceiptProtocolItemsEdit(id) {
        if(id > 0)
        {
            $.ajax({
                url: 'index.php?module=receiptprotocolitems&action=settingsedit&cmd=get',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                beforeSend: function() {
                    App.loading.open();
                },
                success: function(data) {
                    $('#editReceiptProtocolItems').find('#receiptprotocolitems_id').val(data.id);
                    $('#editReceiptProtocolItems').find('#receiptprotocolitems_name').val(data.name);
                    $('#editReceiptProtocolItems').find('#receiptprotocolitems_descritpion').val(data.description);
                    $('#editReceiptProtocolItems').find('#receiptprotocolitems_active').prop("checked", data.active==1?true:false);

                    App.loading.close();
                    $("#editReceiptProtocolItems").dialog('open');
                }
            });
        } else {
            ReceiptProtocolItemsReset();
            $("#editReceiptProtocolItems").dialog('open');
        }

    }

    function updateLiveTable(i) {
        var oTableL = $('#receiptprotocolitems_settings').dataTable();
        var tmp = $('.dataTables_filter input[type=search]').val();
        oTableL.fnFilter('%');
        oTableL.fnFilter(tmp);
    }

    function ReceiptProtocolItemsDelete(id) {
        var conf = confirm('Wirklich löschen?');
        if (conf) {
            $.ajax({
                url: 'index.php?module=receiptprotocolitems&action=settingsdelete',
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

