<script type="text/javascript">
    function createSchlussrechnung(){
	    $("#createSchlussrechnung").dialog('open');
    }
    function createTeilrechnung(){
	    $("#createTeilrechnung").dialog('open');
    }
    function updateLiveTable() {
	    var oTableL = $('#abschlagsrechnung_auftraglist').dataTable();
	    if(oTableL)
	    {
		    oTableL.fnFilter('%');
		    oTableL.fnFilter('');
	    }
    }


    $(document).ready(function() {

	    $("#createTeilrechnung").dialog({
		    modal: true,
		    bgiframe: true,
		    closeOnEscape:false,
		    minWidth:500,
		    minHeight:200,
		    autoOpen: false,
		    buttons: {
			    ABBRECHEN: function() {
				    $(this).dialog('close');
			    },
			    ERSTELLEN: function() {
				    $.ajax({
					    url: 'index.php?module=abschlagsrechnung&action=teilrechnung',
					    data: {
						    id: $('#auftrag').val(),
						    bezeichnung: $('#bezeichnung_text_teilrechnung').val(),
							posnulluebernehmen: $('#PosNulluebernehmen').prop("checked") ? 1 : 0,
                            artikel_nummer: $('#artikel_teilzahlung').val(),
							artikel_bezeichnung: $('#artikel_teilzahlung_bezeichnung').val(),
						    artikel_beschreibung: $('#artikel_teilzahlung_beschreibung').val(),
							artikel_menge: $('#artikel_teilzahlung_menge').val(),
							artikel_preis: $('#artikel_teilzahlung_preis').val()
					    },
					    method: 'post',
					    dataType: 'json',
					    success: function(data) {
							updateLiveTable();
						    window.open('index.php?module=rechnung&action=edit&id=' + data.rechnung,'_blank');
					    }
				    });

				    $(this).dialog('close');
			    }
		    }
	    });



	    $("#createSchlussrechnung").dialog({
		    modal: true,
		    bgiframe: true,
		    closeOnEscape:false,
		    minWidth:500,
		    minHeight:200,
		    autoOpen: false,
		    buttons: {
			ABBRECHEN: function() {
		        $(this).dialog('close');
            },
	        ERSTELLEN: function() {
			        $.ajax({
				        url: 'index.php?module=abschlagsrechnung&action=schlussrechnung',
				        data: {
					        id: $('#auftrag').val(),
							bezeichnung: $('#bezeichnung_text_schlussrechnung').val()
				        },
				        method: 'post',
				        dataType: 'json',
						success: function(data) {
							updateLiveTable();
						    window.open('index.php?module=rechnung&action=edit&id=' + data.rechnung,'_blank');
						}
			        });
			        $(this).dialog('close');
	            }
            }
        });
    });
</script>


<!-- gehort zu tabview -->
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">[TABTEXT]</a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
  [MESSAGE]
    <div class="row">
        <div class="row-height">
            <div class="col-xs-12 col-md-10 col-sm-height">
              <fieldset class="white">
                <legend>&nbsp;</legend>
                [TAB1]
              </fieldset>
            </div>
            <div class="col-xs-12 col-md-2 col-sm-height">
                <div class="inside inside-full-height">
                    <fieldset><legend>&nbsp;Aktion</legend>
                        <input type="button" class="btnGreenNew" onclick="createTeilrechnung()" value="Teilrechnung erzeugen" style="width:100%">
                        <input type="button" class="btnGreenNew" onclick="createSchlussrechnung()" value="Schlussrechnung erzeugen" style="width:100%">
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- tab view schließen -->
</div>

<div id="createTeilrechnung" style="display:none;" title="Teilrechnung erstellen">
    <p>Bitte vergeben Sie einen entsprechenden Namen.</p>
    <input type="hidden" name="auftrag" id="auftrag" value="[AUFTRAG]" /><br>

    <fieldset>
        <legend>Bezeichnung für Teilzahlung-Rechnung</legend>
        <input type="text" size="60" value="[STANDARDBEZEICHNUNGTEILRECHNUNG]" id="bezeichnung_text_teilrechnung" name="bezeichnung_text_teilrechnung" />
    </fieldset>
    <fieldset>
        <legend>Positionen übernehmen</legend>
        <input type="checkbox" value="1" name="PosNulluebernehmen" id="PosNulluebernehmen" />&nbsp;alle Positionen mit Menge 0 aus Ursprungsrechnung übernehmen.
    </fieldset>
    <fieldset>
        <legend>Artikel für Teilzahlung</legend>
        <input type="text" size="60" value="" id="artikel_teilzahlung" name="artikel_teilzahlung" />
    </fieldset>
    <fieldset>
        <legend>Artikelbezeichnung</legend>
        <input type="text" size="60" value="" id="artikel_teilzahlung_bezeichnung" name="artikel_teilzahlung_bezeichnung" />
    </fieldset>
    <fieldset>
        <legend>Artikelbeschreibung</legend>
        <textarea cols="60" rows="7" id="artikel_teilzahlung_beschreibung" name="artikel_teilzahlung_beschreibung"></textarea>
    </fieldset>
    <fieldset>
        <legend>Menge</legend>
        <input type="text" size="60" value="" id="artikel_teilzahlung_menge" name="artikel_teilzahlung_menge" />
    </fieldset>
    <fieldset>
        <legend>Preis</legend>
        <input type="text" size="60" value="" id="artikel_teilzahlung_preis" name="artikel_teilzahlung_preis" />
    </fieldset>

</div>

<div id="createSchlussrechnung" style="display:none;" title="Schlussrechnung erstellen">
    <p>Bitte vergeben Sie einen entsprechenden Namen.</p>
    <input type="hidden" name="auftrag" id="auftrag" value="[AUFTRAG]" />
    <input type="text" size="60" value="[STANDARDBEZEICHNUNGSCHLUSSRECHNUNG]" id="bezeichnung_text_schlussrechnung" name="bezeichnung_text_schlussrechnung" />
</div>




<script type='text/javascript'>
	$("input#artikel_teilzahlung").autocomplete({
		source: "index.php?module=ajax&action=filter&filtername=artikelnummer",
		select: function( event, ui ) {
			var artikeldata = ui.item.value.split(' ')[0];
			$.ajax({
				url: 'index.php?module=abschlagsrechnung&action=data',
				data: {
					artikel: artikeldata
				},
				method: 'post',
				dataType: 'json',
				success: function (data) {
					$('#createTeilrechnung').find('#artikel_teilzahlung').val(artikeldata);
					$('#createTeilrechnung').find('#artikel_teilzahlung_bezeichnung').val(data.name_de);
					$('#createTeilrechnung').find('#artikel_teilzahlung_beschreibung').val(data.anabregs_text);
					$('#createTeilrechnung').find('#artikel_teilzahlung_menge').val('1');
					$('#createTeilrechnung').find('#artikel_teilzahlung_preis').val(data.nettopreis);
				}
			});

		}

	});

</script>
