<div id="tabs">
  <ul>
    <li><a href="#tabs-1">{|Allgemein|}</a></li>
		<li><a href="#tabs-2">{|Boards|}</a></li>
    <li><a href="#tabs-3">{|Stages|}</a></li>
    <li><a href="#tabs-4">{|Freifelder|}</a></li>
		<li><a href="#tabs-5">{|Aufgaben Vorlagen|}</a></li>
  </ul>
  <div id="tabs-1">
    [MESSAGE]
    <form method="post" action="">
      <div class="row">
        <div class="row-height">
          <div class="col-xs-12 col-sm-6 col-sm-height">
            <div class="inside inside-full-height">
              <fieldset>
                <legend>{|Zusatzfelder Adresstabelle|}</legend>
	              <input type="hidden" name="adressetabellezusatz" value="save">
                <table cellspacing="5" width="100%">
                  <tr>
                    <td width="10%">Spalte 1:</td>
                    <td width="10%"><select id="adressetabellezusatz1" name="adressetabellezusatz1">[SELADRESSETABELLEZUSATZ1]</select></td>
                    <td width="80%"><label>[ZUSATZSICHTBARINPIPE1]{|sichtbar in Pipelines|}</label></td>
                  </tr>
                  <tr>
                    <td>Spalte 2:</td>
                    <td><select id="adressetabellezusatz2" name="adressetabellezusatz2">[SELADRESSETABELLEZUSATZ2]</select></td>
                    <td><label>[ZUSATZSICHTBARINPIPE2]{|sichtbar in Pipelines|}</label></td>
                  </tr>
                  <tr>
                    <td>Spalte 3:</td>
                    <td><select id="adressetabellezusatz3" name="adressetabellezusatz3">[SELADRESSETABELLEZUSATZ3]</select></td>
                    <td><label>[ZUSATZSICHTBARINPIPE3]{|sichtbar in Pipelines|}</label></td>
                  </tr>
                  <tr>
                    <td>Spalte 4:</td>
                    <td><select id="adressetabellezusatz4" name="adressetabellezusatz4">[SELADRESSETABELLEZUSATZ4]</select></td>
                    <td><label>[ZUSATZSICHTBARINPIPE4]{|sichtbar in Pipelines|}</label></td>
                  </tr>
                  <tr>
                    <td>Spalte 5:</td>
                    <td><select id="adressetabellezusatz5" name="adressetabellezusatz5">[SELADRESSETABELLEZUSATZ5]</select></td>
                    <td><label>[ZUSATZSICHTBARINPIPE5]{|sichtbar in Pipelines|}</label></td>
                  </tr>
                  <tr>
                    <td>Spalte 6:</td>
                    <td><select id="adressetabellezusatz6" name="adressetabellezusatz6">[SELADRESSETABELLEZUSATZ6]</select></td>
                    <td><label>[ZUSATZSICHTBARINPIPE6]{|sichtbar in Pipelines|}</label></td>
                  </tr>
                  <tr>
                    <td>Spalte 7:</td>
                    <td><select id="adressetabellezusatz7" name="adressetabellezusatz7">[SELADRESSETABELLEZUSATZ7]</select></td>
                    <td><label>[ZUSATZSICHTBARINPIPE7]{|sichtbar in Pipelines|}</label></td>
                  </tr>
                  <tr>
                    <td>Spalte 8:</td>
                    <td><select id="adressetabellezusatz8" name="adressetabellezusatz8">[SELADRESSETABELLEZUSATZ8]</select></td>
                    <td><label>[ZUSATZSICHTBARINPIPE8]{|sichtbar in Pipelines|}</label></td>
                  </tr>
                  <tr>
                    <td>Spalte 9:</td>
                    <td><select id="adressetabellezusatz9" name="adressetabellezusatz9">[SELADRESSETABELLEZUSATZ9]</select></td>
                    <td><label>[ZUSATZSICHTBARINPIPE9]{|sichtbar in Pipelines|}</label></td>
                  </tr>
                  <tr>
                    <td>Spalte 10:</td>
                    <td><select id="adressetabellezusatz10" name="adressetabellezusatz10">[SELADRESSETABELLEZUSATZ10]</select></td>
                    <td><label>[ZUSATZSICHTBARINPIPE10]{|sichtbar in Pipelines|}</label></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td><input type="submit" value="{|Speichern|}"></td>
                    <td></td>
                  </tr>
                </table>
              </fieldset>
            </div>
          </div>
          <div class="col-xs-12 col-sm-5 col-sm-height">
            <div class="inside inside-full-height">
              <fieldset>
                <legend>{|Dashboard|}</legend>
                <table cellspacing="5" width="100%">
                  <tr>
                    <td><label for="dashboard_customer_class">{|Kundenklasse|}:</label></td>
                    <td><select id="dashboard_customer_class" name="dashboard_customer_class">[SELDASHBOARD_CUSTOMER_CLASS]</select></td>
                  </tr>
                  <tr>
                    <td><label for="dashboard_incomming_stage">{|Eingang Stage|}:</label></td>
                    <td><select id="dashboard_incomming_stage" name="dashboard_incomming_stage">[SELDASHBOARD_INCOMMING_STAGE]</select></td>
                  </tr>
                  [INCOMMING_STAGE_ROWS]
                </table>

              </fieldset>
            </div>
          </div>
        </div>
      </div>
    </form>
    [TAB1NEXT]
  </div>

	<div id="tabs-2">
		<form method="post">
			[MESSAGE]
		</form>
		<div class="row">
			<div class="row-height">
				<div class="col-xs-12 col-md-10 col-md-height">
					<div class="inside-white inside-full-height">
						[VIEWSDATATABLE]
					</div>
				</div>
				<div class="col-xs-12 col-md-2 col-md-height">
					<div class="inside inside-full-height">
						<fieldset>
							<legend>{|Aktionen|}</legend>
							<input type="button" class="btnGreenNew" name="wiedervorlage_view_neu" value="&#10010; Neuer Eintrag" onclick="WiedervorlageViewEdit(0);">
						</fieldset>
					</div>
				</div>
			</div>
		</div>
		[TAB2NEXT]
	</div>

	<div id="tabs-3">
		[MESSAGE]
		<div class="row">
			<div class="row-height">
				<div class="col-xs-12 col-md-10 col-md-height">
					<div class="inside_white inside-full-height">
						<fieldset class="white">
							<legend></legend>
							[STAGESDATATABLE]
						</fieldset>
					</div>
				</div>
				<div class="col-xs-12 col-md-2 col-md-height">
					<div class="inside inside-full-height">
						<fieldset>
							<legend>{|Aktionen|}</legend>
							<input type="button" class="btnGreenNew" name="neuereintrag" value="&#10010; Neuer Eintrag" onclick="WiedervorlageStagesEdit(0);">
							<input type="button" class="btnBlueNew" name="standardladen" value="Standard laden" onclick="WiedervorlageStandardLaden();">
						</fieldset>
					</div>
				</div>
			</div>
		</div>
		[TAB3NEXT]
	</div>

	<div id="tabs-4">
		[MESSAGE]
		<div class="row">
			<div class="row-height">
				<div class="col-xs-12 col-md-10 col-md-height">
					<div class="inside_white inside-full-height">
						<fieldset class="white">
							<legend>{|Freifelder|}</legend>
							[TEXTFIELDDATATABLE]
						</fieldset>
					</div>
				</div>
				<div class="col-xs-12 col-md-2 col-md-height">
					<div class="inside inside-full-height">
						<fieldset>
							<legend>{|Aktionen|}</legend>
							<input type="button" id="resubmissiontextfield-create-button" class="btnGreenNew" value="&#10010; {|Neuer Eintrag|}">
						</fieldset>
					</div>
				</div>
			</div>
		</div>
		[TAB4NEXT]
	</div>

	<div id="tabs-5">
		[MESSAGE]
		<div class="row">
			<div class="row-height">
				<div class="col-xs-12 col-md-10 col-md-height">
					<div class="inside_white inside-full-height">
						<fieldset class="white">
							<legend>{|Aufgaben Vorlagen|}</legend>
							[TASKTEMPLATEDATATABLE]
						</fieldset>
					</div>
				</div>
				<div class="col-xs-12 col-md-2 col-md-height">
					<div class="inside inside-full-height">
						<fieldset>
							<legend>{|Aktionen|}</legend>
							<input type="button" id="resubmissiontasktemplate-create-button" class="btnGreenNew" value="&#10010; {|Neuer Eintrag|}">
						</fieldset>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>

<div id="resubmission_textfield_edit" class="hide" title="Freifeld bearbeiten">
	<input type="hidden" id="resubmissiontextfield-id" name="id" value="">
	<fieldset>
		<legend>{|Freifeld-Konfiguration|}</legend>
		<table class="mkTableFormular">
			<tr>
				<td width="140"><label for="resubmissiontextfield-title">{|Bezeichnung|}:</label></td>
				<td><input type="text" name="title" id="resubmissiontextfield-title"></td>
			</tr>
			<tr>
				<td><label for="resubmissiontextfield-availablestage">{|Verfügbar ab Stage|}:</label></td>
				<td>
					<select name="availablestage" id="resubmissiontextfield-availablestage">
						<option value="0">- Immer -</option>
						[STAGEOPTIONELEMENTS]
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="resubmissiontextfield-requiredstage">{|Pflichtfeld ab Stage|}:</label></td>
				<td>
					<select name="requiredstage" id="resubmissiontextfield-requiredstage">
						<option value="0">- Nie -</option>
						[STAGEOPTIONELEMENTS]
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="resubmissiontextfield-showinpipeline">{|In Pipeline anzeigen|}:</label></td>
				<td>
					<input type="checkbox" name="showinpipeline" id="resubmissiontextfield-showinpipeline">
				</td>
			</tr>
			<tr>
				<td><label for="resubmissiontextfield-showintables">{|In Tabellen anzeigen|}:</label></td>
				<td>
					<input type="checkbox" name="showintables" id="resubmissiontextfield-showintables">
				</td>
			</tr>
		</table>
	</fieldset>
</div>


<div id="resubmission_tasktemplate_edit" class="hide" title="Aufgabe Vorlage bearbeiten">
	<div class="row">
		<div class="row-height">
			<div class="col-xs-12 col-md-6 col-sm-height">
				<div class="inside inside-full-height">
					<input type="hidden" id="resubmissiontasktemplate-id" name="id" value="">
					<fieldset>
						<legend>{|Aufgabe|}</legend>
						<table class="mkTableFormular">
							<tr>
								<td width="160"><label for="resubmissiontasktemplate-title">{|Bezeichnung|}:</label></td>
								<td colspan="2"><input type="text" name="title" size="40" id="resubmissiontasktemplate-title"></td>
							</tr>
							<tr>
								<td><label for="resubmissiontasktemplate-employee">{|Bearbeiter|}:</label></td>
								<td colspan="2"><input type="text" name="employee" size="40" id="resubmissiontasktemplate-employee"></td>
							</tr>
							<tr>
								<td><label for="resubmissiontasktemplate-submissiondatedays">{|WVL-Datum zzgl.|}:</label></td>
								<td>
									<input type="text" name="submissiondatedays" size="10" id="resubmissiontasktemplate-submissiondatedays">&nbsp;{|Tage|}&nbsp;&nbsp;&nbsp;
									<input type="text" name="submissiontime" size="15" id="resubmissiontasktemplate-submissiontime">&nbsp;{|Uhr|}
								</td>
							</tr>
							<tr>
								<td><label for="resubmissiontasktemplate-project">{|Projekt|}:</label></td>
								<td colspan="2"><input type="text" name="project" size="40" id="resubmissiontasktemplate-project"></td>
							</tr>
							<tr>
								<td><label for="resubmissiontasktemplate-subproject">{|Teilprojekt|}:</label></td>
								<td colspan="2"><input type="text" name="subproject" size="40" id="resubmissiontasktemplate-subproject"></td>
							</tr>
						</table>
					</fieldset>
				</div>
			</div>
			<div class="col-xs-12 col-md-6 col-sm-height">
				<div class="inside inside-full-height">
					<input type="hidden" id="editid">
					<fieldset>
						<legend>{|Einstellungen|}</legend>
						<table class="mkTableFormular" width="100%">
							<tr>
								<td>
									<label for="resubmissiontasktemplate-requiredfromstage">{|Muss abgeschlossen sein ab Stage|}:</label>
								</td>
								<td>
									<select name="requiredfromstage" id="resubmissiontasktemplate-requiredfromstage">
										<option value="0">- Nie -</option>
										[STAGEOPTIONELEMENTS]
									</select>
								</td>
							</tr>
							<tr>
								<td>
									<label for="resubmissiontasktemplate-addtaskatstage">{|Hinzuf&uuml;gen in Stage|}:</label>
								</td>
								<td>
									<select name="addtaskatstage" id="resubmissiontasktemplate-addtaskatstage">
										[STAGEOPTIONELEMENTS]
									</select>
								</td>
							</tr>
							<tr>
								<td><label for="resubmissiontasktemplate-state">{|Status|}:</label></td>
								<td>
									<select name="state" id="resubmissiontasktemplate-state">
										<option value="open">{|Offen|}</option>
										<option value="processing">{|In Bearbeitung|}</option>
										<option value="completed">{|Abgeschlossen|}</option>
									</select>
								</td>
							</tr>
							<tr>
								<td><label for="resubmissiontasktemplate-priority">{|Priorität|}:</label></td>
								<td>
									<select name="priority" id="resubmissiontasktemplate-priority">
										<option value="low">Niedrig</option>
										<option value="medium">Mittel</option>
										<option value="high">Hoch</option>
									</select>
								</td>
							</tr>
						</table>
					</fieldset>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="row-height">
			<div class="col-md-12 col-sm-height">
				<div class="inside inside-full-height">
					<fieldset>
						<legend>{|Beschreibung|}</legend>
						<textarea name="description" id="resubmissiontasktemplatedescription" rows="5" cols="60"></textarea>
					</fieldset>
				</div>
			</div>
		</div>
	</div>

</div>

[WIEDERVORLAGESTAGESPOPUP]
[WIEDERVORLAGEVIEWSPOPUP]
