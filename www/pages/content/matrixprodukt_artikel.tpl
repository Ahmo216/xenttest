<div id="tabs">
	<ul>
		<li><a href="#tabs-1">[TABTEXT]</a></li>
	</ul>
	<!-- ende gehort zu tabview -->

	<!-- erstes tab -->
	<div id="tabs-1">
		<style>
			.matrixtab-wrapper {
				overflow-x: auto;
			}

			div#matrixcontainer {
				overflow-x: auto;
				width: 100%;
			}

			table.matrixtab {background-color:white; min-width:500px; margin:10px;} table.matrixtab th{background-color:#e0e0e0;} .matrixtab > tbody:nth-child(1) > tr:nth-child(n) > td:nth-child(1) {padding-left:20px; padding-right:20px;} .tbvk {text-align:right;} .tbek {text-align:right;} .tblager {text-align:right;} .tblink {font-weight:normal;font-size:80%; padding-left:20px; padding-right:20px;} table.matrixtab td.row2 {background-color:#f0f0f0;} div.produktdiv {border:0px solid #333; font-size:80%;min-width:120px; min-height:100px;} input.auswahl {
				position: relative;
				left: 3px;
			}
		</style>
		<script>
        var gesamtcount = [GESAMTCOUNT];
        $(document).ready(function () {

            $('#artikeleditdialog').dialog(
                {
                    modal: true,
                    autoOpen: false,
                    minWidth: 940,
                    title: 'Artikel editieren',
                    buttons: {
                        SPEICHERN: function () {
                            $('#frmmassenbearbeitung').submit();
                        },
                        ABBRECHEN: function () {
                            $(this).dialog('close');
                        }
                    },
                    close: function (event, ui) {

                    }
                });

            $('#artikelerzeugendialog').dialog(
                {
                    modal: true,
                    autoOpen: false,
                    minWidth: 940,
                    title: 'Artikel erzeugen',
                    buttons: {
                        SPEICHERN: function () {
                            $('#frmartikelerzeugen').submit();
                        },
                        ABBRECHEN: function () {
                            $(this).dialog('close');
                        }
                    },
                    close: function (event, ui) {

                    }
                });

            $('#artikeldialog').dialog({
                modal: true,
                bgiframe: true,
                closeOnEscape: false,
                autoOpen: false,
                minWidth: 840,
                buttons: {
                    ABBRECHEN: function () {
                        //clearModal();
                        $(this).dialog('close');
                    },
                    SPEICHERN: function () {
                        $(this).dialog('close');
                        /*if($('#name').val() != '' && $('#name').val() != undefined)
												{
													$('#positionModalSubmit').submit();
												} else {
													alert('Bitte einen Namen eingeben!');
												}*/
                    }
                }
            });
            $('#gruppendialog').dialog({
                modal: true,
                bgiframe: true,
                closeOnEscape: false,
                autoOpen: false,
                minWidth: 840,
                buttons: {
                    ABBRECHEN: function () {
                        //clearModal();
                        $(this).dialog('close');
                    },
                    SPEICHERN: function () {
                        $(this).dialog('close');
                        var ausw = 'eigen';
                        var select = $('#gruppendialogselect').val();
                        var nam = $('#gruppendialogname').val();
                        var liste = '';
                        if ($('#gruppendialogauswahlkopie').prop('checked')) {
                            ausw = 'kopie';
                            $('#gruppendialog input[type="checkbox"]').each(function () {
                                if ($(this).prop('checked')) {
                                    var aid = this.id.split('_');
                                    var eins = 1;
                                    if (typeof aid[eins] != 'undefined') {
                                        if (aid[eins]) {
                                            if (liste != '') liste = liste + ';';
                                            liste = liste + aid[eins];
                                        }
                                    }
                                }
                            });


                        }
                        var sortierung = $('#gruppendialogsort').val();
                        if ((ausw == 'eigen' && nam != '') || select != '') {
                            $.ajax({
                                url: 'index.php?module=matrixprodukt&action=artikel&id=[ID]',
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    auswahl: ausw,
                                    do: 'newgroup',
                                    sort: sortierung,
                                    name: nam,
                                    sel: select,
                                    optionen: liste
                                }
                            }).done(function (data) {

                                if (data.status == 1) {
                                    window.location.reload();
                                } else {
                                    alert(data.meldung);
                                }
                            });
                        }
                    }
                }
            });
            $('#optiondialog').dialog({
                modal: true,
                bgiframe: true,
                closeOnEscape: false,
                autoOpen: false,
                minWidth: 840,
                buttons: {
                    ABBRECHEN: function () {
                        //clearModal();
                        $(this).dialog('close');
                    },
                    SPEICHERN: function () {
                        $(this).dialog('close');
                        var nam = $('#optiondialogname').val();
                        var gr = $('#optiondialoggruppe').val();
                        var sortierung = $('#optiondialogsort').val();
                        var sortierung = $('#optiondialogsort').val();
                        if (nam != '') {
                            $.ajax({
                                url: 'index.php?module=matrixprodukt&action=artikel&id=[ID]',
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    do: 'newoption', sort: sortierung, name: nam, gruppe: gr
                                }
                            }).done(function (data) {

                                window.location.reload();
                            });
                        }
                    }
                }
            });

            $('#artikeleditierendialog').dialog({
                modal: true,
                bgiframe: true,
                closeOnEscape: false,
                autoOpen: false,
                minWidth: 840,
                title: 'Artikel bearbeiten',
                buttons: {
                    ABBRECHEN: function () {
                        $(this).dialog('close');
                    },
                    SPEICHERN: function () {
                        var artikeleditwert2 = $('#artikeleditwert2');
                        if (artikeleditwert2) {
                            artikeleditwert2 = $(artikeleditwert2).val();
                        }
                        var artikeleditwert3 = $('#artikeleditwert3');
                        if (artikeleditwert3) {
                            artikeleditwert3 = $(artikeleditwert3).val();
                        }
                        var artikeleditwert4 = $('#artikeleditwert4');
                        if (artikeleditwert4) {
                            artikeleditwert4 = $(artikeleditwert4).val();
                        }
                        var artikeleditgruppe1 = $('#artikeleditgruppe1');
                        if (artikeleditgruppe1) {
                            artikeleditgruppe1 = $(artikeleditgruppe1).val();
                        }
                        var artikeleditgruppe2 = $('#artikeleditgruppe2');
                        if (artikeleditgruppe2) {
                            artikeleditgruppe2 = $(artikeleditgruppe2).val();
                        }
                        var artikeleditgruppe3 = $('#artikeleditgruppe3');
                        if (artikeleditgruppe3) {
                            artikeleditgruppe3 = $(artikeleditgruppe3).val();
                        }
                        var artikeleditgruppe4 = $('#artikeleditgruppe4');
                        if (artikeleditgruppe4) {
                            artikeleditgruppe4 = $(artikeleditgruppe4).val();
                        }
                        $.ajax({
                            url: 'index.php?module=matrixprodukt&action=artikel&id=[ID]&cmd=saveartikel',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                artikelid: $('#artikeleditartikelid').val(),
                                artikel: $('#artikeleditartikel').val(),
                                wert1: $('#artikeleditwert1').val(),
                                wert2: artikeleditwert2,
                                wert3: artikeleditwert3,
                                wert4: artikeleditwert4,
                                gruppe1: artikeleditgruppe1,
                                gruppe2: artikeleditgruppe2,
                                gruppe3: artikeleditgruppe3,
                                gruppe4: artikeleditgruppe4
                            }
                        }).done(function (data) {
                            if (typeof data.status != 'undefined' && data.status == 1) {
                                window.location.reload();
                            } else if (typeof data.Error != 'undefined') {
                                alert(data.Error);
                            }
                        });
                    }
                }
            });

            $('#optioneditdialog').dialog({
                modal: true,
                bgiframe: true,
                closeOnEscape: false,
                autoOpen: false,
                minWidth: 840,
                title: 'Artikel bearbeiten',
                buttons: {
                    ABBRECHEN: function () {
                        $(this).dialog('close');
                    },
                    SPEICHERN: function () {
                        $.ajax({
                            url: 'index.php?module=matrixprodukt&action=artikel&id=[ID]&cmd=saveoption',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                optionid: $('#optioneditid').val(),
                                bezeichnung: $('#optioneditbezeichnung').val(),
                                articlenumber_suffix: $('#optioneditnumbersuffix').val(),
                                sort: $('#optioneditsort').val(),
                                groupid: $('#optioneditgroup').val()
                            }
                        }).done(function (data) {
                            if (typeof data.status != 'undefined' && data.status == 1) {
                                window.location.reload();
                            } else if (typeof data.Error != 'undefined') {
                                alert(data.Error);
                            }
                        });
                    },
                    'LÖSCHEN': function () {
                        if (confirm('Option wirklich löschen?')) {
                            $.ajax({
                                url: 'index.php?module=matrixprodukt&action=artikel&id=[ID]&cmd=deleteoption',
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    optionid: $('#optioneditid').val()
                                }
                            }).done(function (data) {
                                if (typeof data.status != 'undefined' && data.status == 1) {
                                    window.location.reload();
                                } else if (typeof data.Error != 'undefined') {
                                    alert(data.Error);
                                }
                            });
                        }
                    }
                }
            });

			$( "input#optiondialogname" ).autocomplete({
				source: function( request, response ) {
					$.ajax( {
						url: "index.php?module=ajax&action=filter&rmodule=matrixprodukt&filtername=matrixproduktartikeloptionfuergruppe&groupid="+encodeURI($("#optiondialoggruppe").val())
						,
						dataType: "json",
						data: {
							term: request.term
						},
						success: function( data ) {
							if(data == null)
							{
								response ([]);
							}else
								response( data.length === 1 && data[ 0 ].length === 0 ? [] : data );
						}
					} );
				}
			});

        });

        function clickoption(el, ind, modu, sort) {
            var checked = $(el).prop('checked');
            var i = 0;
            if (sort == 0) {
                //Horizontal
                if (modu > 0) {
                    for (i = ind; i < gesamtcount; i += modu) {
                        var k = i;
                        $('#auswahl_' + k).prop('checked', checked);
                    }
                }
            } else {
                for (i = 0; i < modu; i++) {
                    var k = ind * modu + i;
                    $('#auswahl_' + k).prop('checked', checked);
                }
            }
        }

        function clickall(el) {
            var checked = $(el).prop('checked');
            for (i = 0; i < gesamtcount; i++) {
                $('#auswahl_' + i).prop('checked', checked);
            }
        }

        function changeArtikel(artikel, tabellei) {
            $('#artikeldialog').dialog('open');
            var tabelle = $('#tabelle_' + tabellei);
            var ek = $(tabelle).find('.tbek').html();
            var vk = $(tabelle).find('.tbvk').html();
            var nummer = $(tabelle).find('.tbnummer').html();
            var name = $(tabelle).find('.tbname').html();
            $('#modalnummer').val(nummer);
            $('#modalname').val(name);
            $('#modalek').val(ek);
            $('#modalvk').val(vk);
        }

        function EditOption(opt, group) {
            if (typeof group == 'undefined') {
                group = 0;
            }
            $.ajax({
                url: 'index.php?module=matrixprodukt&action=artikel&cmd=getoption&id=[ID]',
                type: 'POST',
                dataType: 'json',
                data: {
                    optionid: opt,
                    groupid: group
                }
            }).done(function (data) {
                if (typeof data.id != 'undefined') {
                    $('#optioneditbezeichnung').val(data.name);
                    $('#optioneditnumbersuffix').val(data.articlenumber_suffix);
                    $('#optioneditid').val(data.id);
                    $('#optioneditgroup').val(data.groupid);
                    $('#optioneditsort').val(data.sort);
                    $('#optioneditdialog').dialog('open');
                }
            });
        }

        function artikelerzeugen() {
            var anz = 0;
            var artikel = '';
            $('input.auswahl:checked').each(function () {
                var found = false;
                $(this).parent().find('span.tbnummer').each(function (){found=true;});
                if (!found) {
                    anz++;
                    var id = this.id.split('_');
                    if (artikel != '') artikel = artikel + ';';
                    artikel = artikel + '' + id[1];
                }
            });
            if (anz == 0) {
                alert('Bitte Auswahlboxen wählen');
                return;
            }

            $('#artikelerzeugendialog').dialog('open');
            $('#artikelerzeugenart').val(artikel);
        }

        function artikeleditieren() {
            var anz = 0;
            var artikel = '';
            $('input.auswahl:checked').each(function () {
                var found = false;
                $(this).parent().find('input[type="hidden"]').each(function () {
                    anz++;
                    if (artikel != '') artikel = artikel + ';';
                    artikel = artikel + '' + $(this).val();
                });
            });
            if (anz == 0) {
                alert('Bitte Auswahlboxen wählen');
                return;
            } else {
                matrixproduktedit_open(artikel);
            }
        }

        function newgroup(nr) {
            $('#gruppendialogname').val();
            $('#gruppendialogsort').val(nr);
            $('#gruppendialog').dialog('open');
            loadgruppenliste();

        }

        function newoption(gruppe, newsort) {
            $('#optiondialoggruppe').val(gruppe);
            $('#optiondialogsort').val(newsort);
            $('#optiondialog').dialog('open');
        }

        function gruppendialogtyp(wert) {
            if (wert == 1) {
                $('#gruppendialogauswahlkopie').prop('checked', true);
            } else {
                $('#gruppendialogauswahleigen').prop('checked', true);
            }
        }

        function loadgruppenliste() {
            $('#optionsauswahldialog').html('');
            var wert = $('#gruppendialogselect').val();
            if (wert) {
                $.ajax({
                    url: 'index.php?module=matrixprodukt&action=artikel&cmd=getoptionen&id=[ID]',
                    type: 'POST',
                    dataType: 'text',
                    data: {
                        gruppe: wert
                    }
                }).done(function (data) {
                    $('#optionsauswahldialog').html(data);
                });
            }
        }

        function EditArtikel(artikelid, tab) {
            $.ajax({
                url: 'index.php?module=matrixprodukt&action=artikel&cmd=getartikel&id=[ID]',
                type: 'POST',
                dataType: 'json',
                data: {
                    artikel: artikelid, index: tab
                }
            }).done(function (data) {

                if (typeof data.id != 'undefined') {
                    $('#artikeleditartikelid').val(data.id);
                } else {
                    $('#artikeleditartikelid').val(0);
                }
                if (typeof data.nummer != 'undefined') {
                    $('#artikeleditartikel').val(data.nummer + ' ' + data.name_de);
                } else {
                    $('#artikeleditartikel').val('');
                }
                if (typeof data.wert1 != 'undefined') {
                    $('#artikeleditwert1').val(data.wert1);
                } else {
                    $('#artikeleditwert1').val('');
                }
                if (typeof data.wert2 != 'undefined') {
                    $('#artikeleditwert2').val(data.wert2);
                } else {
                    $('#artikeleditwert2').val('');
                }
                if (typeof data.wert3 != 'undefined') {
                    $('#artikeleditwert3').val(data.wert3);
                } else {
                    $('#artikeleditwert3').val('');
                }
                if (typeof data.wert4 != 'undefined') {
                    $('#artikeleditwert4').val(data.wert4);
                } else {
                    $('#artikeleditwert4').val('');
                }
                if (typeof data.gruppe1 != 'undefined') {
                    $('#artikeleditgruppe1').val(data.gruppe1);
                } else {
                    $('#artikeleditgruppe1').val('');
                }
                if (typeof data.gruppe2 != 'undefined') {
                    $('#artikeleditgruppe2').val(data.gruppe2);
                } else {
                    $('#artikeleditgruppe2').val('');
                }
                if (typeof data.gruppe3 != 'undefined') {
                    $('#artikeleditgruppe3').val(data.gruppe3);
                } else {
                    $('#artikeleditgruppe3').val('');
                }
                if (typeof data.gruppe4 != 'undefined') {
                    $('#artikeleditgruppe4').val(data.gruppe4);
                } else {
                    $('#artikeleditgruppe4').val('');
                }
                $('#artikeleditierendialog').dialog('open');
            });
        }

        function Etikett(artikelid) {
            $.ajax({
                url: 'index.php?module=matrixprodukt&action=artikel&cmd=etikett&id=[ID]',
                type: 'POST',
                dataType: 'json',
                data: {
                    artikel: artikelid
                }
            }).done(function (data) {

            });
        }
		</script>
		<fieldset>
			<legend>{|Matrix|}</legend>
		</fieldset>
		[MESSAGE]

		<div id="matrixcontainer">
			<div></div><form method="post"><input type="submit" name="changetolist" value="Zu Listenansicht wechseln" /></form></div>
			[TAB1]
		</div>
		<div class="row">
			<div class="row-height">
				<div class="col-xs-12 col-sm-6 col-sm-height">
					<div class="inside inside-full-height">
						<fieldset>
							<legend>{|Artikel|}</legend>
							<table>
								<tr>
									<td>
										<input type="checkbox" id="changeall" onchange="clickall(this);" />&nbsp;
                    <label for="changeall">{|alle Artikel ausw&auml;hlen|}</label>
									</td>
									[VORARTIKELEDITIEREN]
									<td align="center">
										<input type="button" value="Massenbearbeitung öffnen" onclick="artikeleditieren();" />
									</td>
									[NACHARTIKELEDITIEREN]
									<td>
										<input type="button" value="Fehlende Artikel erzeugen" onclick="artikelerzeugen();" />
									</td>
								</tr>
							</table>
						</fieldset>
					</div>
				</div>

				<div class="col-xs-12 col-sm-6 col-sm-height">
					<div class="inside inside-full-height">
						<fieldset>
							<legend>{|Aktionen|}</legend>
							<table>
								<tr>
									<td>
										<input type="button" value="Expertenmodus für die Optionen öffnen"
													 onclick="window.location.href='index.php?module=matrixprodukt&action=artikelgruppen&id=[ID]';" />
									</td>
								</tr>
							</table>
						</fieldset>
					</div>
				</div>
			</div>
		</div>

		<div id="artikelerzeugendialog">
			<form method="POST" id="frmartikelerzeugen" action="index.php?module=matrixprodukt&action=artikel&id=[ID]">
				<input type="hidden" id="artikelerzeugendo" name="do" value="create" />
				<input type="hidden" id="artikelerzeugenart" name="art" value="" />
				<input type="hidden" name="id" value="[ID]" />
				<table>
					<tr>
						<td><input type="radio" checked="checked" id="auskategorie" value="auskategorie" name="nummertyp" /></td>
						<td><label for="auskategorie">{|Artikelnummern aus Nummerkreis von Hauptkategorie verwenden|}</label></td>
					</tr>
					<tr>
						<td><input type="radio" id="ausoptionen" value="ausoptionen" name="nummertyp" /></td>
						<td><label for="ausoptionen">{|Artikelnummern aus Optionen an Hauptnummer anf&uuml;gen|}</label>
						<td></td>
					</tr>
					<tr>
						<td><input type="radio" id="aussuffix" value="aussuffix" name="nummertyp" /></td>
						<td><label for="aussuffix">{|Artikelnummern aus Hauptnummer und Anhang an Artikelnummern bilden. Keine zus&auml;tzlichen Trennzeichen.|}</label>
						<td></td>
					</tr>
					<tr>
						<td>
              <input type="radio" id="ausprefix" value="ausprefix" name="nummertyp"/>
            </td>
						<td>
              <label for="ausprefix">{|Artikelnummern von Hauptartikel mit Suffix|}</label>
              <label for="prefixtrennzeichen">{|Trennzeichen|}:</label>
              <input type="text" id="prefixtrennzeichen" name="prefixtrennzeichen" value="[PREFIXTRENNZEICHEN]" size="1" />
              <label for="prefixstellen">{|Anzahl Stellen|}:</label>
              <input type="text" id="prefixstellen" name="prefixstellen" value=[PREFIXSTELLEN] size="3" />
              <label for="prefixnaechstenummer">{|N&auml;chste Nummer|}:</label>
              <input type="text" id="prefixnaechstenummer" name="prefixnaechstenummer" value="[PREFIXNAECHSTENUMMER]" size="5" />
						<td></td>
					</tr>
					<tr>
						<td>
              <input type="checkbox" id="name_fuer_unterartikel" name="name_fuer_unterartikel" value="1" [NAME_FUER_UNTERARTIKEL] />
            </td>
						<td>
              <label for="name_fuer_unterartikel">{|Optionen an Artikelbezeichnung der Unterartikel h&auml;ngen|}</label>
						</td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" id="ekzusatzbezeichnung" name="ekzusatzbezeichnung" value="1"/>
						</td>
						<td>
							<label for="ekzusatzbezeichnung">{|Zusatzbezeichnung als Lieferantenbezeichnung im Einkaufspreis anlegen|}</label>
						</td>
					</tr>
				</table>
			</form>
		</div>

		<div id="artikeldialog">
			<table>
				<tr>
					<td valign="top">
						<table>
							<tr>
								<td><label for="modalnummer">{|Artikel-Nr|}</label></td>
								<td><input type="text" id="modalnummer" size="40"/></td>
							</tr>
							<tr>
								<td><label for="modalname">{|Artikel|}</label></td>
								<td><input type="text" id="modalname" size="40"/></td>
							</tr>
							<tr>
								<td><label for="modalek">{|EK|}</label></td>
								<td><input type="text" id="modalek"/></td>
							</tr>
							<tr>
								<td><label for="modalvk">{|VK|}</label></td>
								<td><input type="text" id="modalvk"/></td>
							</tr>
						</table>
					</td>
					<td valign="top">
						[DIALOGOPTIONEN]
					</td>
				</tr>
			</table>
		</div>
		[TAB1NEXT]
	</div>

	<div id="artikeleditdialog">
		<div id="innerartikeleditdialog"></div>
	</div>
	<div id="artikelcreatedialog">
		<div id="innerartikelcreatedialog"></div>
	</div>

	<div id="optiondialog">
		<table>
			<tr>
				<td>
					<label for="optiondialogname">{|Name|}:</label>
				</td>
				<td>
					<input type="hidden" id="optiondialoggruppe"/>
					<input type="text" name="optiondialogname" id="optiondialogname" value=""/>
				</td>
			</tr>
			<tr>
				<td>
					<label for="optiondialogsort">{|Sortierung|}:</label>
				</td>
				<td>
					<input type="text" name="optiondialogsort" id="optiondialogsort" value="1"/>
				</td>
			</tr>
		</table>
	</div>

	<div id="artikeleditierendialog" style="display:none;">
		<table>
			<tr>
				<td>
					<label for="artikeleditartikel">{|Artikel|}:</label>
				</td>
				<td>
					<input type="hidden" id="artikeleditartikelid" value=""/>
					<input size="40" type="text" name="artikeleditartikel" id="artikeleditartikel" value=""/>
				</td>
			</tr>
			<tr>
				<td><label for="artikeleditwert1">[GRUPPENNAME1]:</label></td>
				<td>
					<input type="hidden" name="artikeleditgruppe1" id="artikeleditgruppe1"/>
					<input type="text" size="40" name="artikeleditwert1" id="artikeleditwert1" value=""/>
				</td>
			</tr>
			[VORGRUPPE2]
			<tr>
				<td><label for="artikeleditwert2">[GRUPPENNAME2]:</label></td>
				<td>
					<input type="hidden" name="artikeleditgruppe2" id="artikeleditgruppe2"/>
					<input type="text" size="40" name="artikeleditwert2" id="artikeleditwert2" value=""/>
				</td>
			</tr>
			[NACHGRUPPE2]
			[VORGRUPPE3]
			<tr>
				<td>
					<label for="artikeleditwert3">[GRUPPENNAME3]:</label></td>
				<td>
					<input type="hidden" name="artikeleditgruppe3" id="artikeleditgruppe3"/>
					<input type="text" size="40" name="artikeleditwert3" id="artikeleditwert3" value=""/>
				</td>
			</tr>
			[NACHGRUPPE3]
			[VORGRUPPE4]
			<tr>
				<td><label for="artikeleditwert4">[GRUPPENNAME4]:</label></td>
				<td>
					<input type="hidden" name="artikeleditgruppe4" id="artikeleditgruppe4"/>
					<input type="text" size="40" name="artikeleditwert4" id="artikeleditwert4" value=""/>
				</td>
			</tr>
			[NACHGRUPPE4]
		</table>
	</div>
	<div id="optioneditdialog" style="display:none;">
		<table>
			<tr>
				<td><label for="optioneditbezeichnung">{|Bezeichnung|}:</label></td>
				<td>
					<input type="hidden" id="optioneditid"/>
					<input type="hidden" id="optioneditgroup"/>
					<input type="text" size="40" id="optioneditbezeichnung"/>
				</td>
			</tr>
			<tr>
				<td><label for="optioneditnumbersuffix">{|Anhang an Artikelnummer|}:</label></td>
				<td><input type="text" size="40" id="optioneditnumbersuffix" /></td>
			</tr>
			<tr>
				<td><label for="optioneditsort">{|Sortierung|}:</label></td>
				<td>
					<input type="text" size="6" id="optioneditsort"/>
				</td>
			</tr>
		</table>
	</div>

	<div id="gruppendialog">
		<table width="100%" class="mkTableFormular">
			<tr>
				<td></td>
				<td>
					<input checked="checked" type="radio" id="gruppendialogauswahleigen" name="gruppendialogauswahl" value="eigen"/>&nbsp;
					<label for="gruppendialogauswahleigen">{|Neue Gruppe|}</label></td>
				<td colspan="2">
					<input type="radio" id="gruppendialogauswahlkopie" name="gruppendialogauswahl" value="bestehend"/>&nbsp;
					<label for="gruppendialogselect">{|Grundtabelle|}:</label>&nbsp;
					<select name="gruppendialogselect" onclick="gruppendialogtyp(1)" onchange="loadgruppenliste()" id="gruppendialogselect">
						[GRUPPENDIALOGSELECT]
					</select>
				</td>
			</tr>
			<tr>
				<td valign="top"><label for="gruppendialogname">{|Bezeichnung|}:</label></td>
				<td>
					<input type="text" size="30" id="gruppendialogname" name="gruppendialogname" onclick="gruppendialogtyp(0)"/>
				</td>
				<td rowspan="2" colspan="2" id="optionsauswahldialog" valign="top"></td>
			</tr>
			<tr [POSITIONSTYLE]>
				<td><label for="gruppendialogsort">{|Position|}:</label></td>
				<td>
					<select name="gruppendialogsort" id="gruppendialogsort">
						<option value="0" [HORIZONTALCHECKED]>{|Horizontal|}</option>
						<option value="1" [VERTIKALCHECKED]>{|Vertikal|}</option>
					</select>
				</td>
			</tr>
		</table>
	</div>
	<!-- tab view schließen -->
</div>

<div id="GroupheadlineDialog">
	<fieldset>
		<legend>{|Gruppe &auml;ndern / l&ouml;schen|}</legend>
		<input type="hidden" id="GroupheadlineDialogGroupId"/><input type="hidden" id="GroupheadlineDialogArticleId"/>
		<input type="text" size="40" id="GroupheadlineDialogGroupName"/>
	</fieldset>
</div>
