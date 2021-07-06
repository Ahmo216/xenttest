<div id="tabs">
	<ul>
		<li><a href="#tabs-1">[TABTEXT]</a></li>
	</ul>
	<div id="tabs-1">
		[MESSAGE]

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
							<input type="button" class="btnGreenNew" name="extendedapproval_responsibilities_new" value="&#10010; Neuer Eintrag" onclick="ExtendedApprovalResponsibilitiesEdit(0);">
						</fieldset>
					</div>
				</div>
			</div>
		</div>
		[TAB1NEXT]
	</div>
</div>

<div id="editExtendedApprovalResponsibilities" style="display:none;" title="Bearbeiten">
	<form method="post">
		<div class="row">
			<div class="row-height">
				<div class="col-xs-12 col-md-10 col-md-height">
					<div class="inside inside-full-height">
						<input type="hidden" id="extendedapproval_responsibilities_id">
						<fieldset>
							<legend>{|Zuweisung|}</legend>
							<table>
								<tr>
									<td width="100"><label for="extendedapproval_responsibilities_doctype">{|Belegart|}:</label></td>
									<td><select name="extendedapproval_responsibilities_doctype" id="extendedapproval_responsibilities_doctype">
											<!--<option value="anfrage">{|Anfrage|}</option>-->
											<option value="angebot">{|Angebot|}</option>
											<option value="auftrag">{|Auftrag|}</option>
											<option value="bestellung">{|Bestellung|}</option>
											<option value="gutschrift">{|Gutschrift|}</option>
											<!--<option value="preisanfrage">Preisanfrage</option>
											<option value="produktion">Produktion</option>
											<option value="proformarechnung">Proformarechnung</option>-->
											<option value="rechnung">{|Rechnung|}</option>
											<!--<option value="reisekosten">Reisekosten</option>
											<option value="verbindlichkeit">{|Verbindlichkeit|}</option>-->
										</select>
								</tr>
								<tr>
									<td><label for="extendedapproval_responsibilities_requestertype">{|Art Anforderer|}:</label></td>
									<td><select name="extendedapproval_responsibilities_requestertype" id="extendedapproval_responsibilities_requestertype">
											<option value="employee">{|Mitarbeiter|}</option>
											<option value="group">{|Gruppe|}</option>
										</select>
									</td>
								</tr>
								<tr>
									<td><label for="extendedapproval_responsibilities_requester">{|Anforderer|}:</label></td>
									<td><input type="text" name="extendedapproval_responsibilities_requester" id="extendedapproval_responsibilities_requester"</td>
								</tr>
								<tr>
									<td><label for="extendedapproval_responsibilities_moneylimit">{|Limit|}:</label></td>
									<td><input type="text" name="extendedapproval_responsibilities_moneylimit" id="extendedapproval_responsibilities_moneylimit"></td>
								</tr>
								<tr>
									<td><label for="extendedapproval_responsibilities_releasetype">{|Art Freigeber|}:</label></td>
									<td><select name="extendedapproval_responsibilities_releasetype" id="extendedapproval_responsibilities_releasetype">
											<option value="employee">{|Mitarbeiter|}</option>
											<option value="group">{|Gruppe|}</option>
										</select>
									</td>
								</tr>
								<tr>
									<td><label for="extendedapproval_responsibilities_release">{|Freigeber|}:</label></td>
									<td><input type="text" name="extendedapproval_responsibilities_release" id="extendedapproval_responsibilities_release"></td>
								</tr>
								<tr>
									<td><label for="extendedapproval_responsibilities_email">{|E-Mail|}:</label></td>
									<td><input type="checkbox" name="extendedapproval_responsibilities_email" id="extendedapproval_responsibilities_email"></td>
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
        $('#extendedapproval_responsibilities_doctype').focus();
        ExtendedApprovalAutoCompletes();

        $("#editExtendedApprovalResponsibilities").dialog({
            modal: true,
            bgiframe: true,
            closeOnEscape:false,
            minWidth:450,
            maxHeight:700,
            autoOpen: false,
            buttons: {
                ABBRECHEN: function() {
                    ExtendedApprovalResponsibilitiesReset();
                    $(this).dialog('close');
                },
                SPEICHERN: function() {
                    ExtendedApprovalResponsibilitiesEditSave();
                }
            }
        });

        $("#editExtendedApprovalResponsibilities").dialog({
            close: function( event, ui ) { ExtendedApprovalResponsibilitiesReset();}
        });

        $('#extendedapproval_responsibilities_requestertype').on('change',function(){
            ExtendedApprovalAutoCompletes();
        });
        $('#extendedapproval_responsibilities_releasetype').on('change',function(){
            ExtendedApprovalAutoCompletes();
        });
    });

    function ExtendedApprovalResponsibilitiesReset()
    {
        $('#editExtendedApprovalResponsibilities').find('#extendedapproval_responsibilities_id').val('');
        var doctype = document.getElementById('extendedapproval_responsibilities_doctype');
        doctype.selectedIndex = 0;
        var requestertype = document.getElementById('extendedapproval_responsibilities_requestertype');
        requestertype.selectedIndex = 0;
        $('#editExtendedApprovalResponsibilities').find('#extendedapproval_responsibilities_requester').val('');
        $('#editExtendedApprovalResponsibilities').find('#extendedapproval_responsibilities_moneylimit').val('');
        var releasetype = document.getElementById('extendedapproval_responsibilities_releasetype');
        releasetype.selectedIndex = 0;
        $('#editExtendedApprovalResponsibilities').find('#extendedapproval_responsibilities_release').val('');
        $('#editExtendedApprovalResponsibilities').find('#extendedapproval_responsibilities_email').prop("checked",false);
        ExtendedApprovalAutoCompletes();
    }

    function ExtendedApprovalResponsibilitiesEditSave() {
        $.ajax({
            url: 'index.php?module=extendedapproval&action=responsibilitiessave',
            data: {
                //Alle Felder die fürs editieren vorhanden sind
                id: $('#extendedapproval_responsibilities_id').val(),
                doctype: $('#extendedapproval_responsibilities_doctype').val(),
                requestertype: $('#extendedapproval_responsibilities_requestertype').val(),
                requester: $('#extendedapproval_responsibilities_requester').val(),
                moneylimit: $('#extendedapproval_responsibilities_moneylimit').val(),
                releasetype: $('#extendedapproval_responsibilities_releasetype').val(),
                release: $('#extendedapproval_responsibilities_release').val(),
                email: $('#extendedapproval_responsibilities_email').prop("checked")?1:0
            },
            method: 'post',
            dataType: 'json',
            beforeSend: function() {
                App.loading.open();
            },
            success: function(data) {
                App.loading.close();
                if (data.status == 1) {
                    ExtendedApprovalResponsibilitiesReset();
                    updateLiveTable();
                    $("#editExtendedApprovalResponsibilities").dialog('close');
                } else {
                    alert(data.statusText);
                }
            }
        });
    }

    function ExtendedApprovalResponsibilitiesEdit(id) {
        if(id > 0)
        {
            $.ajax({
                url: 'index.php?module=extendedapproval&action=responsibilitiesedit&cmd=get',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                beforeSend: function() {
                    App.loading.open();
                },
                success: function(data) {
                    $('#editExtendedApprovalResponsibilities').find('#extendedapproval_responsibilities_id').val(data.id);
                    $('#editExtendedApprovalResponsibilities').find('#extendedapproval_responsibilities_doctype').val(data.doctype);
                    $('#editExtendedApprovalResponsibilities').find('#extendedapproval_responsibilities_requestertype').val(data.requestertype);
                    $('#editExtendedApprovalResponsibilities').find('#extendedapproval_responsibilities_requester').val(data.requester);
                    $('#editExtendedApprovalResponsibilities').find('#extendedapproval_responsibilities_moneylimit').val(data.moneylimit);
                    $('#editExtendedApprovalResponsibilities').find('#extendedapproval_responsibilities_releasetype').val(data.releasetype);
                    $('#editExtendedApprovalResponsibilities').find('#extendedapproval_responsibilities_release').val(data.release);
                    $('#editExtendedApprovalResponsibilities').find('#extendedapproval_responsibilities_email').prop("checked", data.email==1?true:false);
                    ExtendedApprovalAutoCompletes();
                    App.loading.close();
                    $("#editExtendedApprovalResponsibilities").dialog('open');
                }
            });
        } else {
            ExtendedApprovalResponsibilitiesReset();
            $("#editExtendedApprovalResponsibilities").dialog('open');
        }
    }

    function updateLiveTable(i) {
        var oTableL = $('#extendedapproval_responsibilities').dataTable();
        var tmp = $('.dataTables_filter input[type=search]').val();
        oTableL.fnFilter('%');
        //oTableL.fnFilter('');
        oTableL.fnFilter(tmp);
    }

    function ExtendedApprovalResponsibilitiesDelete(id) {
        var conf = confirm('Wirklich löschen?');
        if (conf) {
            $.ajax({
                url: 'index.php?module=extendedapproval&action=responsibilitiesdelete',
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

    function ExtendedApprovalAutoCompletes(){
        if($('#extendedapproval_responsibilities_requestertype').val() === 'employee'){
            $( "input#extendedapproval_responsibilities_requester" ).autocomplete({
                source: "index.php?module=ajax&action=filter&filtername=mitarbeiteraktuell"
            });
        }
        if($('#extendedapproval_responsibilities_requestertype').val() === 'group'){
            $( "input#extendedapproval_responsibilities_requester" ).autocomplete({
                source: "index.php?module=ajax&action=filter&filtername=gruppekennziffer"
            });
        }

        if($('#extendedapproval_responsibilities_releasetype').val() === 'employee'){
            $( "input#extendedapproval_responsibilities_release" ).autocomplete({
                source: "index.php?module=ajax&action=filter&filtername=mitarbeiteraktuell"
            });
        }
        if($('#extendedapproval_responsibilities_releasetype').val() === 'group'){
            $( "input#extendedapproval_responsibilities_release" ).autocomplete({
                source: "index.php?module=ajax&action=filter&filtername=gruppekennziffer"
            });
        }
    }
</script>