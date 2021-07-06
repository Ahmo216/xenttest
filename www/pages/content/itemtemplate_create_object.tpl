
<div id="newItemTemplateObject" style="display:none;" title="Neu">
	<form method="post">
		<fieldset>
			<legend></legend>
			<input type="hidden" name="itemTemplateModule" id="itemTemplateModule">
			<table>
				<tr>
					<td>
						<input type="radio" name="createObject" value="newObject" checked>{|Neu anlegen|}
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>
			<table>
				<tr>
					<td>
						<input type="radio" name="createObject" value="useTemplate">{|aus Vorlage anlegen|}
					</td>
				</tr>
			</table>
			<br />
			<br />
			<br />
			<legend>{|Vorlage|}</legend>
			[TEMPLATESELECTION]
		</fieldset>
	</form>
</div>



<script type="text/javascript">
    $(document).ready(function() {
        $("#newItemTemplateObject").dialog({
            modal: true,
            bgiframe: true,
            closeOnEscape:false,
            minWidth:650,
						minHeight:450,
						height:500,
            maxHeight:720,
            autoOpen: false,
            buttons: {
                ABBRECHEN: function() {
                    ItemTemplateObjectReset();
                    $(this).dialog('close');
                },
                ANLEGEN: function() {
                    ItemTemplateObjectEditSave();
                }
            }
        });

        $("#newItemTemplateObject").dialog({
            close: function (event, ui) { ItemTemplateObjectReset();}
        });

    });

    function ItemTemplateObjectReset(){
        $('#newItemTemplateObject').find('#itemTemplateModule').val('');
    }

    function ItemTemplateObjectEditSave(){
        var createObject = document.querySelector('input[name="createObject"]:checked').value;
        var selectedTemplate = document.querySelector('input[name="selectedTemplate"]:checked').value;

        $.ajax({
            url: 'index.php?module=itemtemplate&action=savenewobject',
            data: {
                //Alle Felder die fürs editieren vorhanden sind
                createObject: createObject,
                selectedTemplate: selectedTemplate,
                module: $('#itemTemplateModule').val()

            },
            method: 'post',
            dataType: 'json',
            beforeSend: function() {
                App.loading.open();
            },
            success: function(data) {
                App.loading.close();
                if (data.status == 1) {
                    ItemTemplateObjectReset();
                    updateLiveTableItemTemplate();
                    $("#newItemTemplateObject").dialog('close');
                    window.location.replace(data.redirect);
                } else {
                    alert(data.statusText);
                }
            }
        });
    }

    function ItemTemplateObjectEdit(module){
        ItemTemplateObjectReset();
        $("#newItemTemplateObject").dialog('open');
        oMoreData1itemtemplate_selection = module;
        $('#newItemTemplateObject').find('#itemTemplateModule').val(module);
        updateLiveTableItemTemplate();
    }

    function updateLiveTableItemTemplate(i) {
        var oTableL = $('#itemtemplate_selection').dataTable();
        var tmp = $('.dataTables_filter input[type=search]').val();
        oTableL.fnFilter('%');
        oTableL.fnFilter(tmp);
    }

</script>