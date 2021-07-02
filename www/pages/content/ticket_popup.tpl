<!--<script type="text/javascript"> if([ADRESSE]!=0 && [ADRESSE]!=5) opener.window.location.href='index.php?module=adresse&action=edit&id=[ADRESSE]'; </script>-->
<script type="text/javascript" src="js/lib/aciTree/js/jquery.aciPlugin.min.js"></script>
<script type="text/javascript" src="js/lib/aciTree/js/jquery.aciTree.min.js"></script>
<link rel="stylesheet" type="text/css" href="js/lib/aciTree/css/aciTree.css">
<table border="0" width="100%"><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td>
<br><center><form action="" method="post" enctype="multipart/form-data">
<table border="0" width="100%"  class="tableborder" cellpadding="3" cellspacing="0" align="center">
	<tr classname="orange1" class="orange1" valign="top"><td>Betreff: </td><td><input type="text" size="104" name="betreff" value="[BETREFF]"></td><td width="280" rowspan="6">
			<div class="mlmTreeContainerLeft" data-id="[ID]">
				<fieldset>
					<legend>{|Vorlagen|}</legend>
					<div class="mlmTreeSuche"><label for="search">{|Suche|}:</label>
						<input id="search" type="text" value="">
						<hr>
					</div>
				</fieldset>
				<br><br>
				<div id="mlmTree" class="aciTree"></div>
			</div>
			<!--[VORLAGEN]-->
		</td></tr>
<tr classname="orange1" class="orange1"><td>Kunde: </td><td>  <input type="text" size="104" name="verfasser" value="[VERFASSER]"></td></tr>
<tr classname="orange1" class="orange1"><td>E-Mail:</td><td><input type="text" size="104" id="email" name="email" value="[EMAIL]"></td></tr>
<tr classname="orange1" class="orange1"><td>CC:</td><td><input type="text" size="104" name="email_cc" value="[EMAIL_CC]" id="email_cc"></td></tr>
<tr valign="top"><td colspan="2">
<br><textarea cols="110" rows="15" name="eingabetext" id="eingabetext">[TEXTVORLAGE]</textarea></td></tr>
<tr valign="top"><td colspan="2"><table width="100%"><tr valign="top"><td nowrap><!--<b>Anh&auml;nge:</b><br> [ANHAENGE]--><input type="checkbox" value="1" name="originalenachricht" id ="originalenachricht" [ORIGINALENACHRICHT]>&nbsp;Originale Nachricht anhängen</td><td align="right">
&nbsp;&nbsp;<br><b>Anhang f&uuml;r Ticket:&nbsp;</b><input type="file" name="datei[]"><br><input type="file" name="datei[]"><br><input type="file" name="datei[]"><br><br></td></tr></table></td></tr>

<tr height="40" classname="orange2" class="orange2"><td colspan="2">
<table width="100%"><tr><td align="left">
<input type="button" value="Abbrechen" onclick="window.location.href='index.php?module=ticket&action=freigabe&id=[ID]&cmd=[CMD]'; self.close();" style="width:200px;height:40px">
</td><td align="right">
<input type="submit" value="Senden" name="senden" style="width:200px;height:40px">
</td></tr></table>
<br><br></td></tr>
<tr valign="top"><td colspan="2"> 
[TEXT]
</td></tr>

</table>
</form>
</center>
<bR><br>
</td></tr>
</table>
<!-- nclick="opener.focus();opener.location.reload();window.close();return false;" -->
