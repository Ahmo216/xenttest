<!-- gehort zu tabview -->
<div id="tabs">
    <ul>
        <li><a href="#tabs1"></a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs1">
[MESSAGE]
<form action="" method="post" name="eprooform">
[FORMHANDLEREVENT]

  <table class="tableborder" border="0" cellpadding="3" cellspacing="0" width="100%">
    <tbody>
      <tr valign="top" colspan="3">
        <td >
<fieldset><legend>{|Einstellung|}</legend>
    <table width="100%">
        <tr><td width="150">{|Artikel|}:</td><td>[ARTIKEL][MSGARTIKEL]&nbsp;</td></tr>
        <tr><td width="150">{|Seriennummer|}:</td><td>[SERIENNUMMER][MSGSERIENNUMMER]&nbsp;</td></tr>
        <tr><td width="150">{|Kunde|}:</td><td>[ADRESSE][MSGADRESSE]&nbsp;</td></tr>
        <tr><td width="150">{|Lieferschein|}:</td><td>[LIEFERSCHEIN][MSGLIEFERSCHEIN]&nbsp;</td></tr>
        <tr><td width="150">{|Datum Lieferung|}:</td><td>[LIEFERUNG][MSGLIEFERUNG]&nbsp;</td></tr>
        <tr><td width="150">{|Beschreibung|}:</td><td>[BESCHREIBUNG][MSGBESCHREIBUNG]</td><td></tr>
</table></fieldset>

</td></tr>

    <tr valign="" height="" bgcolor="" align="" bordercolor="" class="klein" classname="klein">
    <td width="" valign="" height="" bgcolor="" align="right" colspan="3" bordercolor="" classname="orange2" class="orange2">
    <input type="submit" value="Speichern" name="submit"/>
    </tr>
  
    </tbody>
  </table>
</form>

</div>

<!-- tab view schließen -->
</div>


