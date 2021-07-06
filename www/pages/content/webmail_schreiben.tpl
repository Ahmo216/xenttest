
<script type="application/javascript">
    $(document).ready(function () {
        $('#webmail-senden-form').submit(function () {
            $('#webmail-senden-form').loadingOverlay();
        });
    });
</script>

<form action="" method="post" enctype="multipart/form-data" id="webmail-senden-form">
[MESSAGE]
  <div class="row">
  <div class="row-height">  <div class="col-xs-12 col-md-8 col-md-height">  <div class="inside inside-full-height">
<fieldset><legend>E-mail schreiben</legend>
<table width="100%">
<tr><td width="150">Von:</td><td><select name="from">[EMAILFROM]</select></td></tr>
<tr><td>An:</td><td>[ADRESSESTART]<textarea rows="1" style="width:95%" name="adresse" id="adresse">[ADRESSE]</textarea>[ADRESSEEND]</td></tr>
<tr><td>CC:</td><td>[CCSTART]<textarea rows="1" style="width:95%" name="cc" id="cc">[CCADRESSE]</textarea>[CCEND]</td></tr>
<tr><td>BCC:</td><td>[BCCSTART]<textarea rows="1" style="width:95%" name="bcc" id="bcc">[BCCADRESSE]</textarea>[BCCEND]</td></tr>
<tr><td><br></td><td></td></tr>
<tr><td>Verlinken in CRM:</td><td><input type="text" style="width:95%" name="adresseid" id="adresseid" value="[ADRESSEID]"></td></tr>
<tr><td>Betreff:</td><td><input type="text" style="width:95%" name="betreff" value="[EMAILBETREFF]"></td></tr>
<tr valign="top"><td>E-Mail:</td><td><textarea name="emailtext" id="emailtext">[EMAILTEXT]</textarea><br>(Die Signatur wird automatisch eingef&uuml;gt.)</td></tr>
</table>

</fieldset>
  </div>  
</div>
  <div class="col-xs-12 col-md-4 col-md-height">  
<div class="inside inside-full-height">

<fieldset><legend>{|Anhang|}</legend>

<table width="100%">
  <tr><td>Anhang:</td><td><input type="file" name="upload[]" ></td></tr>
  <tr><td>Anhang:</td><td><input type="file" name="upload[]" ></td></tr>
  <tr><td>Anhang:</td><td><input type="file" name="upload[]" ></td></tr>
</table>
</fieldset>

</div>
</div>
</div>
</div>


<table width="100%">
<tr valign="" height="" bgcolor="" align="" bordercolor="" class="klein" classname="klein">
    <td width="" valign="" height="" bgcolor="" align="right" bordercolor="" classname="orange2" class="orange2">
    <input type="submit" value="Senden" name="senden">
    <input type="button" value="Abbrechen" onclick="window.location.href='[BACK]'" />
</td>
</tr>

</table>
</form>
