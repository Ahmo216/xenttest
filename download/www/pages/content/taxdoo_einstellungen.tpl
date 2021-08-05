<!-- gehort zu tabview -->
<div id="tabs" class="taxdoo-settings">

<!-- ende gehort zu tabview -->
	<ul>
		<li><a href="#tabs-1">[TABTEXT]</a></li>
	</ul>
	<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
[MESSAGE]
[TAB1]
<!--
<div class="row">
<div class="row-height">
<div class="col-xs-12 col-sm-height">
<div class="inside inside-full-height">

<fieldset><legend>{|Status|}</legend>
<form id="meiappsform" action="" method="post" enctype="multipart/form-data">
<div class="info">Das letzte Backup wurde am 24.12.2001 um 06:12 erfolgreich durchgeführt.</div>
</form>

</fieldset>
</div>
</div>
</div>
</div>
-->

<div class="row">
<div class="row-height">


<div class="col-xs-12 col-md-12 col-md-height">
<div class="inside inside-full-height">

<fieldset><legend>{|Zugangsdaten Taxdoo|}</legend>
<form action="" method="post" id="">
<table>
<tr><td width="200">Token:</td><td><input type="text" size="30" name="token" value="[TOKEN]"></td></tr>
<tr><td width="200">Verkäufer Name:</td><td><input type="text" size="30" name="name" value="[NAME]"></td></tr>
<tr><td width="200">Verkäufer Straße:</td><td><input type="text" size="30" name="strasse" value="[STRASSE]"></td></tr>
<tr><td width="200">Verkäufer PLZ:</td><td><input type="text" size="30" name="plz" value="[PLZ]"></td></tr>
<tr><td width="200">Verkäufer Ort:</td><td><input type="text" size="30" name="ort" value="[ORT]"></td></tr>
<tr><td width="200">Verkäufer Bundesland:</td><td><input type="text" size="30" name="bundesland" value="[BUNDESLAND]"></td></tr>
<tr><td width="200">Verkäufer Land (ISO-2):</td><td><input type="text" size="30" name="land" value="[LAND]"></td></tr>
<tr><td width="200">Startdatum:</td><td><input type="date" size="30" name="startdate" value="[STARTDATE]"></td></tr>
	<tr><td width="200">folgende Projekte ausschließen:</td><td>
			<div>
				<label for="whitelist_projects" class="switch" >
					<input type="checkbox" id="whitelist_projects" name="whitelist_projects" [WHITELIST_PROJECTS_CHECKED]>
					<span class="slider round"></span>
				</label>
				<a>nur folgende Projekte &uuml;bertragen:</a>
			</div>
		</td>
	</tr>
<!--<tr>
	<td width="200">Projekte:</td>
	<td colspan="3">
		<input id="displayfilterproject" type="text" disabled value="[PROJECT_LIST]"/>
		<a id="editfilterproject" href="" class="filter-edit" data-filtertype="project">
			<img src="./themes/new/images/produkton_start.png" alt="Filter Bearbeiten"/>
		</a>
	</td>
	<td>
		<input type="checkbox" name="project_blacklist" id="chkprojectblacklist" value="1" [PROJECT_BLACKLIST] />
		<label for="chkprojectblacklist">ausschließen</label>
	</td>
</tr>-->

	<!--<tr><td width="200">Diese Projekte ausschließen:</td><td><input type="radio" name="projects_blacklist" value="[PROJECTS_BLACKLIST]"></td></tr>
<tr><td width="200">Nur diese Projekte einbeziehen:</td><td><input type="radio" name="projects_whitelist" value="[PROJECTS_WHITELIST]"></td></tr>-->

</table>
<input type="submit" value="Speichern" name="speichern">
</form>
</fieldset>


</div>
</div>

</div>
</div>

	<div class="row">
		<div class="row-height">


			<div class="col-xs-12 col-md-8 col-md-height">
				<div class="inside inside-full-height">

					<fieldset><legend>{|Projekte|}</legend>
						[PROJECTS]
					</fieldset>

				</div>
			</div>
			<div class="col-xs-12 col-sm-2 col-sm-height">
				<div class="inside inside-full-height">
					<fieldset><legend>{|Aktion|}</legend>
						[NEWBTN]
					</fieldset>
				</div>
			</div>

		</div>
	</div>

[TAB1NEXT]
</div>

<!-- tab view schließen -->
</div>

<script language="javascript" type="text/javascript" src="../classes/Modules/Taxdoo/www/js/taxdoo.js"></script>

