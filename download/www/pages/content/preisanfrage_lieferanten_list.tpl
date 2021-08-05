<!-- gehort zu tabview -->
<div id="tabs">
	<ul>
		<li><a href="#tabs-1">[TABTEXT]</a></li>
	</ul>
	<!-- ende gehort zu tabview -->

	<!-- erstes tab -->
	<div id="tabs-1">
		[MESSAGE]

		<div id="lieferantenauswahl">
			[TAB1]

			<fieldset>
				<legend>{|Stapelverarbeitung|}</legend>
				<input type="checkbox" id="auswahlalle" onchange="alleauswaehlen();" value="auswahlalle"/>&nbsp;{|alle markieren|}&nbsp;
				<select id="sel_aktion" name="sel_aktion">
					<option value="">{|bitte w&auml;hlen|} ...</option>
					<option value="mail">{|per Mail versenden|}</option>
				</select>&nbsp;
				[DOKUMENTABSCHICKEN]
			</fieldset>
		</div>

		[TAB1NEXT]
	</div>

	<!-- tab view schließen -->
</div>

<script>
	function alleauswaehlen()
  {
    var wert = $('#auswahlalle').prop('checked');
    $('#preisanfrage_lieferanten_list').find(':checkbox').prop('checked',wert);
  }

	function GetAllCheckedSuppliers(){
	  var chkArray = [];

    $("#lieferantenauswahl input:checked").each(function() {

      if($(this).attr('name') == 'auswahl[]'){
        chkArray.push($(this).val());
      }
    });
    return chkArray;
  }

  function ausfuehren(){
		var checkboxen = GetAllCheckedSuppliers();
		var aktion = $('#lieferantenauswahl').find('#sel_aktion').val();
		if(checkboxen != '' && aktion == 'mail'){
      $.ajax({
        url: 'index.php?module=preisanfrage&action=lieferanten&cmd=mailadressen&id=[ID]',
        data: {
          adressids: checkboxen
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
        },
        success: function(data) {
          if(data.emailstring != ''){
            DokumentAbschicken('preisanfrage',data.preisanfrageid);
            $('#externalSite').on('load', () => {
              document.getElementById('externalSite').contentDocument.getElementById('ansprechpartnermanuellverwenden').checked = true;
              document.getElementById('externalSite').contentDocument.getElementById('ansprechpartnermanuellverwenden').disabled = true;
              document.getElementById('externalSite').contentDocument.getElementById('ansprechpartnermanuell').disabled = true;
							document.getElementById('externalSite').contentDocument.getElementById('selectrow').style.display = "none";
							document.getElementById('externalSite').contentDocument.getElementById('manuellrow').style.display = "";
							document.getElementById('externalSite').contentDocument.getElementById('emailchecked').checked = true;

              document.getElementById('externalSite').contentDocument.getElementById('ansprechpartnermanuell').value = data.emailstring;
              document.getElementById('externalSite').contentDocument.getElementById('pran_adressids').value = data.adressidstring;
            })

          }
        }
      });
    }
  }

</script>