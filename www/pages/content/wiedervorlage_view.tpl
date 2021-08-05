
<div id="editWiedervorlageView" style="display:none;" title="Bearbeiten">
	<form method="post">
		<div class="row">
			<div class="row-height">
				<div class="col-md-8 col-md-height">
					<div class="inside inside-full-height">
						<input type="hidden" id="wiedervorlage_view_id">
						<fieldset class="white">
							<legend>{|Wiedervorlage Board|}</legend>
							<table class="mkTableFormular">
								<tr>
									<td width="80"><label for="wiedervorlage_view_name">{|Name|}:</label></td>
									<td><input type="text" name="wiedervorlage_view_name" id="wiedervorlage_view_name" size="40"></td>
								</tr>
								<tr>
									<td><label for="wiedervorlage_view_kennung">{|Kennung|}:</label></td>
									<td><input type="text" name="wiedervorlage_view_kennung" id="wiedervorlage_view_kennung" size="40"></td>
								</tr>
								<tr>
									<td><label for="wiedervorlage_view_projekt">{|Projekt|}:</label></td>
									<td><input type="text" name="wiedervorlage_view_projekt" id="wiedervorlage_view_projekt" size="40"></td>
								</tr>
								<tr>
									<td><label for="wiedervorlage_view_active">{|Aktiv|}:</label></td>
									<td><input type="checkbox" name="wiedervorlage_view_active" id="wiedervorlage_view_active"></td>
								</tr>
								<tr>
									<td><label for="wiedervorlage_view_hide_collection_stage">{|&quot;Ohne Stage&quot; ausblenden|}:</label></td>
									<td><input type="checkbox" name="wiedervorlage_view_hide_collection_stage" id="wiedervorlage_view_hide_collection_stage"></td>
								</tr>
							</table>
						</fieldset>
					</div>
				</div>
				<div class="col-md-4 col-md-height">
					<div class="inside inside-full-height">
						<input type="hidden" id="wiedervorlage_view_id">
						<fieldset class="white">
							<legend></legend>
							<table class="mkTableFormular">
								<tr>
									<td>
										<label for="wiedervorlage_view_members">{|Mitarbeiter-Auswahl|}:</label>
										<select name="wiedervorlage_view_members" id="wiedervorlage_view_members" size="12" multiple="multiple"></select>
									</td>
								</tr>
							</table>
						</fieldset>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        $('#wiedervorlage_view_name').focus();

        $("#editWiedervorlageView").dialog({
            modal: true,
            bgiframe: true,
            closeOnEscape:false,
            minWidth:800,
            maxHeight:700,
            autoOpen: false,
            buttons: {
                ABBRECHEN: function() {
                    WiedervorlageViewReset();
                    $(this).dialog('close');
                },
                SPEICHERN: function() {
                    WiedervorlageViewEditSave();
                }
            }
        });

        $("#editWiedervorlageView").dialog({
            close: function( event, ui ) { WiedervorlageViewReset();}
        });

    });


    function WiedervorlageViewReset()
    {
        $('#editWiedervorlageView').find('#wiedervorlage_view_id').val('');
        $('#editWiedervorlageView').find('#wiedervorlage_view_name').val('');
        $('#editWiedervorlageView').find('#wiedervorlage_view_kennung').val('');
        $('#editWiedervorlageView').find('#wiedervorlage_view_projekt').val('');
        $('#editWiedervorlageView').find('#wiedervorlage_view_active').prop("checked",true);
        $('#editWiedervorlageView').find('#wiedervorlage_view_hide_collection_stage').prop("checked",false);
		    $('#editWiedervorlageView').find('#wiedervorlage_view_members').val('');
    }

    function WiedervorlageViewEditSave() {
        $.ajax({
            url: 'index.php?module=wiedervorlage&action=viewsave',
            data: {
                //Alle Felder die fürs editieren vorhanden sind
                id: $('#wiedervorlage_view_id').val(),
                name: $('#wiedervorlage_view_name').val(),
                shortname: $('#wiedervorlage_view_kennung').val(),
                project: $('#wiedervorlage_view_projekt').val(),
		            members: $('#wiedervorlage_view_members').val(),
                active: $('#wiedervorlage_view_active').prop("checked")?1:0,
		            hide_collection_stage: $('#wiedervorlage_view_hide_collection_stage').prop("checked")?1:0
            },
            method: 'post',
            dataType: 'json',
            beforeSend: function() {
                App.loading.open();
            },
            success: function(data) {
                App.loading.close();
                if (data.status == 1) {
                    WiedervorlageViewReset();
										WiedervorlageViewUpdateLiveTable();
                    $("#editWiedervorlageView").dialog('close');
                } else {
                    alert(data.statusText);
                }
            }
        });
    }

    function WiedervorlageViewEdit(id) {
        if(id > 0)
        {
            $.ajax({
                url: 'index.php?module=wiedervorlage&action=viewedit&cmd=get',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                beforeSend: function() {
                    App.loading.open();
                },
                success: function(data) {
                    $('#editWiedervorlageView').find('#wiedervorlage_view_id').val(data.id);
                    $('#editWiedervorlageView').find('#wiedervorlage_view_name').val(data.name);
                    $('#editWiedervorlageView').find('#wiedervorlage_view_kennung').val(data.shortname);
                    $('#editWiedervorlageView').find('#wiedervorlage_view_projekt').val(data.project);
                    $('#editWiedervorlageView').find('#wiedervorlage_view_active').prop("checked", data.active==1?true:false);
                    $('#editWiedervorlageView').find('#wiedervorlage_view_hide_collection_stage').prop("checked", data.hideCollectionStage==1?true:false);

		                // Mitglieder-MultiSelect befüllen
                    if (typeof data.members !== 'undefined') {
		                    var $members = $('#editWiedervorlageView').find('#wiedervorlage_view_members');
		                    $members.prop('multiple', true);
		                    $members.find('option').remove();

                        $.each(data.members, function (index, member) {
                            var $option = $('<option>')
		                            .val(member.user_id)
		                            .text(member.address_name)
		                            .prop('selected', member.is_member)
                                .on('mousedown', function (e) {
                                    e.preventDefault();
                                    var isSelected = $(e.target).prop('selected');
		                                $(e.target).prop('selected', !isSelected);
                                });
                            $members.append($option);
                        });
                    }

                    App.loading.close();
                    $("#editWiedervorlageView").dialog('open');
                }
            });
        } else {
            WiedervorlageViewReset();
            $("#editWiedervorlageView").dialog('open');
        }

    }

    function WiedervorlageViewUpdateLiveTable(i) {
        var oTableL = $('#wiedervorlage_view').dataTable();
        var tmp = $('.dataTables_filter input[type=search]').val();
        oTableL.fnFilter('%');
        oTableL.fnFilter(tmp);
    }

    function WiedervorlageViewDelete(id) {
        var conf = confirm('Wirklich löschen?');
        if (conf) {
            $.ajax({
                url: 'index.php?module=wiedervorlage&action=viewdelete',
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
												WiedervorlageViewUpdateLiveTable();
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
